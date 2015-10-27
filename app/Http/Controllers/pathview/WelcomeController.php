<?php namespace App\Http\Controllers\pathview;

/**
 * @Author: Yehsvant Bhavnasi, Dr. Weijun Luo
 * @Contact: byeshvant@hotmail.com
 */
use DateTime;
use Illuminate\Support\Facades\DB;
use stdClass;
use Cache;
use App\Http\Models\DbDataFetch;
use App\Http\Models\Usage;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
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


        $data_comm = new DbDataFetch;

        //get the analysis data of past 6 months data from analysis tables
        $web_usage = $data_comm->getAnalysisDetails();

        //get bioc analysis data of past 6 months from bioc table filled by the batch job
        $lib_usage = $data_comm->getBiocStatisTics();

        $web_date = array();
        $web_count = array();
        $web_ip = array();
        foreach($web_usage as $web)
        {
            array_push($web_date,date("M, Y",strtotime($web->getDate())));
            array_push($web_count,$web->getUsage());
            array_push($web_ip,$web->getIp());
        }

        $lib_date = array();
        $lib_count = array();
        $lib_ip = array();
        foreach($lib_usage as $lib)
        {
            array_push($lib_date,date("M, Y",strtotime($lib->getDate())));
            array_push($lib_count,$lib->getUsage());
            array_push($lib_ip,$lib->getIp());
        }


        //get the count of bioc values
        $biocUsage = $data_comm->getTotalBiocAnalysisDetails();
        //get the count details from analysis table
        $analysisUsage = $data_comm->getTotalAnalysisDetails();
        $bioc_count = 0;
        if(!is_null($biocUsage->getUsage())){
            $bioc_count = $biocUsage->getUsage();
        }
         $bioc_ips = 0;
        if(!is_null($biocUsage->getIp())){
            $bioc_ips = $biocUsage->getIp();
        }
         $anal_count = 0;
        if(!is_null($analysisUsage->getUsage())){
            $anal_count = $analysisUsage->getUsage();
        }
         $anal_ips = 0;
        if(!is_null($analysisUsage->getIp())){
            $anal_ips = $analysisUsage->getIp();
        }


        return view('pathview_pages.welcome')->with('usage', $web_count)
            ->with('ip', $web_ip)
            ->with('months', $web_date)
            ->with('bioc_downloads',$lib_count)
            ->with('bioc_ip',$lib_ip)
            ->with('bioc_months',$lib_date)
            ->with('bioc_dnld_cnt',$bioc_count )
            ->with('bioc_ip_cnt', $bioc_ips)
            ->with('web_dnld_cnt', $anal_count)
            ->with('web_ip_cnt', $anal_ips);

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
