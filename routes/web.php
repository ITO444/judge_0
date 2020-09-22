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

Route::get('user/{user:name}', 'PagesController@user');
Route::get('settings', 'PagesController@settings');
Route::post('settings', 'PagesController@saveSettings');

Route::group(['prefix' => 'runner'], function () {
    Route::get('', 'RunnerController@index')->middleware('admin:2');
    Route::post('run', 'RunnerController@run')->middleware('admin:2');
    Route::post('save', 'RunnerController@save')->middleware('admin:2');
    Route::post('language', 'RunnerController@language')->middleware('admin:2');
    Route::post('check', 'RunnerController@check')->middleware('admin:2');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('', 'AdminController@index')->middleware('admin:4');//->middleware('verified');
    Route::get('users', 'AdminController@viewUsers')->middleware('admin:5');
    Route::post('users/{user:name}', 'AdminController@saveUser')->middleware('admin:5');
    Route::get('task', 'TasksController@create')->middleware('admin:4');
    Route::post('task', 'TasksController@store')->middleware('admin:4');
    Route::group(['prefix' => 'lesson'], function () {
        Route::get('{language}', 'AdminController@adminLesson')->middleware('admin:6');
        //Route::get('{user:name}', 'AdminController@adminAttendUser')->middleware('admin:6');
        //Route::post('attend', 'AdminController@adminClearAttend')->middleware('admin:6');
        //Route::get('answer', 'AdminController@adminAnswer')->middleware('admin:6');
        //Route::post('answer', 'AdminController@adminClearAnswer')->middleware('admin:6');
        //Route::get('runner/{language}', 'AdminController@adminRunners')->middleware('admin:6');
    });
});

Route::group(['prefix' => 'lesson'], function () {
    Route::get('', 'AdminController@lesson')->middleware('admin:3');
    Route::get('attend', 'AdminController@attend')->middleware('admin:3');
    Route::post('attend', 'AdminController@saveAttend')->middleware('admin:3');
    Route::get('answer', 'AdminController@answer')->middleware('admin:3');
    Route::post('answer', 'AdminController@saveAnswer')->middleware('admin:3');
});


Route::get('tasks', 'TasksController@index')->middleware('admin:2');

Route::group(['prefix' => 'task/{task:task_id}'], function () {
    Route::get('', 'TasksController@show')->middleware('admin:2');
    Route::get('solution', 'TasksController@solution')->middleware('admin:4');
    Route::get('edit', 'TasksController@edit')->middleware('admin:4');
    Route::post('edit', 'TasksController@update')->middleware('admin:4');

    Route::get('tests/{test?}', 'TasksController@tests')->middleware('admin:4');
    Route::post('tests/{test?}', 'TasksController@saveTest')->middleware('admin:4');
    Route::get('test/{testNumber}/download/{ext}', 'TasksController@downloadTest')->middleware('admin:4');
    Route::delete('tests/{test}', 'TasksController@deleteTest')->middleware('admin:4');

    Route::get('grader', 'TasksController@grader')->middleware('admin:4');
    Route::post('grader', 'TasksController@saveGrader')->middleware('admin:4');

    Route::get('submit', 'TasksController@submit')->middleware('admin:2');
    Route::post('submit', 'TasksController@saveSubmit')->middleware('admin:2');
});

Auth::routes([
    //'register' => false, // Registration Routes...
    //'reset' => false, // Password Reset Routes...
    'verify' => true, // Email Verification Routes...
]);

Route::get('/redirect', 'Auth\LoginController@redirectToProvider');
Route::get('/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/home', 'HomeController@index')->name('home');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
