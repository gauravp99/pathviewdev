<?php namespace App\Http\Controllers\gage;

use App\Http\Requests;
use DateTime;
use Illuminate\Support\Facades\DB;
use stdClass;
use App\Http\Models\DbDataFetch;
use App\Http\Models\Usage;
use Auth;
use App\Http\Controllers\Controller;
/**
 * Class gageController
 * @package App\Http\Controllers
 *
 *
 * This controller is used for the details pages such as index page, about page and tutorial page
 */
class gageController extends Controller {

	/**
	 * Display a listing of the resource.
	 *  For about page Graph sorting the months and pass the data and show it on graph
	 * @return Response
	 */
	public function about()
        {

	
	$data_comm = new DbDataFetch;

        //get the analysis data of past 6 months data from analysis tables
        $web_usage = $data_comm->getAnalysisDetails("gage");

        //get bioc analysis data of past 6 months from bioc table filled by the batch job
        $lib_usage = $data_comm->getBiocStatisTics("gage");

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
        $biocUsage = $data_comm->getTotalBiocAnalysisDetails("gage");
        //get the count details from analysis table
        $analysisUsage = $data_comm->getTotalAnalysisDetails("gage");
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


       
 return view('gage_pages.GageAbout')->with('usage', $web_count)
            ->with('ip', $web_ip)
            ->with('months', $web_date)
            ->with('bioc_downloads',$lib_count)
            ->with('bioc_ip',$lib_ip)
            ->with('bioc_months',$lib_date)
            ->with('bioc_dnld_cnt',$bioc_count)
            ->with('bioc_ip_cnt', $bioc_ips)
            ->with('web_dnld_cnt', $anal_count)
            ->with('web_ip_cnt', $anal_ips);

	}
    public function index()
    {
        /**
         * To get the statistics of usage from bioc and web usage from the
         * database and send it to the javascript library
         */
        if (Auth::user()) {
            return view("gage_pages.GageAnalysis");
        } else {
$data_comm = new DbDataFetch;

        //get the analysis data of past 6 months data from analysis tables
        $web_usage = $data_comm->getAnalysisDetails("gage");

        //get bioc analysis data of past 6 months from bioc table filled by the batch job
        $lib_usage = $data_comm->getBiocStatisTics("gage");

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
        $biocUsage = $data_comm->getTotalBiocAnalysisDetails("gage");
        //get the count details from analysis table
        $analysisUsage = $data_comm->getTotalAnalysisDetails("gage");
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



 return view('gage_pages.GageWelcome')->with('usage', $web_count)
            ->with('ip', $web_ip)
            ->with('months', $web_date)
            ->with('bioc_downloads',$lib_count)
            ->with('bioc_ip',$lib_ip)
            ->with('bioc_months',$lib_date)
            ->with('bioc_dnld_cnt',$bioc_count)
            ->with('bioc_ip_cnt', $bioc_ips)
            ->with('web_dnld_cnt', $anal_count)
            ->with('web_ip_cnt', $anal_ips);



        }
    }

    public function tutorial()
    {

        return view('gage_pages.GageTutorial');
    }



}
