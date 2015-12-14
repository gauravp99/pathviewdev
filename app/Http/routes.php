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

//admin module of the project is developed in angularjs because LARAVEL comes with its own authenticate API
// we cant use this in angularjs so we have to create our own authentication api to have the AUTH working
        Route::group(array('prefix' => 'api'), function() {

            //authentication checking controller for each request a check is done if current user is authenticated or not if not authenticated login page is used
                Route::resource('authenticate', 'admin\AuthenticateController', ['only' => ['index']]);
                Route::post('authenticate', 'admin\AuthenticateController@authenticate');
            //to get all users information in json format to render on the pages
                Route::get('getAllUsers','admin\AdminController@getAllUsers');
            //get the analysis details in year,months and manually
                Route::get('yearly','api\GetAnal@getYears');
                Route::get('monthly','api\GetAnal@getMonths');
                Route::get('manual','api\GetAnal@getManual');
            //controller handling the creatiopn of the user
                Route::post('addUser','api\AngularAdminController@createUser');
            //controller handling emailing to all users of the application
                Route::get('broadCastMessage','admin\AdminController@emailAll');
            //controller handling sending messages to all users
                Route::post('sendMessage','admin\AdminController@ajaxAdminBroadCastMessage');
            //getting the resources for frequently asked questions
                Route::resource('comments', 'CommentController',
                    array('only' => array('index', 'store', 'destroy')));

        });


        Route::post('/adminLogin','admin\AdminController@auth');
        Route::post('/adminEmailAll','admin\AdminController@emailAll');
        Route::get('/admin','admin\AdminController@index');
        Route::post('/adminBroadcastMessage','admin\AdminController@adminBroadcastMessage');

/* end api for admin */







/* Ajax controller for species and pathway match retrievel */
        Route::post('/ajax/specPathMatch','pathview\analysis\AjaxSpeciesPathwayMatch@index');

/* Ajax controller for species and pathway match retrievel */
        Route::post('/ajax/specGeneIdMatch','pathview\analysis\AjaxSpeciesPathwayMatch@speciesGeneIdMatch');

/* Ajax Queue status checking */
        Route::post('/ajax/analysisStatus','pathview\analysis\AjaxAnalysisQueueStatusCheck@index');
/* Ajax checking the analysis queue waiting status */
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

        Route::get('faq',array(
            'uses' => function(){
                return view('profile.faq');
            }
        ));

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
/* URL route for analysis result view page*/
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
/* URL route for POST Analysis Delete from home page */
        Route::post('analysisDelete','pathview\analysis\AnalysisController@delete');

/* URL route for POST Analysis (New Analysis)  */
        Route::post('postAnalysis', 'PathviewAnalysisController@postAnalysis');

/* URL route for POST Analysis (example1)  */
        Route::post('post_exampleAnalysis1', 'PathviewAnalysisController@post_exampleAnalysis1');

/* URL route for POST Analysis (example2)  */
        Route::post('post_exampleAnalysis2','PathviewAnalysisController@post_exampleAnalysis2');

/* URL route for POST Analysis (example3)  */
        Route::post('post_exampleAnalysis3', 'PathviewAnalysisController@post_exampleAnalysis3');

/* URL route for GET Analysis (example1)  */
        Route::get('example1', 'PathviewAnalysisController@example_one');

/* URL route for GET Analysis (example2)  */
        Route::get('example2', 'PathviewAnalysisController@example_two');

/* URL route for GET Analysis (example3)  */
        Route::get('example3', 'PathviewAnalysisController@example_three');


/*URL route for tutrial/Help page */
        Route::get('tutorial', function () {
            return view("pathview_pages.tutorial");
        });

/* URL Route for About page*/
        Route::get('about', 'pathview\PathviewAboutController@index');


/* URL Route for Authentication*/
        Route::controllers([
            'auth' => 'Auth\AuthController',
            'password' => 'Auth\PasswordController',
        ]);

/* URL Route for error Page*/
        Route::get('error', function () {
            return view("errors.customError");
        });
/* URL Route for Space error*/
        Route::get('Spaceerror', function () {
            return view("errors.SpaceExceeded");
        });



/**************************gage application *********************************/

/*URL route for GAGE analysis  page */
        Route::get('gage', function () {

            return view("gage_pages.GageAnalysis");
        });
/*URL route for gage result page*/
        Route::get('gageResult', function () {
            return view("gage_pages.GageResult");
        });

/*URL route for POST new gage analysis page*/
        Route::post('gageAnalysis', 'gage\GageAnalysisController@newGageAnalysis');

/*URL route for POST gage analysis example 1 page*/
        Route::post('exampleGageAnalysis1', 'gage\GageAnalysisController@ExampleGageAnalysis1');

/*URL route for POST gage analysis example 2 page*/
        Route::post('exampleGageAnalysis2', 'gage\GageAnalysisController@ExampleGageAnalysis2');

/*URL route for get Discrete Gage Analysis  page*/
        Route::get('discreteGage',function(){
            return view('gage_pages.analysis.discreteAnalysis');
        });

/*URL route for get Gage Pathview combination Analysis  page*/
        Route::get('gagePathview',function(){
            return view('gage_pages.analysis.gagePathview');
        });

/*URL route for POST Discrete Gage Analysis  page*/
        Route::post('discreteGageAnalysis','gage\GageAnalysisController@discreteGageAnalysis');

/*URL route for POST Pathview Gage Analysis  page*/
        Route::post('gagePathviewAnalysis','gage\GageAnalysisController@GagePathviewAnalysis');

/*URL route for get Gage welcome page*/
        Route::get('gageIndex','gage\gageController@index');

/*URL route for get Gage About page*/
        Route::get('gageAbout','gage\gageController@about');

/*URL route for get Gage tutorial page*/
        Route::get('gageTutorial','gage\gageController@Tutorial');

/*URL route for get Gage history analysis page*/
        Route::get('gage_hist',function(){
            return view('gage_pages.GageHistoryResult');
        });
/*URL route for get Gage registered user profile  page*/
        Route::get('gage-home', 'profile\HomeController@index');

/*URL route for get Gage guest user profile  page*/
        Route::get('gage-guest-home', array(
            'as' => 'gage-guest-home ',
            'uses' => function () {
                return view('profile.guest.guest-home');
            }));

/*URL route for get gage analysis example 1 page*/
        Route::get('gageExample1',function(){
            return view('gage_pages.analysis.gageExample1');
        });

/*URL route for get gage analysis example 2 page*/
        Route::get('gageExample2',function(){
            return view('gage_pages.analysis.gageExample2');
        });

/*URL route for get gage pathview analysis example page*/
        Route::get('pathviewViewer',function(){
            return view('gage_pages.GagePathviewGraphViewer');
        });

/*URL route for get gage results page*/
        Route::get('resultView',function(){
            return view('gage_pages.GageResultView');
        });

/*URL route for get gage file lists page*/
        Route::get('fullList',function(){
            return view('gage_pages.GageFileList');
        });

/*URL route for get gage ajax status check page*/
        Route::post('/ajax/GageanalysisStatus','gage.AjaxGageAnalysisStatusCheck@index');

/**************************gage application *********************************/