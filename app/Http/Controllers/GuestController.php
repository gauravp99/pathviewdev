<?php namespace App\Http\Controllers;

use App\Http\Requests;

class GuestController extends Controller
{

    //
    public function index()
    {
        return view('guest');
    }
}
