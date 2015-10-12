<?php namespace App\Http\Controllers\pathview\analysis;

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
use Response;
use Redis;
use Queue;
use File;
use Storage;
use Illuminate\Support\Facades\Cookie;
use App\Commands\SendJobAnalysisCompletionMail;
use App\Http\Controllers\Controller;
class AnalysisController extends Controller
{

    public $cookieEnabled = false;
    public $uID=0;
    public function delete()
    {
        $analysis_id = $_POST["analysisID"];

        if (strlen($analysis_id) > 13) {

            $analyis_array = explode(',', $analysis_id);

            foreach ($analyis_array as $analysis_id) {

                $result = DB::table('analysis')
                    ->where('analysis_id', '=', $analysis_id)
                    ->where('id', '=', Auth::user()->id)->first();
                if (sizeof($result) > 0) {
                    //analysis belong this user deleting using storage
                    $directory = public_path() . '/all/' . Auth::user()->email . '/' . $analysis_id;
                    DB::table('analysis')
                        ->where('analysis_id', '=', $analysis_id)
                        ->update(array('id' => "0"));
                    $success = File::deleteDirectory($directory);
                }
            }
            return Redirect::back()->with('success', 'Error');

        } else {

            $result = DB::table('analysis')
                ->where('analysis_id', '=', $analysis_id)
                ->where('id', '=', Auth::user()->id)->first();
            if (sizeof($result) > 0) {
                //analysis belong this user deleting using storage
                $directory = public_path() . '/all/' . Auth::user()->email . '/' . $analysis_id;
                DB::table('analysis')
                    ->where('analysis_id', '=', $analysis_id)
                    ->update(array('id' => "0"));
                $success = File::deleteDirectory($directory);
                return Redirect::back()->with('success', 'succes message');
            } else {
                return Redirect::back()->with('success', 'Error');


            }


        }
    }

    public function postAnalysis(CraeteAnalysisRequest $resqest)
    {
        $d = new AnalysisController();
        return $d->analysis("newAnalysis");
    }

    public function get_client_ip()
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

    public function analysis($anal_type)
    {



        try {

        //set cookie if the user is guest user so to track number of jobs submitted
            $uID = Cookie::get('uID');
            if(is_null($uID))
            {
                Cookie::queue("uID",uniqid(),1440);
                $uID = Cookie::get('uID');
            }

            if (Cookie::get('uID') == null)
            {
                $cookieEnabled = false;
                $uID = $this->get_client_ip();
            }
            else{
                $cookieEnabled = true;
            }

            $errors = array();
            $gene_cmpd_color = array();
            $pathway_array = array();
            $err_atr = array();
            $argument = "";
            $time = uniqid();
            $email = "";
//return print_r($_POST);
            /*copy all the post variables into session variables;*/
            foreach ($_POST as $key => $value) {
                $_SESSION[$key] = $value;
    
        }
            $Session = $_SESSION;

            /*regular expression match for all pathway of neumeric value of length 5 and pushing into an array*/

            preg_match_all('!\d{5}!', $_POST['selecttextfield'], $matches);

            foreach ($matches as $pathway1) {
                foreach ($pathway1 as $pathway)
                    array_push($pathway_array, $pathway);
            }

            //retrieving the unique pathway values from the array
            $pathway_array1 = array_unique($pathway_array);

            //$argument saves the argument to be sent to the Rscript which run the analysis
            $argument .= "pathway:";
            /*----------------------Pathway ID----------------------------------------------------------*/
            $i = 0;
            //check in database if the extacted Pathway exist or not if not exist dont add to the arguments and add it to errors list
            foreach ($pathway_array1 as $pathway) {
                $pathway = substr($pathway, 0, 5);
                $val = DB::select(DB::raw("select pathway_id from pathway where pathway_id like '$pathway' LIMIT 1"));
                if (sizeof($val) > 0) {
                    $argument .= $val[0]->pathway_id . ",";
                    $i++;
                } else {
                    array_push($errors, "Entered pathway ID doesn't exist");
                    $err_atr["pathway"] = 1;
                }
            }
            /*----------------------Pathway ID----------------------------------------------------------*/
            $argument .= ";";

            /*----------------------Gene ID----------------------------------------------------------*/
            $geneid = $_POST["geneid"];

            $val = DB::select(DB::raw("select gene_id  from gene where gene_id  like '$geneid' LIMIT 1 "));

            if (sizeof($val) > 0) {
                $argument .= "geneid:" . $val[0]->gene_id;
            } else {
                array_push($errors, "Entered Gene ID doesn't exist");
                $err_atr["geneid"] = 1;
            }
            /*----------------------Gene ID----------------------------------------------------------*/
            $argument .= ";";

            /*----------------------Compound ID----------------------------------------------------------*/

            $cpdid = $_POST["cpdid"];

            $val = DB::select(DB::raw("select compound_id  from compoundID where compound_id  like '$cpdid' LIMIT 1 "));

            if (sizeof($val) > 0) {
                $argument .= "cpdid:" . $val[0]->compound_id . ";";
            } else {
                array_push($errors, "Entered compound ID doesn't exist");
                $err_atr["cpdid"] = 1;
            }
            /*----------------------Compound ID----------------------------------------------------------*/

            /*----------------------Species ID----------------------------------------------------------*/
            $species1 = explode("-", $_POST["species"]);

            $val = DB::select(DB::raw("select species_id from species where species_id like '$species1[0]' LIMIT 1"));

            if (sizeof($val) > 0) {
                $argument .= "species:" . $val[0]->species_id . ";";
            } else {
                array_push($errors, "Entered Species ID doesn't exist");
                $err_atr["species"] = 1;
            }
            /*----------------------Species ID----------------------------------------------------------*/

            /*----------------------Suffix----------------------------------------------------------*/
            $suffix = preg_replace("/[^A-Za-z0-9 ]/", '', $_POST["suffix"]);

            $argument .= "suffix:" . $suffix . ";";
            /*----------------------Suffix----------------------------------------------------------*/

            /*----------------------Kegg ID----------------------------------------------------------*/
            if (isset($_POST["kegg"]))
                $argument .= "kegg:T;";
            else
                $argument .= "kegg:F;";
            /*----------------------Kegg ID----------------------------------------------------------*/


            /*----------------------Layer----------------------------------------------------------*/
            if (isset($_POST["layer"]))
                $argument .= "layer:T;";
            else
                $argument .= "layer:F;";
            /*----------------------Layer----------------------------------------------------------*/

            /*----------------------Split node----------------------------------------------------------*/
            if (isset($_POST["split"]))
                $argument .= "split:T;";
            else
                $argument .= "split:F;";
            /*----------------------Split node----------------------------------------------------------*/

            /*----------------------expand node----------------------------------------------------------*/
            if (isset($_POST["expand"]))
                $argument .= "expand:T;";
            else
                $argument .= "expand:F;";
            /*----------------------expand node----------------------------------------------------------*/

            /*----------------------multi state----------------------------------------------------------*/
            if (isset($_POST["multistate"]))
                $argument .= "multistate:T;";
            else
                $argument .= "multistate:F;";
            /*----------------------multi state----------------------------------------------------------*/

            /*----------------------match data----------------------------------------------------------*/
            if (isset($_POST["matchd"]))
                $argument .= "matchd:T;";
            else
                $argument .= "matchd:F;";
            /*----------------------match data----------------------------------------------------------*/

            /*----------------------gene discrete----------------------------------------------------------*/
            if (isset($_POST["gdisc"]))
                $argument .= "gdisc:T;";
            else
                $argument .= "gdisc:F;";
            /*----------------------gene discrete----------------------------------------------------------*/

            /*----------------------compound discrete----------------------------------------------------------*/
            if (isset($_POST["cdisc"]))
                $argument .= "cdisc:T;";
            else
                $argument .= "cdisc:F;";
            /*----------------------compound discrete----------------------------------------------------------*/

            /*----------------------Key Position----------------------------------------------------------*/
            $argument .= "kpos:" . $_POST["kpos"] . ";";
            /*----------------------Key Position----------------------------------------------------------*/

            /*----------------------Signature position----------------------------------------------------------*/
            $argument .= "pos:" . $_POST["pos"] . ";";
            /*----------------------Signature position----------------------------------------------------------*/

            /*----------------------Compound Label Offset----------------------------------------------------------*/
            if (preg_match('/[a-z]+/', $_POST["offset"])) {
                array_push($errors, "offset should be Numeric");
                $err_atr["offset"] = 1;
            } else {
                $argument .= "offset:" . $_POST["offset"] . ";";
            }
            /*----------------------Compound Label Offset----------------------------------------------------------*/

            /*----------------------Key Align----------------------------------------------------------*/
            $argument .= "align:" . $_POST["align"] . ";";
            /*----------------------Key Align----------------------------------------------------------*/

            /*----------------------Gene Limit----------------------------------------------------------*/
            if (preg_match('/[a-z]+/', $_POST["glmt"])) {
                array_push($errors, "Gene Limit should be Numeric");
                $err_atr["glmt"] = 1;
            }
            $glmtrange = str_replace(",", ",", $_POST["glmt"]);

            $argument .= "glmt:" . $glmtrange . ";";
            /*----------------------Gene Limit----------------------------------------------------------*/

            /*----------------------Gene Bins----------------------------------------------------------*/
            if (preg_match('/[a-z]+/', $_POST["gbins"])) {
                array_push($errors, "Gene Bins should be Numeric");
                $err_atr["gbins"] = 1;
            }
            $argument .= "gbins:" . $_POST["gbins"] . ";";
            /*----------------------Gene Bins----------------------------------------------------------*/

            /*----------------------Compound Limit----------------------------------------------------------*/
            if (preg_match('/[a-z]+/', $_POST["clmt"])) {
                array_push($errors, "Compound Limit should be Numeric");
                $err_atr["clmt"] = 1;
            }
            $clmtrange = str_replace(",", ";", $_POST["clmt"]);
            $argument .= "clmt:" . $clmtrange . ";";
            /*----------------------Compound Limit----------------------------------------------------------*/

            /*----------------------Compound Bins----------------------------------------------------------*/
            if (preg_match('/[a-z]+/', $_POST["cbins"])) {
                array_push($errors, "Compound Bins should be Numeric");
                $err_atr["cbins"] = 1;
            }
            $argument .= "cbins:" . $_POST["cbins"] . ";";
            /*----------------------Compound Bins----------------------------------------------------------*/


            /*----------------------Gene Color Low,Mid,High----------------------------------------------------------*/
            if (strpos($_POST["glow"], '#') !== false) {
                $argument .= "glow:" . $_POST["glow"] . ";";
            } else {
                $argument .= "glow:" . '#' . $_POST["glow"] . ";";
            }

            if (strpos($_POST["gmid"], '#') !== false) {
                $argument .= "gmid:" . $_POST["gmid"] . ";";
            } else {
                $argument .= "gmid:" . '#' . $_POST["gmid"] . ";";
            }

            if (strpos($_POST["ghigh"], '#') !== false) {
                $argument .= "ghigh:" . $_POST["ghigh"] . ";";
            } else {
                $argument .= "ghigh:" . '#' . $_POST["ghigh"] . ";";
            }
            //save for the session data
            $gene_cmpd_color["glow"] = $_POST["glow"];
            $gene_cmpd_color["gmid"] = $_POST["gmid"];
            $gene_cmpd_color["ghigh"] = $_POST["ghigh"];

            /*----------------------Gene Color Low,Mid,High----------------------------------------------------------*/


            /*----------------------Compound Color Low,Mid,High----------------------------------------------------------*/
            if (strpos($_POST["clow"], '#') !== false) {
                $argument .= "clow:" . $_POST["clow"] . ";";
            } else {
                $argument .= "clow:" . '#' . $_POST["clow"] . ";";
            }

            if (strpos($_POST["cmid"], '#') !== false) {
                $argument .= "cmid:" . $_POST["cmid"] . ";";
            } else {
                $argument .= "cmid:" . '#' . $_POST["cmid"] . ";";
            }

            if (strpos($_POST["chigh"], '#') !== false) {
                $argument .= "chigh:" . $_POST["chigh"] . ";";
            } else {
                $argument .= "chigh:" . '#' . $_POST["chigh"] . ";";
            }

            $gene_cmpd_color["clow"] = $_POST["clow"];
            $gene_cmpd_color["cmid"] = $_POST["cmid"];
            $gene_cmpd_color["chigh"] = $_POST["chigh"];

            /*----------------------Compound Color Low,Mid,High----------------------------------------------------------*/

            /*----------------------Node Sum----------------------------------------------------------*/
            $argument .= "nsum:" . $_POST["nodesun"] . ";";
            /*----------------------Node Sum----------------------------------------------------------*/

            /*----------------------Not Applicable Color----------------------------------------------------------*/
            $argument .= "ncolor:" . $_POST["nacolor"] . ";";
            /*----------------------Not Applicable Color----------------------------------------------------------*/


            /*----------------------Check for Compound and gene file uploaded----------------------------------------------------------*/
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
            /*----------------------Check for Compound and gene file uploaded----------------------------------------------------------*/


            /*----------------------IF there are error Generate error and redirect to the form page----------------------------------------------------------*/
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
            /*----------------------IF there are error Generate error and redirect to the form page----------------------------------------------------------*/

            $pathidx = 1;
            $path = "pathway" . $pathidx;


            /*----------------------Path for newAnalysis----------------------------------------------------------*/

            if ($anal_type == "newAnalysis") {




                if (Input::hasFile('gfile')) {

                    $file = Input::file('gfile');
                    $filename = Input::file('gfile')->getClientOriginalName();
                    $gene_extension = file_ext($filename);
                    if ($gene_extension == "txt" || $gene_extension == "csv" || $gene_extension == "rda") {
                        $argument .= "geneextension:" . $gene_extension . ";";
                        $argument .= "filename:" . $filename . ";";
                        $argument .= "";

                    }

                    if(!is_null($_POST['generef']))
                    {
                        if(strcmp(strtolower($_POST['generef']),"null")==0)
                        {
                            $argument .="generef:NULL";
                        }
                        else{
                            $geneRef = $_POST['generef'];
                            $argument .="generef:".$geneRef;
                        }
                    }
                    else{
                        $argument .="generef:NULL";

                    }
                    if(!is_null($_POST['genesam']))
                    {
                        if(strcmp(strtolower($_POST['genesam']),"null")==0)
                    {
                        $argument .=";genesamp:NULL";
                    }else{
                            $genesam = $_POST['genesam'];
                            $argument .=";genesamp:".$genesam;
                        }


                    }else{
                        $argument .=";genesam:NULL";
                    }


                    if (isset($_POST['genecompare']))
                        $argument .= ";genecompare:paired;";
                    else
                        $argument .= ";genecompare:unpaired;";






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
                        return view('errors.SpaceExceeded');
                    }
                }

                $time = uniqid();
                mkdir("all/$email/$time", 0755, false);


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
                        $argument .= ";cpdextension:" . $cpd_extension;
                        $argument .= ";cfilename:" . $filename1;

                    }


                    if(!is_null($_POST['cpdref']))
                    {
                        if(strcmp(strtolower($_POST['cpdref']),"null")==0)
                        {
                            $argument .=";cpdref:NULL";
                        }else{
                            $cpdRef = $_POST['cpdref'];
                            $argument .=";cpdref:".$cpdRef;
                        }



                    }else{
                        $argument .=";cpdref:NULL";
                    }
                    if(!is_null($_POST['cpdsam']))
                    {
                        if(strcmp(strtolower($_POST['cpdsam']),"null")==0)
                        {$argument .=";cpdsamp:NULL";

                        }else{
                            $cpdsam = str_replace(",",";",$_POST['cpdsam']);
                            $argument .=";cpdsamp:".$cpdsam;
                        }


                    }else{
                        $argument .=";cpdsamp:NULL";
                    }
                    if (isset($_POST['cpdCompare']))
                        $argument .= ";cpdcompare:paired;";
                    else
                        $argument .= ";cpdcompare:unpaired;";

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
            /*----------------------end for Path for newAnalysis----------------------------------------------------------*/
            /*----------------------Path for example analysis 1 and 2 ----------------------------------------------------------*/
            else if ($anal_type == "exampleAnalysis1" || $anal_type == "exampleAnalysis2") {

                if (Input::get('gcheck') == 'T' || Input::get('cpdcheck') == 'T') {

                    if (is_null(Auth::user())) {
                        $email = "demo";
                    } else {
                        $email = Auth::user()->email;
                        if (!file_exists("all/$email"))
                            mkdir("all/$email");
                        $f = './all/' . Auth::user()->email;
                        $io = popen('/usr/bin/du -sk ' . $f, 'r');
                        $size = fgets($io, 4096);
                        $size = substr($size, 0, strpos($size, "\t"));

                        pclose($io);
                        $size = 100000 - intval($size);
                        if ($size < 0) {
                            return view('errors.SpaceExceeded');
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
                    $argument .= "targedir:" . public_path() . "/all/" . $email . "/" . $time . ";";
                    if (Input::get('gcheck') == 'T') {
                        $filename = "gse16873.d3.txt";
                        $destFile = public_path() . "/all/$email/$time/";
                        $gene_extension = file_ext($filename);
                        if ($gene_extension == "txt" || $gene_extension == "csv" || $gene_extension == "rda") {
                            $argument .= "geneextension:" . $gene_extension . ";";
                            $argument .= "filename:" . $filename . ";";
                            copy("all/demo/example/gse16873.d3.txt", $destFile . "/gse16873.d3.txt");
                        }
                    }


                    if (Input::get('cpdcheck') == 'T') {
                        $filename1 = "sim.cpd.data2.csv";
                        $cpd_extension = file_ext($filename1);
                        if ($cpd_extension == "txt" || $cpd_extension == "csv" || $cpd_extension == "rda") {
                            $argument .= "cpdextension:" . $cpd_extension . ";";
                            $argument .= "cfilename:" . $filename1 . ";";
                            copy("all/demo/example/sim.cpd.data2.csv", $destFile . "/sim.cpd.data2.csv");
                        }
                    }
                    $_SESSION['argument'] = $argument;

                } else {
                    if ($anal_type == "exampleAnalysis1") {
                        return view('pathview_pages.analysis.exampleAnalysis1');
                    } else {
                        return view('pathview_pages.analysis.exampleAnalysis2');
                    }
                }
            }
            /*----------------------end Path for example analysis 1 and 2 ----------------------------------------------------------*/
            /*----------------------Path for example analysis 3 ----------------------------------------------------------*/
            else if ($anal_type == "exampleAnalysis3") {

                if (Input::get('gcheck') == 'T' || Input::get('cpdcheck') == 'T') {

                    $destFile = public_path();
                    if (is_null(Auth::user())) {
                        $email = "demo";
                    } else {
                        $email = Auth::user()->email;
                        if (!file_exists("all/$email"))
                            mkdir("all/$email");

                        $f = './all/' . Auth::user()->email;
                        $io = popen('/usr/bin/du -sk ' . $f, 'r');
                        $size = fgets($io, 4096);
                        $size = substr($size, 0, strpos($size, "\t"));

                        pclose($io);
                        $size = 100000 - intval($size);
                        if ($size < 0) {
                            return view('errors.SpaceExceeded');
                        }
                    }
                    $time = uniqid();
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
                    $argument .= "targedir:" . public_path() . "/all/" . $email . "/" . $time . ";";


                    if (Input::get('gcheck') == 'T') {
                        $filename = "gene.ensprot.txt";
                        $gene_extension = file_ext($filename);
                        if ($gene_extension == "txt" || $gene_extension == "csv" || $gene_extension == "rda") {
                            $argument .= "geneextension:" . $gene_extension . ";";
                            $argument .= "filename:" . $filename . ";";
                            copy("all/demo/example/gene.ensprot.txt", $destFile . "/gene.ensprot.txt");
                        }
                    }


                    if (Input::get('cpdcheck') == 'T') {
                        //$file1 = Input::file('cfile');
                        $filename1 = "cpd.cas.csv";
                        $cpd_extension = file_ext($filename1);
                        if ($cpd_extension == "txt" || $cpd_extension == "csv" || $cpd_extension == "rda") {
                            $argument .= "cpdextension:" . $cpd_extension . ";";
                            $argument .= "cfilename:" . $filename1 . ";";
                            copy("all/demo/example/cpd.cas.csv", $destFile . "/cpd.cas.csv");
                        }
                    }
                    $_SESSION['argument'] = $argument;


                } else {
                    return view('pathview_pages.analysis.exampleAnalysis2');
                }
            }
            /*----------------------Path for example analysis 3 ----------------------------------------------------------*/

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

            Redis::set('users_count', 0);
            //maintaining a counter on redis number of concurrent users
            if (is_null(Redis::get('users_count'))) {
                Redis::set('users_count', 1);
            } else {
                $ct = Redis::get('users_count')+1;

                Redis::set('users_count', $ct);

            }
            $destFile1 = "/all/$email/$time/";

            /*if(Redis::get('users_count') > 5) {*/
            $runHashdata = array();
            $runHashdata['argument'] = $argument;
            $runHashdata['destFile'] = $destFile;
            $runHashdata['anal_type'] = $anal_type;
            if (Auth::user())
                $runHashdata['user'] = Auth::user()->id;
            else
                $runHashdata['user'] = "demo";
            Redis::set($time, json_encode($runHashdata));
            Redis::set($time . ":Status", "false");


            //code to handle users more than 2 jobs in queue

            $curr_job1 = 0;
            $curr_job2 = 0;
            if(!is_null(Redis::get("id:".$uID)))
            $curr_job1 = Redis::get("id:".$uID);
            if(Auth::user())
                if(!is_null(Redis::get("id:".Auth::user()->email)))
                {
                    $curr_job2 = Redis::get("id:".Auth::user()->email);
                }


            $total_jobs = $curr_job1 + $curr_job2;

            if($total_jobs >=2)
            {

                    Redis::set("wait:".$time,true);
                $process_queue_id = -1;
            }
            else{

                $process_queue_id = Queue::push(new SendJobAnalysisCompletionMail($time));
                if(Auth::user())
                {
                    Redis::set("id:".Auth::user()->email,$total_jobs+1);
                    Redis::del("id:".$uID,0);
                }else{
                    Redis::set("id:".$uID,$total_jobs+1);
                }
            }


            return view('pathview_pages.analysis.Result')->with(array('exception' => null,
                'directory' => $destFile,
                'directory1' => $destFile1,
                'queueid' => $process_queue_id,
                'analysisid' => $time));
            /* return "pushed into queue will be sent an email shortly with all analysis done=" . $process_queue_id;*/
            /* }
             else{

                 $this->runAnalysis($time,$argument,$destFile,$anal_type);
                 return view('analysis.Result')->with(array('exception' => null, 'directory' => $destFile, 'directory1' => $destFile1));
                 Redis::set('users_count',Redis::get('users_count')-1 );
             }*/

            //return view('analysis.Result')->with(array('exception' => null, 'directory' => $destFile, 'directory1' => $destFile1));

        } catch (Exception $e) {
            Redis::set('users_count', Redis::get('users_count') - 1);
            $_SESSION['error'] = $e->getMessage();
            return view(errors . customError);

        }

    }


    public function runAnalysis($time, $argument, $destFile, $anal_type)
    {
        exec("/home/ybhavnasi/R-3.1.2/bin/Rscript my_Rscript.R  \"$argument\"  > $destFile.'/outputFile.Rout' 2> $destFile.'/errorFile.Rout'");


        $date = new \DateTime;

        if (Auth::user())
            DB::table('analysis')->insert(
                array('analysis_id' => $time . "", 'id' => Auth::user()->id . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date,'analysis_origin' => 'pathview', 'ipadd' => get_client_ip())
            );
        else
            DB::table('analysis')->insert(
                array('analysis_id' => $time . "", 'id' => '0' . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'analysis_origin' => 'pathview','ipadd' => get_client_ip())
            );
        //start If there are error in the code analysis saving into database for reporting and solving by admin
        $lines = file($destFile . "errorFile.Rout");
        $flag = false;
        foreach ($lines as $temp) {

            $temp = strtolower($temp);
            $array_string = explode(" ", $temp);
            foreach ($array_string as $a_string) {

                if (strcmp($a_string, 'error') == 0 || strcmp($a_string, 'warning:') == 0 || strcmp($a_string, 'error:') == 0) {
                    DB::table('analysisErrors')->insert(array('analysis_id' => $time . "", 'error_string' => $temp, 'created_at' => $date));
                    $flag = true;
                    break;
                }

            }
            if ($flag) {
                break;
            }


        }
        //end If there are error in the code analysis saving into database for reporting and solving by admin
        //returning the analysis
        //Redis::set('users_count', Redis::get('users_count') - 1);
    }


    public function new_analysis()
    {
        return view('pathview_pages.analysis.NewAnalysis');
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
        return view('pathview_pages.analysis.example_one');
    }

    public function example_two()
    {
        return view('pathview_pages.analysis.example_two');
    }

    public function example_three()
    {
        return view('pathview_pages.analysis.example_three');
    }

}