<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Analysis {

	//

    public $date;
    public $year;
    public $month;
    public $GageAnalysisCount;
    public $pathwayAnalysisCount;
    public $usersCount;
    public $pathwayDistribution;
    public $GageDistribution;


    function __get($name)
    {
        if(strcmp($name,"year")==0)
        {
            return  $this->year;

        }
        else if(strcmp($name,"month")==0)
        {
            return  $this->month;
        }
        else if(strcmp($name,"GageAnalysisCount")==0)
        {
            return  $this->GageAnalysisCount;
        }
        else if(strcmp($name,"pathwayAnalysisCount")==0)
        {
            return  $this->pathwayAnalysisCount;
        }
        else if(strcmp($name,"usersCount")==0)
        {
            return  $this->usersCount;
        }
        else if(strcmp($name,"pathwayDistribution")==0)
        {
            return  $this->pathwayDistribution;
        }
        else if(strcmp($name,"GageDistribution")==0)
        {
            return  $this->GageDistribution;
        }

    }




    function generateData($month,$year)
    {
        $date=$year."-".$month."-01";
        $date_end=$year."-".$month."-31";
        $this->date = $date;
        $this->year = $year;
        $this->month = $month;
        $gageCount = DB::select(DB::raw("select count(*) as \"count\" from analyses where analysis_origin = 'gage' and created_at > '".$date."' and created_at < '".$date_end."'" ));
        $this->GageAnalysisCount = $gageCount[0]->count;;
        $pathviewCount = DB::select(DB::raw("select count(*) as \"count\" from analyses where analysis_origin = 'pathview' and  created_at > '".$date."' and created_at < '".$date_end."'" ));
        $this->pathwayAnalysisCount = $pathviewCount[0]->count;
        $usersCoutn = DB::select(DB::raw("select count(*) as \"count\" from users where created_at > '".$date."' and created_at < '".$date_end."'" ));
        $this->usersCount =$usersCoutn[0]->count;
        $distinctPathwayTypes =  DB::select(DB::raw("select distinct(analysis_type) from analyses where analysis_origin = 'pathview' "));
        $disitnctGageType =DB::select(DB::raw("select distinct(analysis_type) from analyses where analysis_origin = 'gage' "));

        $this->pathwayDistribution = array();
        foreach($distinctPathwayTypes as $pathwayType)
        {
            $count = DB::select(DB::raw("select count(*) as \"count\" from analyses where analysis_origin = 'pathview' and analysis_type = \"".$pathwayType->analysis_type."\" and   created_at > '".$date."' and created_at < '".$date_end."'" ));
            $this->pathwayDistribution[$pathwayType->analysis_type] = $count[0]->count;
        }
        $this->GageDistribution=array();
        foreach($disitnctGageType as $gageType)
        {
            $count = DB::select(DB::raw("select count(*) as \"count\" from analyses where analysis_origin = 'gage' and analysis_type = \"".$gageType->analysis_type."\" and   created_at > '".$date."' and created_at < '".$date_end."'" ));
            $this->GageDistribution[$gageType->analysis_type] = $count[0]->count;
        }

    }


}
