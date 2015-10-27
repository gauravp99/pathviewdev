<?php namespace App\Http\Controllers\gage;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Auth;
use Session;
use File;
use DB;
use Log;
use Redis;
use Illuminate\Http\Request;
use Queue;
use Storage;
use App\Commands\sendGageAnalysisCompletionMail;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * Class GageAnalysis
 * @package App\Http\Controllers
 * this controller is used to handle the gage analysis request. Takes all the form element creates argument list into a single variable
 * and send it to R script also creates folder at user profile do task relate to the analysis
 */
class GageAnalysisController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Response
     *
     * This index function does the following tasks
     * 1. Get the post attributes and creates argument variable seperating the multivalue attributes seperated by comma and a semicolon between each attribute
     * 2. Create the folder if the user is authorized
     * 3. Copy the input file uploaded by the user to the folder created
     * 4. Runs the analysis/ push the job into the queue
     * 5. insert the analysis info into the anlayses table
     * 6. Return the result page
     */
    public function index($analysis)
    {

        $argument = "";
        $destFile = "";
        $email = "";
        $filename = "";
        $time = "";

        /*
         * checks if the user is authorised or not if authorised check if he has the space enough for the analysis to be done
         * if the user exceed the min memory allocated to the user return a page to delete analysis and rerun it.
         */
        if (Auth::user()) {
            //email variable is location of the analysis folder
            $email = Auth::user()->email;
            $f = './all/' . Auth::user()->email;
            if (!file_exists( $f)) {
                try {
                    $result = File::makeDirectory($f);
                } catch (FileNotFoundException $e) {
                    Log::error('Not able to create the file: ' . $e);
                }
            }

            $io = popen('/usr/bin/du -sk ' . $f, 'r');
            $size = fgets($io, 4096);
            $size = substr($size, 0, strpos($size, "\t"));
            pclose($io);
            $size = 100000 - intval($size);
            //checking if file size is less than 99 MB
            if ($size < 1000) {
                return view('errors.SpaceExceeded');
            }
        }
        else{
            $email = "demo";
        }


        /*
         * Start code to create an argument variable to set all the values on form to a single variable and send it to Rscript code
         */

        //reference columns from input type text element
        if (isset($_POST['reference'])) {
            if (strcmp(strtolower($_POST['reference']), 'null') == 0 || strcmp(strtolower($_POST['reference']), '') == 0)
                $argument .= "reference:NULL;";
            else
                $argument .= "reference:" . $_POST['reference'] . ";";
        } else {
            $argument .= "reference:NULL;";
        }

        //sample columns from input type text element
        if (isset($_POST['samples'])) {
            if (strcmp(strtolower($_POST['samples']), 'null') == 0 || strcmp(strtolower($_POST['samples']), '') == 0)
                $argument .= "sample:NULL;";
            else
                $argument .= "sample:" . $_POST['samples'] . ";";
        } else {
            $argument .= "sample:NULL;";
        }

        //check if the file exist if the folder doesnt exist create one
        if (!file_exists("all/$email")) {
            try{
                $result = File::makeDirectory("all/$email");
            }
            catch(FileNotFoundException $e)
            {
                Log::error('Not able to create the file: '.$e);
            }
        }
        $time = uniqid();
        File::makeDirectory("all/$email/$time");
        $destFile = public_path() . "/" . "all/" . $email . "/" . $time . "/";

        //separate file copy for new analysis and example analysis. if the analysis is new analysis file is taken from
        // file upload where as it is from example analysis file is taken from the file in the public folder
        if (strcmp($analysis, 'newAnalysis') == 0) {

            if (Input::hasFile('assayData')) {
                $file = Input::file('assayData');
                $filename = Input::file('assayData')->getClientOriginalName();
                $file->move($destFile, $filename);
            } else {
                $_SESSION['error'] = 'Unfortunately file cannot be uploaded';
                return view('gage_pages.GageAnalysis');
            }
            $filename = Input::file('assayData')->getClientOriginalName();
        } else if (strcmp($analysis, 'exampleGageAnalysis1') == 0) {
            //copy files for example 1 to the destination user profile
            $filename = "gagedata.txt";
            $file = public_path() . "/" . "all/data/gagedata.txt";

            if (!File::copy($file, $destFile . $filename)) {
                $_SESSION['error'] = 'Unfortunately file cannot be uploaded';
                return view('gagei_pages.GageAnalysis');
            }
        }
        else if (strcmp($analysis, 'exampleGageAnalysis2') == 0) {
            //copy files for example 2 to the destination user profile
            $filename = "gse16873.symb.txt";
            $file = public_path() . "/" . "all/demo/example/gse16873.symb.txt";

            if (!File::copy($file, $destFile . $filename)) {
                $_SESSION['error'] = 'Unfortunately file cannot be uploaded';
                return view('gage_pages.GageAnalysis');
            }

        }
        else if(strcmp($analysis, 'GagePathviewAnalysis') == 0)
        {
            //copy files for example 1 to the destination user profile
            $filename = "gagedata.txt";
            $file = public_path() . "/" . "all/data/gagedata.txt";

            if (!File::copy($file, $destFile . $filename)) {
                $_SESSION['error'] = 'Unfortunately file cannot be uploaded';
                return view('gage_pages.GageAnalysis');
            }




        }

        $argument .= "filename:" . $filename . ";";
        $argument .= "destFile:" . $destFile . $filename . ";";
        $argument .= "destDir:" . $destFile . ";";
        $extension = preg_replace('/^.*\./', '', $filename);
        $argument .= "geneextension:" . $extension . ";";

        if (isset($_POST['geneSet']) && is_array($_POST['geneSet'])) {

            if (strcmp($_POST['geneSet'][0], 'sig.idx') == 0 || strcmp($_POST['geneSet'][0], 'met.idx') == 0 || strcmp($_POST['geneSet'][0], 'sigmet.idx') == 0 || strcmp($_POST['geneSet'][0], 'dise.idx') == 0 || strcmp($_POST['geneSet'][0], 'sigmet.idx,dise.idx') == 0) {
                $argument .= "geneSetCategory:kegg;";
            }
            else if (strcmp($_POST['geneSet'][0], 'BP') == 0 || strcmp($_POST['geneSet'][0], 'CC') == 0 || strcmp($_POST['geneSet'][0], 'MF') == 0 || strcmp($_POST['geneSet'][0], 'BP,CC,MF') == 0 ) {
                $argument .= "geneSetCategory:go;";
            }
            else if(strcmp($_POST['geneSet'][0], 'custom') == 0)
            {
                $argument .= "geneSetCategory:custom;";
            }
            $argument .= "geneSet:";
            foreach ($_POST['geneSet'] as $geneSet) {
                $argument .= $geneSet . ",";
            }
            $argument .= ";";
        }

        if (isset($_POST['species'])) {
            $argument .= "species:";
            if (strcmp($_POST['geneIdType'], 'entrez') == 0 || strcmp($_POST['geneIdType'], 'kegg') == 0) {
                $argument .= explode('-', $_POST['species'])[0] . ";";
            } else if(strcmp($_POST['geneIdType'], 'Entrez Gene') == 0 || strcmp($_POST['geneIdType'], 'ORF') == 0 || strcmp($_POST['geneIdType'], 'TAIR') == 0) {
                $goSpecies = DB::table('GoSpecies')->where('species_id',substr($_POST['species'],0,3))->first();

                if(sizeof($goSpecies) > 0)
                {
                    $argument .= explode('-',$goSpecies->Go_name )[0] . ";";
                }
                else{
                    $goSpecies1 = DB::table('GoSpecies')->where('Go_name',$_POST['species'])->first();
                    if(sizeof($goSpecies1) > 0)
                    {
                        $argument .= explode('-',$goSpecies1->Go_name )[0] . ";";
                    }
                    else{
                        $argument .= "Human;";
                    }
                }

            }
            else{
                $argument .= explode('-', $_POST['species'])[0] . ";";
            }
        }

        if (isset($_POST['cutoff'])) {
            $argument .= "cutoff:";
            $argument .= $_POST['cutoff'].";";
        }

        if (isset($_POST['geneIdType'])) {

            $argument .= "geneIdType:" . $_POST['geneIdType'] . ";";

            //if the example is 2nd example or geneIdType is geneIdType
            if (strcmp($_POST['geneIdType'], 'custom') == 0) {
                if(Input::hasFile('geneIdFile'))
                {
                    $file = Input::file('geneIdFile');
                    $filename1 = Input::file('geneIdFile')->getClientOriginalName();
                    $destFile = public_path() . "/" . "all/" . $email . "/" . $time . "/";
                    $file->move($destFile, $filename1);
                    $argument .= "gsfn:" . $filename1 . ";";
                    $argument .= "gsetextension:" . preg_replace('/^.*\./', '', $filename) . ";";
                }
                else{
                    $filename1= "c1_all_v3_0_symbols.gmt";
                    $file1 = public_path() . "/" . "all/demo/example/c1_all_v3_0_symbols.gmt";

                    if (!File::copy($file1, $destFile . $filename1)) {
                        $_SESSION['error'] = 'Unfortunately file cannot be uploaded';
                        return view('gage_pages.GageAnalysis');
                    }
                    $argument .= "gsfn:" . $filename1 . ";";
                    $argument .= "gsetextension:" . preg_replace('/^.*\./', '', $filename1) . ";";
                }
            }
        }



        if (isset($_POST['setSizeMin'])) {
            $argument .= "setSizeMin:" . $_POST['setSizeMin'] . ";";
        }

        if (isset($_POST['setSizeMax'])) {
            if (strcmp(strtolower($_POST['setSizeMax']), 'infinite') == 0) {
                $argument .= "setSizeMax:INF;";
            } else {
                $argument .= "setSizeMax:" . $_POST['setSizeMax'] . ";";
            }
        } else {
            $argument .= "setSizeMax:INF;";
        }

        if (isset($_POST['compare'])) {
            $argument .= "compare:" . $_POST['compare'] . ";";
        }

        if (isset($_POST['test2d'])) {
            $argument .= "test.2d:T;";
        } else {
            $argument .= "test.2d:F;";
        }


        if (isset($_POST['rankTest'])) {
            $argument .= "rankTest:T;";
        } else {
            $argument .= "rankTest:F;";
        }


        if (isset($_POST['useFold'])) {
            $argument .= "useFold:T;";
        } else {
            $argument .= "useFold:F;";
        }

        if (isset($_POST['test'])) {
            $argument .= "test:" . $_POST['test'] . ";";
        }

        if (isset($_POST['dopathview'])) {
            $argument .= "do.pathview:T;";
            if (isset($_POST['dataType'])) {
                $argument .= "data.type:" . $_POST['dataType'] . ";";
            }
            if(strcmp($analysis, 'GagePathviewAnalysis') == 0)
            {
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
                if (isset($_POST["offset"])) {
                    $argument .= "offset:" . $_POST["offset"] . ";";
                }
                /*----------------------Compound Label Offset----------------------------------------------------------*/

                /*----------------------Key Align----------------------------------------------------------*/
                $argument .= "align:" . $_POST["align"] . ";";
                /*----------------------Key Align----------------------------------------------------------*/

                /*----------------------Gene Limit----------------------------------------------------------*/
                if (isset($_POST["glmt"])){
                    $glmtrange = str_replace(",", ";", $_POST["glmt"]);

                    $argument .= "glmt:" . $glmtrange . ";";
                }
                /*----------------------Gene Limit----------------------------------------------------------*/

                /*----------------------Gene Bins----------------------------------------------------------*/
                if (isset($_POST["gbins"])){
                    $argument .= "gbins:" . $_POST["gbins"] . ";";
                }

                /*----------------------Gene Bins----------------------------------------------------------*/

                /*----------------------Compound Limit----------------------------------------------------------*/
                if (isset($_POST["clmt"])){
                    $clmtrange = str_replace(",", ";", $_POST["clmt"]);
                    $argument .= "clmt:" . $clmtrange . ";";
                }

                /*----------------------Compound Limit----------------------------------------------------------*/

                /*----------------------Compound Bins----------------------------------------------------------*/
                if (isset($_POST["cbins"])) {
                    $argument .= "cbins:" . $_POST["cbins"] . ";";
                }

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



                /*----------------------Compound Color Low,Mid,High----------------------------------------------------------*/

                /*----------------------Node Sum----------------------------------------------------------*/
                $argument .= "nsum:" . $_POST["nodesun"] . ";";
                /*----------------------Node Sum----------------------------------------------------------*/

                /*----------------------Not Applicable Color----------------------------------------------------------*/
                $argument .= "ncolor:" . $_POST["nacolor"] . ";";
                /*----------------------Not Applicable Color----------------------------------------------------------*/





            }

        } else {
            $argument .= "do.pathview:F;";
        }



        $_SESSION['argument'] = $argument;

        //Push job into queue code with status flag insert int redis

        /*$runHashdata = array();
        $runHashdata['argument'] = $argument;
        $runHashdata['destFile'] = $destFile;
        if (Auth::user())
            $runHashdata['user'] = Auth::user()->id;
        else
            $runHashdata['user'] = "demo";
        Redis::set($time, json_encode($runHashdata));
        Redis::set($time.":Status","false");
        $process_queue_id = Queue::push(new sendGageAnalysisCompletionMail($time));
        $_SESSION['argument'] = $argument;
        return  view('Gage.GageResultView');*/

        //creates a process to run the R script
        if (isset($_POST['dopathview'])&& strcmp($analysis, 'GagePathviewAnalysis') == 0)
        {
            exec("/home/ybhavnasi/R-3.1.2/bin/Rscript scripts/GagePathviewRscript.R  \"$argument\"  > $destFile.'/outputFile.Rout' 2> $destFile.'/errorFile.Rout'");
        }else {
            exec("/home/ybhavnasi/R-3.1.2/bin/Rscript scripts/PathviewGageRscript.R  \"$argument\"  > $destFile.'/outputFile.Rout' 2> $destFile.'/errorFile.Rout'");
        }
        //function to get the user using the application ip address

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

        $date = new \DateTime;

        //insert into the analysis table if the analysis is done event if the error occurred we insert into table
        if (Auth::user())
            DB::table('analysis')->insert(
                array('analysis_id' => $time . "", 'id' => Auth::user()->id . "", 'arguments' => $argument . "", 'analysis_type' => $analysis, 'created_at' => $date, 'ip_add' => get_client_ip(), 'analysis_origin' => 'gage')
            );
        else
            DB::table('analysis')->insert(
                array('analysis_id' => $time . "", 'id' => '0' . "", 'arguments' => $argument . "", 'analysis_type' => $analysis, 'created_at' => $date, 'ip_add' => get_client_ip(), 'analysis_origin' => 'gage')
            );

        return view('gage_pages.GageResult');

    }



    public function discreteGageAnalysis()
    {
        /**
         * check if the user is authorised user if not creating a directory within user directory.
         * if guest user a input folder is created under users folder
         */
        if (Auth::user()) {
            //email variable is location of the analysis folder
            $email = Auth::user()->email;
            $f = './all/' . Auth::user()->email;
            if (!file_exists( $f)) {
                try {
                    $result = File::makeDirectory($f);
                } catch (FileNotFoundException $e) {
                    Log::error('Not able to create the file: ' . $e);
                }
            }

            $io = popen('/usr/bin/du -sk ' . $f, 'r');
            $size = fgets($io, 4096);
            $size = substr($size, 0, strpos($size, "\t"));
            pclose($io);
            $size = 100000 - intval($size);
            //checking if file size is less than 99 MB
            if ($size < 1000) {
                return view('errors.SpaceExceeded');
            }
        }
        else{
            $email = "demo";
        }

        if (!file_exists("all/$email")) {
            try{
                $result = File::makeDirectory("all/$email");
            }
            catch(FileNotFoundException $e)
            {
                Log::error('Not able to create the file: '.$e);
            }
        }
        $time = uniqid();
        File::makeDirectory("all/$email/$time", 0777,true);
        $destFile = public_path() . "/" . "all/" . $email . "/" . $time . "/";
        /**
         * create a list of arguments from the user input
         */
        $argument = "";
        $argument .= "destDir:" . $destFile . ";";
        if(Input::hasFile('sampleListInputFile'))
        {
            $argument .="sampleList:file". ";";
            $file = Input::file('sampleListInputFile');
            $filename1 = Input::file('sampleListInputFile')->getClientOriginalName();
            $destFile = public_path() . "/" . "all/" . $email . "/" . $time . "/";
            $file->move($destFile, $filename1);
            $argument .="sampleListFile:".$filename1.";";
        }else{
            $argument .="sampleList:inputbox". ";";;
            $sampleList = $_POST['sampleList'];
            $file = public_path() . "/" . "all/" . $email . "/" . $time . "/sampleList.txt";
            $bytes_written = File::put($file, $sampleList);
            if ($bytes_written === false)
            {
                die("Error writing to file");
            }

        }
        if(Input::hasFile('backgroundListInputFile'))
        {
            $argument .="baclgroundList:file". ";";;
            $file = Input::file('backgroundListInputFile');
            $filename1 = Input::file('backgroundListInputFile')->getClientOriginalName();
            $destFile = public_path() . "/" . "all/" . $email . "/" . $time . "/";
            $file->move($destFile, $filename1);
            $argument .="baclgroundListFile:".$filename1.";";

        }else{
            $argument .="baclgroundList:inputbox". ";";
            $BackgroundList = $_POST['backgroundList'];
            $file = public_path() . "/" . "all/" . $email . "/" . $time . "/backgroundList.txt";
            File::put($file, $BackgroundList);
            if ($bytes_written === false)
            {
                die("Error writing to file");
            }

        }


        if (isset($_POST['geneSet']) && is_array($_POST['geneSet'])) {

            if (strcmp($_POST['geneSet'][0], 'sig.idx') == 0 || strcmp($_POST['geneSet'][0], 'met.idx') == 0 || strcmp($_POST['geneSet'][0], 'sigmet.idx') == 0 || strcmp($_POST['geneSet'][0], 'dise.idx') == 0 || strcmp($_POST['geneSet'][0], 'sigmet.idx,dise.idx') == 0) {
                $argument .= "geneSetCategory:kegg;";
            }
            else if (strcmp($_POST['geneSet'][0], 'BP') == 0 || strcmp($_POST['geneSet'][0], 'CC') == 0 || strcmp($_POST['geneSet'][0], 'MF') == 0 || strcmp($_POST['geneSet'][0], 'BP,CC,MF') == 0 ) {
                $argument .= "geneSetCategory:go;";
            }
            else if(strcmp($_POST['geneSet'][0], 'custom') == 0)
            {
                $argument .= "geneSetCategory:custom;";
            }
            $argument .= "geneSet:";
            foreach ($_POST['geneSet'] as $geneSet) {
                $argument .= $geneSet . ",";
            }
            $argument .= ";";
        }

        if (isset($_POST['species'])) {
            $argument .= "species:";
            if (strcmp($_POST['geneIdType'], 'entrez') == 0 || strcmp($_POST['geneIdType'], 'kegg') == 0) {
                $argument .= explode('-', $_POST['species'])[0] . ";";
            } else if(strcmp($_POST['geneIdType'], 'Entrez Gene') == 0 || strcmp($_POST['geneIdType'], 'ORF') == 0 || strcmp($_POST['geneIdType'], 'TAIR') == 0) {
                $goSpecies = DB::table('GoSpecies')->where('species_id',substr($_POST['species'],0,3))->first();

                if(sizeof($goSpecies) > 0)
                {
                    $argument .= explode('-',$goSpecies->Go_name )[0] . ";";
                }
                else{
                    $goSpecies1 = DB::table('GoSpecies')->where('Go_name',$_POST['species'])->first();
                    if(sizeof($goSpecies1) > 0)
                    {
                        $argument .= explode('-',$goSpecies1->Go_name )[0] . ";";
                    }
                    else{
                        $argument .= "Human;";
                    }
                }

            }
            else{
                $argument .= explode('-', $_POST['species'])[0] . ";";
            }
        }

        if (isset($_POST['cutoff'])) {
            $argument .= "cutoff:";
            $argument .= $_POST['cutoff'].";";
        }

        if (isset($_POST['geneIdType'])) {

            $argument .= "geneIdType:" . $_POST['geneIdType'] . ";";

            //if the example is 2nd example or geneIdType is geneIdType
            if (strcmp($_POST['geneIdType'], 'custom') == 0) {
                if(Input::hasFile('geneIdFile'))
                {
                    $file = Input::file('geneIdFile');
                    $filename1 = Input::file('geneIdFile')->getClientOriginalName();
                    $destFile = public_path() . "/" . "all/" . $email . "/" . $time . "/";
                    $file->move($destFile, $filename1);
                    $argument .= "gsfn:" . $filename1 . ";";
                    $argument .= "gsetextension:" . preg_replace('/^.*\./', '', $filename1) . ";";
                }
                else{
                    $filename1= "c1_all_v3_0_symbols.gmt";
                    $file1 = public_path() . "/" . "all/demo/example/c1_all_v3_0_symbols.gmt";

                    if (!File::copy($file1, $destFile . $filename1)) {
                        $_SESSION['error'] = 'Unfortunately file cannot be uploaded';
                        return view('gage_pages.GageAnalysis');
                    }
                    $argument .= "gsfn:" . $filename1 . ";";
                    $argument .= "gsetextension:" . preg_replace('/^.*\./', '', $filename1) . ";";
                }
            }
        }



        if (isset($_POST['setSizeMin'])) {
            $argument .= "setSizeMin:" . $_POST['setSizeMin'] . ";";
        }

        if (isset($_POST['setSizeMax'])) {
            if (strcmp(strtolower($_POST['setSizeMax']), 'infinite') == 0) {
                $argument .= "setSizeMax:INF;";
            } else {
                $argument .= "setSizeMax:" . $_POST['setSizeMax'] . ";";
            }
        } else {
            $argument .= "setSizeMax:INF;";
        }

        if (isset($_POST['resultBasedOn'])) {
            $argument .= "resultBasedOn:" . $_POST['resultBasedOn'] . ";";
        }
        $_SESSION['argument'] = $argument;
        $_SESSION['destDir'] = $destFile;
        exec("/home/ybhavnasi/R-3.1.2/bin/Rscript scripts/DiscreteGageRscript.R  \"$argument\"  > $destFile.'/outputFile.Rout' 2> $destFile.'/errorFile.Rout'");

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

        $date = new \DateTime;

        if (Auth::user())
            DB::table('analysis')->insert(
                array('analysis_id' => $time . "", 'id' => Auth::user()->id . "", 'arguments' => $argument . "", 'analysis_type' => "discreteGageAnalysis", 'created_at' => $date, 'ip_add' => get_client_ip(), 'analysis_origin' => 'gage')
            );
        else
            DB::table('analysis')->insert(
                array('analysis_id' => $time . "", 'id' => '0' . "", 'arguments' => $argument . "", 'analysis_type' => "discreteGageAnalysis", 'created_at' => $date, 'ip_add' => get_client_ip(), 'analysis_origin' => 'gage')
            );
        return view('gege_pages.DiscreteGageResult');

    }
    public function newGageAnalysis()
    {
        $d = new GageAnalysisController();
        return $d->index("newAnalysis");
    }

    public function ExampleGageAnalysis1()
    {
        $d = new GageAnalysisController();
        return $d->index("exampleGageAnalysis1");

    }
    public function ExampleGageAnalysis2()
    {
        $d = new GageAnalysisController();
        return $d->index("exampleGageAnalysis2");

    }
    public function GagePathviewAnalysis()
    {
        $d = new GageAnalysisController();
        return $d->index("GagePathviewAnalysis");
    }

}
