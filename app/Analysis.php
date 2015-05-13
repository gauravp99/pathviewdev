<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Analysis extends Model
{

    protected $table = 'analyses';
    protected $fillable = ['argument', 'analysis_type', 'analysis_id', 'id'];


}
