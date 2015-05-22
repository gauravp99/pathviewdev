<?php namespace App\Http\Controllers;

/**
 * @Author: Yehsvant Bhavnasi, Dr. Weijun Luo
 * @Contact: byeshvant@hotmail.com
 * Controller for list use profile previous analysis
 */
use App\Http\Requests;
use App\User;

class PreviousAnalysis extends Controller
{

    public function user($username)
    {

        $user = User::where('id', '=', $username);
        if ($user->count()) {
            $user = $user->first();
            return view('profile.user_anal')->with('user', $user);
        }
        return App::abort(404);

    }

}
