<?php namespace App\Http\Controllers\gage\analysis;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\pathview\analysis\AnalysisController;
use Illuminate\Http\Request;
use Redis;
use Illuminate\Support\Facades\Cookie;
use \Illuminate\Support\Facades\Input;
class AjaxAnalysisQueueStatusCheck extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        if (strcmp(Redis::get(Input::get('analysisid') . ":Status"), "true") == 0) {
            return "true";

        }
        else{
                return "false";
            }
	}
	public function checkStatus(){

		//if the count is greater than 2 then set a wait flag and set all details into redis so that it can be fetched again by queue and run the job again
		//if less thna 2 then wait falg is removed also the details are fetched and a job is pushed to the queue
		if (Cookie::get('uID') == null) {
			$analysis = new AnalysisController();
			$uID = $analysis->get_client_ip();
		}else{
			$uID = Cookie::get("uID");
		}
		$jobflag = Redis::get("wait:".Input::get('analysisid'));
		if(!is_null($jobflag))
		{

			if($jobflag == "true")
			{
				return "pushedJob";
			}else{
				return "still waiting";
			}
		}



	}

}
