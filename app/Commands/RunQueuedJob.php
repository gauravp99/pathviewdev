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
use App\Http\Models\QueueJob;
use Illuminate\Support\Facades\Config;
use DateTime;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\pathview\analysis\AnalysisController;
use Queue;


/***
 * Class RunQueuedJob
 * @package App\Commands
 *
 * This class is used to run the pathview and gage analysis by the queue
 * handle is function called when queue submits the job
 */
class RunQueuedJob extends Command implements SelfHandling, ShouldBeQueued
{

    use InteractsWithQueue, SerializesModels;

    //analysisID for each unique request
    protected $analysisID;
    //user id of the requester
    protected $user_unique_id;
    //either gage or pathview
    protected $analysis_origin;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($analysisID, $user_unique_id, $analysis_origin)
    {
        $this->analysisID = $analysisID;
        $this->user_unique_id = $user_unique_id;
        $this->analysis_origin = $analysis_origin;
    }

    /**
     * Execute the command.
     *this function is called by the queue handler
     * @return void
     */
    public function handle()
    {
        //Log::info('Starting Pull');


        //retrieving information from redis set for the analysis
        $hashdata = json_decode(Redis::get($this->analysisID), true);

        //argument of the job
        $argument = $hashdata['argument'];

        //destination folder  to keep the log files
        $destFile = $hashdata['destFile'];

        //to insert into database analysis type example 1 or 2
        $anal_type = $hashdata['anal_type'];

        //user id to insert into database
        $userobject = $hashdata['user'];

        //ip address of the user performing the anaysis
        $ip_add = $hashdata['ip_add'];

        // check if user is guest user or authenticated user corresponding user id
        if ($userobject == "demo")
            $user = 0;
        else
            $user = $userobject;


        //log file names for the rscript
        $outFile = $destFile . '/' . $this->analysisID . '/outputFile.Rout';
        $errorfile = $destFile . '/' . $this->analysisID . '/errorFile.Rout';


        //location of R and public path
        $Rloc = Config::get("app.RLoc");
        $publicPath = Config::get("app.publicPath");

        //this handle function can be called either from pathview or from gage this code make sure specific rscript is called
        if (strcmp($this->analysis_origin, "pathview") == 0)
        {
            exec($Rloc . "Rscript " . $publicPath . "my_Rscript.R \"$argument\" >$outFile  2> $errorfile ");
        }
        else {
                if (strcmp($anal_type, "discreteGageAnalysis") == 0) {
                    exec($Rloc . "Rscript " . $publicPath . "discrete.R \"$argument\" > $outFile  2> $errorfile ");

                }
                else
                {
                    exec($Rloc . "Rscript " . $publicPath . "GageRscript.R \"$argument\" > $outFile  2> $errorfile ");
                }

        }


        //once the anlalysis is done deleting the analysis id from redis server
        Redis::del($this->analysisID);

        //making the analysis status to true used by waitingjob status check to trigger and show the view
        Redis::set($this->analysisID . ":Status", "true");

        //reduce the count of jobs currently executing
        $count = Redis::get("id:" . $this->user_unique_id) - 1;

        //make sure the count is not made less than 0
        if ($count < 0)
            $count = 0;

        //update the total number of jobs executed by user
        Redis::set("id:" . $this->user_unique_id, $count);

        //inserting data into table
        $date = new DateTime;
        $record = DB::table('analysis')->where('analysis_id', $this->analysisID)->get();
        if (strcmp($anal_type, "Analysis History regenerated") == 0 || sizeof($record) > 0) {
            DB::table('analysis')
                ->where('analysis_id', $this->analysisID)
                ->update(['analysis_type' => 'Analysis History regenerated']);
        } else {
            if ($user != 0)
                DB::table('analysis')->insert(
                    array('analysis_id' => $this->analysisID . "", 'id' => $user . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'analysis_origin' => $this->analysis_origin, 'ip_add' => $ip_add)
                );
            else
                DB::table('analysis')->insert(
                    array('analysis_id' => $this->analysisID . "", 'id' => '0' . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'analysis_origin' => $this->analysis_origin, 'ip_add' => $ip_add)
                );
        }


        //deleting the wait tag if exist for analysis
        if (!is_null(Redis::get("wait:" . $this->analysisID)))
            Redis::del("wait:" . $this->analysisID);

        //get any job present in waiting status to run in queue
        $wjobidsjson = Redis::get("wids:" . $this->user_unique_id);

        $wjobids = array();
        if (!is_null($wjobidsjson)) {
            $wjobids = json_decode($wjobidsjson);
        }

        //if there are waiting jobs for user
        if (sizeof($wjobids) > 0) {

            //take the first waiting job
            $first_waiting_object = array_shift($wjobids);

            //taking the analysis id
            $first_waiting_job = $first_waiting_object->analysis_id;

            //taking the origin either gage/ pathview for analysis
            $first_waiting_job_origin = $first_waiting_object->analysis_origin;

            //update the redis with waiting ids
            Redis::set("wids:" . $this->user_unique_id, json_encode($wjobids));

            Redis::set("wait:" . $first_waiting_job, "true");

            //push the job to queue
            $process_queue_id = Queue::push(new RunQueuedJob($first_waiting_job, $this->user_unique_id, $first_waiting_job_origin));

            //increse the number of jobs currently in executing
            $jobs = Redis::get("id:" . $this->user_unique_id);
            Redis::set("id:" . $this->user_unique_id, $jobs + 1);

        }


    }
}
