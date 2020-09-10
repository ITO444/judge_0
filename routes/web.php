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

Route::get('/', 'PagesController@index');

Route::get('/users/{id}', 'PagesController@user');
Route::get('/settings', 'PagesController@settings');

Route::get('/runner', 'RunnerController@index');
Route::post('/runner/run', 'RunnerController@run');
Route::post('/runner/save', 'RunnerController@save');
Route::post('/runner/language', 'RunnerController@language');
Route::post('/runner/check', 'RunnerController@check');

Route::get('/queue','PagesController@queue');
Route::post('/queue', 'PagesController@queue');

Auth::routes([
    //'register' => false, // Registration Routes...
    //'reset' => false, // Password Reset Routes...
    'verify' => true, // Email Verification Routes...
]);
Route::get('/redirect', 'Auth\LoginController@redirectToProvider');
Route::get('/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/home', 'HomeController@index')->name('home');