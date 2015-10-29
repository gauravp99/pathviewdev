<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Models\PathviewAnalysis;
use Illuminate\Support\Facades\Config;
use Redis;
use Queue;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Cookie;
use App\Commands\SendJobAnalysisCompletionMail;
class PathviewAnalysisController extends Controller {

	public $cookieEnabled = false;
	public $uID=0;


	public function analysis($analyType)
	{

		//to store the number of parallel users to the application

		if(is_null(Redis::get("users_count"))){
			Redis::set("users_count",0);
		}

		$user_count = Redis::get("users_count");
		Redis::set("users_count",$user_count+1);

		//check if cookie enabled on the browser by setting a key and getting the value if able to get it
		//cookie is set otherwise work with ip address
		
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
		

		$this->uID = Cookie::get('uID');
		if(is_null($this->uID))
		{
		//	if(Auth::user())
		//	{
		//		$this->uID = Auth::user()->email;
		//	}else{
				Cookie::queue("uID",uniqid(),1440);
				$this->uID = Cookie::get('uID');
		//	}

		}

		if(is_null(Cookie::get('uID')))
		{
			$this->cookieEnabled = false;
			$this->uID = $this->get_client_ip();
		}
		else{
			$this->cookieEnabled = true;
		}

		//variables needs declaration used in later part of the code
		$err_atr = array(); // map saving the errors missed by javascript
		$gene_cmpd_color = array(); // saving the color of gene and compound
		$geneFileSize = 0;
		$compoundFileSize = 0;





		//set all post variables to session so that can be used for repopulating the form
		foreach ($_POST as $key => $value) {
			$_SESSION[$key] = $value;
		}
		$session = $_SESSION;

		//get errors listed in this array if mised by javascript is cached here
		$errors = array();

		$analsisObject = new PathviewAnalysis();

		//create a directory for user unique for each request
		//to get unique file name using uniqid() function returning 10 character unique id


		$uniqid = uniqid();
		$path = "";
		if(Auth::user())
		{
			//check if the user is done with his assigned memory quota

			$path = "all/".Auth::user()->email."/".$uniqid;
			if(!file_exists("all/".Auth::user()->email))
			{
				File::makeDirectory("all/".Auth::user()->email, $mode = 0755, true, true);
			}

		}else{
			$path = "all/demo/".$uniqid;
			if(!file_exists("all/demo"))
			{
				File::makeDirectory("all/demo", $mode = 0775, true, true);
			}
		}

		File::makeDirectory($path, $mode = 0775, true, true);
		$analsisObject->setTargetDirectory(public_path()."/".$path);
		//gene file
		if($analyType == 'newAnalysis')
		{
			//get files
			//get ref and sample text values
			// get compare values for both gene and compond variables
			if (Input::hasFile('gfile')) {

				$filename = Input::file('gfile')->getClientOriginalName();
				Input::file('gfile')->move($path,$filename);

				$analsisObject->setGeneFileName($filename);
				$gene_extension = File::extension($filename);
				$geneFileSize = ($_FILES['gfile']['size'])/(1024*1024);

				if ($gene_extension != "txt" && $gene_extension != "csv") {
					array_push($errors,'Gene data file extension is not supported( use .txt,.csv)');
					$err_atr["gfile"] = 1;
				}
				else{
					$analsisObject->setGeneExtension($gene_extension);
					if(!is_null($_POST['generef']))
					{
						$analsisObject->setGeneReference($_POST['generef']);
					}
					if(!is_null($_POST['genesam']))
					{
						$analsisObject->setGeneSample($_POST['genesam']);
					}
					if(!isset($_POST['genecompare']))
					{
						$analsisObject->setGeneCompare("paired");
					}else{
						$analsisObject->setGeneCompare("unpaired");
					}
				}



			}
			if (Input::hasFile('cpdfile')) {


				$filename1 = Input::file('cpdfile')->getClientOriginalName();
				$analsisObject->setCompoundFileName($filename1);
				Input::file('cpdfile')->move($path,$filename1);
				$cpd_extension = File::extension($filename1);
				$compoundFileSize = $_FILES['cpdfile']['size']/(1024*1024);

				if ($cpd_extension != "txt" && $cpd_extension != "csv" ) {
					array_push($errors, "compound data file extension is not supported( use .txt,.csv,.rda)");
					$err_atr["cpdfile"] = 1;
				}
				else{
					$analsisObject->setCompoundExtension($cpd_extension);
					if(!is_null($_POST['cpdref']))
					{
						$analsisObject->setCompoundRefeence($_POST['cpdref']);
					}
					if(!is_null($_POST['cpdsam']))
					{
						$analsisObject->setCompoundSample($_POST['cpdsam']);
					}
					if(!isset($_POST['cpdCompare']))
					{
						$analsisObject->setCompoundCompare("paired");
					}else{
						$analsisObject->setCompoundCompare("unpaired");
					}
				}



			}

		}
		else
		{

			$gene_filename = "";
			$compound_filename ="";
			$gene_filename_path = "";
			$compound_filename_path ="";
			if ($analyType == 'exampleAnalysis1')
			{

				if (Input::get('gcheck') == 'T')
				{
					$geneFileSize = 1;
					$gene_filename = Config::get('constants.example1_genefilename');
					$gene_filename_path = Config::get('constants.example1_genefilename_path');
				}

				if (Input::get('cpdcheck') == 'T')
				{
					$compoundFileSize = 1;
					$compound_filename = Config::get('constants.example1_compoundfilename');
					$compound_filename_path = Config::get('constants.example1_compoundfilename_path');
				}

				if(!(Input::get('gcheck') == 'T')&& !(Input::get('cpdcheck') == 'T'))
				{
					return view('pathview_pages.analysis.exampleAnalysis1');
				}



			}
			if ($analyType == 'exampleAnalysis2')
			{




				if (Input::get('gcheck') == 'T')
				{
					$geneFileSize = 1;
					$gene_filename = Config::get('constants.example2_genefilename');
					$gene_filename_path = Config::get('constants.example2_genefilename_path');

					//example2 genecompare and gene sample addition code and gene reference values addition code
					if(!is_null($_POST['generef']))
					{
						$analsisObject->setGeneReference($_POST['generef']);
					}
					if(!is_null($_POST['genesam']))
					{
						$analsisObject->setGeneSample($_POST['genesam']);
					}
					if(!isset($_POST['genecompare']))
					{
						$analsisObject->setGeneCompare("paired");
					}else{
						$analsisObject->setGeneCompare("unpaired");
					}

				}

				if (Input::get('cpdcheck') == 'T')
				{
					$compoundFileSize = 1;
					$compound_filename = Config::get('constants.example2_compoundfilename');
					$compound_filename_path = Config::get('constants.example2_compoundfilename_path');

					//example2 genecompare and compound sample addition code and compound reference values addition code
					if(!is_null($_POST['cpdref']))
					{
						$analsisObject->setCompoundRefeence($_POST['cpdref']);
					}
					if(!is_null($_POST['cpdsam']))
					{
						$analsisObject->setCompoundSample($_POST['cpdsam']);
					}
					if(!isset($_POST['cpdCompare']))
					{
						$analsisObject->setCompoundCompare("paired");
					}else{
						$analsisObject->setCompoundCompare("unpaired");
					}
				}
				if(!(Input::get('gcheck') == 'T') && !(Input::get('cpdcheck') == 'T'))
				{
					return view('pathview_pages.analysis.exampleAnalysis2');
				}

			}
			if ($analyType == 'exampleAnalysis3')
			{
				if (Input::get('gcheck') == 'T')
				{
					$geneFileSize = 1;
					$gene_filename = Config::get('constants.example3_genefilename');
					$gene_filename_path = Config::get('constants.example3_genefilename_path');
				}

				if (Input::get('cpdcheck') == 'T')
				{
					$compoundFileSize = 2;
					$compound_filename = Config::get('constants.example3_compoundfilename');
					$compound_filename_path = Config::get('constants.example3_compoundfilename_path');
				}
				if(!(Input::get('gcheck') == 'T')&& !(Input::get('cpdcheck') == 'T'))
				{
					return view('pathview_pages.analysis.exampleAnalysis3');
				}

			}

			if(Input::get('gcheck') == 'T')
			{
				copy($gene_filename_path,$path."/".$gene_filename);
				$analsisObject->setGeneFileName($gene_filename);
				$analsisObject->setGeneExtension(preg_replace('/^.*\./', '', $gene_filename));
			}

			if(Input::get('cpdcheck') == 'T')
			{
				copy($compound_filename_path,$path."/".$compound_filename);
				$analsisObject->setCompoundFileName($compound_filename);
				$analsisObject->setCompoundExtension(preg_replace('/^.*\./', '', $compound_filename));
			}

		}

		//------------------------suffix
		$analsisObject->setSuffix(preg_replace("/[^A-Za-z0-9 ]/", '', $_POST["suffix"]));

		//-----------------------pathwayids
		preg_match_all('!\d{5}!', $_POST['selecttextfield'], $matches);
		$pathway_array = array();
		$i = 0;
		foreach ($matches as $pathway1) {

			foreach ($pathway1 as $pathway)
			{
				//check in db if pathway exists or not
				$val = DB::select(DB::raw("select pathway_id from pathway where pathway_id like '$pathway' LIMIT 1"));
				if(sizeof($val) > 0)
					array_push($pathway_array, $pathway);
				$i = $i + 1;
				//limit imposed as per req to pathway id not more than 20 to each request
				if($i == 20)
					break;
			}
			if($i == 20)
				break;
		}
		//remove redundent pathway ids
		$pathway_array1 = array_unique($pathway_array);

		if(sizeof($pathway_array1) == 0)
		{
			array_push($errors, "Entered pathway ID doesn't exist");
			$err_atr["pathway"] = 1;
		}
		$analsisObject->setPathwayIDs($pathway_array1);

		//------------Gene ID

		$gene_id = $_POST['geneid'];

		$val = DB::select(DB::raw("select gene_id  from gene where gene_id  like '$gene_id' LIMIT 1 "));

		if(sizeof($val)>0){
			$analsisObject->setGeneId($gene_id);
		}else{
			array_push($errors, "Entered Gene ID doesn't exist");
			$err_atr["geneid"] = 1;
		}

		//---------Compound ID

		$cpd_id = $_POST['cpdid'];

		$val = DB::select(DB::raw("select compound_id  from compoundID where compound_id  like '$cpd_id' LIMIT 1 "));

		if (sizeof($val) > 0) {
			$analsisObject->setCompoundId($cpd_id);
		}else{
			array_push($errors, "Entered compound ID doesn't exist");
			$err_atr["cpdid"] = 1;
		}


		//----------Species ID

		$species_id = explode("-", $_POST["species"]);

		$val = DB::select(DB::raw("select species_id from species where species_id like '$species_id[0]' LIMIT 1"));

		if (sizeof($val) > 0) {
			$analsisObject->setSpeciesID($val[0]->species_id);
		}else{
			array_push($errors, "Entered Species ID doesn't exist");
			$err_atr["species"] = 1;
		}

		//------------Kegg ID
		if (isset($_POST["kegg"]))
			$analsisObject->setKeggFlag(true);
		else
			$analsisObject->setKeggFlag(false);

		//-------------Layer
		if(isset($_POST["layer"]))
			$analsisObject->setLayerFlag(true);
		else
			$analsisObject->setLayerFlag(false);

		//----------Split node
		if(isset($_POST["split"]))
			$analsisObject->setSplitFlag(true);
		else
			$analsisObject->setSplitFlag(false);

		//---------Expand node
		if(isset($_POST["expand"]))
			$analsisObject->setExpandFlag(true);
		else
			$analsisObject->setExpandFlag(false);


		//--------Multi State
		if(isset($_POST["multistate"]))
			$analsisObject->setMultistateFlag(true);
		else
			$analsisObject->setMultistateFlag(false);

		//---------Match Data
		if(isset($_POST["matchd"]))
			$analsisObject->setMatchDataFlag(true);
		else
			$analsisObject->setMatchDataFlag(false);

		//----------gene descrete
		if(isset($_POST["gdis"]))
			$analsisObject->setGeneDiscreteFlag(true);
		else
			$analsisObject->setGeneDiscreteFlag(false);

		//----------Compound descrete
		if(isset($_POST['cdisc']))
			$analsisObject->setCompoundDiscreteFlag(true);
		else
			$analsisObject->setCompoundDiscreteFlag(false);

		//-----------Key Position
		$analsisObject->setKeyPosition($_POST['kpos']);

		//------------Signature Position
		$analsisObject->setSignatureposition($_POST['pos']);

		//-----------compound label offset
		if (preg_match('/[a-z]+/', $_POST["offset"])) {
			array_push($errors, "offset should be Numeric");
			$err_atr["offset"] = 1;
		} else {
			$analsisObject->setOffset($_POST["offset"]);
		}

		//-----------Key Align
		$analsisObject->setKeyAlign($_POST['align']);

		//-----------Gene Limit
		if (preg_match('/[a-z]+/', $_POST["glmt"])) {
			array_push($errors, "Gene Limit should be Numeric");
			$err_atr["glmt"] = 1;
		}else{
			$analsisObject->setGeneLimit($_POST["glmt"]);
		}

		//-------------Gene Bins
		if (preg_match('/[a-z]+/', $_POST["gbins"])) {
			array_push($errors, "Gene Bins should be Numeric");
			$err_atr["gbins"] = 1;
		}
		else{
			$analsisObject->setGeneBins($_POST["gbins"]);
		}

		//-----------CompoundLimit
		if (preg_match('/[a-z]+/', $_POST["clmt"])) {
			array_push($errors, "Compound Limit should be Numeric");
			$err_atr["clmt"] = 1;
		} else{
			$analsisObject->setCompoundLimit($_POST['clmt']);
		}

		//-----------Compound Bins
		if (preg_match('/[a-z]+/', $_POST["cbins"])) {
			array_push($errors, "Compound Bins should be Numeric");
			$err_atr["cbins"] = 1;
		}
		else{
			$analsisObject->setCompoundBins($_POST["cbins"]);
		}

		//---------Gene Low,mid,high colors
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

		$gene_cmpd_color["glow"] = $_POST["glow"];
		$gene_cmpd_color["gmid"] = $_POST["gmid"];
		$gene_cmpd_color["ghigh"] = $_POST["ghigh"];



		//----------Compound Low,Mid,High
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


		$gene_cmpd_color["clow"] = $_POST["clow"];
		$gene_cmpd_color["cmid"] = $_POST["cmid"];
		$gene_cmpd_color["chigh"] = $_POST["chigh"];



		//--------Node Sum
		$analsisObject->setNodeSum($_POST["nodesun"]);

		//--------Not Applicable Color
		$analsisObject->setNaColor($_POST["nacolor"]);


		if(sizeof($errors)>0)
		{
			if (strcmp($analyType, 'exampleAnalysis1') == 0) {
				return Redirect::to('example1')
					->with('err', $errors)
					->with('err_atr', $err_atr)
					->with('Sess', $session)->with('genecolor', $gene_cmpd_color);
			} else if (strcmp($analyType, 'exampleAnalysis2') == 0) {
				return Redirect::to('example2')
					->with('err', $errors)
					->with('err_atr', $err_atr)
					->with('Sess', $session)->with('genecolor', $gene_cmpd_color);
			} else if (strcmp($analyType, 'exampleAnalysis3') == 0) {
				return Redirect::to('example3')
					->with('err', $errors)
					->with('err_atr', $err_atr)
					->with('Sess', $session)->with('genecolor', $gene_cmpd_color);
			} else if (strcmp($analyType, 'newAnalysis') == 0) {
				return Redirect::to('analysis')
					->with('err', $errors)
					->with('err_atr', $err_atr)
					->with('Sess', $session)->with('genecolor', $gene_cmpd_color);
			}
		}


		$_SESSION['id'] = $species_id[0] . substr($pathway_array1[0], 0, 5);
		$_SESSION['suffix'] = preg_replace("/[^A-Za-z0-9 ]/", '', $_POST["suffix"]);
		$_SESSION['workingdir'] = $path;
		$_SESSION['anal_type'] = $analyType;
		$_SESSION['analyses_id'] = $uniqid;

		if (isset($_POST["multistate"]))
			$_SESSION['multistate'] = "T";
		else
			$_SESSION['multistate'] = "T";

		//call createArgumentList function in PathviewAnalysis Bean
		$argument = $analsisObject->createArgument();

		$_SESSION['argument'] = $argument;


		//send data to queue handling the job
		$runHashdata = array();
		$runHashdata['destFile'] = public_path();
		$runHashdata['anal_type'] = $analyType;
		if (Auth::user())
			$runHashdata['user'] = Auth::user()->id;
		else
			$runHashdata['user'] = "demo";
		$runHashdata['argument'] = $argument;

		Redis::set($uniqid,json_encode($runHashdata));
		Redis::set($uniqid.":Status","false");


		//$geneFileSize;
		//$compoundFileSize;
		//to calcular the approx time it will take to run the analysis
		// factors number of pathview 1
		// size of the file Number of MB's more seconds
		// gene and compound both 1
		// number of parallel users;

		$factor = 0;
		$noOfPathways = sizeof($pathway_array1);

		$numberofUser = Redis::get("users_count");

		$totalSize = $geneFileSize + $compoundFileSize;

		$factor = ($noOfPathways*0.7 + $numberofUser*0.5 +  $totalSize*0.6)/3;
		$queueEnabled = Config::get("app.enableQueue");

		if(!$queueEnabled){
			$this->runAnalysis($uniqid,$argument,$path,$analyType);
			Redis::set('users_count',Redis::get('users_count') -1 );
			return view('pathview_pages.analysis.Result')->with(array('exception' => null, 'directory' => $path, 'analysisid' => $uniqid,'queueid' => 0, 'directory1' => $path , 'factor' => '-1000','queue' => false));
		}   else {


			//code to check if there are more than 2 current jobs for user executing
			$jobs = Redis::get("id:" . $this->uID) + 1;
			$process_queue_id = 0;
			if (is_null($jobs)) {
				Redis::set("id:" . $this->uID, 0);
			} else {

				if ($jobs > 2) {
					Redis::set("wait:" . $uniqid, true);
					$process_queue_id = -1;
					Redis::set("id:" . $this->uID, $jobs);
					return view('pathview_pages.analysis.Result')->with(array('exception' => null,
						'directory' => public_path() . "/" . $path,
						'directory1' => $path,
						'directory1' => $path,
						'queueid' => $process_queue_id,
						'analysisid' => $uniqid,
						'factor', $factor));

				} else {
					Redis::set("id:" . $this->uID, $jobs);
					$process_queue_id = Queue::push(new SendJobAnalysisCompletionMail($uniqid, $this->uID));
					$users_count = Redis::get("users_count");
					Redis::set("users_count", $users_count - 1);

					return view('pathview_pages.analysis.Result')->with(array('exception' => null,
						'directory' => public_path() . "/" . $path,
						'directory1' => $path,
						'queueid' => $process_queue_id,
						'analysisid' => $uniqid,
						'factor' => $factor));
				}

			}
		}

	}


	public function runAnalysis($time, $argument, $destFile, $anal_type)
	{
		$Rloc = Config::get("app.RLoc");
		$publicPath = Config::get("app.publicPath");
		exec($Rloc."Rscript ".$publicPath."my_Rscript.R \"$argument\"  > $destFile.'/outputFile.Rout' 2> $destFile.'/errorFile.Rout'");


		$date = new \DateTime;

		if (Auth::user())
			DB::table('analysis')->insert(
				array('analysis_id' => $time . "", 'id' => Auth::user()->id . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date,'analysis_origin' => 'pathview', 'ip_add' => "127.0.0.1")
			);
		else
			DB::table('analysis')->insert(
				array('analysis_id' => $time . "", 'id' => '0' . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'analysis_origin' => 'pathview','ip_add' => "127.0.0.1")
			);

		//start If there are error in the code analysis saving into database for reporting and solving by admin
		if(file_exists(public_path()."/".$destFile . "/errorFile.Rout"))
		{
			$lines = file(public_path()."/".$destFile . "/errorFile.Rout");
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
		}


	}



	public function new_analysis()
	{
		return view('pathview_pages.analysis.NewAnalysis');
	}

	public function postAnalysis()
	{
		$d = new PathviewAnalysisController();
		return $d->analysis("newAnalysis");
	}

	public function post_exampleAnalysis1()
	{
		$d = new PathviewAnalysisController();
		return $d->analysis("exampleAnalysis1");
	}

	public function post_exampleAnalysis2()
	{
		$d = new PathviewAnalysisController();
		return $d->analysis("exampleAnalysis2");
	}

	public function post_exampleAnalysis3()
	{
		$d = new PathviewAnalysisController();
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

}
