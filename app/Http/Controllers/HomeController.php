<?php

namespace App\Http\Controllers;

use App\AppInfo;
use App\Category;
use App\Music;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $app_info=AppInfo::orderBy('id','DESC')->first();
        $users=User::count();
        $categories=Category::count();
        $musics=Music::count();
        return view('home',compact('musics','categories','users','app_info'));
    }
}
