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

Route::get('user/{user}', 'PagesController@user');
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
    Route::get('', 'AdminController@index')->middleware('admin:4');
    Route::get('users', 'AdminController@viewUsers')->middleware('admin:5');
    Route::post('users/save/{user}', 'AdminController@saveUser')->middleware('admin:5');
    Route::get('task', 'TasksController@create')->middleware('admin:4');
    Route::post('task/save', 'TasksController@store')->middleware('admin:4');
});

Route::get('tasks', 'TasksController@index')->middleware('admin:2');

Route::group(['prefix' => 'task/{task}'], function () {
    Route::get('', 'TasksController@show')->middleware('admin:2');
    Route::get('solution', 'TasksController@solution')->middleware('admin:4');
    Route::get('edit', 'TasksController@edit')->middleware('admin:4');
    Route::post('edit/save', 'TasksController@update')->middleware('admin:4');

    Route::get('tests/{test?}', 'TasksController@tests')->middleware('admin:4');
    Route::post('test/save/{test?}', 'TasksController@saveTest')->middleware('admin:4');
    Route::get('test/{test}/download/{ext}', 'TasksController@downloadTest')->middleware('admin:4');
    Route::delete('tests/{test}', 'TasksController@deleteTest')->middleware('admin:4');

    Route::get('submit', 'TasksController@submit')->middleware('admin:2');
    Route::post('submit/save', 'TasksController@saveSubmit')->middleware('admin:2');
});

Auth::routes([
    //'register' => false, // Registration Routes...
    //'reset' => false, // Password Reset Routes...
    'verify' => true, // Email Verification Routes...
]);
Route::get('/redirect', 'Auth\LoginController@redirectToProvider');
Route::get('/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/home', 'HomeController@index')->name('home');