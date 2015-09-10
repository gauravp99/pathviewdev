<?php namespace App\Http\Controllers;

/**
 * @Author: Yehsvant Bhavnasi, Dr. Weijun Luo
 * @Contact: byeshvant@hotmail.com
 */
use DateTime;
use Illuminate\Support\Facades\DB;
use stdClass;
use Cache;
class WelcomeController extends Controller
{


    public function __construct()
    {

        $this->middleware('guest');
    }

    /**
     * This function is called whenever a {{url(/)}} is requested this function is called from routes.php file
     * @return Response
     */
    public function index()
    {

        /**
         * To get the statistics of usage from bioc and web usage from the
         * database and send it to the javascript library
         */
        $usage = array();
        $ip = array();
        $months = array();

        /**
         * WEb usage statistic gets the last 6 months details of the usage such as @ip(ipadderess distinct)
         * @Analyses(Number of analyses generated)
         */
        $val = Cache::remember('analysis_details', 10, function()
        {
            return DB::select(DB::raw('SELECT COUNT(1) as count,count(distinct ipadd) as ipadd_count, DATE_FORMAT(created_at, \'%b-%y\') as date FROM analyses where analysis_origin = \'pathview\' and created_at >= CURDATE() - INTERVAL 6 MONTH GROUP BY YEAR(created_at), MONTH(created_at)'));
        });
        if (Cache::has('analysis_details'))
        {
            $val = Cache::get('analysis_details');
        }
        foreach ($val as $month) {
            array_push($usage, $month->count);
            array_push($ip, $month->ipadd_count);
            array_push($months, $month->date);
        }

        /**
         * Pathway Package downloads. Script file biocstatistics.sh is used to get the details
         * of current month from bio website. This script is a cron job running each week on saturday 5:00 AM EST
         */
        $bioc_val = Cache::remember('bioc_val', 10, function()
        {
            return DB::select(DB::raw('select concat(concat(month,\'-\'),year%100) as date,numberof_uniqueip as ipadd,numberof_downloads as downloads from biocstatistics'));
        });
        if (Cache::has('bioc_val'))
        {
            $bioc_val = Cache::get('bioc_val');
        }
       // $bioc_val = DB::select(DB::raw('select concat(concat(month,\'-\'),year%100) as date,numberof_uniqueip as ipadd,numberof_downloads as downloads from biocstatistics'));
        $bioc_downloads = array();
        $bioc_ip = array();
        $bioc_months = array();

        foreach ($bioc_val as $month) {
            array_push($bioc_downloads, $month->downloads);
            array_push($bioc_ip, $month->ipadd);
            array_push($bioc_months, $month->date);
        }

        /**
         * Pathway Package downloads and web usage counts you can see that we are adding 15000 and 7500 to the sql query's since
         * we didnt had any statistics count of the initial 1 year we manually added approximation value
         */
        $count_bioc_downlds = Cache::remember('count_bioc_downlds', 10, function()
        {
            return DB::select(DB::raw('select sum(numberof_downloads)+15000 as "downloads" from biocstatistics'));
        });
        if (Cache::has('count_bioc_downlds'))
        {
            $count_bioc_downlds = Cache::get('count_bioc_downlds');
        }
        $count_bioc_ips = Cache::remember('count_bioc_ips', 10, function()
        {
            return DB::select(DB::raw('select sum(numberof_uniqueip)+7500 as "ip" from biocstatistics'));
        });

        if (Cache::has('count_bioc_ips'))
        {
            $count_bioc_ips = Cache::get('count_bioc_ips');
        }

        $count_web_downlds = Cache::remember('count_web_downlds', 10, function()
        {
            return DB::select(DB::raw('select count(*) as "downloads" from analyses where analysis_origin = \'pathview\' '));
        });
        if (Cache::has('count_web_downlds'))
        {
            $count_web_downlds = Cache::get('count_bioc_downlds');
        }
        $count_web_ips = Cache::remember('count_web_ips', 10, function()
        {
            return DB::select(DB::raw('select count(distinct ipadd) as "ip" from analyses where analysis_origin = \'pathview\' '));
        });
        if (Cache::has('count_web_ips'))
        {
            $count_web_ips = Cache::get('count_web_ips');
        }

        /**
         * To make sure that the data is not empty from the database
         */
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


        /**
         * Start Sorting the date according the dates order
         */
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
        /**
         * End Sorting the date according the dates order
         */

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


    /**
     * @return \Illuminate\View\View
     * used for tutorial page
     */
    public function instructions()
    {
        return view('Instructions');

    }
}
