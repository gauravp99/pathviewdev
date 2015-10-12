<?php
/**
 * Created by PhpStorm.
 * User: ybhavnasi
 * Date: 9/24/15
 * Time: 4:13 PM
 */

namespace App\Http\Models;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Usage;
use Illuminate\Http\Request;
class DbDataFetch
{

    public $database;

    //get details from analysis table filled in application by users analysis
    public function getAnalysisDetails()
    {
        $analysis_vals =  DB::select(DB::raw('SELECT COUNT(1) as count,count(distinct ip_add) as ip_add_count, created_at as date FROM analysis where analysis_origin = \'pathview\'  GROUP BY YEAR(created_at), MONTH(created_at)'));
        $web_usage = array();

        //fill the array lists with usage objects

        foreach ($analysis_vals as $analysis_val )
            array_push($web_usage,new Usage(date('Y-m-d',strtotime($analysis_val->date)),$analysis_val->ip_add_count,$analysis_val->count));


        usort($web_usage , function($a,$b){
            return $a->getDate() > $b->getDate();
        });

        if(sizeof($web_usage) > 6)
        {
            return array_slice($web_usage, -6, 6, true);
        }
        else
        {
            return $web_usage;
        }

    }

    public function getTotalAnalysisDetails()
    {
        //retrieve usage from BIOC tables
        $anal_sum_dwnlds =  DB::select(DB::raw('select count(*) as "downloads" from analysis where analysis_origin = \'pathview\' '));
        $anal_ip_dwnlds =  DB::select(DB::raw('select count(distinct ip_add) as "ip" from analysis where analysis_origin = \'pathview\' '));


        if(!is_null($anal_sum_dwnlds)&&!is_null($anal_ip_dwnlds))
        {
            $biocUsage = new Usage(null,$anal_ip_dwnlds[0]->ip,$anal_sum_dwnlds[0]->downloads);
            return $biocUsage;
        }
        else{
            return null;
        }

    }

    public function getTotalBiocAnalysisDetails()
    {
        //retrieve usage from BIOC tables
        $bioc_sum_dwnlds =  DB::select(DB::raw('select sum(number_of_downloads)+15000 as "downloads" from biocStatistics'));
        $bioc_ip_dwnlds =  DB::select(DB::raw('select sum(number_of_unique_ip)+7500 as "ips" from biocStatistics'));
            if(!is_null($bioc_ip_dwnlds)&&!is_null($bioc_sum_dwnlds))
            {
                $biocUsage = new Usage(null,$bioc_ip_dwnlds[0]->ips,$bioc_sum_dwnlds[0]->downloads);
                return $biocUsage;
            }
            else{
                return null;
            }
    }


    //get details from Biocstatistcis table filled by script
    public function getBiocStatisTics()
    {
        $bioc_vals =  DB::select(DB::raw('select concat(concat(month,\'-\'),year) as date,number_of_unique_ip as ip_add,number_of_downloads as downloads from biocStatistics'));
        $lib_usage = array();
        //fill the array lists with usage objects
        foreach ($bioc_vals as $bioc_val )
            array_push($lib_usage,new Usage(date('Y-m-d',strtotime($bioc_val->date)),$bioc_val->ip_add,$bioc_val->downloads));
        usort($lib_usage , function($a,$b){
            return $a->getDate() > $b->getDate();
        });

        //return last 6 records

        if(sizeof($lib_usage) > 6)
        return array_slice($lib_usage, -6, 6, true);
        else
            return $lib_usage;
    }


    function __toString()
    {
      return "DB";
    }


}