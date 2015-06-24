<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\speciesPathwayMatch;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Input;
use DB;
class AjaxSpeciesPathwayMatch extends Controller {


	public function index()
	{
        $var = substr(Input::get('species'),0,3);
        $pathway = DB::select(DB::raw("select pathway_id from speciesPathwayMatch where species_id = '$var'  "));
       return (json_encode($pathway));
        die();
	}



}

