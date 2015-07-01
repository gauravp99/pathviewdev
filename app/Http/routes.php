<?php

/*
|--------------------------------------------------------------------------
| Pathway Application Routes
|--------------------------------------------------------------------------
| @Author: Yeshvant Bhavnasi, Dr Weijun Luo
| Pathway web is web interface for the pathway R library project
| Routes.php Here is where you can register all of the routes for an application.
| It's a breeze.
|
*/

/* Ajax controller for species and pathway match retrievel */
Route::post('/ajax/specPathMatch','AjaxSpeciesPathwayMatch@index');
Route::post('/ajax/analysisStatus','AjaxAnalysisQueueStatusCheck@index');

/* URL route for Controller for welcome page */
Route::get('/', array(
    'as' => 'home',
    'uses' => 'WelcomeController@index'));

/* URL route for Controller for user profile page */
Route::get('/user/{username}', array(
    'as' => 'profile-user',
    'uses' => 'ProfileController@user'
));

/* URL route for profile (edit page)  */
Route::post('edit_post', 'ProfileController@edit_post');

/* URL route for PreviousAnalysis Controller for user analysis history profile page */
Route::get('/prev_anal/{username}', array(
    'as' => 'prev-prof-user',
    'uses' => 'PreviousAnalysis@user'
));

/* URL route for Home Controller for user analysis history profile page */
Route::get('home', 'HomeController@index');

/* URL route for Controller for Edit user page */
Route::get('/edit_user/{username}', array(
    'as' => 'profile-edit-user',
    'uses' => 'ProfileController@edit'));

/* URL route for Password Reset Home  */
Route::get('passwordReset', array(
    'as' => 'passwordReset ',
    'uses' => function () {
        return view('auth.password_edit');
    }));
/* URL rout for user password reset from insdie the profile*/
Route::post('/reset','Auth\PasswordEditController@index');

/* URL route for Controller for Guest page */
Route::get('guest', 'GuestController@index');

/* URL route for Guest Home  */
Route::get('guest-home', array(
    'as' => 'guest-home ',
    'uses' => function () {
        return view('guest.guest-home');
    }));

/* URL route for Controller for Analysis page user page */
Route::get('analysis', 'AnalysisController@new_analysis');

/* URL route for Analysis Result Viewing page user page */
Route::get('viewer', array(
    'as' => 'viewer',
    'uses' => function () {
        return view('analysis.viewer');
    }));

Route::get('resultview', array(
    'as' => 'resultview',
    'uses' => function () {
        return view('analysis.ResultView');
    }));

/* URL route for Analysis Result history Viewing page user page */
Route::get('anal_hist', array(
    'as' => 'anal_hist',
    'uses' => function () {
        return view('profile.anal_hist');
    }));

/* URL route for Analysis (New Analysis)  */
Route::post('postAnalysis', 'AnalysisController@postAnalysis');

/* URL route for Analysis (example1)  */
Route::post('post_exampleAnalysis1', 'AnalysisController@post_exampleAnalysis1');

/* URL route for Analysis (example2)  */
Route::post('post_exampleAnalysis2', 'AnalysisController@post_exampleAnalysis2');

/* URL route for Analysis (example3)  */
Route::post('post_exampleAnalysis3', 'AnalysisController@post_exampleAnalysis3');

Route::get('example1', 'AnalysisController@example_one');

Route::get('example2', 'AnalysisController@example_two');

Route::get('example3', 'AnalysisController@example_three');


Route::get('test', function () {
    return view("rserve.index");
});

Route::get('error', function () {
    return view("errors.customError");
});

/*URL route for tutrial/Help page */
Route::get('tutorial', function () {
    return view("tutorial");
});

/* URL Route for About page*/
Route::get('about', function () {

    $usage = array();
    $ip = array();
    $months = array();

    $val = DB::select(DB::raw('SELECT COUNT(1) as count,count(distinct ipadd) as ipadd_count, DATE_FORMAT(created_at, \'%b-%y\') as date FROM analyses where created_at >= CURDATE() - INTERVAL 6 MONTH GROUP BY YEAR(created_at), MONTH(created_at)'));
    foreach ($val as $month) {
        array_push($usage, $month->count);
        array_push($ip, $month->ipadd_count);
        array_push($months, $month->date);
    }

    /**
     * get the all details from database for biocstatistics table which is filled using a script when is run each week on saturday 5:00 AM EST
     */

    $bioc_val = DB::select(DB::raw('select concat(concat(month,\'-\'),year%100) as date,numberof_uniqueip as ipadd,numberof_downloads as downloads from biocstatistics'));

    $bioc_downloads = array();

    $bioc_ip = array();

    $bioc_months = array();

    foreach ($bioc_val as $month) {
        array_push($bioc_downloads, $month->downloads);
        array_push($bioc_ip, $month->ipadd);
        array_push($bioc_months, $month->date);
    }

    /**
     * sql query to get the counts of package downloads and web usage from the database
     */
    $count_bioc_downlds = DB::select(DB::raw('select sum(numberof_downloads)+15000 as "downloads" from biocstatistics'));
    $count_bioc_ips = DB::select(DB::raw('select sum(numberof_uniqueip)+7500 as "ip" from biocstatistics'));
    $count_web_downlds = DB::select(DB::raw('select count(*) as "downloads" from analyses'));
    $count_web_ips = DB::select(DB::raw('select count(distinct ipadd) as "ip" from analyses'));

    /**
     * To make sure that you are having data from sql query and they are not null
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
    /* End To make sure that you are having data from sql query and they are not null */

    /*Sorting the dates according to the order of dates not efficient code need to change */
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
    /*End Sorting the dates according to the order of dates not efficient code need to change */

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
