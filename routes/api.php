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
Route::post('/auth/forgot_password', 'Auth\AuthController@forgotPassword')->middleware('requestHandler:ForgotPasswordRequest');
Route::post('/session/update','Auth\AuthController@updateToken')->middleware('requestHandler:UpdateSessionRequest');

Route::post('/add_billing_card', 'BillingController@AddBillingCard')->middleware('requestHandler:AddBillingCardRequest');
Route::post('/update_profile_picture', 'UsersController@updateProfilePicture')->middleware('requestHandler:UpdateProfilePictureRequest');
Route::post('/update_profile', 'UsersController@updateProfile')->middleware('requestHandler:UpdateProfileRequest');
Route::post('/update_walkthrough_status', 'UsersController@updateWalkthroughStatus')->middleware('requestHandler:UpdateWalkthroughStatusRequest');
Route::post('/auth/logout', 'Auth\AuthController@logout')->middleware('requestHandler:LogoutRequest');
Route::post('/reset_password', 'UsersController@resetPassword')->middleware('requestHandler:ResetPasswordRequest');
Route::get('/get/users','UsersController@getUsers')->middleware('requestHandler:GetUsersRequest');
Route::get('/products/get','ProductsController@getProducts')->middleware('requestHandler:GetProductsRequest');
Route::get('/products/search','ProductsController@searchProducts')->middleware('requestHandler:SearchProductsRequest');
Route::get('/product/detail','ProductsController@productDetail')->middleware('requestHandler:GetProductDetailsRequest');

Route::post('/event/create','EventsController@create')->middleware('requestHandler:CreateEventRequest');
Route::get('/events','EventsController@getMyEvents')->middleware('requestHandler:GetMyEventsRequest');

Route::get('/public/events','EventsController@getPublicEvents')->middleware('requestHandler:GetPublicEventsRequests');
Route::post('/event/invitation/accept','EventsController@acceptEventInvitation')->middleware('requestHandler:AcceptEventInvitationRequest');
Route::post('/event/invitation/decline','EventsController@declineEventInvitation')->middleware('requestHandler:DeclineEventInvitationRequest');
Route::get('/event/detail','EventsController@getEventDetail')->middleware('requestHandler:GetEventDetailRequest');
Route::get('/event/invitations','EventsController@fetchEventInvitations')->middleware('requestHandler:FetchEventInvitationsRequest');
Route::post('/event/cancel','EventsController@cancel')->middleware('requestHandler:CancelEventRequest');

Route::post('/product/create','ProductsController@create')->middleware('requestHandler:CreateProductRequest');
Route::post('/event/join','EventsController@joinEvent')->middleware('requestHandler:JoinEventRequest');
Route::post('/event/update','EventsController@update')->middleware('requestHandler:UpdateEventRequest');

Route::post('/wishlist/add','WishlistController@add')->middleware('requestHandler:AddToWishlistRequest');
Route::post('/wishlist/remove','WishlistController@remove')->middleware('requestHandler:RemoveFromWishlistRequest');
Route::get('/wishlist','WishlistController@get')->middleware('requestHandler:GetWishlistRequest');

Route::get('/friends','UsersController@friends')->middleware('requestHandler:GetUserFriendsRequest');