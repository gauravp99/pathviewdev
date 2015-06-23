<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Input;

class AjaxSpeciesPathwayMatch extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        print_r(Input::get('species'));

die();
	}



}

