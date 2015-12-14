<?php namespace App\Http\Controllers\gage;

use App\Http\Models\GageAnalysis;
use App\Http\Models\PathviewAnalysis;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use DB;

class gageAnalysisController extends Controller {

	public function index($analysis)
	{

		//check if the user is authorised or not if user is authorised then check if user specific folder is existed or not
		// if not existed delete it

		$file_location = "";
		if (Auth::user()) {

			$email = Auth::user()->email;
			$file_location = $email;
			$f = './all/' . $email;
			//check if file exist or not if not exist  create it otherwise delete it
			if (!file_exists($f)) {
				try {
					$result = File::makeDirectory($f);
				} catch (FileNotFoundException $e) {
					Log::error('Not able to create the file: ' . $e);
				}
			}

			//check if the file space is less than 99MB if more than that delete old analysis
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
			//all guest analysis are placed in this directory
			$file_location = "demo";
		}

		//check for all post varaibles in the form and set to the model GageAnalysis and then create argument out of the model

		$gage_model = new GageAnalysis();

		$unique_analysis_id = uniqid();

		File::makeDirectory("all/$file_location/$unique_analysis_id");
		$destFile = public_path() . "/" . "all/" . $file_location ."/".$unique_analysis_id."/";


		//separate file copy for new analysis and example analysis. if the analysis is new analysis file is taken from
		// file upload where as it is from example analysis file is taken from the file in the public folder
		if (strcmp($analysis, 'newAnalysis') == 0) {

			if (Input::hasFile('assayData')) {
				$file = Input::file('assayData');
				$filename = Input::file('assayData')->getClientOriginalName();
				$gage_model->setAssayData($filename);
				$file->move($destFile, $filename);
			} else {
				$_SESSION['error'] = 'Unfortunately file cannot be uploaded';
				return view('gage_pages.GageAnalysis');
			}
		}
		else {

			if (strcmp($analysis, 'exampleGageAnalysis1') == 0) {
				$file_location1 = Config::get('constants.example1_gage_file_location');
				$filename = Config::get('constants.example1_gage_file_name');
			}
			else if (strcmp($analysis, 'exampleGageAnalysis2') == 0) {
				$file_location1 = Config::get('constants.example2_gage_file_location');
				$filename = Config::get('constants.example2_gage_file_name');
			}
			else if(strcmp($analysis, 'GagePathviewAnalysis') == 0){
				$file_location1 = Config::get('constants.example_gage_pathview_file_location');
				$filename = Config::get('constants.example_gage_pathview_file_name');
			}

			$complete_file_location = public_path()."/".$file_location1;

			try{

				copy($complete_file_location, $destFile . $filename);
			}catch(Exception $e)
			{
				$_SESSION['error'] = 'Unfortunately file cannot be uploaded';
				return view('gage_pages.GageAnalysis');
			}


			$gage_model->setAssayData($filename);

		}

		$gage_model->setDestFile($destFile."/".$filename);
		$gage_model->setDestDir($destFile);
		$gage_model->setFileExtension(preg_replace('/^.*\./', '', $filename));

		if (isset($_POST['reference'])) {
			if (strcmp(strtolower($_POST['reference']), 'null') == 0 || strcmp(strtolower($_POST['reference']), '') == 0)
				$gage_model->setReference("NULL");
			else
				$gage_model->setReference($_POST['reference']);
		} else {
			$gage_model->setReference("NULL");
		}


		if (isset($_POST['samples'])) {
			if (strcmp(strtolower($_POST['samples']), 'null') == 0 || strcmp(strtolower($_POST['samples']), '') == 0)
				$gage_model->setSample("NULL");
			else
				$gage_model->setSample($_POST['samples']);
		} else {
			$gage_model->setSample($_POST['samples']);
		}

		if (isset($_POST['geneSet']) && is_array($_POST['geneSet'])) {
			if (strcmp($_POST['geneSet'][0], 'sig.idx') == 0 || strcmp($_POST['geneSet'][0], 'met.idx') == 0 || strcmp($_POST['geneSet'][0], 'sigmet.idx') == 0 || strcmp($_POST['geneSet'][0], 'dise.idx') == 0 || strcmp($_POST['geneSet'][0], 'sigmet.idx,dise.idx') == 0) {
				$gage_model->setGeneSetCategory("kegg");
			}
			else if (strcmp($_POST['geneSet'][0], 'BP') == 0 || strcmp($_POST['geneSet'][0], 'CC') == 0 || strcmp($_POST['geneSet'][0], 'MF') == 0 || strcmp($_POST['geneSet'][0], 'BP,CC,MF') == 0 ) {
				$gage_model->setGeneSetCategory("go");
			}
			else if(strcmp($_POST['geneSet'][0], 'custom') == 0)
			{
				$gage_model->setGeneSetCategory("custom");
			}

			$geneSet_String = "";
			foreach ($_POST['geneSet'] as $geneSet) {
				$geneSet_String .= $geneSet . ",";
			}
			$gage_model->setGeneSet($geneSet_String);

		}

		$species = "";
		if (isset($_POST['species'])) {

			if (strcmp($_POST['geneIdType'], 'entrez') == 0 || strcmp($_POST['geneIdType'], 'kegg') == 0) {
				$species = explode('-', $_POST['species'])[0] ;
			} else if(strcmp($_POST['geneIdType'], 'Entrez Gene') == 0 || strcmp($_POST['geneIdType'], 'ORF') == 0 || strcmp($_POST['geneIdType'], 'TAIR') == 0) {
				$goSpecies = DB::table('GoSpecies')->where('species_id',substr($_POST['species'],0,3))->first();

				if(sizeof($goSpecies) > 0)
				{
					$species .= explode('-',$goSpecies->Go_name )[0] ;
				}
				else{
					$goSpecies1 = DB::table('GoSpecies')->where('Go_name',$_POST['species'])->first();
					if(sizeof($goSpecies1) > 0)
					{
						$species .= explode('-',$goSpecies1->Go_name )[0];
					}
					else{
						$species .= "Human";
					}
				}

			}
			else{
				$species .= explode('-', $_POST['species'])[0]; 
			}
		}

		$gage_model->setSpecies($species);


		if (isset($_POST['cutoff'])) {
			$gage_model->setCutoff($_POST['cutoff']);
		}

		if (isset($_POST['geneIdType'])) {
			$gage_model->setGeneIdType($_POST['geneIdType']);

			//if selected geneIDType is custom type look for the file uploaded if not uploaded we can take the default file
			if (strcmp($_POST['geneIdType'], 'custom') == 0) {

				$destFile = public_path()."/all/".$file_location . "/" . $unique_analysis_id . "/";

				if(Input::hasFile('geneIdFile'))
				{
					$file = Input::file('geneIdFile');
					$filename1 = Input::file('geneIdFile')->getClientOriginalName();
					if(!$file->move($destFile, $filename1))
					{
						$_SESSION['error'] = 'Unfortunately one of the files cannot be uploaded';
						return view('gage_pages.GageAnalysis');
					}
					$gage_model->setCustomGeneIDFileExtension(preg_replace('/^.*\./', '', $filename1));
					$gage_model->setCustomGeneIDFile($filename1);
				}
				else{
					$filename1= Config::get('constants.custom_gene_id_gage_file_name'); //"c1_all_v3_0_symbols.gmt";
					$file1 = Config::get('constants.custom_gene_id_gage_file_location');//public_path() . "/" . "all/demo/example/c1_all_v3_0_symbols.gmt";

					if (!File::copy(public_path()."/".$file1, $destFile . $filename1)) {
						$_SESSION['error'] = 'Unfortunately file cannot be uploaded';
						return view('gage_pages.GageAnalysis');
					}

					$gage_model->setCustomGeneIDFile($filename1);
					$gage_model->setCustomGeneIDFileExtension(preg_replace('/^.*\./', '', $filename1));
				}

			}

			//set size min
			if (isset($_POST['setSizeMin'])) {
				$gage_model->setSetSizeMin($_POST['setSizeMin']);
			}

			//set size max
			if (isset($_POST['setSizeMax'])) {
				if (strcmp(strtolower($_POST['setSizeMax']), 'infinite') == 0) {
					$gage_model->setSetSizeMax(INF);
				} else {
					$gage_model->setSetSizeMax($_POST['setSizeMax']);
				}
			} else {
				$gage_model->setSetSizeMax("INF");
			}

			if (isset($_POST['compare'])) {
				$gage_model->setCompare($_POST['compare']);
			}

			if (isset($_POST['test2d'])) {
				$gage_model->setTest2d("T");
			} else {
				$gage_model->setTest2d("F");
			}


			if (isset($_POST['rankTest'])) {
				$gage_model->setRankTest("T");
			} else {
				$gage_model->setRankTest("F");
			}

			if (isset($_POST['useFold'])) {
				$gage_model->setUseFold("T");
			} else {
				$gage_model->setUseFold("F");
			}

			if (isset($_POST['test'])) {
				$gage_model->setTest($_POST['test']);
			}

			if(isset($_POST['normalizedData']))
			{
				$gage_model->setNormalizedData("T");
			}else{
				$gage_model->setNormalizedData("F");
			}

			if(isset($_POST['countData']))
			{
				$gage_model->setCountData("T");
			}else{
				$gage_model->setCountData("F");
			}

			if(isset($_POST['logTransformed']))
			{
				$gage_model->setLogTransformed("F");
			}else{
				$gage_model->setLogTransformed("T");
			}

			if (isset($_POST['dopathview'])) {
				$gage_model->setDopathview("T");
				if (isset($_POST['dataType'])) {
					$gage_model->setDataType($_POST['dataType']);
				}

			//for gage pathview analysis
				$analsisObject = new PathviewAnalysis();
				if(strcmp($analysis, 'GagePathviewAnalysis') == 0)
				{
					$gage_model->setPathviewAnalysisFlag("T");
					$analsisObject = new PathviewAnalysis();

					/*----------------------Kegg ID----------------------------------------------------------*/
					if (isset($_POST["kegg"]))
						$analsisObject->setKeggFlag("T");
					else
						$analsisObject->setKeggFlag("T");
					/*----------------------Kegg ID----------------------------------------------------------*/


					/*----------------------Layer----------------------------------------------------------*/
					if (isset($_POST["layer"]))
						$analsisObject->setLayerFlag("T");
					else
						$analsisObject->setLayerFlag("F");
					/*----------------------Layer----------------------------------------------------------*/

					/*----------------------Split node----------------------------------------------------------*/
					if (isset($_POST["split"]))
						$analsisObject->setSplitFlag("T");
					else
						$analsisObject->setSplitFlag("F");
					/*----------------------Split node----------------------------------------------------------*/

					/*----------------------expand node----------------------------------------------------------*/
					if (isset($_POST["expand"]))
						$analsisObject->setExpandFlag("T");
					else
						$analsisObject->setExpandFlag("F");
					/*----------------------expand node----------------------------------------------------------*/

					/*----------------------multi state----------------------------------------------------------*/
					if (isset($_POST["multistate"]))
						$analsisObject->setMultistateFlag("T");
					else
						$analsisObject->setMultistateFlag("F");
					/*----------------------multi state----------------------------------------------------------*/

					/*----------------------match data----------------------------------------------------------*/
					if (isset($_POST["matchd"]))
						$analsisObject->setMatchDataFlag("T");
					else
						$analsisObject->setMatchDataFlag("F");
					/*----------------------match data----------------------------------------------------------*/

					/*----------------------gene discrete----------------------------------------------------------*/
					if (isset($_POST["gdisc"]))
						$analsisObject->setGeneDiscreteFlag("T");
					else
						$analsisObject->setGeneDiscreteFlag("F");
					/*----------------------gene discrete----------------------------------------------------------*/

					/*----------------------compound discrete----------------------------------------------------------*/
					if (isset($_POST["cdisc"]))
						$analsisObject->setCompoundDiscreteFlag("T");
					else
						$analsisObject->setCompoundDiscreteFlag("F");
					/*----------------------compound discrete----------------------------------------------------------*/

					/*----------------------Key Position----------------------------------------------------------*/
					$analsisObject->setKeyPosition($_POST["kpos"]);
					/*----------------------Key Position----------------------------------------------------------*/

					/*----------------------Signature position----------------------------------------------------------*/
					$analsisObject->setSignatureposition($_POST["pos"]);
					/*----------------------Signature position----------------------------------------------------------*/

					/*----------------------Compound Label Offset----------------------------------------------------------*/
					if (isset($_POST["offset"])) {
						$analsisObject->setOffset($_POST["offset"]);
					}
					/*----------------------Compound Label Offset----------------------------------------------------------*/

					/*----------------------Key Align----------------------------------------------------------*/
					$analsisObject->setKeyAlign($_POST["align"]);
					/*----------------------Key Align----------------------------------------------------------*/

					/*----------------------Gene Limit----------------------------------------------------------*/
					if (isset($_POST["glmt"])){
						$analsisObject->setGeneLimit(str_replace(",", ";", $_POST["glmt"]));
					}
					/*----------------------Gene Limit----------------------------------------------------------*/

					/*----------------------Gene Bins----------------------------------------------------------*/
					if (isset($_POST["gbins"])){
						$analsisObject->setGeneBins($_POST["gbins"]);
					}

					/*----------------------Gene Bins----------------------------------------------------------*/

					/*----------------------Compound Limit----------------------------------------------------------*/
					if (isset($_POST["clmt"])){
						$analsisObject->setCompoundLimit(str_replace(",", ";", $_POST["clmt"]));
					}

					/*----------------------Compound Limit----------------------------------------------------------*/

					/*----------------------Compound Bins----------------------------------------------------------*/
					if (isset($_POST["cbins"])) {
						$analsisObject->setCompoundBins($_POST["cbins"]);
					}

					/*----------------------Compound Bins----------------------------------------------------------*/


					/*----------------------Gene Color Low,Mid,High----------------------------------------------------------*/
					if (strpos($_POST["glow"], '#') !== false) {
						$analsisObject->setGeneLow($_POST["glow"]);
					} else {
						$analsisObject->setGeneLow("#".$_POST["glow"]);
					}

					if (strpos($_POST["gmid"], '#') !== false) {
						$analsisObject->setGeneMid($_POST["gmid"]);
					} else {
						$analsisObject->setGeneMid("#".$_POST["gmid"]);
					}

					if (strpos($_POST["ghigh"], '#') !== false) {
						$analsisObject->setGeneHigh($_POST["ghigh"]);
					} else {
						$analsisObject->setGeneHigh("#".$_POST["ghigh"]);
					}


					/*----------------------Gene Color Low,Mid,High----------------------------------------------------------*/


					/*----------------------Compound Color Low,Mid,High----------------------------------------------------------*/
					if (strpos($_POST["clow"], '#') !== false) {
						$analsisObject->setCompoundLow($_POST["clow"]);
					} else {
						$analsisObject->setCompoundLow("#".$_POST["clow"]);
					}

					if (strpos($_POST["cmid"], '#') !== false) {
						$analsisObject->setCompoundMid($_POST["cmid"]);
					} else {
						$analsisObject->setCompoundMid("#".$_POST["cmid"]);
					}

					if (strpos($_POST["chigh"], '#') !== false) {
						$analsisObject->setCompoundHigh($_POST["chigh"]);
					} else {
						$analsisObject->setCompoundHigh("#".$_POST["chigh"]);
					}



					/*----------------------Compound Color Low,Mid,High----------------------------------------------------------*/

					/*----------------------Node Sum----------------------------------------------------------*/
					$analsisObject->setNodeSum($_POST["nodesun"]);
					/*----------------------Node Sum----------------------------------------------------------*/

					/*----------------------Not Applicable Color----------------------------------------------------------*/
					$analsisObject->setNaColor($_POST["nacolor"]);
					/*----------------------Not Applicable Color----------------------------------------------------------*/

					$gage_model->setPathviewAnalyisObject($analsisObject);

				}
			}
			else{
				$gage_model->setDopathview("F");
			}

			$argument = $gage_model->generateArgument();
			$_SESSION['argument'] = $argument;
 			$_SESSION['destDir'] = $destFile;
        #n $argument;
        $Rloc = Config::get("app.RLoc");
        $publicPath = Config::get("app.publicPath");
        $_SESSION['analysis_id'] = $unique_analysis_id;

if (isset($_POST['dopathview'])&& strcmp($analysis, 'GagePathviewAnalysis') == 0)
        {
            exec($Rloc."Rscript ".$publicPath."GagePathviewRscript.R \"$argument\" > $destFile.'/outputFile.Rout' 2> $destFile.'/errorFile.Rout'");
            //exec("/home/ybhavnasi/R-3.1.2/bin/Rscript scripts/GagePathviewRscript.R  \"$argument\"  > $destFile.'/outputFile.Rout' 2> $destFile.'/errorFile.Rout'");
        }else {
            exec($Rloc."Rscript ".$publicPath."GageRscript.R \"$argument\" > $destFile.'/outputFile.Rout' 2> $destFile.'/errorFile.Rout'");
            //exec("/home/ybhavnasi/R-3.1.2/bin/Rscript scripts/GageRscript.R  \"$argument\"  > $destFile.'/outputFile.Rout' 2> $destFile.'/errorFile.Rout'");
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

//function to get the user using the application ip address

        $date = new \DateTime;

        //insert into the analysis table if the analysis is done event if the error occurred we insert into table
        if (Auth::user())
            DB::table('analysis')->insert(
                array('analysis_id' => $unique_analysis_id . "", 'id' => Auth::user()->id . "", 'arguments' => $argument . "", 'analysis_type' => $analysis, 'created_at' => $date, 'ip_add' => get_client_ip(), 'analysis_origin' => 'gage')
            );
        else
            DB::table('analysis')->insert(
                array('analysis_id' =>  $unique_analysis_id . "", 'id' => '0' . "", 'arguments' => $argument . "", 'analysis_type' => $analysis, 'created_at' => $date, 'ip_add' => get_client_ip(), 'analysis_origin' => 'gage')
            );

        return view('gage_pages.GageResult');

		}
























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
		$_SESSION['analysis_id'] = $time;
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
            $argument .="backgroundList:file". ";";;
            $file = Input::file('backgroundListInputFile');
            $filename1 = Input::file('backgroundListInputFile')->getClientOriginalName();
            $destFile = public_path() . "/" . "all/" . $email . "/" . $time . "/";
            $file->move($destFile, $filename1);
            $argument .="backgroundListFile:".$filename1.";";

        }else if($_POST['backgroundList'] != "")
        {
            $argument .="backgroundList:inputbox". ";";
            $BackgroundList = $_POST['backgroundList'];
            $file = public_path() . "/" . "all/" . $email . "/" . $time . "/backgroundList.txt";
            $bytes_written = File::put($file, $BackgroundList);
            if ($bytes_written === false)
            {
                die("Error writing to file");
            }

        }else{
            $argument .="backgroundList:none;";
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
            $argument .= "qcut:";
            $argument .= $_POST['cutoff'].";";
        }
        if (isset($_POST['dopathview'])) {
            $argument .= "do.pathview:T;";
            if (isset($_POST['dataType'])) {
                $argument .= "data.type:" . $_POST['dataType'] . ";";
            }
            if (isset($_POST['bins'])) {
                $argument .= "bins:" . $_POST['bins'] . ";";
            }
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

                    if (!File::copy($file1, $destFile.$filename1)) {
                        $_SESSION['error'] = 'Unfortunately file cannot be uploaded';
                        return view('gage_pages.GageAnalysis');
                    }
                    $argument .= "gsfn:" . $filename1 . ";";
                    $argument .= "gsetextension:" . preg_replace('/^.*\./', '', $filename1) . ";";
                }
            }
        }

        if (isset($_POST['ncutoff'])) {
            $argument .= "ncut:";
            $argument .= $_POST['ncutoff'].";";
        }

        if(isset($_POST["testEnrich"]))
        {
            $argument .= "test.enrich:T;";

        }else{
            $argument .= "test.enrich:F;";
        }



        $_SESSION['argument'] = $argument;
        $_SESSION['destDir'] = $destFile;
        #n $argument;
		$Rloc = Config::get("app.RLoc");
		$publicPath = Config::get("app.publicPath");
        $_SESSION['analysis_id'] = $time;

       exec($Rloc."Rscript ".$publicPath."discrete.R \"$argument\" > $destFile.'/outputFile.Rout' 2> $destFile.'/errorFile.Rout'");

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
        return view('gage_pages.DiscreteGageResult');

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

