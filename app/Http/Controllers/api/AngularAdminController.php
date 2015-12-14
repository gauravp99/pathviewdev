<?php namespace App\Http\Controllers\api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Illuminate\Http\Request;
use DB;
use Mail;
class AngularAdminController extends Controller {

	/**
	 * @return string
	 * called from admin create user page
	 *
	 */
	public function createUser()
	{

		//get the details of name and email and organisation parameter from the admin page
		$user_name = Input::get('name');
		$user_email = Input::get('email');
		$user_organization = Input::get('organization');

		//check in db form user details if user already exists or not if exist then skip
		$user = DB::table("users")->where('email', $user_email)->get();

		//if size eqal to zero then the user is not existed in the database
		if(sizeof($user) == 0 )
		{
			//if user doesnt specify the password make the organisation to default pathview
			if(strcmp($user_organization,"")=="")
			{
				$user_organization = "Pathview";
			}

			//generate a unique passowrd using uniq php function
			$password = uniqid();

			//insert the data into table
			DB::table('users')->insert(
				array('email' => $user_email, 'name' => $user_name , 'password' => bcrypt($password),'organisation' => $user_organization)
			);


			//send an email to user with the current password
			//collect the datils insert into a variable called data and send mail using in built laravel mail function
			$data['email'] = $user_email;
			$data['subject'] = "Pathview profile created successfully";
			$data['body'] = "Thank you for registering your profile at Pathway";
			$data['userPassword'] = $password;
			$data['name'] = $user_name;

			//this code sends mail to the corresponding user
			//the html code visibility of mail is as give in the emails.userCreated page
			Mail::send('emails.userCreated', $data, function ($message) use ($data) {
				try {
					$message->to(trim($data['email']), trim($data['name']))->subject($data['subject']);
				} catch (Exception $e) {
					return "exception in mail";
				}
			});

			//if successfully created
			return "createdUser";
		}
		else{
			//return already created these messages are shown using javascript on frontend
			return "userAlreadyExist";
		}

		return "createUser";
	}



}
