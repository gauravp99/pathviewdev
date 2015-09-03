<?php namespace App\Http\Controllers\api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use User;
use App;
use DB;
use Mail;

use Illuminate\Http\Request;

class GetAnal extends Controller {

	public function getMonths()
	{

		$year = date("Y");
		$month = date("m");
		$monthtemp  = $month;

		$analysisarray= array();
		while($month > 0)
		{
			$analysis = new App\Analysis();
			if($month == $monthtemp  || $month > 10)
			{
				$analysis->generateData($month, $year);
			}else{
				$analysis->generateData("0".$month, $year);
			}
			array_push($analysisarray, $analysis);

			$month = $month - 1;
		}
		return $analysisarray;
	}

	public function getYears()
	{
		$year = date("Y");
		$minYear = DB::select(DB::raw("select min(created_at) as \"min\" from analyses"));

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
				$analysis = new App\Analysis();

				if ($month == $monthtemp || $month >= 10) {
					$count = DB::select(DB::raw("select count(*) as \"count\" from analyses where  created_at >'".$least_year_in_table."-".$month."-01' and created_at < '".$least_year_in_table."-".$month."-31'"));
					if($count[0]->count == 0)
					{
						$month = $month - 1;
						continue;
					}
					$analysis->generateData($month, $least_year_in_table);
				} else {

					$count = DB::select(DB::raw("select count(*) as \"count\" from analyses where  created_at >'".$least_year_in_table."-".$month."-01' and created_at < '".$least_year_in_table."-".$month."-31'"));
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

	public function getManual()
	{
		return "manually";
	}
}
