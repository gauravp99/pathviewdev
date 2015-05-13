<?php namespace App\Http\Controllers;

use App;
use App\Http\Requests;
use App\User;
use Auth;
use DB;

class ProfileController extends Controller
{

    //

    public function user($username)
    {

        $user = User::where('id', '=', $username);
        if ($user->count()) {
            $user = $user->first();
            return view('profile.user')->with('user', $user);
        }
        return App::abort(404);

    }

    public function edit($username)
    {

        $user = User::where('id', '=', $username);
        if ($user->count()) {
            $user = $user->first();
            return view('profile.user_edit')->with('user', $user);
        }
        return App::abort(404);

    }

    public function edit_post()
    {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $id = Auth::user()->id;
        $date = new \DateTime;
        DB::table('users')
            ->where('id', $id)
            ->update(array('name' => $name, 'email' => $email, 'updated_at' => $date));
        return view('profile.user')->with('user', Auth::user());

    }

}
