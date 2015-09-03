<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Response;
use App\comment;
use Input;
use Auth;
use Illuminate\Http\Request;

class CommentController extends Controller {

    public function index()
    {
        return Response::json(Comment::get());
    }

    public function store()
    {
        if(Auth::user()) {
            comment::create(array(
                'author' => Input::get('author'),
                'text_string' => Input::get('text'),
                'user_email' => Auth::email(),
                'issue_id' => Input::get('issue_id')
            ));
        }
        else{
            comment::create(array(
                'author' => Input::get('author'),
                'text_string' => Input::get('text'),
                'user_email' => "guest",
                'issue_id' => Input::get('issue_id')
            ));
        }

        return Response::json(array('success' => true));
    }
    public function destroy($id)
    {
        comment::destroy($id);

        return Response::json(array('success' => true));
    }

}
