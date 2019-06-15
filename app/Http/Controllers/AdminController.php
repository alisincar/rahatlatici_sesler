<?php

namespace App\Http\Controllers;

use App\AppInfo;
use App\Category;
use App\Music;
use App\User;
use Illuminate\Http\Request;

use App\Admin;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin', ['only' => 'index', 'edit']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $app_info=AppInfo::orderBy('id','DESC')->first();
        $users=User::count();
        $categories=Category::count();
        $musics=Music::count();
        return view('admin.dashboard',compact('musics','categories','users','app_info'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.auth.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Admin üyeliğinde mailin benzersiz olması ve parolaların aynı olması zorunluluğunu ayarlıyoruz
        $validateArray = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:8'],
            'password_confirmation' => ['required', 'string', 'min:8','same:password'],
        ];
        $vld = Validator::make($request->all(), $validateArray);
        if ($vld->fails()) {
            return redirect()->back()
                ->withErrors($vld)->withInput();
        }
        // store in the database
        $admins = new Admin;
        $admins->name = $request->name;
        $admins->email = $request->email;
        $admins->password = bcrypt($request->password);
        $admins->save();
        return redirect()->route('admin.auth.login');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
