<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', 'Api\UserController@login')->name('login');
Route::post('/register', 'Api\UserController@register');

Route::middleware('auth:api')->group(function () {
    Route::post('/refresh', 'Api\UserController@refresh');
    Route::post('/logout', 'Api\UserController@logout');
    Route::get('/test', 'Api\UserController@test');
});  // 在header中添加 Authorization:bearer token
