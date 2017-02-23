<?php namespace App\Http\Controllers\profile;
/**
 * @Author: Yehsvant Bhavnasi, Dr. Weijun Luo
 * @Contact: byeshvant@hotmail.com
 */
use App\Analysis;
use App\SharedAnalysis;
use Auth;
use DB;
use Input;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
class HomeController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */


    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {

        return view('profile.home')->with('analyses',Analysis::where('id', Auth::user()->id)->where('isDeleted','N')->orderBy('created_at', 'desc')->paginate(20))->with('shared_analyses', SharedAnalysis::where('shared_user', Auth::user()->id)->where('isDeleted','N')->orderBy('created_at', 'desc')->paginate(20));
    }


    ## This method controls the access of the result page of the analysis 
    ## by the registered user.
    public function analysis_history()
    {
	  if (Input::has('shared_analysis'))
	  {
	    return view('profile.anal_hist');
	  }
	  else
	  {
	     $analysis=Input::get('analyses');
             $analyses = DB::table('analysis')->where(['id' => Auth::user()->id,'analysis_id'=>$analysis])->get();
             if(sizeof($analyses) > 0)
	     {
	        return view('profile.anal_hist');
	     }
	     else
	     {
	       ## If the user other then the owner tries to access the analysis using the URL, redirect to 
	       ## error page.
	       return Redirect::to('404');
	     }

          }
    }
}
