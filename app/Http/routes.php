<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', array(
    'as' => 'home',
    'uses' => 'WelcomeController@index'));
/*Route::get('/',array('as'=>'home','uses' => function(){
    return view('guest-home');
}));*/


Route::get('/user/{username}', array(
    'as' => 'profile-user',
    'uses' => 'ProfileController@user'
));

Route::get('/prev_anal/{username}', array(
    'as' => 'prev-prof-user',
    'uses' => 'PreviousAnalysis@user'
));

Route::get('home', 'HomeController@index');
Route::get('guest', 'GuestController@index');
Route::get('/edit_user/{username}', array(
    'as' => 'profile-edit-user',
    'uses' => 'ProfileController@edit'));
Route::get('analysis', 'AnalysisController@new_analysis');
Route::get('viewer', array(
    'as' => 'viewer',
    'users' => function () {
        return view('analysis.viewer');
    }));
Route::get('anal_hist', array(
    'as' => 'anal_hist',
    'users' => function () {
        return view('profile.anal_hist');
    }));
Route::get('guest-home', array(
    'as' => 'guest-home ',
    'users' => function () {
        return view('guest-home');
    }));
Route::post('postAnalysis', 'AnalysisController@postAnalysis');
Route::post('post_exampleAnalysis1', 'AnalysisController@post_exampleAnalysis1');
Route::post('post_exampleAnalysis2', 'AnalysisController@post_exampleAnalysis2');
Route::post('post_exampleAnalysis3', 'AnalysisController@post_exampleAnalysis3');
Route::post('edit_post', 'ProfileController@edit_post');
Route::get('example1', 'AnalysisController@example_one');
Route::get('example2', 'AnalysisController@example_two');
Route::get('example3', 'AnalysisController@example_three');
Route::get('tutorial', function () {
    return view("tutorial");
});
Route::get('about', function () {
    //$m = array("Jan","Feb","Mar","Apr","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
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
    function cmpares($a, $b)
    {
        $aDate = DateTime::createFromFormat("M-Y", $a->Month);
        $bDate = DateTime::createFromFormat("M-Y", $b->Month);
        return $aDate->getTimestamp() - $bDate->getTimestamp();
    }

    usort($array, "cmpares");
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



    return view('about')
        ->with('usage', $usage)
        ->with('ip', $ip)
        ->with('months', $months)
        ->with('bioc_downloads', array_reverse($sorted_bioc_download_12))
        ->with('bioc_ip', array_reverse($sorted_bioc_ip_12))
        ->with('bioc_months', array_reverse($sorted_bioc_month_12))
        ->with('bioc_dnld_cnt', $count_bioc_downlds->downloads)
        ->with('bioc_ip_cnt', $count_bioc_ips->ip)
        ->with('web_dnld_cnt', $count_web_downlds->downloads)
        ->with('web_ip_cnt', $count_web_ips->ip);
});


Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
