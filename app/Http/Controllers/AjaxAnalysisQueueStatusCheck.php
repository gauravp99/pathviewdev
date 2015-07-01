<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Redis;
use Auth;
use Mail;
use \Illuminate\Support\Facades\Input;

class AjaxAnalysisQueueStatusCheck extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
    {

        //check if the status is true send a mail to user with status and return true
      if (strcmp(Redis::get(Input::get('analysisid') . ":Status"), "true") == 0) {
          //mail is sent only if the user created is an authorised user
            if (Auth::user()) {
                $data['content'] = "?analyses=" . Input::get('analysisid') . "&id=" . Input::get('id') . "&suffix=" . Input::get('suffix') . "&email=" . Auth::user()->email;
                $data['name'] = Auth::user()->name;
                $data['time'] = date('l jS \of F Y h:i:s A');
                $data['anal_type'] = Input::get('anal_type');
                $data['argument'] = Input::get('argument');

                Mail::send('emails.result', $data, function ($message) use ($data) {
                    try {

                        $user = Auth::user();
                        $message->to($user->email, $user->name)->subject('Analysis completed');

                    } catch (Exception $e) {
                       return "exception in mail";
                    }
                });


            }
          else
          {
              Redis::get(Input::get('analysisid') . ":Status");
          }


        }

//checking if the status is true or not if status is true then delete the process from the redis key value pair.
if(strcmp(Redis::get(Input::get('analysisid') . ":Status"), "true")==0)
{
    Redis::del(Input::get('analysisid') . ":Status");
    Redis::del(Input::get('analysisid'));
    return "true";
}else{
    return "false";
}


        die();
    }




}
