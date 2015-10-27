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

                if (Cookie::get('uID') == null)
                {
                    $analysis = new AnalysisController();
                    $uID = $analysis->get_client_ip();
                }
                else{
                    $uID = Cookie::get("uID");
                }

                Redis::del(Input::get('analysisid') . ":Status");
                Redis::del(Input::get('analysisid'));
                return "true";

            } else {
                if (Cookie::get('uID') == null)
                {
                    $analysis = new AnalysisController();
                    $uID = $analysis->get_client_ip();
                }
                else{
                    $uID = Cookie::get("uID");
                }

                Redis::del(Input::get('analysisid') . ":Status");
                Redis::del(Input::get('analysisid'));
                return "true";

            }


        }else{
            return "false";
        }




        die();
    }

    public function checkStatus(){

        if(Auth::user()){
            $count = 0;
            if(!is_null(Redis::get("id:".Auth::user()->email)))
            {
                $count = Redis::get("id:".Auth::user()->email);

            }

            if($count < 2)
            {
                Redis::set("id:".Auth::user()->email,$count+1);
                $jobflag = Redis::get("wait:".Input::get('analysisid'));
                if(!is_null($jobflag))
                {
                    $process_queue_id = Queue::push(new SendJobAnalysisCompletionMail(Input::get('analysisid'),Auth::user()->email));
                    Redis::del("wait:".Input::get('analysisid'));
                }

                Redis::set("users_count",Redis::get("users_count")-1);
                return "pushedJob";
            }
            else{
                return "stillWaiting";
            }

        }else{

            if (Cookie::get('uID') == null) {
                $analysis = new AnalysisController();
                $uID = $analysis->get_client_ip();
            }else{
                $uID = Cookie::get("uID");
            }

            $count = 0;
            if(!is_null(Redis::get("id:".$uID)))
            {
                $count = Redis::get("id:".$uID);
            }
            if($count <= 2){

                $jobflag = Redis::get("wait:".Input::get('analysisid'));
                if(!is_null($jobflag))
                {
                    $process_queue_id = Queue::push(new SendJobAnalysisCompletionMail(Input::get('analysisid'),$uID));
                    Redis::del("wait:".Input::get('analysisid'));
                }
                return "pushedJob";
            }
            else{
                return $uID+"stillWaiting"+$count;
            }


        }





    }



}
