<?php namespace App\Http\Controllers\pathview\analysis;


use App\Http\Requests;

use \Illuminate\Support\Facades\Input;
use DB;
use App\Http\Controllers\Controller;
/**
 * Class AjaxSpeciesPathwayMatch
 * @package App\Http\Controllers
 *
 * This program is used for ajax request to find out the pathways mapped to a particular species
 * stored at table speciesPathwayMatch Table
 *
 */
class AjaxSpeciesPathwayMatch extends Controller {

	public function index()
	{
        //check for the pathway related to a species stored at table speciesPathwayMatch
        $var = substr(Input::get('species'),0,3);
	//check for the species which are 2 character long, since a trim to 3 characters
	//will get a hyphen along with the species id which will fail the database query
	//For eg. ko- is the species id after trimming , hyphen if any has to be replaced with null
	//character
	$var = str_replace("-","",$var);
        $pathway = DB::select(DB::raw("select concat(concat(a.pathway_id,'-'),b.pathway_desc) as \"pathway_id\" from speciesPathwayMatch a,pathway b where a.pathway_id = b.pathway_id and species_id = '$var'  "));
        return (json_encode($pathway));
        die();
	}

    public function speciesGeneIdMatch()
    {
            $var = substr(Input::get('species'),0,3);
            $gene_id = DB::select(DB::raw("select geneid from GageSpeceisGeneIdMatch where species_id = '$var'  "));

            return json_encode($gene_id);
            die();

    }

}

