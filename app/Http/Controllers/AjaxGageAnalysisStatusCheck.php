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
	 * @return Response
	 */
	public function index()
	{

        if(strcmp(Redis::get(Input::get('analysisid') . ":Status"), "true")==0)
        {
            Redis::del(Input::get('analysisid') . ":Status");
            Redis::del(Input::get('analysisid'));
            return "true";
        }else{
            return "false";
        }



	}



}
