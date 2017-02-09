<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentReply extends Model {


    protected $table = 'commentReply';
    protected $fillable = array('comment_id', 'name', 'text_string');
}
