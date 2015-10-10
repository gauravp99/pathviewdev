<?php namespace App\Http\Controllers\profile;

/**
 * @Author: Yehsvant Bhavnasi, Dr. Weijun Luo
 * @Contact: byeshvant@hotmail.com
 * Controller for list use profile previous analysis
 */
use App\Http\Requests;
use App\User;
use Auth;
use App;
use App\Analysis;
use App\Http\Controllers\Controller;
class PreviousAnalysis extends Controller
{

    public function user($username)
    {
        $user = User::where('id', '=', $username);
        if(Auth::user()->id==$username) {
            if ($user->count()) {
                $user = $user->first();
                return view('profile.home')->with('user', $user)->with('analyses',Analysis::where('id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(20));
            }
        }
        return App::abort(404);

    }

}
