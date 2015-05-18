<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CraeteAnalysisRequest;
use Auth;
use DB;
use Illuminate\Support\Facades\Redirect;
use Input;
use Request;

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
        //this.analysis("new_analysis");

    }


    public function analysis($anal_type)
    {



$errors =array();
        $err_atr = array();
        $argument = "";

$geneid = $_POST["geneid"];
        //check for geneid entered is currect or notlike \"'+$_POST["geneid"]+'%\" LIMIT 1'
        $val = DB::select(DB::raw("select geneid  from gene where geneid  like '$geneid' LIMIT 1 "));
        if(sizeof($val)>0)
        {
            $argument .= "geneid:" . $val[0]->geneid  . ",";
        }
        else
        {

            array_push($errors,"Entered Gene ID doesn't exist");
            array_push($err_atr,"geneid");

        }
        $cpdid = str_replace(" ", "-", $_POST["cpdid"]);
        $val = DB::select(DB::raw("select cmpdid  from compound where cmpdid  like '$cpdid' LIMIT 1 "));
        if(sizeof($val)>0)
        {
            $argument .= "cpdid:" . $val[0]->cmpdid  . ",";
        }
        else
        {
            array_push($errors,"Entered compound ID doesn't exist");
            array_push($err_atr,"cpdid");
        }

        $species1 = explode("-", $_POST["species"]);
        $spe = substr($species1[0], 0, 3);
        $val = DB::select(DB::raw("select species_id from Species where species_id like '$spe%' LIMIT 1" ));

        if(sizeof($val)>0)
        {
            $argument .= "species:" . $val[0]->species_id . ",";
        }
        else
        {
            array_push($errors,"Entered Species ID doesn't exist");
            array_push($err_atr,"species");
        }

        $suffix = str_replace(" ", "-", $_POST["suffix"]);
        $argument .= "suffix:" . $suffix . ",";


        $path = explode("-", $_POST["pathway"]);
        $path_id =substr($path[0], 0, 5);
        if (preg_match('/[a-z]+[0-9]+/', substr($path[0], 0, 5)))
        {

            $path_id =substr($path[0], 3, 8);
            $spe = substr($path[0], 0, 3);
            $val = DB::select(DB::raw("select species_id from Species where species_id like '$spe%' LIMIT 1" ));

            if(sizeof($val)>0)
            {
                $argument .= "species:" . $val[0]->species_id . ",";
            }
            else
            {
                array_push($errors,"Entered Species ID doesn't exist");
                array_push($err_atr,"species");
            }
        }
        else
        {
            $path_id =substr($path[0], 0, 5);
        }
        $val = DB::select(DB::raw("select pathway_id from Pathway where pathway_id like '$path_id%' LIMIT 1" ));
        if(sizeof($val)>0)
        {
            $argument .= "pathway:" . $val[0]->pathway_id . ",";
        }
        else
        {
            array_push($errors,"Entered pathway ID doesn't exist");
            array_push($err_atr,"pathway");
        }
        #$argument .= "pathway:" . substr($path[0], 0, 5) . ",";
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
        if (preg_match('/[a-z]+/', $_POST["offset"]))
        {
            array_push($errors,"offset should be Numeric");
            array_push($err_atr,"offset");
        }
        else {
            $argument .= "offset:" . $_POST["offset"] . ",";
        }
        $argument .= "align:" . $_POST["align"] . ",";

       # if (Input::hasFile('gfile')) {
        if(isset($_POST["gcheck"])){
            if($anal_type=="newAnalysis") {
                if (Input::hasFile('gfile')) {
                if (preg_match('/[a-z]+/', $_POST["glmt"])) {
                    array_push($errors, "glimit should be Numeric");
                    array_push($err_atr,"glmt");
                }
                if (preg_match('/[a-z]+/', $_POST["gbins"])) {
                    array_push($errors, "gbins should be Numeric");
                    array_push($err_atr,"gbins");
                }
                $argument .= "glmt:" . $_POST["glmt"] . ",";
                $argument .= "gbins:" . $_POST["gbins"] . ",";
                $argument .= "glow:" . $_POST["glow"] . ",";
                $argument .= "gmid:" . $_POST["gmid"] . ",";
                $argument .= "ghigh:" . $_POST["ghigh"] . ",";
            }}
            else
            {
                if (preg_match('/[a-z]+/', $_POST["glmt"])) {
                    array_push($errors, "glimit should be Numeric");
                    array_push($err_atr,"glmt");
                }
                if (preg_match('/[a-z]+/', $_POST["gbins"])) {
                    array_push($errors, "gbins should be Numeric");
                    array_push($err_atr,"gbins");
                }
                $argument .= "glmt:" . $_POST["glmt"] . ",";
                $argument .= "gbins:" . $_POST["gbins"] . ",";
                $argument .= "glow:" . $_POST["glow"] . ",";
                $argument .= "gmid:" . $_POST["gmid"] . ",";
                $argument .= "ghigh:" . $_POST["ghigh"] . ",";
            }
        }

        #if (Input::hasFile('cfile')) {
        if(isset($_POST["cpdcheck"])){
            if($anal_type=="newAnalysis") {
                if (Input::hasFile('cfile')) {
                    if (preg_match('/[a-z]+/', $_POST["clmt"])) {
                        array_push($errors, "climit should be Numeric");
                        array_push($err_atr,"clmt");
                    }
                    if (preg_match('/[a-z]+/', $_POST["cbins"])) {
                        array_push($errors, "cbins should be Numeric");
                        array_push($err_atr,"cbins");
                    }
                    $argument .= "clmt:" . $_POST["clmt"] . ",";
                    $argument .= "cbins:" . $_POST["cbins"] . ",";
                    $argument .= "clow:" . $_POST["clow"] . ",";
                    $argument .= "cmid:" . $_POST["cmid"] . ",";
                    $argument .= "chigh:" . $_POST["chigh"] . ",";
                }
            }
            else
            {
                if (preg_match('/[a-z]+/', $_POST["clmt"])) {
                    array_push($errors, "climit should be Numeric");
                    array_push($err_atr,"clmt");
                }
                if (preg_match('/[a-z]+/', $_POST["cbins"])) {
                    array_push($errors, "cbins should be Numeric");
                    array_push($err_atr,"cbins");
                }
                $argument .= "clmt:" . $_POST["clmt"] . ",";
                $argument .= "cbins:" . $_POST["cbins"] . ",";
                $argument .= "clow:" . $_POST["clow"] . ",";
                $argument .= "cmid:" . $_POST["cmid"] . ",";
                $argument .= "chigh:" . $_POST["chigh"] . ",";
            }
        }

            $argument .= "nsum:". $_POST["nodesun"].",";
        $argument .= "ncolor:". $_POST["nacolor"].",";
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
        if($gene_extension != "txt" && $gene_extension != "csv" && $gene_extension != "rda"){
            array_push($errors, "Gene data file extension is not supported( use .txt,.csv,.rda)");
            array_push($err_atr,"gfile");
        }}

        if (Input::hasFile('cfile')) {
            $filename1 = Input::file('cfile')->getClientOriginalName();
            $cpd_extension = file_ext($filename1);

            if ($cpd_extension != "txt" && $cpd_extension != "csv" && $cpd_extension != "rda") {
                array_push($errors, "compound data file extension is not supported( use .txt,.csv,.rda)");
                array_push($err_atr,"cfile");
            }
        }
if(sizeof($errors)>0)
{

    foreach ($_POST as $key => $value) {
        $_SESSION[$key] = $value;
    }
    $Session = $_SESSION;

    if(strcmp($anal_type,'exampleAnalysis1')==0)
    {

    return Redirect::to('example1')
        ->with('err',$errors)
        ->with('err_atr',$err_atr)
        ->with('Sess',$Session);;}
    else if(strcmp($anal_type,'exampleAnalysis2')==0)
    {
        return Redirect::to('example1')
            ->with('err',$errors)
            ->with('err_atr',$err_atr)
            ->with('Sess',$Session);
    }
    else if(strcmp($anal_type,'exampleAnalysis3')==0)
    {
        return Redirect::to('example3')
            ->with('err',$errors)
            ->with('err_atr',$err_atr)
            ->with('Sess',$Session);;
    }

}


        function create_zip($files = array(), $destination = '', $overwrite = false)
        {
            //if the zip file already exists and overwrite is false, return false
            if (file_exists($destination) && !$overwrite) {
                return false;
            }
            //vars
            $valid_files = array();
            //if files were passed in...
            if (is_array($files)) {
                //cycle through each file
                foreach ($files as $file) {
                    //make sure the file exists
                    if (file_exists($file)) {
                        $valid_files[] = $file;
                    }
                }
            }
            //if we have good files...
            if (count($valid_files)) {
                //create the archive
                $zip = new ZipArchive();
                if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                    return false;
                }
                //add the files
                foreach ($valid_files as $file) {
                    $zip->addFile($file, $file);
                }
                //debug
                //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

                //close the zip -- done!
                $zip->close();

                //check to make sure the file exists
                return file_exists($destination);
            } else {
                return false;
            }
        }


        if ($anal_type == "newAnalysis") {

            if (Input::hasFile('gfile')) {

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
                    }
                    $time = time();
                    mkdir("all/$email/$time", 0755, true);


                    $_SESSION['id'] = substr($species1[0], 0, 3) . substr($path[0], 0, 5);
                    $_SESSION['suffix'] = $suffix;
                    $_SESSION['workingdir'] = public_path() . "/all/" . $email . "/" . $time;


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

                    $pathidx = 1;
                    $path = "pathway" . $pathidx;
                    while (isset($_POST[$path])) {
                        $path1 = explode("-", $_POST["pathway$pathidx"]);
                        $argument .= ",pathway$pathidx:" . substr($path1[0], 0, 5);

                        $pathidx++;
                        $path = "pathway" . $pathidx;
                    }

                }
                $argument .= ",pathidx:" . ($pathidx);
            } else {
                return view('analysis.NewAnalysis');
            }
        } else if ($anal_type == "exampleAnalysis1" || $anal_type == "exampleAnalysis2") {

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
                    }
                    $time = time();

                    if(sizeof($errors)>0) {
                        return $errors;#view('analysis.NewAnalysis')->with("error"->$errors);
                    }
                    mkdir("all/$email/$time", 0755, true);


                    $_SESSION['id'] = substr($species1[0], 0, 3) . substr($path[0], 0, 5);
                    $_SESSION['suffix'] = $suffix;
                    $_SESSION['workingdir'] = public_path() . "/all/" . $email . "/" . $time;


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

                    $pathidx = 1;
                    $path = "pathway" . $pathidx;
                    while (isset($_POST[$path])) {
                        $path1 = explode("-", $_POST["pathway$pathidx"]);
                        $argument .= ",pathway$pathidx:" . substr($path1[0], 0, 5);

                        $pathidx++;
                        $path = "pathway" . $pathidx;
                    }

                }

                $argument .= ",pathidx:" . ($pathidx);
            } else {
                return view('analysis.NewAnalysis');
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
                    }
                    $time = time();
                    mkdir("all/$email/$time", 0755, true);


                    $_SESSION['id'] = substr($species1[0], 0, 3) . substr($path[0], 0, 5);
                    $_SESSION['suffix'] = $suffix;
                    $_SESSION['workingdir'] = public_path() . "/all/" . $email . "/" . $time;


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

                    $pathidx = 1;
                    $path = "pathway" . $pathidx;
                    while (isset($_POST[$path])) {
                        $path1 = explode("-", $_POST["pathway$pathidx"]);
                        $argument .= ",pathway$pathidx:" . substr($path1[0], 0, 5);

                        $pathidx++;
                        $path = "pathway" . $pathidx;
                    }

                }
                $argument .= ",pathidx:" . ($pathidx);
            } else {
                return view('analysis.NewAnalysis');
            }
        }
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

        #echo $argument;
        exec("Rscript my_Rscript.R $argument  > $destFile.'/outputFile.Rout' 2> $destFile.'/errorFile.Rout'");

        $date = new \DateTime;


        if (Auth::user())
            DB::table('analyses')->insert(
                array('analysis_id' => $time . "", 'id' => Auth::user()->id . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'ipadd' => get_client_ip())
            );
        else
            DB::table('analyses')->insert(
                array('analysis_id' => $time . "", 'id' => '0' . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'ipadd' => get_client_ip())
            );

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
