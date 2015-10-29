<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\User;
use Mail;
use Illuminate\Support\Facades\Config;
use Redis;
use DB;

/**
 * Class sendGageAnalysisCompletionMail
 * @package App\Commands
 *
 * This job is used run the gage analysis by the queue
 */
class sendGageAnalysisCompletionMail extends Command implements SelfHandling {

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */


    protected $time;

    public function __construct($time)
    {
        $this->time = $time;
    }


    function get_client_ip()
    {

        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }


	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		//code to run the job on queue for gage analysis

        $hashdata = json_decode(Redis::get($this->time),true);

        $time = $this -> time;

        $argument = $hashdata['argument'];
        $destFile = $hashdata['destFile'];
        $userobject = $hashdata['user'];
        if($userobject=="demo")
            $user = 0;
        else
            $user = $userobject;
        $outFile = $destFile . '/outputFile.Rout';
        $errorfile = $destFile . '/errorFile.Rout';
        echo $outFile;
        echo $errorfile;
        $Rloc = Config::get("app.RLoc");
        $publicPath = Config::get("app.publicPath");

        exec($Rloc."Rscript ".$publicPath."GageRscript.R \"$argument\" >$outFile  2> $errorfile ");

        $anal_type = 'gage';
        $date = new \DateTime;
        $record = DB::table('analyses')->where('analysis_id',$time)->get();

            if ($user != 0)
                DB::table('analyses')->insert(
                    array('analysis_id' => $time . "", 'id' => $user . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'ipadd' => $this->get_client_ip(),'analysis_origin' => 'gage')
                );
            else
                DB::table('analyses')->insert(
                    array('analysis_id' => $time . "", 'id' => '0' . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'ipadd' => $this->get_client_ip(),'analysis_origin' => 'gage')

                );

        Redis::set($time.":Status","true");
    }


}
