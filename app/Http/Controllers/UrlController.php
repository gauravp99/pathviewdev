<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Input;
use DB; 
use ZipArchive;
use Mail; 
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Response;
class UrlController extends Controller
{

   public function index()
   {
      $download_link ="http://10.23.56.41/abc/xyz";
      $response=["download" => $download_link];
      $output=json_encode($response, JSON_UNESCAPED_SLASHES);
      $status_code=200;
      return response() -> json($download_link, $status_code);
   }

   public function postAnalysis()
   {
      $uniqid = uniqid();
      $path = "all/demo/".$uniqid;
      if(!file_exists("all/demo"))
      {
         File::makeDirectory("all/demo", $mode = 0775, true, true);
      }
      File::makeDirectory($path, $mode = 0775, true, true);

      $valid_input_keys=array('gene_data', 'cpd_data', 'auto_sel', 'pathway_id', 'suffix', 'gene_id', 'cpd_id', 'species', 'kegg', 'layer', 'split', 'expand', 'multistate', 'matched', 'discrete_gene', 'discrete_cpd', 'keyposition', 'signatureposition', 'offset', 'align', 'node_sum', 'limit_gene', 'bins_gene', 'limit_cpd', 'bins_cpd', 'na_color', 'low_gene', 'mid_gene', 'high_gene', 'low_cpd', 'mid_cpd', 'high_cpd', 'gene_sample', 'gene_reference', 'gene_compare', 'cpd_reference', 'cpd_compare', 'cpd_sample');
      $request_input_keys=array_keys(Input::all());
      $validate_input_array=array_diff($request_input_keys, $valid_input_keys);
      if (sizeof($validate_input_array) != 0)
      {
	 $valid_keys_string=implode(",", $valid_input_keys);
	 $incorrect_options=implode(",", $validate_input_array);
	 $response_message="Invalid arguments '$incorrect_options'. Tha valid argument are one of these : '$valid_keys_string'";
	 $status_code=400;
         return response()->json(['message' => $response_message], 400);
      }

      if (Input::has('suffix'))
      {
        $suffix=Input::get('suffix');
      }
      else
      {
        $suffix='multistatekegg';
      }
    
      //Create target Directory where the files will be kept once the analysis is done
      $parsed_argument=Array();
      $parsed_argument=$this->createArgument($path);
      $argument= $parsed_argument['argument'];
      $response_message=  $parsed_argument['message'];
      $response_code= $parsed_argument['status_code'];
      $response_warning= $parsed_argument['warning_msg'];
      $analyType='new analysis';


      //Check if the status code is 400 then return without doing analysis
      //A 400 request means something went wrong while doing the analysis.
      
      if($response_code == 400)
      {
        return response()->json(['message' => $response_message], 400);

      }

      //Check the return value from the function runAnalysisForApi.
      //If the R script which is invoked with the arguments created above 
      //does not execute successfully then return from there without completing 
      //the analysis. 
      
      $ret_value = $this->runAnalysisForApi($uniqid, $argument, $path, $analyType);

      if(!$ret_value)
      {
         $msg="Error while executing the analysis script. It could be because of the incorrect values supplied to the arguments";
         $status_code=400;
         return response()->json(['message' => $msg], $status_code);
      } 
       

      // Creating zip file for the code.
      $directory = public_path() . "/all/demo/" . $uniqid;
      chdir($directory);
      $rootPath = realpath($directory);

      // Initialize archive object
      $id=$parsed_argument['id'];
      $zip = new ZipArchive();
      $zip->open('file.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
      // Create recursive directory iterator
      $files = new RecursiveIteratorIterator(
              new RecursiveDirectoryIterator($rootPath),
              RecursiveIteratorIterator::LEAVES_ONLY
      );

      foreach ($files as $name => $file) {
          // Skip directories (they would be added automatically)
          if (!$file->isDir()) {
              //if (strpos($file, 'log') !== false || strpos($file, "" . $id . ".txt") || (strpos($file, $suffix))) {
              if (strpos($file, 'log') === FALSE  &&  strpos($file, 'Rout') === FALSE && strpos($file, 'workenv') === FALSE) {
                  // Get real and relative path for current file
                  $filePath = $file->getRealPath();
                  $relativePath = substr($filePath, strlen($rootPath) + 1);

                  // Add current file to archive
                  $zip->addFile($filePath, $relativePath);

              }
          }
       }

       // Zip archive will be created only after closing object
       $zip->close();
       $download_link =asset('all/demo/'.$uniqid.'/file.zip');
       $options = app('request')->header('accept-charset') == 'utf-8' ? JSON_UNESCAPED_UNICODE : null;
       $status_code=200;
       if ($response_warning == '')
       {
         return response() -> json(['download link' => ''.$download_link]); 
       }
       else
       {
         return response() -> json(['download link' => ''.$download_link, 'warning' => $response_warning ]); 
       }
       #return response() -> json(['download link' => ''.$download_link]); 
   }

   public function createArgument($targetDir)
   {
      $gfile = NULL;
      $cpdfile = NULL;
      $geneid = "ENTREZ";
      $cpdid = "KEGG";
      $species = "hsa";
      $pathway = array('00010');
      $suffix='pathway';
      $kegg = 'T';
      $layer = 'T';
      $split = 'F';
      $expand = 'F';
      $multistate = 'T';
      $matchd = 'T';
      $gdisc = 'F';
      $cdisc = 'F';
      $kpos = "topright";
      $pos = "bottomleft";
      $offset = "1.0";
      $align = "y";
      $glmt = 1;
      $gbins = 10;
      $glow = "green";
      $gmid = "grey";
      $ghigh = "red";
      $clmt = 1;
      $cbins = 10;
      $clow = "blue";
      $cmid = "grey";
      $chigh = "yellow";
      $nsum = "sum";
      $ncolor = "transparent";
      $compare = "paired";
      $autopathway= 'F';

      $argument ="";

      $response_arr=Array();
      $response_arr['argument']=$argument;
      $response_arr['message']='OK';
      $response_arr['status_code']=200;
      $response_arr['id']='hsa00640';
      $response_arr['warning_msg']='';
      $all_inputs=Input::all();

      //Gene File
      if (Input::hasFile('gene_data')) 
      {
          $gfile= Input::file('gene_data')->getClientOriginalName();
          Input::file('gene_data')->move($targetDir,$gfile);
      }

      //Compound File
      //ToDO: If the file size is greater than 2M then
      //hasFile method returans false even if a file is provided as an argument.
      //The settings for php.ini has to be changed to consider the file size as 10M.
      if (Input::hasFile('cpd_data')) 
      {
         $cpdfile  = Input::file('cpd_data')->getClientOriginalName();
         Input::file('cpd_data')->move($targetDir,$cpdfile);
      }

      // Check for at least gene or compound file is provides in the input
      if(is_null($gfile) && is_null($cpdfile))
      {
            $msg="Both Gene Data and Compound Data can't be empty.";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
	    return $response_arr;
      }

      $argument .="filename:".$gfile.";";

      if(!is_null($cpdfile))
      {
         $argument .="cfilename:".$cpdfile.";";
         $cpd_extension = File::extension($cpdfile);
         //$compoundFileSize = $_FILES['cpdfile']['size']/(1024*1024);
	 if ($cpd_extension != "txt" && $cpd_extension != "csv" ) 
	 {
            $msg='Compound data file extension is not supported( use .txt,.csv,.rda)';
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
	    return $response_arr;
         }
         $argument .="cpdextension:".$cpd_extension.";";
      }  

      //if (Input::has('autopathway'))
      if (Input::has('auto_sel'))
      {
         //if (!(Input::get('autopathway') == 'T' || Input::get('autopathway') == 'F' ))
         if (!(Input::get('auto_sel') == 'T' || Input::get('auto_sel') == 'F' ))
         {
            $msg="Auto pathway selection value should be either T (True) or F (False). Default value is taken as F (False) ";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
            return $response_arr;
         }
         $autopathway=Input::get('auto_sel');
      }

      //-----------------------pathwayids
      if (Input::has('pathway_id') || Input::hasFile('pathway_id')) 
      {
	      if(Input::has('pathway_id'))
	      {
                  $input_pathway=Input::get('pathway_id');
	      }
	      else
	      {
	          $file=Input::file('pathway_id');
	          $file_contents=file($file->getRealPath());
	          $input_pathway=implode(',', $file_contents);
	      }
         preg_match_all('!\d{5}!', $input_pathway, $matches);
         $pathway_array = array();
	 $invalid_pathway_ids = array();
         $i = 0;
	 foreach ($matches as $pathway1) 
	 {
            foreach ($pathway1 as $pathway)
            {
               //check in db if pathway exists or not
         	$val = DB::select(DB::raw("select pathway_id from pathway where pathway_id like '$pathway' LIMIT 1"));
         	if((sizeof($val) > 0) or  $pathway == '00000')
         		array_push($pathway_array, $pathway);
		else
	        {
                      array_push($invalid_pathway_ids, $pathway);
		}
         	$i = $i + 1;
         	//limit imposed as per req to pathway id not more than 20 to each request
         	if(sizeof(array_unique($pathway_array)) >= 20)
         	{
         		break;
         	}
             }
             if(sizeof(array_unique($pathway_array)) > 20)
             {
                $msg='Please provide pathway Ids < 20';
         	$status_code=400;
                //return response()->json([ 'message' => $msg ], $status_code);
	     } 
          }
          $pathway_array1 = array_unique($pathway_array);
	  if(sizeof($invalid_pathway_ids) > 0)
	  {
             $status_code=400;
	     $invalid_pathways=implode(",", $invalid_pathway_ids);
	     $warning_msg="Warning: Wrong pathway ID(s) '$invalid_pathways'. These will not be considered for doing pathway analysis.";
             $response_arr['warning_msg']=$warning_msg;
	  }
          if(sizeof($pathway_array1) == 0)
          {
             $status_code=400;
             $msg='Wrong pathway ID entered.Please provide the valid pathway IDs';
             $response_arr['status_code']=$status_code;
             $response_arr['message']=$msg;
	     return $response_arr;
          }
          $pathway = $pathway_array1;
      }

      ////------------Suffix
      if(Input::has('suffix')) 
      {
        $suffix=Input::get('suffix');
      }

      ////------------Gene ID

      if(Input::has('gene_id')) 
      {
         $gene_id = Input::get('gene_id');
         $val = DB::select(DB::raw("select gene_id  from gene where gene_id  like '$gene_id' LIMIT 1 "));

         if(sizeof($val)>0){
            $geneid=$gene_id;
         }else{
            $msg="Entered Gene ID doesn't exist";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
	    return $response_arr;
         }
       }

      //---------Compound ID

      if(Input::has('cpd_id')) 
      {
         $cpd_id = Input::get('cpd_id');
         $val = DB::select(DB::raw("select compound_id  from compoundID where compound_id  like '$cpd_id' LIMIT 1 "));

         if (sizeof($val) > 0) {
             $cpdid=$cpd_id;
         }else{
            $msg="Entered compound ID doesn't exist";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
	    return $response_arr;
         }
       }

      //----------Species ID
      if(Input::has('species'))
      {
         $species_id = explode("-", Input::get('species'));
         $val = DB::select(DB::raw("select species_id from species where species_id like '$species_id[0]' LIMIT 1"));
	 if (sizeof($val) > 0) 
	 {
            $species=$val[0]->species_id;
	 }
	 else
	 {
            $msg="Entered Species ID doesn't exist.";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
	    return $response_arr;
         }
      }
      //------------Kegg ID
      if (Input::has('kegg'))
      {
         if (!(Input::get('kegg') == 'T' || Input::get('kegg') == 'F' ))
         {
             $msg="Kegg value should be either 'true' or 'false'.";
             $status_code=400;
             $response_arr['message']=$msg;
             $response_arr['status_code']=$status_code;
             return $response_arr;
         }
         $kegg=Input::get('kegg');
      }
      
      //-------------Layer
      if (Input::has('layer'))
      {
         if (!(Input::get('layer') == 'T' || Input::get('layer') == 'F' ))
         {
            $msg="Layer value should be either 'true' or 'false'. Default value is taken ";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
            return $response_arr;
         }
         $layer=Input::get('layer');
      }
      
      //----------Split node
      if (Input::has('split'))
      {
         if (!(Input::get('split') == 'T' || Input::get('split') == 'F' ))
         {
            $msg="Split value should be either T (True) or F (False). Default value is taken as F (False) ";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
            return $response_arr;
         }
         $split=Input::get('split');
      }

      
      //---------Expand node
      if (Input::has('expand'))
      {
         if (!(Input::get('expand') == 'T' || Input::get('expand') == 'F' ))
         {
            $msg="Expand value should be either T or F. Default value is taken as F (False).";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
            return $response_arr;
         }
         $expand=Input::get('expand');
      }
      
      
      //--------Multi State
      if (Input::has('multistate'))
      {
         if (!(Input::get('multistate') == 'T' || Input::get('multistate') == 'F' ))
         {
            $msg="Multistate value should be either  T (True) or F (False). Default value is taken T (True).";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
            return $response_arr;
         }
         $multistate=Input::get('multistate');
      }
      
      //---------Match Data
      if (Input::has('matched'))
      {
         if (!(Input::get('matched') == 'T' || Input::get('matched') == 'F' ))
         {
            $msg="Matched value should be either T (True) or F (False). Default value is taken T (True)";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
            return $response_arr;
         }
         $matchd=Input::get('matched');
      }
      
      //----------gene descrete
      if (Input::has('discrete_gene'))
      {
         if (!(Input::get('discrete_gene') == 'T' || Input::get('discrete_gene') == 'F' ))
         {
             $msg="Gene Data should be treated either discrete or not. Default values is false, i.e. data should be treated as continuous. The value should be therefore either T (True) or F (False).";
             $status_code=400;
             $response_arr['message']=$msg;
             $response_arr['status_code']=$status_code;
             return $response_arr;
         }
         $gdisc=Input::get('discrete_gene');
      }
      
      //----------Compound descrete
      if (Input::has('discrete_cpd'))
      {
         if (!(Input::get('discrete_cpd') == 'T' || Input::get('discrete_cpd') == 'F'))
         {
             $msg="Compound Data should be treated either discrete or not. Default values is false, i.e. data should be treated as continuous. The value should be therefore either T (True) or F (False).";
             $status_code=400;
             $response_arr['message']=$msg;
             $response_arr['status_code']=$status_code;
             return $response_arr;
         }
         $cdisc=Input::get('discrete_cpd');
      }

      
      //-----------Key Position

      if (Input::has('keyposition'))
      {
       	$valid_set_keyposition = array('bottomleft', 'bottomright', 'topright', 'topleft');
	$kpos=Input::get('keyposition');
	if(!in_array($kpos, $valid_set_keyposition))
	{
            $msg="Key Position should either take one of the values from 'bottomleft' or 'bottomright' or 'topright' or 'topleft'";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
	    return $response_arr;
        }
      }

      //-----------Signature Position
      if (Input::has('signatureposition'))
      {
	$pos=Input::get('signatureposition');
       	$valid_set_keyposition = array('bottomleft', 'bottomright', 'topright', 'topleft');
	if(!in_array($pos, $valid_set_keyposition))
	{
            $msg="Signature Position should either take one of the values from 'bottomleft' or 'bottomright' or 'topright' or 'topleft'";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
	    return $response_arr;
	}
      }
      

      //-----------compound label offset
      if(Input::has('offset'))
      {
         if (preg_match('/[a-z]+/', Input::get('offset'))) 
         {
           $msg="Offset should be numeric";
           $status_code=400;
           $response_arr['message']=$msg;
           $response_arr['status_code']=$status_code;
           return $response_arr;
         } 
         $offset=Input::get('offset');
      }
     
      //----------Align
      if (Input::has('align'))
      {
         if (!(Input::get('align') == 'y' || Input::get('align') == 'x'))
         {
            $msg="Potential values are 'x' aligned by x coordinates, and 'y' aligned by y coordinates.The value should be therefore either 'x' or 'y'";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
            return $response_arr;
         }
         $align=Input::get('align');
      }
      //----------NSum
      if (Input::has('node_sum'))
      {
         $valid_nsum_options=array('sum', 'mean', 'median', 'max', 'max.abs', 'random');
         $valid_nsum_string=implode(",", $valid_nsum_options);
         if(!in_array(Input::get('node_sum'), $valid_nsum_options))
         {
             $msg="nsum value should be one of these $valid_nsum_string";
             $status_code=400;
             $response_arr['message']=$msg;
             $response_arr['status_code']=$status_code;
             return $response_arr;
         }
         $nsum=Input::get('node_sum');
      }
      //---------NA Color -
      if (Input::has('na_color'))
      {
         if (!(Input::get('na_color') == 'transparent' || Input::get('na_color') == 'grey'))
         {
             $msg="Potential value for ncolor can be 'transparent' or 'grey";
             $status_code=400;
             $response_arr['message']=$msg;
             $response_arr['status_code']=$status_code;
             return $response_arr;
         }
         $ncolor=Input::get('na_color');
      }
      //---------Gene Limit -
      if (Input::has('limit_gene'))
      {
         if (preg_match('/[a-z]+/', Input::get('limit_gene'))) 
         {
            $msg="Gene limit should be numeric";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
            return $response_arr;
         }
         $glmt=Input::get('limit_gene');
      }
      //---------Compound Limit -
      if (Input::has('limit_cpd'))
      {
         if (preg_match('/[a-z]+/', Input::get('limit_cpd'))) 
         {
            $msg="Compound limit should be numeric";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
            return $response_arr;
         }
         $clmt=Input::get('limit_cpd');
      }
      //---------Gene bins -
      if (Input::has('bins_gene'))
      {
         if (preg_match('/[a-z]+/', Input::get('bins_gene')))
         {
            $msg="Gene bins should be numeric";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
            return $response_arr;
         }
         $gbins=Input::get('bins_gene');
      }

      //---------Compound bins -
      if (Input::has('bins_cpd'))
      {
	if (preg_match('/[a-z]+/', Input::get('bins_cpd')))
        {
           $msg="Compound bins should be numeric";
           $status_code=400;
           $response_arr['message']=$msg;
           $response_arr['status_code']=$status_code;
           return $response_arr;
        }
        $cbins=Input::get('bins_cpd');
      }
      if (Input::has('low_gene'))
      {
	 $glow=Input::get('low_gene');
      }

      if (Input::has('mid_gene'))
      {
	 $gmid=Input::get('mid_gene');
      }
      if (Input::has('high_gene'))
      {
	 $ghigh=Input::get('high_gene');
      }
      if (Input::has('low_cpd'))
      {
	 $clow=Input::get('low_cpd');
      }
      if (Input::has('mid_cpd'))
      {
	 $cmid=Input::get('mid_cpd');
      }
      if (Input::has('high_cpd'))
      {
	 $chigh=Input::get('high_cpd');
      }
      if(Input::has('gene_reference'))
      {
         if (preg_match('/[a-z]+/', Input::get('gene_reference'))) 
         {
            $msg="gene reference should be a comma separated list of numbers.";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
            return $response_arr;
	 
	 }
         $argument .="generef:".Input::get('gene_reference').";";
      }
      else
          $argument .="generef:NULL;";
      if(Input::has('gene_sample'))
      {
         if (preg_match('/[a-z]+/', Input::get('gene_sample'))) 
         {
            $msg="Gene sample should be a comma separated list of numbers.";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
            return $response_arr;
	 }
         $argument .="genesamp:".Input::get('gene_sample').";";
      }
      else
        $argument .="genesamp:NULL;";
      if(Input::has('gene_compare'))
      {
         if (!(Input::get('gene_compare') == 'paired' || Input::get('gene_compare') == 'unpaired'))
	 {
             $msg="Potential value for geneCompare can be 'paired' or 'unpaired'. Default value is taken as paired.";
             $status_code=400;
             $response_arr['message']=$msg;
             $response_arr['status_code']=$status_code;
	     return $response_arr;
	 }
	 
          $argument .="genecompare:".Input::get('gene_compare').";";
      }
      else
      {
          $argument .="genecompare:paired;";
      }

      if(Input::has('cpd_reference'))
      {
         if (preg_match('/[a-z]+/', Input::get('cpd_reference'))) 
         {
            $msg="Gene sample should be a comma separated list of numbers.";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
            return $response_arr;
	 }
         $argument .="cpdref:".Input::get('cpd_reference').";";
      }
      else
      {
          $argument .="cpdref:NULL;";
      }

      if(Input::has('cpd_sample'))
      {
         if (preg_match('/[a-z]+/', Input::get('cpd_reference'))) 
         {
            $msg="Gene sample should be a comma separated list of numbers.";
            $status_code=400;
            $response_arr['message']=$msg;
            $response_arr['status_code']=$status_code;
            return $response_arr;
	 }
         $argument .="cpdsamp:".Input::get('cpd_sample').";";
      }
      else
      {
        $argument .="cpdsamp:NULL;";
      }

      if(Input::has('cpd_compare'))
      {
         if (!(Input::get('cpd_compare') == 'paired' || Input::get('cpd_compare') == 'unpaired'))
	 {
             $msg="Potential value for cpd_compare can be 'paired' or 'unpaired'. Default value is taken as paired.";
             $status_code=400;
             $response_arr['message']=$msg;
             $response_arr['status_code']=$status_code;
	     return $response_arr;
	 }
	 
          $argument .="cpdcompare:".Input::get('cpd_compare').";";
      }
      else
      {
          $argument .="cpdcompare:paired;";
      }
      $argument .="geneid:".$geneid.";";
      $argument .="cpdid:".$cpdid.";";
      $argument .="species:".$species.";";
      //$argument .="autoPathwaySelection:".$autopathway.";";
      $argument .="autosel:".$autopathway.";";
      $argument .="pathway:".join(",",$pathway).";";
      $argument .="suffix:".$suffix.";";
      $argument .="kegg:".$kegg.";";
      $argument .="layer:".$layer.";";
      $argument .="split:".$split.";";
      $argument .="expand:".$expand.";";
      $argument .="multistate:".$multistate.";";
      $argument .="matchd:".$matchd.";";
      $argument .="gdisc:".$gdisc.";";
      $argument .="cdisc:".$cdisc.";";
      $argument .="kpos:".$kpos.";";
      $argument .="pos:".$pos.";";
      $argument .="offset:".$offset.";";
      $argument .="align:".$align.";";
      $argument .="glmt:".$glmt.";";
      $argument .="gbins:".$gbins.";";
      $argument .="clmt:".$clmt.";";
      $argument .="cbins:".$cbins.";";
      $argument .="glow:".$glow.";";
      $argument .="gmid:".$gmid.";";
      $argument .="ghigh:".$ghigh.";";
      $argument .="clow:".$clow.";";
      $argument .="cmid:".$cmid.";";
      $argument .="chigh:".$chigh.";";
      $argument .="nsum:".$nsum.";";
      $argument .="ncolor:".$ncolor.";";
      $argument .="geneextension:txt;";
      $argument .="targedir:".$targetDir.";";
     $response_arr['argument']=$argument;
     $response_arr['id'] = $species . substr($pathway[0], 0, 5);
     return $response_arr;
   }

   public function runAnalysisForApi($time, $argument, $destFile, $anal_type)
   {
      $return_status=false;
      $Rloc = Config::get("app.RLoc");
      $publicPath = Config::get("app.publicPath");
      try 
      {
         exec($Rloc."Rscript ".$publicPath."my_Rscript.R \"$argument\"", $output, $status);
	 if ($status != 0) 
	 {
            return false;
         }
      }
      catch (Exception $e) 
      {
      	error_log($e->getMessage());
      	$return_status=false;
      }
      
      $date = new \DateTime;
      DB::table('analysis')->insert(
      		array('analysis_id' => $time . "", 'id' => '0' . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'analysis_origin' => 'pathview_restapi','ip_add' => "127.0.0.1")
      	);
      //start If there are error in the code analysis saving into database for reporting and solving by admin
      if(file_exists(public_path()."/".$destFile . "/errorFile.Rout"))
      {
         $lines = file(public_path()."/".$destFile . "/errorFile.Rout");
         $flag = false;
	 foreach ($lines as $temp) 
	 {
            $temp = strtolower($temp);
            $array_string = explode(" ", $temp);
	    foreach ($array_string as $a_string) 
	    {
	       if (strcmp($a_string, 'error') == 0 || strcmp($a_string, 'error:') == 0) 
	       {
               	  DB::table('analysisErrors')->insert(array('analysis_id' => $time . "", 'error_string' => $temp, 'created_at' => $date));
               	  $flag = true;
               	  $return_status=false;
               	  break;
               }
            }
	    if ($flag) 
	    {
               $return_status=false;
               break;
            }
            $return_status=true;
         }
       }
       return $return_status;
    }
}
