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

    // this function is called whenever a password reset is userd
	public function index()
	{

        //get the current user details
        $user = Auth::user();

        //check if the user is authorised or not
        if((Auth::user())) {

            //check if the old password mentioned is correct or not
            if (Hash::check($_POST["oldpassword"], $user->password))
            {
                //create a laravel validator and check for the rules give below
                $rules = [
                    'email' => 'required',
                    'oldpassword' => 'required',
                    'password' => 'required|confirmed|min:6|max:20'
                ];
                //run the validator on the data
                $validator = Validator::make($_POST, $rules);

                //if validation is passed then password reset is successfully done otherwise a message is sent with error details
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

            }
            return redirect()->back()
                ->withErrors('Entered Old Password is not correct');
        }

    }

}
