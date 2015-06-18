<?php namespace App\Http\Controllers\Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Hash;
use Validator;
use Redirect;
use Illuminate\Http\Request;

class PasswordEditController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

        $user = Auth::user();
        if((Auth::user())) {
            if (Hash::check($_POST["oldpassword"], $user->password))
            {
                $rules = [
                    'email' => 'required',
                    'oldpassword' => 'required',
                    'password' => 'required|confirmed|min:6|max:20'
                ];
                $validator = Validator::make($_POST, $rules);
                if ($validator->fails())
                {
                    return redirect()->back()
                        ->withErrors($validator->messages());
                }
                else
                {

                        $user->password = bcrypt($_POST['password']);
                        $user->save();

                    return Redirect::route('home');
                }
                echo "Hello in save method" .print_r($user);
                return "hello";

            }
            return redirect()->back()
                ->withErrors('Entered Old Password is not correct');

        }

    }



}
