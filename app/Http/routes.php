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
    'uses' => 'WelcomeController@index')
);

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
Route::get('Spaceerror', function () {
    return view("errors.SpaceExceeded");
});

/*URL route for tutrial/Help page */
Route::get('tutorial', function () {
    return view("tutorial");
});

/* URL Route for About page*/
Route::get('about', 'PathviewAboutController@index');


Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);


//gage application

Route::get('gage', function () {

    return view("Gage.GageAnalysis");
});

Route::get('gageResult', function () {
    return view("Gage.GageResult");
});

Route::post('gageAnalysis', 'GageAnalysisController@newGageAnalysis');
Route::post('exampleGageAnalysis1', 'GageAnalysisController@ExampleGageAnalysis1');

Route::get('gageIndex','gageController@index');
Route::get('gageAbout','gageController@about');
Route::get('gageTutorial','gageController@Tutorial');
Route::get('gage_hist',function(){
    return view('Gage.GageHistoryResult');
});

Route::get('gage-home', 'HomeController@index');

Route::get('gage-guest-home', array(
    'as' => 'gage-guest-home ',
    'uses' => function () {
        return view('guest.guest-home');
    }));

Route::post('analysisDelete','AnalysisController@delete');

Route::get('gageExample1',function(){
   return view('Gage.analysis.gageExample1');
});
Route::get('pathviewViewer',function(){
    return view('Gage.GagePathviewGraphViewer');
});

Route::get('resultView',function(){
    return view('Gage.GageResultView');
});
Route::post('/ajax/GageanalysisStatus','AjaxGageAnalysisStatusCheck@index');
