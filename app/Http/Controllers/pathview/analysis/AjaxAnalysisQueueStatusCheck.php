<?php namespace App\Http\Controllers\pathview\analysis;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Redis;
use Auth;
use Mail;
use Illuminate\Support\Facades\Cookie;
use \Illuminate\Support\Facades\Input;
use App\Commands\SendJobAnalysisCompletionMail;
use Queue;
use Illuminate\Support\Facades\URL;
/**
 * Class AjaxAnalysisQueueStatusCheck
 * @package App\Http\Controllers
 *
 * This program checks the queued job status this controller function is called from ajax call
 * Queue status is maintained in redis. whenever redis status is set to "true" it means that analysis is completed
 * we us redis to check the status because it is faster in retrieving things
 *
 * Status of each job when submitted is set using <#analysisID>:Status
 *
 */

class AjaxAnalysisQueueStatusCheck extends Controller
{


    /**
     * @return string
     * This function is called on ajax request from the analysis result page
     * this function looks for queue status in redis for job if completed successfully or not
     * if completed successfully it returns true.
     */
    public function index()
    {

        //check if the status is true send a mail to user with status and return true
        if (strcmp(Redis::get(Input::get('analysisid') . ":Status"), "true") == 0) {

            //mail is sent only if the user created is an authorised user
            if (Auth::user()) {
                $data['content'] = "?analyses=" . Input::get('analysisid') . "&id=" . Input::get('id') . "&suffix=" . Input::get('suffix') . "&email=" . Auth::user()->email;
                $data['name'] = Auth::user()->name;
                $data['email'] = Auth::user()->email;
                $data['time'] = date('l jS \of F Y h:i:s A');
                $data['anal_type'] = Input::get('anal_type');
                $data['argument'] = Input::get('argument');
                $data['url'] = URL::to('/');

		//Commenting it now not to send mail when analysis completed
                //try{
                //    Mail::queue('emails.result', $data, function ($message) use ($data) {
                //    #Mail::send('emails.result', $data, function ($message) use ($data) {
                //        try {
                //            $user_email = $data['email'];
                //            $user_name = $data['name'];
                //            $message->to($user_email, $user_name)->subject('Analysis completed');

                //        } catch (Exception $e) {
                //            return "exception in mail";
                //        }
                //    });
                //}
                //catch(Exception $e)
                //{
                //    //delete the analaysisid status from redis
                //    Redis::del(Input::get('analysisid') . ":Status");
                //    Redis::del(Input::get('analysisid'));
                //    return "true";
                //}


                //delete the analaysisid status from redis
                Redis::del(Input::get('analysisid') . ":Status");
                Redis::del(Input::get('analysisid'));
                return "true";

            } else {

                Redis::del(Input::get('analysisid') . ":Status");
                Redis::del(Input::get('analysisid'));
                return "true";

            }
        }else{
            return "false";
        }

        die();
    }


    /**
     * @return string
     * This function is called whenever a user is requesting multiple jobs at a time typically more than 2 requests at a time
     * if this happens then wait flag is set for user whenever number of parallel request are reduced to less than 1 then the request is sent to queue
     */

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
