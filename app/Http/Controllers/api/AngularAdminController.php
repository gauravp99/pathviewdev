<?php namespace App\Http\Controllers\api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Illuminate\Http\Request;
use DB;
use Mail;
class AngularAdminController extends Controller {

	public function createUser()
	{



		$user_name = Input::get('name');
		$user_email = Input::get('email');
		$user_organization = Input::get('organization');
		$user = DB::table("users")->where('email', $user_email)->get();
		if(sizeof($user) == 0 )
		{
			if(strcmp($user_organization,"")=="")
			{
				$user_organization = "Pathview";
			}
			$password = uniqid();

			DB::table('users')->insert(
				array('email' => $user_email, 'name' => $user_name , 'password' => bcrypt($password),'organisation' => $user_organization)
			);


			$data['email'] = $user_email;
			$data['subject'] = "Pathview profile created successfully";
			$data['body'] = "Thank you for registering your profile at Pathway";
			$data['userPassword'] = $password;

			//$user = DB::table("users")->where('email', $value)->get();

			$data['name'] = $user_name;

			/*if (!is_null($user)) {
                $data['name'] = $user->name;
            } else {
                $data['name'] = "user";
            }*/

			Mail::send('emails.userCreated', $data, function ($message) use ($data) {
				try {

					$message->to(trim($data['email']), trim($data['name']))->subject($data['subject']);

				} catch (Exception $e) {
					return "exception in mail";
				}
			});
			return "createdUser";
		}
		else{
			return "userAlreadyExist";
		}
		return "createUser";
	}

	public function broadCastMessage()
	{
		return "broadcastMessage";


	}


}
