<?php
use Illuminate\Support\Facades\Input;
namespace App\Http\Controllers;
use View;
use Validator;
use Input;
use Redirect;
use Mail;
use Hash;
use App\User;
#use Illuminate\Support\Facades\Redirect;
#class RegistrationController extends \BaseController {
class RegistrationController extends Controller {
    public function __construct()
    {
        $this->beforeFilter('guest');
    }
	/**
	 * Show a form to register the user.
	 *
	 * @return Response
	 */
	public function create()
	{
           return View::make('registration.create');
	}
	/**
	 * Create a new forum member.
	 *
	 * @return Response
	 */
	public function store()
	{

        $rules = [
            #'username' => 'required|min:6|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'organisation' => 'required'
        ];
        $validator = Validator::make(Input::only('organisation', 'email', 'password', 'password_confirmation'), $rules);
        if($validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $confirmation_code = str_random(30);
        User::create([
            #'username' => Input::get('username'),
            'name' => Input::get('name'),
            'email' => Input::get('email'),
            'password' => Hash::make(Input::get('password')),
            'confirmation_code' => $confirmation_code,
	    'activation' => 0
        ]);
        Mail::send('emails.verify', compact('confirmation_code'), function($message) {
            $message->to(Input::get('email'), Input::get('name'))->subject('[Pathview] Please verify your email address, '.Input::get('name'));
        });
        #Flash::message('Thanks for signing up! Please check your email and follow the instructions to complete the sign up process');
        return redirect('/')->with('message', 'Thanks for signing up! Please check your email and follow the instructions to complete the sign up process.');
        #return Redirect::home()->with('message', 'Thanks for signing up! Please check your email and follow the instructions to complete the sign up process');
    }
    /**
     * Attempt to confirm a users account.
     *
     * @param $confirmation_code
     *
     * @throws InvalidConfirmationCodeException
     * @return mixed
     */
    public function confirm($confirmation_code)
    {
	    
        if( ! $confirmation_code)
        {
            return redirect('/')->with('message', 'You have successfully verified your account 1');
            #return Redirect::home();
        }
        $user = User::whereConfirmationCode($confirmation_code)->first();
	$name = $user->name;
	$email = $user->email;
	error_log('user------------');
	error_log($email);
        if ( ! $user)
        {
            #return Redirect::home();
            #return redirect('/auth/login')->with('message', 'You have successfully verified your account. You can now login.');
            return redirect('/')->with('message', 'This account has already been verified successfully.');
        }
        $user->activated = 1;
        $user->confirmation_code = null;
        $user->save();
	$data1 = []; 
        Mail::send('emails.welcome', $data1, function($message) use ($email, $name){
            $message->to($email, $name)->subject('[Pathview] Welcome to Pathview, '.$name);
        });
        #$user->save();
        return redirect('/')->with('message', 'You have successfully verified your account. You can now login.');
    }
}
