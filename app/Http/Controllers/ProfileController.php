<?php namespace App\Http\Controllers;

/**
 * @Author: Yehsvant Bhavnasi, Dr. Weijun Luo
 * @Contact: byeshvant@hotmail.com
 * Controller for user profile like listing the use analysis history and editing user and profile viewing etc. in future there is lot of scope of adding other
 * functionlity to this page
 */
use App;
use App\Http\Requests;
use App\User;
use Auth;
use DB;

class ProfileController extends Controller
{

    /**
     * @param $username
     * @return $this
     * method to list user Profile page
     */
    public function user($username)
    {

        $user = User::where('id', '=', $username);
        if ($user->count()) {
            $user = $user->first();
            return view('profile.user')->with('user', $user);
        }
        return App::abort(404);

    }

    /**
     * @param $username
     * @return $this
     * method to list the edit user
     */
    public function edit($username)
    {

        $user = User::where('id', '=', $username);
        if(Auth::user()->id==$username) {
            if ($user->count()) {
                $user = $user->first();
                return view('profile.user_edit')->with('user', $user);
            }

        }

        return App::abort(403);


    }

    /**
     * @return $this
     * ?Method to edit the profile of the user
     */
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
