<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class comment extends Model {


protected $fillable = array('author', 'text_string','user_email');
}
