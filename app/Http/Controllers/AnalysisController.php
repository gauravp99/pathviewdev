<?php namespace App\Http\Controllers;

/**
 * @Author: Yehsvant Bhavnasi, Dr. Weijun Luo
 * @Contact: byeshvant@hotmail.com
 * Pathview web @main controller here are all the analysis examples control functions
 */
use App\Http\Requests;
use App\Http\Requests\CraeteAnalysisRequest;
use Auth;
use DB;
use Exception;
use Illuminate\Support\Facades\Redirect;
use Input;
use Request;

require_once 'config.php';
require_once 'helpers.php';
require_once 'Parser.php';

class Rserve_Connection
{

    const PARSER_NATIVE = 0;
    const PARSER_REXP = 1;
    const PARSER_DEBUG = 2;
    const PARSER_NATIVE_WRAPPED = 3;

    const DT_INT = 1;
    const DT_CHAR = 2;
    const DT_DOUBLE = 3;
    const DT_STRING = 4;
    const DT_BYTESTREAM = 5;
    const DT_SEXP = 10;
    const DT_ARRAY = 11;

    /** this is a flag saying that the contents is large (>0xfffff0) and hence uses 56-bit length field */
    const DT_LARGE = 64;

    const CMD_login = 0x001;
    const CMD_voidEval = 0x002;
    const CMD_eval = 0x003;
    const CMD_shutdown = 0x004;
    const CMD_openFile = 0x010;
    const CMD_createFile = 0x011;
    const CMD_closeFile = 0x012;
    const CMD_readFile = 0x013;
    const CMD_writeFile = 0x014;
    const CMD_removeFile = 0x015;
    const CMD_setSEXP = 0x020;
    const CMD_assignSEXP = 0x021;

    const CMD_setBufferSize = 0x081;
    const CMD_setEncoding = 0x082;

    const CMD_detachSession = 0x030;
    const CMD_detachedVoidEval = 0x031;
    const CMD_attachSession = 0x032;

    // control commands since 0.6-0
    const CMD_ctrlEval = 0x42;
    const CMD_ctrlSource = 0x45;
    const CMD_ctrlShutdown = 0x44;

    const CMD_Response = 0x10000;

    // errors as returned by Rserve
    const ERR_auth_failed = 0x41;
    const ERR_conn_broken = 0x42;
    const ERR_inv_cmd = 0x43;
    const ERR_inv_par = 0x44;
    const ERR_Rerror = 0x45;
    const ERR_IOerror = 0x46;
    const ERR_not_open = 0x47;
    const ERR_access_denied = 0x48;
    const ERR_unsupported_cmd = 0x49;
    const ERR_unknown_cmd = 0x4a;
    const ERR_data_overflow = 0x4b;
    const ERR_object_too_big = 0x4c;
    const ERR_out_of_mem = 0x4d;
    const ERR_ctrl_closed = 0x4e;
    const ERR_session_busy = 0x50;
    const ERR_detach_failed = 0x51;

    public static $machine_is_bigendian = NULL;

    private static $init = FALSE;

    private $host;
    private $port;
    private $socket;
    private $auth_request;
    private $auth_method;

    private $debug;

    private $ascync;

    /**
     * @param mixed host name or IP or a Rserve_Session instance
     * @param int $port if 0 then host is interpreted as unix socket,
     *
     */
    public function __construct($host = '127.0.0.1', $port = 6311, $params = array())
    {
        if (!self::$init) {
            self::init();
        }
        if (is_object($host) AND $host instanceof Rserve_Session) {
            $session = $host->key;
            $this->port = $host->port;
            $host = $host->host;
            if (!$host) {
                $host = '127.0.0.1';
            }
            $this->host = $host;
        } else {
            $this->host = $host;
            $this->port = $port;
            $session = NULL;
        }
        $this->debug = isset($params['debug']) ? (bool)$params['debug'] : FALSE;
        $this->async = isset($params['async']) ? (bool)$params['async'] : FALSE;
        $this->username = isset($params['username']) ? $params['username'] : FALSE;
        $this->password = isset($params['password']) ? $params['password'] : FALSE;
        $this->openSocket($session);
    }

    /**
     * initialization of the library
     */
    public static function init()
    {
        if (self::$init) {
            return;
        }
        $m = pack('s', 1);
        self::$machine_is_bigendian = ($m[0] == 0);
        #spl_autoload_register('Rserve_Connection::autoload');
        self::$init = TRUE;
    }

    /**
     * Open a new socket to Rserv
     * @return resource socket
     */
    private function openSocket($session_key = NULL)
    {
        if ($this->port == 0) {
            $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
        } else {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        }
        if (!$socket) {
            throw new Rserve_Exception('Unable to create socket [' . socket_strerror(socket_last_error()) . ']');
        }
        //socket_set_option($socket, SOL_TCP, SO_DEBUG,2);
        $ok = socket_connect($socket, $this->host, $this->port);
        if (!$ok) {
            throw new Rserve_Exception('Unable to connect [' . socket_strerror(socket_last_error()) . ']');
        }
        $this->socket = $socket;
        if (!is_null($session_key)) {
            // Try to resume session
            $n = socket_send($socket, $session_key, 32, 0);
            if ($n < 32) {
                throw new Rserve_Exception('Unable to send session key');
            }
            $r = $this->getResponse();
            if ($r['is_error']) {
                $msg = $this->getErrorMessage($r['error']);
                throw new Rserve_Exception('invalid session key : ' . $msg);
            }
            return;
        }

        // No session, check handshake
        $buf = '';
        $n = socket_recv($socket, $buf, 32, 0);
        if ($n < 32 || strncmp($buf, 'Rsrv', 4) != 0) {
            throw new Rserve_Exception('Invalid response from server.');
        }
        $rv = substr($buf, 4, 4);
        if (strcmp($rv, '0103') != 0) {
            throw new Rserve_Exception('Unsupported protocol version.');
        }
        $key = null;
        for ($i = 12; $i < 32; $i += 4) {
            $attr = substr($buf, $i, 4);
            if ($attr == 'ARpt') {
                $this->auth_request = TRUE;
                $this->auth_method = 'plain';

            } elseif ($attr == 'ARuc') {
                $this->auth_request = TRUE;
                $this->auth_method = 'crypt';
            }
            if ($attr[0] === 'K') {
                $key = substr($attr, 1, 3);
            }
        }
        //if($this->auth_method=="plain") $this->login(); else $this->login($key);
    }

    /**
     * Get the response from a command
     * @param resource $socket
     * @return array contents
     */
    protected function getResponse()
    {
        $header = NULL;
        $n = socket_recv($this->socket, $header, 16, 0);
        if ($n != 16) {
            // header should be sent in one block of 16 bytes
            return FALSE;
        }
        $len = int32($header, 4);
        $ltg = $len; // length to get
        $buf = '';
        while ($ltg > 0) {
            $n = socket_recv($this->socket, $b2, $ltg, 0);
            if ($n > 0) {
                $buf .= $b2;
                unset($b2);
                $ltg -= $n;
            } else {
                break;
            }
        }
        $res = int32($header);
        return (array(
            'code' => $res,
            'is_error' => ($res & 15) != 1,
            'error' => ($res >> 24) & 127,
            'header' => $header,
            'contents' => $buf // Buffer contains messages part
        ));
    }

    /**
     * Translate an error code to an error message
     * @param int $code
     */
    public function getErrorMessage($code)
    {
        switch ($code) {
            case self::ERR_auth_failed    :
                $m = 'auth failed';
                break;
            case self::ERR_conn_broken    :
                $m = 'connexion broken';
                break;
            case self::ERR_inv_cmd        :
                $m = 'invalid command';
                break;
            case self::ERR_inv_par        :
                $m = 'invalid parameter';
                break;
            case self::ERR_Rerror        :
                $m = 'R error';
                break;
            case self::ERR_IOerror        :
                $m = 'IO error';
                break;
            case self::ERR_not_open        :
                $m = 'not open';
                break;
            case self::ERR_access_denied :
                $m = 'access denied';
                break;
            case self::ERR_unsupported_cmd:
                $m = 'unsupported command';
                break;
            case self::ERR_unknown_cmd    :
                $m = 'unknown command';
                break;
            case self::ERR_data_overflow    :
                $m = 'data overflow';
                break;
            case self::ERR_object_too_big :
                $m = 'object too big';
                break;
            case self::ERR_out_of_mem    :
                $m = 'out of memory';
                break;
            case self::ERR_ctrl_closed    :
                $m = 'control closed';
                break;
            case self::ERR_session_busy    :
                $m = 'session busy';
                break;
            case self::ERR_detach_failed    :
                $m = 'detach failed';
                break;
            default:
                $m = 'unknown error';
        }
        return $m;
    }

    public static function autoload($name)
    {
        $s = strtolower(substr($name, 0, 6));
        if ($s != 'rserve') {
            return FALSE;
        }
        $s = substr($name, 7);
        $s = str_replace('_', '/', $s);
        $s .= '.php';
        require $s;
        return TRUE;
    }

    /**
     * Allow accces to socket
     */
    public function getSocket()
    {
        return $this->socket;
    }


    /**
     * Login to rserve
     * Similar to RSlogin  http://rforge.net/doc/packages/RSclient/Rclient.html
     * Inspired from https://github.com/SurajGupta/RserveCLI2/blob/master/RServeCLI2/Qap1.cs
     *               https://github.com/SurajGupta/RserveCLI2/blob/master/RServeCLI2/RConnection.cs
     * @param string $salt
     */
    /*public function login($salt=null) {
        switch ( $this->auth_method )
        {
        case "plain":
            break;
        case "crypt":
            if(!$salt) throw new Rserve_Exception("Should pass the salt for login");
            $this->password=crypt($this->password,$salt);
            break;
        default:
            throw new Rserve_Exception( "Could not interpret login method '{$this->auth_method}'" );
        }
        $data = _rserve_make_data(self::DT_STRING, "{$this->username}\n{$this->password}");
        $r=$this->sendCommand(self::CMD_login, $data );
        if( !$r['is_error'] ) {
            return true;
        }
        throw new Rserve_Exception( "Could not login" );
    }
*/

    /**
     * Set Asynchronous mode
     * @param bool $async
     */
    public function setAsync($async)
    {
        $this->async = (bool)$async;
    }

    /**
     * Evaluate a string as an R code and return result
     * @param string $string
     * @param int $parser
     */
    public function evalString($string, $parser = self::PARSER_NATIVE)
    {

        $data = _rserve_make_data(self::DT_STRING, $string);

        $r = $this->sendCommand(self::CMD_eval, $data);
        if ($this->async) {
            return TRUE;
        }
        if (!$r['is_error']) {
            return $this->parseResponse($r['contents'], $parser);
        }
        throw new Rserve_Exception('unable to evaluate', $r);
    }

    /**
     * send a command to Rserve
     * @param int $command command code
     * @param string $data data packets
     * @return int    if $async, TRUE
     */
    protected function sendCommand($command, $data)
    {

        $pkt = _rserve_make_packet($command, $data);

        if ($this->debug) {
            $this->debugPacket($pkt);
        }

        socket_send($this->socket, $pkt, strlen($pkt), 0);

        if ($this->async) {
            return TRUE;
        }
        // get response
        return $this->getResponse();
    }

    /**
     * Debug a Rserve packet
     * @param array|string $packet
     */
    public function debugPacket($packet)
    {
        /*
          [0]  (int) command
          [4]  (int) length of the message (bits 0-31)
          [8]  (int) offset of the data part
          [12] (int) length of the message (bits 32-63)
        */
        if (is_array($packet)) {
            $buf = $packet['contents'];
            $header = $packet['header'];
        } else {
            $header = substr($packet, 0, 16);
            $buf = substr($packet, 16);
        }
        $command = int32($header, 0);
        $lengthLow = int32($header, 4);
        $offset = int32($header, 8);
        $lenghtHigh = int32($header, 12);
        if ($command & self::CMD_Response) {
            $is_error = $command & 15 != 1;
            $cmd = 'CMD Response' . (($is_error) ? 'OK' : 'Error');
            $err = ($command >> 24) & 0x7F;
        } else {
            $cmd = dechex($command) & 0xFFF;
        }
        echo '[header:<' . $cmd . ' Length:' . dechex($lenghtHigh) . '-' . dechex($lengthLow) . ' offset' . $offset . ">\n";
        $len = strlen($buf);
        $i = 0;
        while ($len > 0) {
            $type = int8($buf, $i);
            $m_len = int24($buf, $i + 1);
            $i += 4;
            $i += $m_len;
            $len -= $m_len + 4;
            echo 'data:<' . $this->getDataTypeTitle($type) . ' length:' . $m_len . ">\n";
        }
        echo "]\n";
    }

    /**
     * Data Type value to label
     * @param int $x
     */
    public function getDataTypeTitle($x)
    {
        switch ($x) {
            case self::DT_INT :
                $m = 'int';
                break;
            case self::DT_CHAR :
                $m = 'char';
                break;
            case self::DT_DOUBLE :
                $m = 'double';
                break;
            case self::DT_STRING :
                $m = 'string';
                break;
            case self::DT_BYTESTREAM :
                $m = 'stream';
                break;

            case self::DT_SEXP :
                $m = 'sexp';
                break;

            case self::DT_ARRAY :
                $m = 'array';
                break;
            default:
                $m = 'unknown';
        }
        return $m;
    }

    /**
     *
     * Parse a response from Rserve
     * @param string $r
     * @param int $parser
     * @return parsed results
     */
    private function parseResponse($buf, $parser)
    {
        $type = int8($buf, 0);
        if ($type != self::DT_SEXP) { // Check Data type of the packet
            throw new Rserve_Exception('Unexpected packet Data type (expect DT_SEXP)', $buf);
        }
        $i = 4; // + 4 bytes (Data part HEADER)
        $r = NULL;
        switch ($parser) {
            case self::PARSER_NATIVE:
                $r = Rserve_Parser::parse($buf, $i);
                break;
            case self::PARSER_REXP:
                $r = Rserve_Parser::parseREXP($buf, $i);
                break;
            case self::PARSER_DEBUG:
                $r = Rserve_Parser::parseDebug($buf, $i);
                break;
            case self::PARSER_NATIVE_WRAPPED:
                $old = Rserve_Parser::$use_array_object;
                Rserve_Parser::$use_array_object = TRUE;
                $r = Rserve_Parser::parse($buf, $i);
                Rserve_Parser::$use_array_object = $old;
                break;
            default:
                throw new Rserve_Exception('Unknown parser');
        }
        return $r;
    }

    /**
     * Detach the current session from the current connection.
     * Save envirnoment could be attached to another R connection later
     * @return array with session_key used to
     * @throws Rserve_Exception
     */
    public function detachSession()
    {
        $r = $this->sendCommand(self::CMD_detachSession, NULL);
        if (!$r['is_error']) {
            $x = $r['contents'];
            if (strlen($x) != (32 + 3 * 4)) {
                throw new Rserve_Exception('Invalid response to detach');
            }

            $port = int32($x, 4);
            $key = substr($x, 12);
            $session = new Rserve_Session($key, $this->host, $port);

            return $session;
        }
        throw new Rserve_Exception('Unable to detach sesssion', $r);
    }

    /**
     * Assign a value to a symbol in R
     * @param string $symbol name of the variable to set (should be compliant with R syntax !)
     * @param Rserve_REXP $value value to set
     */
    public function assign($symbol, Rserve_REXP $value)
    {
        $symbol = (string)$symbol;
        $data = _rserve_make_data(self::DT_STRING, $symbol);
        $bin = Rserve_Parser::createBinary($value);
        $data .= _rserve_make_data(self::DT_SEXP, $bin);
        $r = $this->sendCommand(self::CMD_assignSEXP, $data);
        return $r;
    }

    /**
     * Create a new connection to Rserve for async calls
     * @return    Rserve_Connection
     */
    public function newConnection()
    {
        $newConnection = clone($this);
        $newConnection->openSocket();
        return $newConnection;
    }

    /**
     * Get results from an eval command  in async mode
     * @param int $parser
     * @return mixed contents of response
     */
    public function getResults($parser = self::PARSER_NATIVE)
    {
        $r = $this->getResponse();
        if (!$r['is_error']) {
            return $this->parseResponse($r['contents'], $parser);
        }
        throw new Rserve_Exception('unable to evaluate', $r);
    }

    /**
     * Close the current connection
     */
    public function close()
    {
        return socket_close($this->socket);
    }

}

/**
 * R Session wrapper
 * @author Cl�ment Turbelin
 *
 */
class Rserve_Session
{

    /**
     * Session key
     * @var string
     */
    public $key;

    /**
     *
     * @var int
     */
    public $port;

    public $host;

    public function __construct($key, $host, $port)
    {
        $this->key = $key;
        $this->port = $port;
        $this->host = $host;
    }

    public function __toString()
    {
        $k = base64_encode($this->key);
        return sprintf('Session %s:%d identified by base64:%s', $this->host, $this->port, $k);
    }

}

/**
 * RServe Exception
 * @author Cl�ment Turbelin
 *
 */
class Rserve_Exception extends Exception
{
    public $packet;

    public function __construct($message, $packet = NULL)
    {
        parent::__construct($message);
        $this->packet = $packet;
    }
}

class Rserve_Parser_Exception extends Rserve_Exception
{
}

Rserve_Connection::init();



class AnalysisController extends Controller
{


    /**
     * New Analysis page
     * @return \Illuminate\View\View
     */
    public function new_analysis()
    {
        return view('analysis.NewAnalysis');
    }

    /**
     * @param CraeteAnalysisRequest $resqest
     * @return $this|\Illuminate\View\View
     */
    public function postAnalysis(CraeteAnalysisRequest $resqest)
    {

        $d = new AnalysisController();
        return $d->analysis("newAnalysis");
        //this.analysis("new_analysis");

    }

    /**
     * @param $anal_type
     * @return $this|\Illuminate\View\View
     *
     * This function does the following things
     *
     * 1. Read the arguments from the form and copy into the arguments variable which is send to R script
     * 2. Check for any issue with input like adding some values which are not supported
     * 3. Checking for values with a comma or code. detect any abnormal input
     * 4. once analysis is successfully done insert data into database
     */
    public function analysis($anal_type)
    {
        #$r = new Rserve_Connection(RSERVE_HOST);

        #return "Temeprory outage testing for issues";
        $errors = array();
        $gene_cmpd_color = array();
        $err_atr = array();
        $argument = "";
        $time = time();
        $email = "";
        $path_id = "";
        $pathway_array1 = array();


        /*--------------------------------------------------------------Start checking for the arguments list--------------------------------------------------------*/
        /**
         * Start check if the @geneid (Gene id) entered is present if not present then add to @errors
         */

        $geneid = $_POST["geneid"];
        $val = DB::select(DB::raw("select geneid  from gene where geneid  like '$geneid' LIMIT 1 "));

        if (sizeof($val) > 0) {
            $argument .= "geneid:" . $val[0]->geneid . ",";
        } else {
            array_push($errors, "Entered Gene ID doesn't exist");
            $err_atr["geneid"] = 1;

        }
        /**
         * Start check if the @geneid (Gene id) entered is present if not present then add to @errors
         */


        /**
         * Start check if the @cmpdid (Compound id) entered is present if not present then add to @errors
         */

        $cpdid = $_POST["cpdid"];

        $val = DB::select(DB::raw("select cmpdid  from compound where cmpdid  like '$cpdid' LIMIT 1 "));
        if (sizeof($val) > 0) {
            $argument .= "cpdid:" . str_replace(" ", "-", $val[0]->cmpdid) . ",";
        } else {
            array_push($errors, "Entered compound ID doesn't exist");
            $err_atr["cpdid"] = 1;
        }

        /**
         * End check if the @cmpdid (Compound id) entered is present if not present then add to @errors
         */


        /**
         * Start check if the @species (Species id) entered is present if not present then add to @errors
         */

        $spe = substr($_POST["species"], 0, 3);

        $val = DB::select(DB::raw("select species_id from Species where species_id like '$spe' LIMIT 1"));
        $species1 = explode("-", $_POST["species"]);
        if (sizeof($val) > 0) {
            $argument .= "species:" . $val[0]->species_id . ",";
        } else {
            array_push($errors, "Entered Species ID doesn't exist");
            $err_atr["species"] = 1;
        }
        /**
         * End check if the @species (Species id) entered is present if not present then add to @errors
         */


        /**
         * Start check if the @suffix (Suffix) contains any illegal character is present if present then remove those
         */
        $suffix = preg_replace("/[^A-Za-z0-9 ]/", '', $_POST["suffix"]);

        $argument .= "suffix:" . $suffix . ",";
        /**
         * End check if the @suffix (Suffix) contains any illegal character is present if present then remove those
         */

        /**
         * Start check if the @pathway (Pathway ID) is present if not present then add to @errors
         */
        $path = explode("-", $_POST["pathway"]);

        $path_id = substr($path[0], 0, 5);

        /** if pathway contains both species id and pathway id then devide the species and pathway check again for existance
         * and add to arguments list if not present the add to errors list
         *
         */
        /*Check for pathway  having speceis regular expression match */
        if (preg_match('/[a-z]+[0-9]+/', substr($path[0], 0, 5))) {

            $path_id = substr($path[0], 3, 8);
            $spe = substr($path[0], 0, 3);
            $val = DB::select(DB::raw("select species_id from Species where species_id like '$spe' LIMIT 1"));

            if (sizeof($val) > 0) {
                $argument .= "species:" . $val[0]->species_id . ",";
            } else {
                array_push($errors, "Entered Species ID doesn't exist");
                $err_atr["species"] = 1;
            }
        } else {
            /**
             * If only the pathway id is present
             */
            $path_id = substr($path[0], 0, 5);
        }

        $val = DB::select(DB::raw("select pathway_id from Pathway where pathway_id like '$path_id' LIMIT 1"));
        if (sizeof($val) > 0) {
            $argument .= "pathway:" . $val[0]->pathway_id . ",";
        } else {
            array_push($errors, "Entered pathway ID doesn't exist");
            $err_atr["pathway"] = 1;
        }
        /**
         * End check if the @pathway (Pathway ID) is present if not present then add to @errors
         */

        /**
         * Start adding to arguments
         * @kegg (Kegg Native),
         * @layer (Same Layer),
         * @split (Split Group),
         * @expand (Expand Node),
         * @multistate (Multi State),
         * @matchd  (Matched data),
         * @gdisc (Discrete Gene),
         * @cdisc (Discrete Compound),
         * adding to arguments */

        if (isset($_POST["kegg"]))
            $argument .= "kegg:T,";
        else
            $argument .= "kegg:F,";

        if (isset($_POST["layer"]))
            $argument .= "layer:T,";
        else
            $argument .= "layer:F,";

        if (isset($_POST["split"]))
            $argument .= "split:T,";
        else
            $argument .= "split:F,";

        if (isset($_POST["expand"]))
            $argument .= "expand:T,";
        else
            $argument .= "expand:F,";

        if (isset($_POST["multistate"]))
            $argument .= "multistate:T,";
        else
            $argument .= "multistate:F,";

        if (isset($_POST["matchd"]))
            $argument .= "matchd:T,";
        else
            $argument .= "matchd:F,";

        if (isset($_POST["gdisc"]))
            $argument .= "gdisc:T,";
        else
            $argument .= "gdisc:F,";

        if (isset($_POST["cdisc"]))
            $argument .= "cdisc:T,";
        else
            $argument .= "cdisc:F,";

        /**
         * End adding to arguments
         * @kegg (Kegg Native),
         * @layer (Same Layer),
         * @split (Split Group),
         * @expand (Expand Node),
         * @multistate (Multi State),
         * @matchd  (Matched data),
         * @gdisc (Discrete Gene),
         * @cdisc (Discrete Compound),
         * adding to arguments */

        /**
         * Start adding to arguments
         * @kpos Key position ,
         * @pos Signature position,
         */
        $argument .= "kpos:" . $_POST["kpos"] . ",";
        $argument .= "pos:" . $_POST["pos"] . ",";
        /**
         * End adding to arguments
         * @kpos Key position ,
         * @pos Signature position,
         */

        /**
         * Start adding the offset
         * here we check the argument contains non neumeric characters
         * if contains non neumeric character add it to the @errors list
         */
        if (preg_match('/[a-z]+/', $_POST["offset"])) {
            array_push($errors, "offset should be Numeric");
            $err_atr["offset"] = 1;
        } else {
            $argument .= "offset:" . $_POST["offset"] . ",";
        }
        $argument .= "align:" . $_POST["align"] . ",";
        /**
         * End adding the offset
         * here we check the argument contains non neumeric characters
         * if contains non neumeric character add it to the @errors list
         */

        /**
         * Start adding the arguments
         * @glmt (Gene limit Range)
         * @gbins (Gene Bins)
         * @glow (Gene low level color)
         * @gmid (Gene mid level color)
         * @ghigh (Gene high level color)
         * @climt (Cmpound limits range)
         * @cbins (Compound number of bins)
         * @clow  (Compound low level color)
         * @cmid (Compound mid level color)
         * @chigh (Compound high level color)
         */
        if ($anal_type == "newAnalysis") {
            if (Input::hasFile('gfile')) {
                if (preg_match('/[a-z]+/', $_POST["glmt"])) {
                    array_push($errors, "glimit should be Numeric");
                    $err_atr["glmt"] = 1;
                }
                if (preg_match('/[a-z]+/', $_POST["gbins"])) {
                    array_push($errors, "gbins should be Numeric");
                    $err_atr["gbins"] = 1;
                }
                $argument .= "glmt:" . $_POST["glmt"] . ",";
                $argument .= "gbins:" . $_POST["gbins"] . ",";
                if (strpos($_POST["glow"], '#') !== false) {
                    $argument .= "glow:" . $_POST["glow"] . ",";
                } else {
                    $argument .= "glow:" . '#' . $_POST["glow"] . ",";
                }
                if (strpos($_POST["gmid"], '#') !== false) {
                    $argument .= "gmid:" . $_POST["gmid"] . ",";
                } else {
                    $argument .= "gmid:" . '#' . $_POST["gmid"] . ",";
                }
                if (strpos($_POST["ghigh"], '#') !== false) {
                    $argument .= "ghigh:" . $_POST["ghigh"] . ",";
                } else {
                    $argument .= "ghigh:" . '#' . $_POST["ghigh"] . ",";
                }

                $gene_cmpd_color["glow"] = $_POST["glow"];
                $gene_cmpd_color["gmid"] = $_POST["gmid"];
                $gene_cmpd_color["ghigh"] = $_POST["ghigh"];


            }
        } else if (isset($_POST["gcheck"])) {
            if (preg_match('/[a-z]+/', $_POST["glmt"])) {
                array_push($errors, "glimit should be Numeric");
                $err_atr["glmt"] = 1;
            }
            if (preg_match('/[a-z]+/', $_POST["gbins"])) {
                array_push($errors, "gbins should be Numeric");
                $err_atr["gbins"] = 1;
            }
            $argument .= "glmt:" . $_POST["glmt"] . ",";
            $argument .= "gbins:" . $_POST["gbins"] . ",";
            if (strpos($_POST["glow"], '#') !== false) {
                $argument .= "glow:" . $_POST["glow"] . ",";
            } else {
                $argument .= "glow:" . '#' . $_POST["glow"] . ",";
            }
            if (strpos($_POST["gmid"], '#') !== false) {
                $argument .= "gmid:" . $_POST["gmid"] . ",";
            } else {
                $argument .= "gmid:" . '#' . $_POST["gmid"] . ",";
            }
            if (strpos($_POST["ghigh"], '#') !== false) {
                $argument .= "ghigh:" . $_POST["ghigh"] . ",";
            } else {
                $argument .= "ghigh:" . '#' . $_POST["ghigh"] . ",";
            }
            $gene_cmpd_color["glow"] = $_POST["glow"];
            $gene_cmpd_color["gmid"] = $_POST["gmid"];
            $gene_cmpd_color["ghigh"] = $_POST["ghigh"];

        }


        if ($anal_type == "newAnalysis") {
            if (Input::hasFile('cfile')) {
                if (preg_match('/[a-z]+/', $_POST["clmt"])) {
                    array_push($errors, "climit should be Numeric");
                    $err_atr["clmt"] = 1;
                }
                if (preg_match('/[a-z]+/', $_POST["cbins"])) {
                    array_push($errors, "cbins should be Numeric");
                    $err_atr["cbins"] = 1;
                }
                $argument .= "clmt:" . $_POST["clmt"] . ",";
                $argument .= "cbins:" . $_POST["cbins"] . ",";


                if (strpos($_POST["clow"], '#') !== false) {
                    $argument .= "clow:" . $_POST["clow"] . ",";
                } else {
                    $argument .= "clow:" . '#' . $_POST["clow"] . ",";
                }
                if (strpos($_POST["gmid"], '#') !== false) {
                    $argument .= "cmid:" . $_POST["cmid"] . ",";
                } else {
                    $argument .= "cmid:" . '#' . $_POST["cmid"] . ",";
                }
                if (strpos($_POST["chigh"], '#') !== false) {
                    $argument .= "chigh:" . $_POST["chigh"] . ",";
                } else {
                    $argument .= "chigh:" . '#' . $_POST["chigh"] . ",";
                }



                $gene_cmpd_color["clow"] = $_POST["clow"];
                $gene_cmpd_color["cmid"] = $_POST["cmid"];
                $gene_cmpd_color["chigh"] = $_POST["chigh"];
            }
        } else if (isset($_POST["cpdcheck"])) {
            if (preg_match('/[a-z]+/', $_POST["clmt"])) {
                array_push($errors, "climit should be Numeric");
                $err_atr["clmt"] = 1;
            }
            if (preg_match('/[a-z]+/', $_POST["cbins"])) {
                array_push($errors, "cbins should be Numeric");
                $err_atr["cbins"] = 1;
            }
            $argument .= "clmt:" . $_POST["clmt"] . ",";
            $argument .= "cbins:" . $_POST["cbins"] . ",";

            if (strpos($_POST["clow"], '#') !== false) {
                $argument .= "clow:" . $_POST["clow"] . ",";
            } else {
                $argument .= "clow:" . '#' . $_POST["clow"] . ",";
            }
            if (strpos($_POST["gmid"], '#') !== false) {
                $argument .= "cmid:" . $_POST["cmid"] . ",";
            } else {
                $argument .= "cmid:" . '#' . $_POST["cmid"] . ",";
            }
            if (strpos($_POST["chigh"], '#') !== false) {
                $argument .= "chigh:" . $_POST["chigh"] . ",";
            } else {
                $argument .= "chigh:" . '#' . $_POST["chigh"] . ",";
            }

            $gene_cmpd_color["clow"] = $_POST["clow"];
            $gene_cmpd_color["cmid"] = $_POST["cmid"];
            $gene_cmpd_color["chigh"] = $_POST["chigh"];

        }

        /**
         * end adding the arguments
         * @glmt (Gene limit Range)
         * @gbins (Gene Bins)
         * @glow (Gene low level color)
         * @gmid (Gene mid level color)
         * @ghigh (Gene high level color)
         * @climt (Cmpound limits range)
         * @cbins (Compound number of bins)
         * @clow  (Compound low level color)
         * @cmid (Compound mid level color)
         * @chigh (Compound high level color)
         */

        /**
         * Start adding the arguments
         * @nsum (Node summing function)
         * @ncolor (NA Color)
         */
        $argument .= "nsum:" . $_POST["nodesun"] . ",";
        $argument .= "ncolor:" . $_POST["nacolor"] . ",";
        /**
         * end adding the arguments
         * @nsum (Node summing function)
         * @ncolor (NA Color)
         */

        /**
         * @param $filename
         * @return mixed|string
         * Function getting the filename extension
         */
        function file_ext($filename)
        {
            if (!preg_match('/\./', $filename)) return '';
            return preg_replace('/^.*\./', '', $filename);
        }

        function file_ext_strip($filename)
        {
            return preg_replace('/\.[^.]*$/', '', $filename);
        }

        /**
         * @gfile Checking if the file extension is correct and in the supported list or not
         *
         */
        if (Input::hasFile('gfile')) {
            $filename = Input::file('gfile')->getClientOriginalName();

            $gene_extension = file_ext($filename);
            if ($gene_extension != "txt" && $gene_extension != "csv" && $gene_extension != "rda") {
                array_push($errors, "Gene data file extension is not supported( use .txt,.csv,.rda)");
                $err_atr["gfile"] = 1;
            }
        }

        /**
         * @cfile Checking if the file extension is correct and in the supported list or not
         *
         */
        if (Input::hasFile('cfile')) {
            $filename1 = Input::file('cfile')->getClientOriginalName();
            $cpd_extension = file_ext($filename1);

            if ($cpd_extension != "txt" && $cpd_extension != "csv" && $cpd_extension != "rda") {
                array_push($errors, "compound data file extension is not supported( use .txt,.csv,.rda)");
                $err_atr["cfile"] = 1;
            }
        }

        /**
         * Checking if the any of the arguments contains errors or not if contains @error
         * then redirect ot the same page with @errors as the session attribute
         */
        $pathidx = 1;
        $pathway_array = array();
        $path = "pathway" . $pathidx;
        while (isset($_POST[$path])) {
            $path1 = explode("-", $_POST["pathway$pathidx"]);

            if (strcmp(substr($path1[0], 0, 5), $path_id) != 0) {

                array_push($pathway_array, $path1[0]);
            }
            /* $argument .= ",pathway$pathidx:" . substr($path1[0], 0, 5);*/


            $pathidx++;
            $path = "pathway" . $pathidx;
        }

        $pathway_array1 = array_unique($pathway_array);

        $pathcounter = 1;

        foreach ($pathway_array1 as $val1) {
            $path_id12 = substr($val1, 0, 5);
            $val = DB::select(DB::raw("select pathway_id from Pathway where pathway_id like '$path_id12' LIMIT 1"));
            if (sizeof($val) > 0) {
                $argument .= "pathway$pathcounter:" . $val[0]->pathway_id . ",";

            } else {
                array_push($errors, "Entered pathway ID " . $pathcounter . " value " . $val1 . " doesn't exist");
                $err_atr["pathway" . $pathcounter] = 1;
            }
            $pathcounter++;

        }

        if (sizeof($errors) > 0) {

            foreach ($_POST as $key => $value) {
                $_SESSION[$key] = $value;
            }
            $Session = $_SESSION;

            if (strcmp($anal_type, 'exampleAnalysis1') == 0) {

                return Redirect::to('example1')
                    ->with('err', $errors)
                    ->with('err_atr', $err_atr)
                    ->with('Sess', $Session)->with('genecolor', $gene_cmpd_color);
            } else if (strcmp($anal_type, 'exampleAnalysis2') == 0) {
                return Redirect::to('example2')
                    ->with('err', $errors)
                    ->with('err_atr', $err_atr)
                    ->with('Sess', $Session)->with('genecolor', $gene_cmpd_color);
            } else if (strcmp($anal_type, 'exampleAnalysis3') == 0) {
                return Redirect::to('example3')
                    ->with('err', $errors)
                    ->with('err_atr', $err_atr)
                    ->with('Sess', $Session)->with('genecolor', $gene_cmpd_color);
            } else if (strcmp($anal_type, 'newAnalysis') == 0) {
                return Redirect::to('analysis')
                    ->with('err', $errors)
                    ->with('err_atr', $err_atr)
                    ->with('Sess', $Session)->with('genecolor', $gene_cmpd_color);
            }

        }

        /*--------------------------------------------------------------End checking for the arguments--------------------------------------------------------*/


        /** Different pipeline for different analysis type */
        if ($anal_type == "newAnalysis") {

            if (Input::hasFile('gfile')) {
                $pathidx = 1;
                $file = Input::file('gfile');
                $filename = Input::file('gfile')->getClientOriginalName();
                $destFile = public_path();
                $gene_extension = file_ext($filename);
                if ($gene_extension == "txt" || $gene_extension == "csv" || $gene_extension == "rda") {
                    $argument .= "geneextension:" . $gene_extension . ",";


                    if (is_null(Auth::user())) {
                        $email = "demo";
                    } else {
                        $email = Auth::user()->email;
                        if (!file_exists("all/$email"))
                            mkdir("all/$email");
                        $f = './all/' . Auth::user()->email;
                        $io = popen('/usr/bin/du -sh ' . $f, 'r');
                        $size = fgets($io, 4096);
                        $size = substr($size, 0, strpos($size, "\t"));

                        pclose($io);
                        $size = 100 - intval($size);
                        if ($size < 0) {
                            return view('/home');
                        }

                    }
                    $time = time();
                    mkdir("all/$email/$time", 0755, true);


                    $_SESSION['id'] = substr($species1[0], 0, 3) . substr($path[0], 0, 5);
                    $_SESSION['suffix'] = $suffix;
                    $_SESSION['workingdir'] = "/all/" . $email . "/" . $time;
                    $_SESSION['anal_type'] = $anal_type;

                    $_SESSION['analyses_id'] = $time;
                    if (isset($_POST["multistate"]))
                        $_SESSION['multistate'] = "T";
                    else
                        $_SESSION['multistate'] = "T";

                    $destFile = public_path() . "/all/$email/$time/";
                    $argument .= "targedir:" . public_path() . "/all/" . $email . "/" . $time;
                    $argument .= ",filename:" . $filename;
                    if ($_FILES['gfile']['size'] > 0) {
                        $file->move($destFile, $filename);
                    } else {
                        header("Location:NewAnalysis.php");
                        exit;
                    }
                    $_SESSION['argument'] = $argument;

                    if (Input::hasFile('cfile')) {
                        $file1 = Input::file('cfile');
                        $filename1 = Input::file('cfile')->getClientOriginalName();
                        $cpd_extension = file_ext($filename1);
                        if ($cpd_extension == "txt" || $cpd_extension == "csv" || $cpd_extension == "rda") {
                            $argument .= ",cpdextension:" . $cpd_extension;
                            $argument .= ",cfilename:" . $filename1;
                            $file1->move($destFile, $filename1);
                        }
                    }
                    $_SESSION['argument'] = $argument;
                    $pathidx = 1;
                    $pathway_array = array();
                    $path = "pathway" . $pathidx;
                    while (isset($_POST[$path])) {
                        $path1 = explode("-", $_POST["pathway$pathidx"]);

                        if (strcmp(substr($path1[0], 0, 5), $path_id) != 0) {

                            array_push($pathway_array, substr($path1[0], 0, 5));
                        }
                        /* $argument .= ",pathway$pathidx:" . substr($path1[0], 0, 5);*/


                        $pathidx++;
                        $path = "pathway" . $pathidx;
                    }

                    $pathway_array1 = array_unique($pathway_array);

                    $pathcounter = 1;

                    foreach ($pathway_array1 as $val1) {
                        $path_id12 = substr($val1[0], 0, 5);
                        $val = DB::select(DB::raw("select pathway_id from Pathway where pathway_id like '$path_id12' LIMIT 1"));
                        if (sizeof($val) > 0) {
                            $argument .= "pathway$pathcounter:" . $val[0]->pathway_id . ",";

                        } else {
                            array_push($errors, "Entered pathway ID doesn't exist");
                            $err_atr["pathway" . $pathcounter] = 1;
                        }
                        $pathcounter++;

                    }
                    $argument .= ",pathidx:" . ($pathcounter);


                }

            } else {
                return view('analysis.NewAnalysis');
            }

        } /* analysis type is example analysis */
        else if ($anal_type == "exampleAnalysis1" || $anal_type == "exampleAnalysis2") {

            if (Input::get('gcheck') == 'T') {

                //$file = Input::file('gfile');
                $filename = "gse16873.d3.txt";
                $destFile = public_path();
                $gene_extension = file_ext($filename);
                if ($gene_extension == "txt" || $gene_extension == "csv" || $gene_extension == "rda") {
                    $argument .= "geneextension:" . $gene_extension . ",";


                    if (is_null(Auth::user())) {
                        $email = "demo";
                    } else {
                        $email = Auth::user()->email;
                        if (!file_exists("all/$email"))
                            mkdir("all/$email");
                        $f = './all/' . Auth::user()->email;
                        $io = popen('/usr/bin/du -sh ' . $f, 'r');
                        $size = fgets($io, 4096);
                        $size = substr($size, 0, strpos($size, "\t"));

                        pclose($io);
                        $size = 100 - intval($size);
                        if ($size < 0) {
                            return view('/home');
                        }
                    }


                    mkdir("all/$email/$time", 0755, true);


                    $_SESSION['id'] = substr($species1[0], 0, 3) . substr($path[0], 0, 5);
                    $_SESSION['suffix'] = $suffix;
                    $_SESSION['workingdir'] = "/all/" . $email . "/" . $time;
                    $_SESSION['anal_type'] = $anal_type;
                    $_SESSION['analyses_id'] = $time;


                    if (isset($_POST["multistate"]))
                        $_SESSION['multistate'] = "T";
                    else
                        $_SESSION['multistate'] = "T";

                    $destFile = public_path() . "/all/$email/$time/";
                    $destFile1 = "/all/$email/$time/";
                    $argument .= "targedir:" . public_path() . "/all/" . $email . "/" . $time;
                    $argument .= ",filename:" . $filename;
                    copy("all/demo/example/gse16873.d3.txt", $destFile . "/gse16873.d3.txt");

                    if (Input::get('cpdcheck') == 'T') {
                        //$file1 = Input::file('cfile');
                        $filename1 = "sim.cpd.data2.csv";
                        $cpd_extension = file_ext($filename1);
                        if ($cpd_extension == "txt" || $cpd_extension == "csv" || $cpd_extension == "rda") {
                            $argument .= ",cpdextension:" . $cpd_extension;
                            $argument .= ",cfilename:" . $filename1;

                            copy("all/demo/example/sim.cpd.data2.csv", $destFile . "/sim.cpd.data2.csv");
                        }
                    }
                    $_SESSION['argument'] = $argument;
                    $pathway_array = array();
                    $pathidx = 1;
                    $path = "pathway" . $pathidx;
                    while (isset($_POST[$path])) {
                        $path1 = explode("-", $_POST["pathway$pathidx"]);

                        if (strcmp(substr($path1[0], 0, 5), $path_id) != 0) {
                            array_push($pathway_array, substr($path1[0], 0, 5));
                        }
                        /* $argument .= ",pathway$pathidx:" . substr($path1[0], 0, 5);*/
                        $pathway_array1 = array_unique($pathway_array);

                        $pathidx++;
                        $path = "pathway" . $pathidx;
                    }
                    $pathcounter = 1;
                    foreach ($pathway_array1 as $val) {
                        $argument .= ",pathway$pathcounter:" . $val;
                        $pathcounter++;
                    }
                }

                $argument .= ",pathidx:" . ($pathcounter);
            } else {
                return view('analysis.exampleAnalysis1');
            }
        } else if ($anal_type == "exampleAnalysis3") {

            if (Input::get('gcheck') == 'T') {

                //$file = Input::file('gfile');
                $filename = "gene.ensprot.txt";
                $destFile = public_path();
                $gene_extension = file_ext($filename);
                if ($gene_extension == "txt" || $gene_extension == "csv" || $gene_extension == "rda") {
                    $argument .= "geneextension:" . $gene_extension . ",";


                    if (is_null(Auth::user())) {
                        $email = "demo";
                    } else {
                        $email = Auth::user()->email;
                        if (!file_exists("all/$email"))
                            mkdir("all/$email");
                        $f = './all/' . Auth::user()->email;
                        $io = popen('/usr/bin/du -sh ' . $f, 'r');
                        $size = fgets($io, 4096);
                        $size = substr($size, 0, strpos($size, "\t"));

                        pclose($io);
                        $size = 100 - intval($size);
                        if ($size < 0) {
                            return view('/home')->with('error', 'No space avaialable please delete some previous analysis');
                        }
                    }
                    $time = time();
                    mkdir("all/$email/$time", 0755, true);


                    $_SESSION['id'] = substr($species1[0], 0, 3) . substr($path[0], 0, 5);
                    $_SESSION['suffix'] = $suffix;
                    $_SESSION['workingdir'] = "/all/" . $email . "/" . $time;
                    $_SESSION['anal_type'] = $anal_type;
                    $_SESSION['analyses_id'] = $time;
                    if (isset($_POST["multistate"]))
                        $_SESSION['multistate'] = "T";
                    else
                        $_SESSION['multistate'] = "T";

                    $destFile = public_path() . "/all/$email/$time/";
                    $destFile1 = "/all/$email/$time/";
                    $argument .= "targedir:" . public_path() . "/all/" . $email . "/" . $time;
                    $argument .= ",filename:" . $filename;
                    copy("all/demo/example/gene.ensprot.txt", $destFile . "/gene.ensprot.txt");

                    if (Input::get('cpdcheck') == 'T') {
                        //$file1 = Input::file('cfile');
                        $filename1 = "cpd.cas.csv";
                        $cpd_extension = file_ext($filename1);
                        if ($cpd_extension == "txt" || $cpd_extension == "csv" || $cpd_extension == "rda") {
                            $argument .= ",cpdextension:" . $cpd_extension;
                            $argument .= ",cfilename:" . $filename1;
                            copy("all/demo/example/cpd.cas.csv", $destFile . "/cpd.cas.csv");
                        }
                    }
                    $_SESSION['argument'] = "cfilename:" . $filename1 . ",filename:" . $filename . "," . $argument;
                    $pathway_array = array();
                    $pathidx = 1;
                    $path = "pathway" . $pathidx;
                    while (isset($_POST[$path])) {
                        $path1 = explode("-", $_POST["pathway$pathidx"]);

                        if (strcmp(substr($path1[0], 0, 5), $path_id) != 0) {
                            array_push($pathway_array, substr($path1[0], 0, 5));
                        }
                        /* $argument .= ",pathway$pathidx:" . substr($path1[0], 0, 5);*/
                        $pathway_array1 = array_unique($pathway_array);

                        $pathidx++;
                        $path = "pathway" . $pathidx;
                    }
                    $pathcounter = 1;
                    foreach ($pathway_array1 as $val) {
                        $argument .= ",pathway$pathcounter:" . $val;
                        $pathcounter++;
                    }
                }
                $argument .= ",pathidx:" . ($pathcounter);
            } else {
                return view('analysis.exampleAnalysis2');
            }
        }

        /** gettingthe client ip address stored into database */
        function get_client_ip()
        {
            $ipaddress = '';
            if (getenv('HTTP_CLIENT_IP'))
                $ipaddress = getenv('HTTP_CLIENT_IP');
            else if (getenv('HTTP_X_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
            else if (getenv('HTTP_X_FORWARDED'))
                $ipaddress = getenv('HTTP_X_FORWARDED');
            else if (getenv('HTTP_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_FORWARDED_FOR');
            else if (getenv('HTTP_FORWARDED'))
                $ipaddress = getenv('HTTP_FORWARDED');
            else if (getenv('REMOTE_ADDR'))
                $ipaddress = getenv('REMOTE_ADDR');
            else if (isset($_SERVER['REMOTE_ADDR']))
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $ipaddress = 'UNKNOWN';
            return $ipaddress;
        }

        /** Rscript code running with the arguments */
        #exec("Rscript my_Rscript.R $argument  > $destFile.'/outputFile.Rout' 2> $destFile.'/errorFile.Rout'");
        #exec("Rscript my_input.R $argument > $destFile.'/outputFile.Rout' 2> $destFile.'/errorFile.Rout'");
        #exec("library(\"RSclient\") c=RS.connect() RS.eval(c,analyses(input))");
        try {
            $r = new Rserve_Connection(RSERVE_HOST);
            $r->evalString('analyses("' . $argument . '")');
            #return print_r($x);

            $r->close();
        } catch (Exception $e) {
            echo $e;
        }

        /** check if rscript generated any error if generated then log into database */
        $date = new \DateTime;


        if (Auth::user())
            DB::table('analyses')->insert(
                array('analysis_id' => $time . "", 'id' => Auth::user()->id . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'ipadd' => get_client_ip())
            );
        else
            DB::table('analyses')->insert(
                array('analysis_id' => $time . "", 'id' => '0' . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'ipadd' => get_client_ip())
            );

        /**
         * If error occured save the error into a database for debugging and error for administrator
         */
        /*$lines = file($destFile . "errorFile.Rout");
        $flag = false;
        foreach ($lines as $temp) {

            $temp = strtolower($temp);
            $array_string = explode(" ", $temp);
            foreach ($array_string as $a_string) {

                if (strcmp($a_string, 'error') == 0) {
                    DB::table('analyses_errors')->insert(array('analysis_id' => $time . "", 'error_string' => $temp, 'created_at' => $date));
                    $flag = true;
                    break;
                }

            }
            if ($flag) {
                break;
            }


        }*/


        $destFile1 = "/all/$email/$time/";

        return view('analysis.Result')->with(array('directory' => $destFile, 'directory1' => $destFile1));
    }

    public function post_exampleAnalysis1(CraeteAnalysisRequest $resqest)
    {
        $d = new AnalysisController();
        return $d->analysis("exampleAnalysis1");
    }

    public function post_exampleAnalysis2(CraeteAnalysisRequest $resqest)
    {
        $d = new AnalysisController();
        return $d->analysis("exampleAnalysis2");
    }

    public function post_exampleAnalysis3(CraeteAnalysisRequest $resqest)
    {
        $d = new AnalysisController();
        return $d->analysis("exampleAnalysis3");
    }

    public function example_one()
    {
        return view('analysis.example_one');
    }

    public function example_two()
    {
        return view('analysis.example_two');
    }

    public function example_three()
    {
        return view('analysis.example_three');
    }

}
