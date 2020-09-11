<?php

use Illuminate\Support\Facades\Route;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('', 'PagesController@index');

Route::get('users/{user}', 'PagesController@user');
Route::get('settings', 'PagesController@settings');
Route::post('settings/save', 'PagesController@saveSettings');

Route::group(['prefix' => 'runner'], function () {
    Route::get('', 'RunnerController@index');
    Route::post('run', 'RunnerController@run');
    Route::post('save', 'RunnerController@save');
    Route::post('language', 'RunnerController@language');
    Route::post('check', 'RunnerController@check');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('users', function ()    {
        // Matches The "/admin/users" URL
    });
});
Route::get('/admin', 'AdminController@index');
Route::get('/admin/users', 'AdminController@viewUsers')->middleware('admin:5');
Route::post('/admin/users/save/{user}', 'AdminController@saveUser')->middleware('admin:5');

Auth::routes([
    //'register' => false, // Registration Routes...
    //'reset' => false, // Password Reset Routes...
    'verify' => true, // Email Verification Routes...
]);
Route::get('/redirect', 'Auth\LoginController@redirectToProvider');
Route::get('/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/home', 'HomeController@index')->name('home');