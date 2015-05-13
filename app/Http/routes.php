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


    return view('about')->with('usage', $usage)
        ->with('ip', $ip)
        ->with('months', $months)
        ->with('bioc_downloads', $bioc_downloads)
        ->with('bioc_ip', $bioc_ip)
        ->with('bioc_months', $bioc_months);


});


Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
