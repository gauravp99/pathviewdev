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

/* start api for admin */
Route::group(array('prefix' => 'api'), function() {
    Route::get('yearly','api\GetAnal@getYears');
    Route::get('monthly','api\GetAnal@getMonths');
    Route::get('manual','api\GetAnal@getManual');
    Route::post('addUser','api\AngularAdminController@createUser');
    Route::get('broadCastMessage','admin\AdminController@emailAll');
    Route::resource('comments', 'CommentController',
    array('only' => array('index', 'store', 'destroy')));
});
Route::post('analysisDelete','pathview\analysis\AnalysisController@delete');

/*Route::post('/api/addUser','api\AngularAdminController@');*/

/* end api for admin */

/* admin */
Route::post('/adminLogin','admin\AdminController@auth');
Route::post('/adminEmailAll','admin\AdminController@emailAll');
Route::get('/admin','admin\AdminController@index');
Route::post('/adminBroadcastMessage','admin\AdminController@adminBroadcastMessage');


/* Ajax controller for species and pathway match retrievel */
Route::post('/ajax/specPathMatch','pathview\analysis\AjaxSpeciesPathwayMatch@index');
Route::post('/ajax/analysisStatus','pathview\analysis\AjaxAnalysisQueueStatusCheck@index');
Route::post('/ajax/waitingAnalysisStatus','pathview\analysis\AjaxAnalysisQueueStatusCheck@checkStatus');

/* URL route for Controller for welcome page */
Route::get('/', array(
    'as' => 'home',
    'uses' => 'pathview\WelcomeController@index')
);

/* send an email from user without accessing their email address from the application */
Route::get('/contact', array(
    'as' => 'contact',
        'uses' => function () {
            return view('MessageUs');
        }
));

/* URL route for Controller for user profile page */
Route::post('/postMessage','profile\ProfileController@post_message');


/* user details get page */
Route::get('/user/{username}', array(
    'as' => 'profile-user',
    'uses' => 'profile\ProfileController@user'
));

/* URL route for profile (edit page)  */
Route::post('edit_post', 'profile\ProfileController@edit_post');

/* URL route for PreviousAnalysis Controller for user analysis history profile page */
Route::get('/prev_anal/{username}', array(
    'as' => 'prev-prof-user',
    'uses' => 'profile\PreviousAnalysis@user'
));

/* URL route for Home Controller for user analysis history profile page */
Route::get('home', 'profile\HomeController@index');

/* URL route for Controller for Edit user page */
Route::get('/edit_user/{username}', array(
    'as' => 'profile-edit-user',
    'uses' => 'profile\ProfileController@edit'));

/* URL route for Password Reset Home  */
Route::get('passwordReset', array(
    'as' => 'passwordReset ',
    'uses' => function () {
        return view('auth.password_edit');
    }));
/* URL rout for user password reset from insdie the profile*/
Route::post('/reset','Auth\PasswordEditController@index');

/* URL route for Controller for Guest page */
Route::get('guest', 'profile\GuestController@index');

/* URL route for Guest Home  */
Route::get('guest-home', array(
    'as' => 'guest-home ',
    'uses' => function () {
        return view('profile.guest.guest-home');
    }));

/* URL route for Controller for Analysis page user page */
Route::get('analysis', 'pathview\analysis\AnalysisController@new_analysis');

/* URL route for Analysis Result Viewing page user page */
Route::get('viewer', array(
    'as' => 'viewer',
    'uses' => function () {
        return view('pathview_pages.analysis.viewer');
    }));

Route::get('resultview', array(
    'as' => 'resultview',
    'uses' => function () {
        return view('pathview_pages.analysis.ResultView');
    }));

/* URL route for Analysis Result history Viewing page user page */
Route::get('anal_hist', array(
    'as' => 'anal_hist',
    'uses' => function () {
        return view('profile.anal_hist');
    }));

/* URL route for Analysis (New Analysis)  */
Route::post('postAnalysis', 'pathview\analysis\AnalysisController@postAnalysis');

/* URL route for Analysis (example1)  */
Route::post('post_exampleAnalysis1', 'pathview\analysis\AnalysisController@post_exampleAnalysis1');

/* URL route for Analysis (example2)  */
Route::post('post_exampleAnalysis2', 'pathview\analysis\AnalysisController@post_exampleAnalysis2');

/* URL route for Analysis (example3)  */
Route::post('post_exampleAnalysis3', 'pathview\analysis\AnalysisController@post_exampleAnalysis3');

Route::get('example1', 'pathview\analysis\AnalysisController@example_one');

Route::get('example2', 'pathview\analysis\AnalysisController@example_two');

Route::get('example3', 'pathview\analysis\AnalysisController@example_three');





/*URL route for tutrial/Help page */
Route::get('tutorial', function () {
    return view("pathview_pages.tutorial");
});

/* URL Route for About page*/
Route::get('about', 'pathview\PathviewAboutController@index');


Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);


/*test page*/


Route::get('error', function () {
    return view("errors.customError");
});
Route::get('Spaceerror', function () {
    return view("errors.SpaceExceeded");
});