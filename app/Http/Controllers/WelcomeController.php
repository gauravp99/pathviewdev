<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

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
        array_push($usage, 1);
        array_push($usage, 1);
        array_push($usage, 1);
        array_push($usage, 1);
        array_push($ip, 1);
        array_push($ip, 1);
        array_push($ip, 1);
        array_push($ip, 1);

        $val = DB::select(DB::raw('SELECT COUNT(1) as count,count(distinct ipadd) as ipadd_count, DATE_FORMAT(created_at, \'%b-%y\') as date FROM analyses where created_at >= CURDATE() - INTERVAL 6 MONTH GROUP BY YEAR(created_at), MONTH(created_at)'));
        foreach ($val as $month) {
            array_push($usage, $month->count);
            array_push($ip, $month->ipadd_count);
            array_push($months, $month->date);
        }
        if (sizeof($months) <= 6)
            $months = array("Dec-14", "Jan-15", "Feb-15", "Mar-15", "April-15", "May-15");

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


        return view('welcome')->with('usage', $usage)
            ->with('ip', $ip)
            ->with('months', $months)
            ->with('bioc_downloads', $bioc_downloads)
            ->with('bioc_ip', $bioc_ip)
            ->with('bioc_months', $bioc_months);

    }


    public function instructions()
    {


        return view('Instructions');

    }
}
