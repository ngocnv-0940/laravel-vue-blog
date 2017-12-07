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
Route::post('upload', 'UserController@uploadImage')->name('user.upload');
Route::get('user/{user}/media', 'UserController@media')->name('user.media');
Route::resource('user', 'UserController');
Route::patch('post/status', 'PostController@changeStatus')->name('post.status');
Route::resource('post', 'PostController');
Route::resource('category', 'CategoryController');
Route::resource('tag', 'TagController');
Route::resource('comment', 'CommentController');
Route::patch('like/{slug}/toggle', 'LikeController@likeOrUnlike')->name('like.toggle');
Route::resource('like', 'LikeController');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('user');

    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/password', 'Settings\PasswordController@update');
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
});
