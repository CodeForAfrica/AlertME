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


Auth::routes();


// The rest

Route::get('/', 'HomeController@showHome');
Route::get('about', 'HomeController@showAbout');
Route::get('map', 'HomeController@showMap');

Route::get('search', 'HomeController@getSearch');

Route::get('project/{id}', 'HomeController@showProject');

Route::get('subscriptions/{confirm_token}', 'api\ApiSubscriptionController@confirm');
Route::post('subscriptions/{confirm_token}', 'api\ApiSubscriptionController@confirm');


// Secure Routes
Route::group(array('middleware' => 'auth'), function () {
    Route::get('dashboard', 'DashboardController@showHome');

    Route::get('dashboard/datasources', 'DashboardController@showDataSources');
    Route::get('dashboard/datasources/sync', 'DashboardController@syncDataSources');

    Route::get('dashboard/categories', 'DashboardController@showCategories');

    Route::get('dashboard/pages', 'DashboardController@showPages');
    Route::post('dashboard/pages', 'DashboardController@setPages');

    Route::get('dashboard/subscriptions', 'DashboardController@showSubscriptions');

    Route::get('dashboard/profile', 'DashboardController@showProfile');
    Route::post('dashboard/profile', 'DashboardController@setProfile');
    Route::get('dashboard/settings', 'DashboardController@showSettings');
    Route::post('dashboard/settings', 'DashboardController@setSettings');
});


Route::get('/authtest', array('middleware' => 'auth.basic', function () {
    return View::make('hello');
}));


// API v1

Route::group(array('prefix' => 'api/v1', 'middleware' => 'auth.basic'), function () {
    Route::resource('datasources', 'api\ApiDataSourceController');
    Route::resource('categories', 'api\ApiCategoryController');
});
Route::group(array('prefix' => 'api/v1'/*, 'middleware' => 'csrf'*/), function () {
    Route::get('subscriptions/email', 'api\ApiSubscriptionController@email');
    Route::resource('subscriptions', 'api\ApiSubscriptionController');
});
Route::group(array('prefix' => 'api/v1'), function () {
    Route::resource('projectsgeojson', 'api\ApiProjectsGeojsonController', array('only' => array('index')));
    Route::resource('projects', 'api\ApiProjectController', array('only' => array('index', 'show')));
    Route::resource('categories', 'api\ApiCategoryController', array('only' => array('index', 'show')));
});


// Scraping

Route::get('scrapers', 'ScrapersController@index');
Route::get('scrapers/{id_or_slug}', 'ScrapersController@show');
Route::get('scrapers/{id_or_slug}/run', 'ScrapersController@scrape');

Route::resource('scrapes', 'ScrapesController');

// Redirects

//Route::any('login', function () {
//    return redirect('auth/login');
//});
//Route::any('logout', function () {
//    return redirect('auth/logout');
//});

