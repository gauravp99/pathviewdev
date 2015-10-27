<?php namespace App\Http\Controllers\admin;

use App\Http\Models\DbDataFetch;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Admin;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cookie;

class AuthenticateController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$admin = Admin::all();
		return $admin;
	}

	public function authenticate(Request $request)
	{

		$credentials = $request->only('email', 'password');


		/*try {
			// verify the credentials and create a token for the user
			if (! $token = JWTAuth::attempt($credentials)) {
				return response()->json(['error' => 'invalid_credentials'], 401);
			}
		} catch (JWTException $e) {
			// something went wrong
			return response()->json(['error' => 'could_not_create_token'], 500);
		}*/

		//return response()->json(compact('token'));

		$db = new DbDataFetch();
		$returnedValues = $db->getAdmin($credentials['email'],$credentials['password']);


		if(sizeof($returnedValues)>0)
		{
			$token =[];
			$uniq = uniqid();
			$token["id"] = $uniq;
			$arr = array();
			$arr['id']="1";
			$arr['role']='admin';
			$token["user"] = "1";
			$token["role"] = "admin";
			$_SESSION["token"] = $uniq;
			Cookie::queue("token",$uniq,1440);

			return response()->json($token);
		} else {
			return response()->json(['error' => 'invalid_credentials'], 500);
		}


	}

}

class User {
	public $id;
	public $role;

	/**
	 * User constructor.
	 * @param $id
	 * @param $role
	 */
	public function __construct($id, $role)
	{
		$this->id = $id;
		$this->role = $role;
	}

}
