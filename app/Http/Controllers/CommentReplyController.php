<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Response;
use App\commentReply;
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
class CommentReplyController extends Controller {

    public function index()
    {
        return Response::json(CommentReply::get());
    }

    public function store()
    {
            CommentReply::create(array(
                'comment_id' => Input::get('commentId'),
                #'comment_id' => 7,
                'name' => Input::get('name'),
                'text_string' => Input::get('text')
            ));

        return Response::json(array('success' => true));
    }
    public function destroy($id)
    {
        commentReply::destroy($id);

        return Response::json(array('success' => true));
    }

}
