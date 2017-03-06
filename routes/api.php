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
Route::post('/auth/register', 'Auth\AuthController@register')->middleware('requestHandler:RegisterRequest');
Route::post('/auth/login', 'Auth\AuthController@login')->middleware('requestHandler:LoginRequest');

Route::post('/add_billing_card', 'BillingController@AddBillingCard')->middleware('requestHandler:AddBillingCardRequest');
Route::post('/update_profile_picture', 'UsersController@updateProfilePicture')->middleware('requestHandler:UpdateProfilePictureRequest');
Route::post('/update_profile', 'UsersController@updateProfile')->middleware('requestHandler:UpdateProfileRequest');
