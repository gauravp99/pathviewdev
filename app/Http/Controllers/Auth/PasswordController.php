<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Mail;
use DB;
class PasswordController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
    ##This function has been re-written and partially taken from Auth vendor.
    ##To avoid changes in the vendor directory , it has been added here.
    ##Before sending password reset mail to user, check if the user's account is
    #activated, if not then re send the activation mail to the user for activating 
    #the account
    public function postEmail(Request $request)
    {
    	$this->validate($request, ['email' => 'required|email']);
    	$user_check = $this->passwords->getUser($request->only('email'));
    	if (!$user_check->activated) 
    	{
    	      $confirmation_code=$user_check->confirmation_code;
    	      $email=$user_check->email;
    	      $name=$user_check->name;
                  $data['name'] = $name;
                  $data['email'] = $email;
                  Mail::send('emails.verify', compact('confirmation_code'), function($message) use ($data) {
                  $message->to($data['email'], $data['name'])->subject("[Pathview] Please activate your account first before resetting your password, ".$data['name']);
                });
               return back()->with('status', 'Your account is not activated. Please activate it first. An activation code has been sent to your email.');
            }
    
    	$response = $this->passwords->sendResetLink($request->only('email'), function($m)
    	{
    		$m->subject($this->getEmailSubject());
    	});
    
    	switch ($response)
    	{
    		case PasswordBroker::RESET_LINK_SENT:
    			return redirect()->back()->with('status', trans($response));
    
    		case PasswordBroker::INVALID_USER:
    			return redirect()->back()->withErrors(['email' => trans($response)]);
    	}
    }

    /**
     * Create a new password controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard $auth
     * @param  \Illuminate\Contracts\Auth\PasswordBroker $passwords
     * @return void
     */

    public function __construct(Guard $auth, PasswordBroker $passwords)
    {
        
        $this->auth = $auth;
        $this->passwords = $passwords;
        $this->middleware('guest');
    }

}
