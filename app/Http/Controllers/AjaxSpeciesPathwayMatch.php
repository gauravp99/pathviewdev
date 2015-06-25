<?php namespace App\Http\Controllers;

/*
 * This program is used for ajax request to find out the pathways mapped to a particular species
 * stored at table speciesPathwayMatch Table
 */
use App\Http\Requests;

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

