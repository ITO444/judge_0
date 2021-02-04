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

Route::get('', 'PagesController@index')->middleware('admin:1');

Route::get('user/{user:name}', 'PagesController@user')->middleware('admin:1');
Route::get('settings', 'PagesController@settings')->middleware('admin:1');
Route::post('settings', 'PagesController@saveSettings')->middleware('admin:2');
Route::get('leaderboard', 'PagesController@leaderboard')->middleware('admin:3');
Route::get('leaderboard/{page?}', 'PagesController@leaderboard')->middleware('admin:3');

Route::group(['prefix' => 'runner'], function () {
    Route::get('', 'RunnerController@index')->middleware('admin:2');
    Route::post('run', 'RunnerController@run')->middleware('admin:2');
    Route::post('save', 'RunnerController@save')->middleware('admin:2');
    Route::post('language', 'RunnerController@language')->middleware('admin:2');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('', 'AdminController@index')->middleware('admin:4');//->middleware('verified');
    Route::get('users', 'AdminController@viewUsers')->middleware('admin:5');
    Route::post('users/{user:name}', 'AdminController@saveUser')->middleware('admin:5');
    Route::get('task', 'TasksController@create')->middleware('admin:4');
    Route::post('task', 'TasksController@store')->middleware('admin:4');
    Route::get('contest', 'ContestsController@create')->middleware('admin:6');
    Route::post('contest', 'ContestsController@store')->middleware('admin:6');
    Route::get('lesson/{language}', 'AdminController@adminLesson')->middleware('admin:6');
    Route::get('images/{image:name?}', 'AdminController@images')->middleware('admin:4');
    Route::post('images/{image:name?}', 'AdminController@saveImage')->middleware('admin:4');
    Route::post('temp_level', 'AdminController@changeTempLevel')->middleware('admin:4');
    Route::get('reset_temp_level', 'AdminController@resetTempLevel');
});

Route::group(['prefix' => 'lesson'], function () {
    Route::get('', 'AdminController@lesson')->middleware('admin:3');
    Route::get('attend', 'AdminController@attend')->middleware('admin:3');
    Route::post('attend', 'AdminController@saveAttend')->middleware('admin:3');
    Route::get('answer', 'AdminController@answer')->middleware('admin:3');
    Route::post('answer', 'AdminController@saveAnswer')->middleware('admin:3');
});


Route::get('tasks/{tab}/{order?}', 'TasksController@index')->middleware('admin:2');

Route::group(['prefix' => 'task/{task:task_id}'], function () {
    Route::get('', 'TasksController@show')->middleware('admin:2, 1');
    Route::get('solution', 'TasksController@solution')->middleware('admin:4');

    Route::get('edit', 'TasksController@edit')->middleware('admin:4');
    Route::post('edit', 'TasksController@update')->middleware('admin:4');

    Route::get('tests/{test?}', 'TasksController@tests')->middleware('admin:4');
    Route::post('tests/{test?}', 'TasksController@saveTest')->middleware('admin:4');
    Route::get('test/{testNumber}/download/{ext}', 'TasksController@downloadTest')->middleware('admin:4');
    Route::delete('tests/{test}', 'TasksController@deleteTest')->middleware('admin:4');

    Route::get('grader', 'TasksController@grader')->middleware('admin:4');
    Route::post('grader', 'TasksController@saveGrader')->middleware('admin:4');

    Route::get('submit', 'TasksController@submit')->middleware('admin:2, 1');
    Route::post('submit', 'TasksController@saveSubmit')->middleware('admin:2, 1');

    Route::get('publish', 'TasksController@publish')->middleware('admin:6');
    Route::get('unpublish', 'TasksController@unpublish')->middleware('admin:6');

});

Route::get('contests/{page?}', 'ContestsController@index')->middleware('admin:2');

Route::group(['prefix' => 'contest/{contest:contest_id}'], function () {
    Route::get('', 'ContestsController@show')->middleware('admin:2, 1');
    Route::get('editorial', 'ContestsController@editorial')->middleware('admin:4');

    Route::get('edit', 'ContestsController@edit')->middleware('admin:4');
    Route::post('edit', 'ContestsController@update')->middleware('admin:4');

    Route::get('edit/tasks', 'ContestsController@editTasks')->middleware('admin:4');
    Route::post('edit/tasks', 'ContestsController@updateTasks')->middleware('admin:4');

    Route::get('edit/task/{task:task_id}', 'ContestsController@editTask')->middleware('admin:4');
    Route::post('edit/task/{task:task_id}', 'ContestsController@updateTask')->middleware('admin:4');
    Route::delete('edit/task/{task:task_id}', 'ContestsController@deleteTask')->middleware('admin:4');

    Route::get('edit/contestants', 'ContestsController@editContestants')->middleware('admin:4');
    Route::post('edit/contestants', 'ContestsController@addContestant')->middleware('admin:4');
    Route::delete('edit/contestants/{participation}', 'ContestsController@deleteContestant')->middleware('admin:4');

    Route::post('register', 'ContestsController@register')->middleware('admin:1');
    Route::delete('register', 'ContestsController@unregister')->middleware('admin:1');

    Route::get('results', 'ContestsController@results')->middleware('admin:1, 1');

    Route::get('publish', 'ContestsController@publish')->middleware('admin:7');
    Route::get('unpublish', 'ContestsController@unpublish')->middleware('admin:7');
});

Route::group(['prefix' => 'submissions'], function () {
    Route::get('', 'SubmissionsController@index')->middleware('admin:2');
    Route::get('user/{user:name}', 'SubmissionsController@user')->middleware('admin:2');
    Route::get('task/{task:task_id}', 'SubmissionsController@task')->middleware('admin:2');
    Route::get('contest/{contest:contest_id}', 'SubmissionsController@contest')->middleware('admin:2, 1');
});
Route::get('submission/{submission}', 'SubmissionsController@show')->middleware('admin:2, 1');
Route::delete('submission/{submission}/rejudge', 'SubmissionsController@rejudge')->middleware('admin:6');

Auth::routes([
    //'register' => false, // Registration Routes...
    //'reset' => false, // Password Reset Routes...
    'verify' => true, // Email Verification Routes...
]);

Route::get('/redirect', 'Auth\LoginController@redirectToProvider');
Route::get('/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/home', 'HomeController@index')->name('home')->middleware('admin:1');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('admin:1');
