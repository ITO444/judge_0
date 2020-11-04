<?php

use Illuminate\Support\Facades\Broadcast;
use App\Task;
use App\Submission;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('update.runner.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('update.publish.{id}', function ($user, $id) {
    $level = $user->level;
    $task = Task::find($id);
    return $level >= $task->edit_level && $level >= 6;
});

Broadcast::channel('update.submit.{submission}', function ($user, Submission $submission) {
    return (int) $user->id === (int) $submission->user->id;
});
