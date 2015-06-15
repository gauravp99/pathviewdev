<?php namespace App\Http\Controllers;

/**
 * @Author: Yehsvant Bhavnasi, Dr. Weijun Luo
 * @Contact: byeshvant@hotmail.com
 * Controller for list use profile previous analysis
 */
use App\Http\Requests;
use App\User;
use Auth;
use App;

class PreviousAnalysis extends Controller
{

    public function user($username)
    {


        $user = User::where('id', '=', $username);
        if(Auth::user()->id==$username) {
            if ($user->count()) {
                $user = $user->first();
                return view('home')->with('user', $user);
            }
        }
        return App::abort(404);

    }

}
