<?php namespace App\Http\Controllers\profile;

/**
 * @Author: Yehsvant Bhavnasi, Dr. Weijun Luo
 * @Contact: byeshvant@hotmail.com
 * Contoller for guest home page
 */
use App\Http\Requests;
use App\Http\Controllers\Controller;
class GuestController extends Controller
{
    public function __construct()
    {

        $this->middleware('guest');
    }


    public function index()
    {
        return view('profile.guest.guest-home');
    }
}
