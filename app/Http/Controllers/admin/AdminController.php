<?php namespace App\Http\Controllers\admin;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use User;
use App;
use DB;
use Input;
use Mail;
use Illuminate\Http\Request;

class AdminController extends Controller {


	//auth function checking for credentails in user table.
	public function auth()
	{
		if (Auth::attempt(['email' => $_POST['email'], 'password' => $_POST['password']]))
		{
			return view("admin.adminprofile");
		}
	}

	//function called before performing any task in the admin page typically checks if logged user is admin or not.
	public function index()
	{

		if(Auth::user())
		{
			if(strcmp(Auth::user()->email,"pathwayadmin@gmail.com")==0)
			{
				return view('admin.adminprofile');
			}
			else{
				App::abort(404);
			}

		}
		else{
			return view('admin.adminLogin');
		}

	}

	public function ajaxAdminBroadCastMessage()
	{
		//get email list from the html page sent interms of the ajax request
		$emailList = Input::get('emailList');

		$subject = Input::get("subject");

		$messageText = Input::get("message");
		$users = explode(";",$emailList);

		foreach($users as $value)
		{
			if(!is_null(trim($value)) && !(strcmp(trim($value),'')==0))
			{

				$data['email'] = $value;
				$data['subject'] = $subject;
				$data['body'] = $messageText;

				$user = DB::table("users")->where('email', $value)->get();

				$data['name'] = 'user';
				/*if (!is_null($user)) {
					$data['name'] = $user->name;
				} else {
					$data['name'] = "user";
				}*/

				Mail::send('emails.adminBroadcast', $data, function ($message) use ($data) {
					try {

						$message->to(trim($data['email']), trim($data['name']))->subject($data['subject']);

					} catch (Exception $e) {
						return "exception in mail";
					}
				});
			}


		}

	}
	//function used for sending mail to all users registered in the application
	public function adminBroadcastMessage()
	{

		$me= $_POST['message'];



		$data['msg'] = htmlspecialchars($me);

		$data['path'] = public_path()."";
		$users = explode(";",$_POST['usersList']);

		foreach ($users as &$value) {
			if (!is_null(trim($value))) {
				$data['email'] = $value;
				echo $value;
				$user = DB::table("users")->where('email', $value);
				if (is_null($user)) {
					$data['name'] = $user->name;
				} else {
					$data['name'] = "user";
				}

				Mail::send('emails.adminBroadcast', $data, function ($message) use ($data) {
					try {

						$message->to(trim($data['email']), trim($data['name']))->subject('Pathway Project test Message');

					} catch (Exception $e) {
						return "exception in mail";
					}
				});
			}
		}
	}

	public function getAllUsers()
	{
		return DB::table('users')->select('email')->get();

	}
	public function getAdmin()
	{
		return DB::table('admin')->get();

	}

}
