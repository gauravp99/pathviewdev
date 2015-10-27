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

class SendJobAnalysisCompletionMail extends Command implements SelfHandling, ShouldBeQueued
{

    use InteractsWithQueue, SerializesModels;
    protected $time;
    public $uniqueID;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($time,$uniqueID)
    {
        $this->time = $time;
        $this->uniqueID = $uniqueID;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
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

    public function handle()
    {

        //reduce the number of parllel job of the user currently exist by 1

        $hashdata = json_decode(Redis::get($this->time),true);
        Redis::del("wait:".$this->time);


        $time = $this -> time;

        $argument = $hashdata['argument'];
        $destFile = $hashdata['destFile'];
        $anal_type = $hashdata['anal_type'];
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

        exec($Rloc."Rscript ".$publicPath."my_Rscript.R \"$argument\" >$outFile  2> $errorfile ");
        $count = Redis::get("id:".$this->uniqueID) -1 ;
        Redis::set("test:".$count,$this->uniqueID);
        if($count <0)
            $count = 0;
        Redis::set("id:".$this->uniqueID,$count);

        $date = new DateTime;
        $record = DB::table('analysis')->where('analysis_id',$time)->get();
        if(strcmp($anal_type,"Analysis History regenerated")==0 || sizeof($record)>0) {

        DB::table('analysis')
            ->where('analysis_id', $time)
            ->update(['analysis_type' => 'Analysis History regenerated']);
        }
        else
        {
            if ($user != 0)
                DB::table('analysis')->insert(
                    array('analysis_id' => $time . "", 'id' => $user . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'analysis_origin' => 'pathview','ip_add' => $this->get_client_ip())
                );
            else
                DB::table('analysis')->insert(
                    array('analysis_id' => $time . "", 'id' => '0' . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'analysis_origin' => 'pathview','ip_add' => $this->get_client_ip())

                );
        }
        Redis::del($time);
        Redis::set($time.":Status","true");




    }
}
