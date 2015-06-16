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
                    'password' => 'required|confirmed|min:6|max:20' // only validate password if it has been entered
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

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
