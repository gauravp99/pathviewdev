<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use User;
use App;
use DB;
use Mail;
use Illuminate\Http\Request;

class AdminController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function emailAll()
	{



	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function auth()
	{
		if (Auth::attempt(['email' => $_POST['email'], 'password' => $_POST['password']]))
		{
			return view("admin.adminprofile");
		}
	}
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

}
