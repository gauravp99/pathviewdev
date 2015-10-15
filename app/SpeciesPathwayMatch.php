<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SpeciesPathwayMatch extends Model {

    protected $table = 'speciesPathwayMatch';

    protected $guarded = array('match_id');

}
