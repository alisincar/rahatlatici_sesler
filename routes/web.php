<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Auth::routes();



Route::prefix('admin')->group(function () {
    Route::get('/', 'Admin\AdminController@index')->name('admin.dashboard');
    Route::get('dashboard', 'Admin\AdminController@index')->name('admin.dashboard');
    Route::get('register', 'Admin\AdminController@create')->name('admin.register');
    Route::post('register', 'Admin\AdminController@store')->name('admin.register.store');
    Route::get('login', 'Auth\AdminLoginController@login')->name('admin.auth.login');
    Route::post('login', 'Auth\AdminLoginController@loginAdmin')->name('admin.auth.loginAdmin');

    Route::group(['middleware' => ['admin']], function () {
        Route::post('logout', 'Auth\AdminLoginController@logout')->name('admin.auth.logout');
        Route::resource('categories', 'Admin\CategoryController');
        Route::resource('musics', 'Admin\MusicController');
    });

});


Route::get('/', 'HomeController@index')->name('home');
Route::get('/login', 'HomeController@login')->name('login');
Route::post('logout', 'Auth\AdminLoginController@logout')->name('logout');

