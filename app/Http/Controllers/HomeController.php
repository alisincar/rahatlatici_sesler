<?php

namespace App\Http\Controllers;

use App\Models\AppInfo;
use App\Models\Category;
use App\Models\Music;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function login()
    {
        return view('auth.login');
    }
}
