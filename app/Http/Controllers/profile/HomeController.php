<?php namespace App\Http\Controllers\profile;
/**
 * @Author: Yehsvant Bhavnasi, Dr. Weijun Luo
 * @Contact: byeshvant@hotmail.com
 */
use App\Analysis;
use Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
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


        return view('profile.home')->with('analyses',Analysis::where('id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(20));
    }
}
