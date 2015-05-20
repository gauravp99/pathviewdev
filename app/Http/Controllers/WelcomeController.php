<?php namespace App\Http\Controllers;

use DateTime;
use Illuminate\Support\Facades\DB;
use stdClass;

class WelcomeController extends Controller
{


    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index()
    {
        /*
        Mail::send('emails.test',array('name'=>'yeshvant'),function($message){
            $message ->to('byeshvant@hotmail.com','bhavnasiyeshvant')->subject('test email');
        });*/
        /* $user = User::find(1);
         echo '<pre>'.print_r($user).'</pre>';*/

        $usage = array();
        $ip = array();
        $months = array();
        /*       array_push($usage, 1);
               array_push($usage, 1);
               array_push($usage, 1);
               array_push($usage, 1);
               array_push($ip, 1);
               array_push($ip, 1);
               array_push($ip, 1);
               array_push($ip, 1);*/

        $val = DB::select(DB::raw('SELECT COUNT(1) as count,count(distinct ipadd) as ipadd_count, DATE_FORMAT(created_at, \'%b-%y\') as date FROM analyses where created_at >= CURDATE() - INTERVAL 6 MONTH GROUP BY YEAR(created_at), MONTH(created_at)'));
        foreach ($val as $month) {
            array_push($usage, $month->count);
            array_push($ip, $month->ipadd_count);
            array_push($months, $month->date);
        }
        /* if (sizeof($months) <= 6)
             $months = array("Dec-14", "Jan-15", "Feb-15", "Mar-15", "April-15", "May-15");*/

        //bio conducter statistics for package

        $bioc_val = DB::select(DB::raw('select concat(concat(month,\'-\'),year%100) as date,numberof_uniqueip as ipadd,numberof_downloads as downloads from biocstatistics'));
        $bioc_downloads = array();
        $bioc_ip = array();
        $bioc_months = array();

        foreach ($bioc_val as $month) {
            array_push($bioc_downloads, $month->downloads);
            array_push($bioc_ip, $month->ipadd);
            array_push($bioc_months, $month->date);
        }

        $count_bioc_downlds = DB::select(DB::raw('select sum(numberof_downloads)+15000 as "downloads" from biocstatistics'));
        $count_bioc_ips = DB::select(DB::raw('select sum(numberof_uniqueip)+7500 as "ip" from biocstatistics'));
        $count_web_downlds = DB::select(DB::raw('select count(*) as "downloads" from analyses'));
        $count_web_ips = DB::select(DB::raw('select count(distinct ipadd) as "ip" from analyses'));

        foreach ($count_bioc_downlds as $bioc_dwnld) {
            $count_bioc_downlds = $bioc_dwnld;
            break;
        }

        foreach ($count_bioc_ips as $bioc_dwnld) {
            $count_bioc_ips = $bioc_dwnld;
            break;
        }

        foreach ($count_web_downlds as $bioc_dwnld) {
            $count_web_downlds = $bioc_dwnld;
            break;
        }

        foreach ($count_web_ips as $bioc_dwnld) {
            $count_web_ips = $bioc_dwnld;
            break;
        }


        $array = array();
        $array[0] = new stdClass();
        $id = 0;
        foreach ($bioc_months as $mon) {
            $array[$id] = new stdClass();
            $array[$id]->id = $id;
            $array[$id]->Month = $mon;
            $array[$id]->ip = $bioc_ip[$id];
            $array[$id]->download = $bioc_downloads[$id];
            $id = $id + 1;
        }

        usort($array, function ($a, $b) {
            $aDate = DateTime::createFromFormat("M-Y", $a->Month);
            $bDate = DateTime::createFromFormat("M-Y", $b->Month);
            return $aDate->getTimestamp() - $bDate->getTimestamp();
        });
        $sorted_bioc_months = array();
        $sorted_bioc_ip = array();
        $sorted_bioc_download = array();
        foreach ($array as $mon) {
            array_push($sorted_bioc_months, $mon->Month);
            array_push($sorted_bioc_ip, $mon->ip);
            array_push($sorted_bioc_download, $mon->download);


        }
#to get lst 12 months statistics only
        $sorted_bioc_month_12 = array();
        $sorted_bioc_ip_12 = array();
        $sorted_bioc_download_12 = array();
        $total_months = sizeof($sorted_bioc_months) - 1;
        for ($i = $total_months; $i > $total_months - 12; $i = $i - 1) {
            array_push($sorted_bioc_month_12, $sorted_bioc_months[$i]);
            array_push($sorted_bioc_ip_12, $sorted_bioc_ip[$i]);
            array_push($sorted_bioc_download_12, $sorted_bioc_download[$i]);
        }


        return view('welcome')->with('usage', $usage)
            ->with('ip', $ip)
            ->with('months', $months)
            ->with('bioc_downloads', array_reverse($sorted_bioc_download_12))
            ->with('bioc_ip', array_reverse($sorted_bioc_ip_12))
            ->with('bioc_months', array_reverse($sorted_bioc_month_12))
            ->with('bioc_dnld_cnt', $count_bioc_downlds->downloads)
            ->with('bioc_ip_cnt', $count_bioc_ips->ip)
            ->with('web_dnld_cnt', $count_web_downlds->downloads)
            ->with('web_ip_cnt', $count_web_ips->ip);

    }

    public function cmpares($a, $b)
    {
        $aDate = DateTime::createFromFormat("M-Y", $a->Month);
        $bDate = DateTime::createFromFormat("M-Y", $b->Month);
        return $aDate->getTimestamp() - $bDate->getTimestamp();
    }

    public function instructions()
    {


        return view('Instructions');

    }
}
