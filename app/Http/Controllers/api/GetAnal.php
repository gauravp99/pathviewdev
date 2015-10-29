<?php namespace App\Http\Controllers\api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use User;
use App;
use DB;
use Mail;
use App\Http\Models\Analysis;
use Illuminate\Http\Request;


/**
 * Class GetAnal
 * @package App\Http\Controllers\api
 *
 * this controller is for admin to look at the analysis of the current year
 */
class GetAnal extends Controller {

	public function getMonths()
	{

		//this function returns the json formatted analysis details
		// in map with key as map and list of analysis for that months as value

		//get the current year details
		$year = date("Y");

		//get the current month details
		$month = date("m");

		$monthtemp  = $month;

		//get the analysis of all months starting from january of current year to the current month
		$analysisarray= array();

		//check if month value retrieved now is greate than zero
		while($month > 0)
		{
			//get the analysis model object to save details and send as json for flexibility and standards maintainence
			$analysis = new App\Analysis();

			//to append zero infront of the month if the month is less than 10
			if($month == $monthtemp  || $month > 10)
			{
				$analysis->generateData($month, $year);
			}else{
				$analysis->generateData("0".$month, $year);
			}

			//add the month details to array
			array_push($analysisarray, $analysis);

			//reduce the month value
			$month = $month - 1;
		}


		//return the created analysis array with months
		return $analysisarray;
	}


	//this code have to be updated as this is not tried using in the application

	public function getYears()
	{
		//get the details according to years
		$year = date("Y");

		$minYear = DB::select(DB::raw("select min(created_at) as \"min\" from analysis"));

		$least_year_in_table = $minYear[0]->min;
		//$least_year_in_table = explode($least_year_in_table,"-")[0];

		$least_year_in_table =  substr($least_year_in_table,0,4);
		$month = date("m");
		$monthtemp  = $month;

		$analysisarrayYear = array();
		while($least_year_in_table <= $year ) {
			$analysisarray= array();
			if($least_year_in_table == $year)
			{
				$month = date("m");
			}
			else{
				$month = 12;
			}
			while ($month > 0) {
				$analysis = new Analysis();

				if ($month == $monthtemp || $month >= 10) {
					$count = DB::select(DB::raw("select count(*) as \"count\" from analysis where  created_at >'".$least_year_in_table."-".$month."-01' and created_at < '".$least_year_in_table."-".$month."-31'"));
					if($count[0]->count == 0)
					{
						$month = $month - 1;
						continue;
					}
					$analysis->generateData($month, $least_year_in_table);
				} else {

					$count = DB::select(DB::raw("select count(*) as \"count\" from analysis where  created_at >'".$least_year_in_table."-".$month."-01' and created_at < '".$least_year_in_table."-".$month."-31'"));
					if($count[0]->count == 0)
					{
						$month = $month - 1;
						continue;
					}
					$analysis->generateData("0" . $month, $least_year_in_table);
				}
				$month = $month - 1;
				array_push($analysisarray, $analysis);
			}
			$analysisarrayYear[$least_year_in_table] = $analysisarray;
			$least_year_in_table = $least_year_in_table + 1;
		}
		return $analysisarrayYear;
	}


	//yet to implement this code
	public function getManual()
	{
		return "manually";
	}
}
