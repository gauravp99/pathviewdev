<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Auth;
use Session;
use File;
use DB;
use Redis;
use Illuminate\Http\Request;
use Queue;
use App\Commands\sendGageAnalysisCompletionMail;

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
            $email = Auth::user()->email;
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
            $result = File::makeDirectory("all/$email");
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
                return view('Gage.GageAnalysis');
            }
            $filename = Input::file('assayData')->getClientOriginalName();
        } else if (strcmp($analysis, 'exampleGageAnalysis1') == 0) {
            $filename = "gagedata.txt";
            $file = public_path() . "/" . "all/data/gagedata.txt";

            if (!File::copy($file, $destFile . $filename)) {
                $_SESSION['error'] = 'Unfortunately file cannot be uploaded';
                return view('Gage.GageAnalysis');
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
            } else {
                $argument .= "geneSetCategory:go;";
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
            } else {
                $argument .= $_POST['species'] . ";";
            }
        }

        if (isset($_POST['cutoff'])) {
            $argument .= "cutoff:";
            $argument .= $_POST['cutoff'] . ";";
        }


        if (isset($_POST['geneIdType'])) {

            $argument .= "geneIdType:" . $_POST['geneIdType'] . ";";
            if (strcmp($_POST['geneIdType'], 'custom') == 0) {
                $file = Input::file('geneIdFile');
                $filename1 = Input::file('geneIdFile')->getClientOriginalName();
                $destFile = public_path() . "/" . "all/" . $email . "/" . $time . "/";
                $file->move($destFile, $filename1);
                $argument .= "gsfn:" . $filename1 . ";";
            }
        }


        if (isset($_POST['setSizeMin'])) {
            $argument .= "setSizeMin:" . $_POST['setSizeMin'] . ";";
        }

        if (isset($_POST['setSizeMax'])) {
            if (strcmp(strtolower($_POST['setSizeMax']), 'inf') == 0) {
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
        } else {
            $argument .= "do.pathview:F;";
        }
        if (isset($_POST['dataType'])) {
            $argument .= "data.type:" . $_POST['dataType'] . ";";
        }

        $_SESSION['argument'] = $argument;

        //Push job into queuue code with status flag insert int redis

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
        exec("/home/ybhavnasi/R-3.1.2/bin/Rscript scripts/GageRscript.R  \"$argument\"  > $destFile.'/outputFile.Rout' 2> $destFile.'/errorFile.Rout'");

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
            DB::table('analyses')->insert(
                array('analysis_id' => $time . "", 'id' => Auth::user()->id . "", 'arguments' => $argument . "", 'analysis_type' => 'gage', 'created_at' => $date, 'ipadd' => get_client_ip(), 'analysis_origin' => 'gage')
            );
        else
            DB::table('analyses')->insert(
                array('analysis_id' => $time . "", 'id' => '0' . "", 'arguments' => $argument . "", 'analysis_type' => 'gage', 'created_at' => $date, 'ipadd' => get_client_ip(), 'analysis_origin' => 'gage')
            );

        return view('Gage.GageResult');

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


}
