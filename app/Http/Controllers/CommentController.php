<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Response;
use App\comment;
use Input;
use Auth;
use Illuminate\Http\Request;


/***
 * Class CommentController
 * @package App\Http\Controllers
 *
 * This controller is used to handle all the tasks related to the comment adding page
 * this page is used to work with angular js application
 * works as an API to call the add delete operation
 */
class CommentController extends Controller {

    public function index()
    {
        return Response::json(Comment::orderBy('id', 'DESC')->get());
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
