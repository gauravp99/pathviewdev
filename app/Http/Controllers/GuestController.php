<?php namespace App\Http\Controllers;

/**
 * @Author: Yehsvant Bhavnasi, Dr. Weijun Luo
 * @Contact: byeshvant@hotmail.com
 * Contoller for guest home page
 */
use App\Http\Requests;

class GuestController extends Controller
{

    //
    public function index()
    {
        return view('guest');
    }
}
