<?php namespace App\Http\Controllers;

/**
 * @Author: Yehsvant Bhavnasi, Dr. Weijun Luo
 * @Contact: byeshvant@hotmail.com
 * Pathview web @main controller here are all the analysis examples control functions are done
 */
use App\Http\Requests;
use App\Http\Requests\CraeteAnalysisRequest;
use Auth;
use DB;
use Illuminate\Support\Facades\Redirect;
use Input;
use Request;

require "Rserv_Connection.php";

class AnalysisController extends Controller
{

    public function new_analysis()
    {
        return view('analysis.NewAnalysis');
    }

    public function postAnalysis(CraeteAnalysisRequest $resqest)
    {
        $d = new AnalysisController();
        return $d->analysis("newAnalysis");
    }

    public function analysis($anal_type)
    {

        $errors = array();
        $gene_cmpd_color = array();
        $pathway_array = array();
        $err_atr = array();
        $argument = "";
        $time = time();
        $email = "";

        foreach ($_POST as $key => $value) {
            $_SESSION[$key] = $value;
        }

        $Session = $_SESSION;

        preg_match_all('!\d{5}!', $_POST['selecttextfield'], $matches);

        foreach ($matches as $pathway1) {
            foreach ($pathway1 as $pathway)
                array_push($pathway_array, $pathway);
        }

        $pathway_array1 = array_unique($pathway_array);

        $argument .= "pathway:";

        $i = 0;

        foreach ($pathway_array1 as $pathway) {
            $pathway = substr($pathway, 0, 5);
            $val = DB::select(DB::raw("select pathway_id from Pathway where pathway_id like '$pathway' LIMIT 1"));
            if (sizeof($val) > 0) {
                $argument .= $val[0]->pathway_id . ";";
                $i++;
            } else {
                array_push($errors, "Entered pathway ID doesn't exist");
                $err_atr["pathway"] = 1;
            }
        }

        $argument .= ",";

        $geneid = $_POST["geneid"];

        $val = DB::select(DB::raw("select geneid  from gene where geneid  like '$geneid' LIMIT 1 "));

        if (sizeof($val) > 0) {
            $argument .= "geneid:" . $val[0]->geneid . ",";
        } else {
            array_push($errors, "Entered Gene ID doesn't exist");
            $err_atr["geneid"] = 1;
        }

        $cpdid = $_POST["cpdid"];

        $val = DB::select(DB::raw("select cmpdid  from compound where cmpdid  like '$cpdid' LIMIT 1 "));

        if (sizeof($val) > 0) {
            $argument .= "cpdid:" . $val[0]->cmpdid . ",";
        } else {
            array_push($errors, "Entered compound ID doesn't exist");
            $err_atr["cpdid"] = 1;
        }

        $species1 = explode("-", $_POST["species"]);

        $val = DB::select(DB::raw("select species_id from Species where species_id like '$species1[0]' LIMIT 1"));

        if (sizeof($val) > 0) {
            $argument .= "species:" . $val[0]->species_id . ",";
        } else {
            array_push($errors, "Entered Species ID doesn't exist");
            $err_atr["species"] = 1;
        }

        $suffix = preg_replace("/[^A-Za-z0-9 ]/", '', $_POST["suffix"]);

        $argument .= "suffix:" . $suffix . ",";

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

        $argument .= "kpos:" . $_POST["kpos"] . ",";

        $argument .= "pos:" . $_POST["pos"] . ",";

        if (preg_match('/[a-z]+/', $_POST["offset"])) {
            array_push($errors, "offset should be Numeric");
            $err_atr["offset"] = 1;
        } else {
            $argument .= "offset:" . $_POST["offset"] . ",";
        }

        $argument .= "align:" . $_POST["align"] . ",";

        if (preg_match('/[a-z]+/', $_POST["glmt"])) {
            array_push($errors, "Gene Limit should be Numeric");
            $err_atr["glmt"] = 1;
        }

        if (preg_match('/[a-z]+/', $_POST["gbins"])) {
            array_push($errors, "Gene Bins should be Numeric");
            $err_atr["gbins"] = 1;
        }

        $glmtrange = str_replace(",", ";", $_POST["glmt"]);

        $argument .= "glmt:" . $glmtrange . ",";

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

        if (preg_match('/[a-z]+/', $_POST["clmt"])) {
            array_push($errors, "Compound Limit should be Numeric");
            $err_atr["clmt"] = 1;
        }

        if (preg_match('/[a-z]+/', $_POST["cbins"])) {
            array_push($errors, "Compound Bins should be Numeric");
            $err_atr["cbins"] = 1;
        }

        $clmtrange = str_replace(",", ";", $_POST["clmt"]);

        $argument .= "clmt:" . $clmtrange . ",";

        $argument .= "cbins:" . $_POST["cbins"] . ",";

        if (strpos($_POST["clow"], '#') !== false) {
            $argument .= "clow:" . $_POST["clow"] . ",";
        } else {
            $argument .= "clow:" . '#' . $_POST["clow"] . ",";
        }

        if (strpos($_POST["cmid"], '#') !== false) {
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

        $argument .= "nsum:" . $_POST["nodesun"] . ",";

        $argument .= "ncolor:" . $_POST["nacolor"] . ",";

        function file_ext($filename)
        {
            if (!preg_match('/\./', $filename)) return '';
            return preg_replace('/^.*\./', '', $filename);
        }

        function file_ext_strip($filename)
        {
            return preg_replace('/\.[^.]*$/', '', $filename);
        }

        if (Input::hasFile('gfile')) {
            $filename = Input::file('gfile')->getClientOriginalName();
            $gene_extension = file_ext($filename);
            if ($gene_extension != "txt" && $gene_extension != "csv" && $gene_extension != "Rdata") {
                array_push($errors, "Gene data file extension is not supported( use .txt,.csv,.rda)");
                $err_atr["gfile"] = 1;
            }
        }

        if (Input::hasFile('cpdfile')) {
            $filename1 = Input::file('cpdfile')->getClientOriginalName();
            $cpd_extension = file_ext($filename1);
            if ($cpd_extension != "txt" && $cpd_extension != "csv" && $cpd_extension != "Rdata") {
                array_push($errors, "compound data file extension is not supported( use .txt,.csv,.rda)");
                $err_atr["cpdfile"] = 1;
            }
        }

        $pathidx = 1;
        $path = "pathway" . $pathidx;

        if (sizeof($errors) > 0) {
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


        if ($anal_type == "newAnalysis") {

            if (Input::hasFile('gfile')) {
                $file = Input::file('gfile');
                $filename = Input::file('gfile')->getClientOriginalName();
                $gene_extension = file_ext($filename);
                if ($gene_extension == "txt" || $gene_extension == "csv" || $gene_extension == "rda") {
                    $argument .= "geneextension:" . $gene_extension . ",";
                    $argument .= "filename:" . $filename . ",";
                }
            }

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
            $oldmask = umask(0);
            mkdir("all/$email/$time", 0755, false);
            umask($oldmask);

            $_SESSION['id'] = $species1[0] . substr($path[0], 0, 5);
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

            $_SESSION['argument'] = $argument;

            if (Input::hasFile('cpdfile')) {
                $file1 = Input::file('cpdfile');
                $filename1 = Input::file('cpdfile')->getClientOriginalName();
                $cpd_extension = file_ext($filename1);
                if ($cpd_extension == "txt" || $cpd_extension == "csv" || $cpd_extension == "rda") {
                    $argument .= ",cpdextension:" . $cpd_extension;
                    $argument .= ",cfilename:" . $filename1;

                }
            }
            if ($_FILES['gfile']['size'] > 0) {
                $file->move($destFile, $filename);
            }

            if ($_FILES['cpdfile']['size'] > 0) {
                $file1->move($destFile, $filename1);
            }

            if ($_FILES['cpdfile']['size'] == 0 && $_FILES['gfile']['size'] == 0) {
                array_push($errors, "Input file cannot be empty");
                $err_atr['gfile'] = 1;
                return Redirect::to('analysis')
                    ->with('err', $errors)
                    ->with('err_atr', $err_atr)
                    ->with('Sess', $Session)->with('genecolor', $gene_cmpd_color);

            }
            $_SESSION['argument'] = $argument;


        } /* analysis type is example analysis */

        else if ($anal_type == "exampleAnalysis1" || $anal_type == "exampleAnalysis2") {

            if (Input::get('gcheck') == 'T' || Input::get('cpdcheck') == 'T') {

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
                $_SESSION['id'] = $species1[0] . substr($path[0], 0, 5);
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
                $argument .= "targedir:" . public_path() . "/all/" . $email . "/" . $time . ",";
                if (Input::get('gcheck') == 'T') {
                    $filename = "gse16873.d3.txt";
                    $destFile = public_path() . "/all/$email/$time/";
                    $gene_extension = file_ext($filename);
                    if ($gene_extension == "txt" || $gene_extension == "csv" || $gene_extension == "rda") {
                        $argument .= "geneextension:" . $gene_extension . ",";
                        $argument .= "filename:" . $filename . ",";
                        copy("all/demo/example/gse16873.d3.txt", $destFile . "/gse16873.d3.txt");
                    }
                }


                if (Input::get('cpdcheck') == 'T') {
                    $filename1 = "sim.cpd.data2.csv";
                    $cpd_extension = file_ext($filename1);
                    if ($cpd_extension == "txt" || $cpd_extension == "csv" || $cpd_extension == "rda") {
                        $argument .= "cpdextension:" . $cpd_extension . ",";
                        $argument .= "cfilename:" . $filename1 . ",";
                        copy("all/demo/example/sim.cpd.data2.csv", $destFile . "/sim.cpd.data2.csv");
                    }
                }
                $_SESSION['argument'] = $argument;

            } else {
                if ($anal_type == "exampleAnalysis1") {
                    return view('analysis.exampleAnalysis1');
                } else {
                    return view('analysis.exampleAnalysis2');
                }
            }
        } else if ($anal_type == "exampleAnalysis3") {

            if (Input::get('gcheck') == 'T' || Input::get('cpdcheck') == 'T') {

                $destFile = public_path();
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


                $_SESSION['id'] = $species1[0] . substr($path[0], 0, 5);
                $_SESSION['suffix'] = $suffix;
                $_SESSION['workingdir'] = "/all/" . $email . "/" . $time;
                $_SESSION['anal_type'] = $anal_type;
                $_SESSION['analyses_id'] = $time;
                if (isset($_POST["multistate"]))
                    $_SESSION['multistate'] = "T";
                else
                    $_SESSION['multistate'] = "T";

                $destFile = public_path() . "/all/$email/$time/";
                $argument .= "targedir:" . public_path() . "/all/" . $email . "/" . $time . ",";


                if (Input::get('gcheck') == 'T') {
                    $filename = "gene.ensprot.txt";
                    $gene_extension = file_ext($filename);
                    if ($gene_extension == "txt" || $gene_extension == "csv" || $gene_extension == "rda") {
                        $argument .= "geneextension:" . $gene_extension . ",";
                        $argument .= "filename:" . $filename . ",";
                        copy("all/demo/example/gene.ensprot.txt", $destFile . "/gene.ensprot.txt");
                    }
                }


                if (Input::get('cpdcheck') == 'T') {
                    //$file1 = Input::file('cfile');
                    $filename1 = "cpd.cas.csv";
                    $cpd_extension = file_ext($filename1);
                    if ($cpd_extension == "txt" || $cpd_extension == "csv" || $cpd_extension == "rda") {
                        $argument .= "cpdextension:" . $cpd_extension . ",";
                        $argument .= "cfilename:" . $filename1 . ",";
                        copy("all/demo/example/cpd.cas.csv", $destFile . "/cpd.cas.csv");
                    }
                }
                $_SESSION['argument'] = $argument;


            } else {
                return view('analysis.exampleAnalysis2');
            }
        }


        function get_client_ip()
        {

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

        exec("/home/ybhavnasi/R-3.1.2/bin/Rscript my_Rscript.R \"$argument\"  > $destFile.'/outputFile.Rout' 2> $destFile.'/errorFile.Rout'");

        $date = new \DateTime;

        if (Auth::user())
            DB::table('analyses')->insert(
                array('analysis_id' => $time . "", 'id' => Auth::user()->id . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'ipadd' => get_client_ip())
            );
        else
            DB::table('analyses')->insert(
                array('analysis_id' => $time . "", 'id' => '0' . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'ipadd' => get_client_ip())
            );

        $lines = file($destFile . "errorFile.Rout");
        $flag = false;
        foreach ($lines as $temp) {

            $temp = strtolower($temp);
            $array_string = explode(" ", $temp);
            foreach ($array_string as $a_string) {

if (strcmp($a_string, 'error') == 0 || strcmp($a_string, 'warning:') == 0 || strcmp($a_string, 'error:')==0 ) {
                    DB::table('analyses_errors')->insert(array('analysis_id' => $time . "", 'error_string' => $temp, 'created_at' => $date));
                    $flag = true;
                    break;
                }

            }
            if ($flag) {
                break;
            }


        }
        $destFile1 = "/all/$email/$time/";

        return view('analysis.Result')->with(array('exception' => null, 'directory' => $destFile, 'directory1' => $destFile1));
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
