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

Route::post('/auth/fblogin', 'Auth\AuthController@fblogin')->middleware('requestHandler:FbLoginRequest');
Route::post('/auth/login', 'Auth\AuthController@login')->middleware('requestHandler:LoginRequest');