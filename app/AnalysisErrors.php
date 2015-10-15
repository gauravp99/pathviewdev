<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AnalysisErrors extends Model {

    protected $table = 'analysisErrors';
    protected $guarded = array('error_id');

}
