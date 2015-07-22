<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Auth;
use Session;

use DB;
use Illuminate\Http\Request;

class GageAnalysis extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{


        $argument ="";
        $destFile = "";
        $email ="";
        $filename = "";
        $time = "";


            if(isset($_POST['reference'])) {
                if(strcmp(strtolower($_POST['reference']),'null')==0 || strcmp(strtolower($_POST['reference']),'')==0)
                $argument .="reference:NULL;";
                else
                    $argument .="reference:".$_POST['reference'].";";

            } else {
                $argument .="reference:NULL;";
            }

            if(isset($_POST['samples'])) {
                if(strcmp(strtolower($_POST['samples']),'null')==0 ||strcmp(strtolower($_POST['samples']),'') == 0 )
                    $argument .="sample:NULL;";
                else
                    $argument .="sample:".$_POST['samples'].";";
            } else {
                $argument .="sample:NULL;";
            }


        if (is_null(Auth::user())) {
            $email = "demo";
        } else {
            $email = Auth::user()->email;
        }

            if (!file_exists("all/$email")) {
                mkdir("all/$email");
            }

        $time = uniqid();

        mkdir("all/$email/$time", 0755, false);

        if (Input::hasFile('assayData')) {

            $file = Input::file('assayData');
            $filename = Input::file('assayData')->getClientOriginalName();
            $destFile = public_path()."/"."all/".$email."/".$time."/";
            $file->move($destFile, $filename);
        }
        else
        {
            $_SESSION['error'] = 'Unfortunately file cannot be uploaded';
            return view('Gage.GageAnalysis');
        }



        $filename = Input::file('assayData')->getClientOriginalName();
        $argument .="filename:".$filename.";";
        $argument .="destFile:".$destFile.$filename.";";
        $argument .="destDir:".$destFile.";";
        $extension = preg_replace('/^.*\./', '', $filename);
        $argument .="geneextension:".$extension.";";

        if(isset($_POST['geneSet']) && is_array($_POST['geneSet'])) {

           if(strcmp($_POST['geneSet'][0], 'sig.idx')==0 || strcmp($_POST['geneSet'][0], 'met.idx')==0 || strcmp($_POST['geneSet'][0], 'sigmet.idx')==0 || strcmp($_POST['geneSet'][0], 'dise.idx')==0 || strcmp($_POST['geneSet'][0], 'sigmet.idx,dise.idx')==0  )
           {
               $argument .="geneSetCategory:kegg;";
           }
            else
            {
                $argument .="geneSetCategory:go;";
            }
            $argument .="geneSet:";
            foreach($_POST['geneSet'] as $geneSet )
            {

                $argument .=$geneSet.",";

            }
            $argument .=";";
        }

        if(isset($_POST['species']))
        {
            $argument .="species:";
            if(strcmp($_POST['geneIdType'],'entrez')==0 || strcmp($_POST['geneIdType'],'kegg') == 0) {
                $argument .= explode('-',$_POST['species'])[0] . ";";
            }
            else{
                $argument .= $_POST['species'] . ";";
            }
        }

        if(isset($_POST['cutoff']))
        {
            $argument .="cutoff:";
            $argument .=$_POST['cutoff'].";";
        }



        if(isset($_POST['geneIdType'])) {

            $argument .="geneIdType:".$_POST['geneIdType'].";";
            if(strcmp($_POST['geneIdType'],'custom')==0)
            {
                $file = Input::file('geneIdFile');
                $filename1 = Input::file('geneIdFile')->getClientOriginalName();
                $destFile = public_path()."/"."all/".$email."/".$time."/";
                $file->move($destFile, $filename1);
                $argument .="gsfn:".$filename1.";";
            }
        }



        if(isset($_POST['setSizeMin'])) {
            $argument .="setSizeMin:".$_POST['setSizeMin'].";";
        }

        if(isset($_POST['setSizeMax'])) {
            if(strcmp(strtolower($_POST['setSizeMax']),'inf')==0)
            {
                $argument .="setSizeMax:INF;";
            }else
            {
                $argument .="setSizeMax:".$_POST['setSizeMax'].";";
            }
        }
        else
        {
            $argument .="setSizeMax:INF;";
        }

        if(isset($_POST['compare'])) {
            $argument .="compare:".$_POST['compare'].";";
        }

        if(isset($_POST['test2d'])) {
            $argument .="test.2d:T;";
        }
        else{
            $argument .="test.2d:F;";
        }


        if(isset($_POST['rankTest'])) {
            $argument .="rankTest:T;";
        }
        else
        {
            $argument .="rankTest:F;";
        }


        if(isset($_POST['useFold'])) {
            $argument .="useFold:T;";
        }
        else
        {
            $argument .="useFold:F;";
        }

        if(isset($_POST['test'])) {
            $argument .="test:".$_POST['test'].";";
        }

        if(isset($_POST['dopathview'])) {
            $argument .="do.pathview:T;";
        }
        else
        {
            $argument .="do.pathview:F;";
        }
        if(isset($_POST['dataType']))
        {
            $argument .="data.type:".$_POST['dataType'].";";
        }

        $_SESSION['argument'] = $argument;

       exec("/home/ybhavnasi/R-3.1.2/bin/Rscript scripts/GageRscript.R  \"$argument\"  > $destFile.'/outputFile.Rout' 2> $destFile.'/errorFile.Rout'");
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
        #echo $argument;
        if (Auth::user())
            DB::table('analyses')->insert(
                array('analysis_id' => $time . "", 'id' => Auth::user()->id . "", 'arguments' => $argument . "", 'analysis_type' => 'gage', 'created_at' => $date, 'ipadd' => get_client_ip(),'analysis_origin' => 'gage')
            );
        else
            DB::table('analyses')->insert(
                array('analysis_id' => $time . "", 'id' => '0' . "", 'arguments' => $argument . "", 'analysis_type' => 'gage', 'created_at' => $date, 'ipadd' => get_client_ip(),'analysis_origin' => 'gage')
            );
       return view('Gage.GageResult');

	}



}
