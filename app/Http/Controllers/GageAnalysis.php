<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Auth;
use Session;
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

                $argument .="reference:".$_POST['reference'].";";
            }

            if(isset($_POST['samples'])) {

                $argument .="sample:".$_POST['samples'].";";
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

           if(strcmp($_POST['geneSet'][0], 'signalling')==0 || strcmp($_POST['geneSet'][0], 'metabolic')==0 || strcmp($_POST['geneSet'][0], 'sigmet')==0 || strcmp($_POST['geneSet'][0], 'disease')==0 || strcmp($_POST['geneSet'][0], 'all')==0  )
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

        if(isset($_POST['geneIdType'])) {
            $argument .="geneIdType:".$_POST['geneIdType'].";";
        }



        if(isset($_POST['setSizeMin'])) {
            $argument .="setSizeMin:".$_POST['setSizeMin'].";";
        }

        if(isset($_POST['setSizeMax'])) {
            $argument .="setSizeMax:".$_POST['setSizeMax'].";";
        }

        if(isset($_POST['compare'])) {
            $argument .="compare:".$_POST['compare'].";";
        }

        if(isset($_POST['sameDir'])) {
            $argument .="sameDir:".$_POST['sameDir'].";";
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

       // Session::put('argument',$argument);
        $_SESSION['argument'] = $argument;

        exec("/home/ybhavnasi/R-3.1.2/bin/Rscript scripts/GageRscript.R  \"$argument\"  > $destFile.'/outputFile.Rout' 2> $destFile.'/errorFile.Rout'");

        return view('Gage.GageResult');

	}



}
