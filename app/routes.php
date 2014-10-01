<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('', 'HomeController@showHome');

// Authentication
Route::get('login', 'AuthController@showLogin');
Route::post('login', 'AuthController@postLogin');
Route::get('logout', 'AuthController@getLogout');

// Secure-Routes
Route::group(array('before' => 'auth'), function()
{
    Route::get('dashboard', 'DashboardController@showHome');
    Route::get('dashboard/settings', 'DashboardController@showSettings');
});
