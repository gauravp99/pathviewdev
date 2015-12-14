<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\User;
use Mail;
use Redis;
use DB;
use Illuminate\Support\Facades\Config;
use DateTime;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\pathview\analysis\AnalysisController;
use Queue;
//use Illuminate\Log;

/***
 * Class SendJobAnalysisCompletionMail
 * @package App\Commands
 *
 * This class is used to run the pathview analysis by the queue
 * handle is function called when queue submits the job
 */
class SendJobAnalysisCompletionMail extends Command implements SelfHandling, ShouldBeQueued
{

    use InteractsWithQueue, SerializesModels;

    protected $analysisID,$user_unique_id;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($analysisID,$user_unique_id)
    {


        $this->analysisID = $analysisID;
        $this->user_unique_id = $user_unique_id;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        //Log::info('Starting Pull');


       //retrieving information from redis set for the analysis
        $hashdata = json_decode(Redis::get($this->analysisID),true);



        $argument = $hashdata['argument'];
        $destFile = $hashdata['destFile'];
        $anal_type = $hashdata['anal_type'];
        $userobject = $hashdata['user'];
        $ip_add = $hashdata['ip_add'];
      // check if user is guest user or authenticated user
	    if($userobject=="demo")
            $user = 0;
        else
            $user = $userobject;

        $outFile = $destFile . '/outputFile.Rout';
        $errorfile = $destFile . '/errorFile.Rout';

        $Rloc = Config::get("app.RLoc");
        $publicPath = Config::get("app.publicPath");

        exec($Rloc."Rscript ".$publicPath."my_Rscript.R \"$argument\" >$outFile  2> $errorfile ");

        Redis::del($this->analysisID);
        Redis::set($this->analysisID.":Status","true");

        $count = Redis::get("id:".$this->user_unique_id) -1 ;

        if($count < 0)
            $count = 0;

        Redis::set("id:".$this->user_unique_id,$count);

        $date = new DateTime;
        $record = DB::table('analysis')->where('analysis_id',$this->analysisID)->get();
        if(strcmp($anal_type,"Analysis History regenerated")==0 || sizeof($record)>0) {

        DB::table('analysis')
            ->where('analysis_id', $this->analysisID)
            ->update(['analysis_type' => 'Analysis History regenerated']);
        }
        else
        {
            if ($user != 0)
                DB::table('analysis')->insert(
                    array('analysis_id' => $this->analysisID . "", 'id' => $user . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'analysis_origin' => 'pathview','ip_add' => $ip_add)
                );
            else
                DB::table('analysis')->insert(
                    array('analysis_id' => $this->analysisID . "", 'id' => '0' . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'analysis_origin' => 'pathview','ip_add' => $ip_add)
                );
        }



        //deleting the wait tag if exist for usr
        if(!is_null(Redis::get("wait:".$this->analysisID)))
        Redis::del("wait:".$this->analysisID);

        //get any job present in waiting status to run in queue
        $wjobidsjson = Redis::get("wids:" . $this->user_unique_id);

        $wjobids = array();
        if(!is_null($wjobidsjson))
        {
            $wjobids = json_decode($wjobidsjson);
        }

        if(sizeof($wjobids) > 0)
        {


            $first_waiting_job = array_shift($wjobids);

            //update the redis with waiting ids
            Redis::set("wids:" . $this->user_unique_id,json_encode($wjobids));

                Redis::set("wait:".$first_waiting_job,"true");

            //push the job to queue
            $process_queue_id = Queue::push(new SendJobAnalysisCompletionMail($first_waiting_job, $this->user_unique_id));

            $jobs = Redis::get("id:". $this->user_unique_id);
            Redis::set("id:" . $this->user_unique_id, $jobs+1);


        }


    }
}
