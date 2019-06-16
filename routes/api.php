<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login', 'Api\UserController@login');
Route::post('/register', 'Api\UserController@register');
Route::get('/versionControl', 'Api\BaseController@versionControl');

Route::group(['middleware' => 'auth:api', 'prefix' => 'user'], function () {
    Route::get('/', 'Api\BaseController@index');
    Route::get('/category', 'Api\BaseController@getCategory');
    Route::group(['prefix' => 'favorites'], function () {
        Route::get('/', 'Api\FavoriteController@index');
        Route::post('/store', 'Api\FavoriteController@store');
        Route::delete('/delete', 'Api\FavoriteController@delete');
    });
});
