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

class SendJobAnalysisCompletionMail extends Command implements SelfHandling, ShouldBeQueued
{

    use InteractsWithQueue, SerializesModels;
    protected $time;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($time)
    {
        $this->time = $time;
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

        $hashdata = json_decode(Redis::get($this->time),true);

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

        exec("/home/ybhavnasi/R-3.1.2/bin/Rscript /home/ybhavnasi/Desktop/Pathway/public/my_Rscript.R \"$argument\" >$outFile  2> $errorfile ");

        $date = new \DateTime;

        if ($user != 0)
            DB::table('analyses')->insert(
                array('analysis_id' => $time . "", 'id' => $user . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'ipadd' => $this->get_client_ip())
            );
        else
            DB::table('analyses')->insert(
                array('analysis_id' => $time . "", 'id' => '0' . "", 'arguments' => $argument . "", 'analysis_type' => $anal_type, 'created_at' => $date, 'ipadd' => $this->get_client_ip())
            );

        Redis::set('users_count',Redis::get('users_count')-1 );




    }
}
