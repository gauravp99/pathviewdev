<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Redis;
use Auth;
use Mail;
use Illuminate\Http\Request;

use \Illuminate\Support\Facades\Input;
class AjaxGageAnalysisStatusCheck extends Controller {

	/**
	 * Display a listing of the resource.
	 *
     *
     * This program checks the queued job status this controller function is called from ajax call
     * Queue status is maintained in redis. whenever redis status is set to "true" it means that analysis is completed
     * we us redis to check the status because it is faster in retrieving things
     *
     * Status of each job when submitted is set using <#analysisID>:Status
     *
     * this function return true is the analysis is completed and status is changed to true
     * or else it returns false
	 * @return Response
	 */
	public function index()
	{
        //compare the status is equal to true
        if(strcmp(Redis::get(Input::get('analysisid') . ":Status"), "true")==0)
        {

            //have to write code to send mail to user that analysis is done

            //deleting the status check from redis
            Redis::del(Input::get('analysisid') . ":Status");
            Redis::del(Input::get('analysisid'));
            return "true";
        }
        else {
            return "false";
        }
	}



}
