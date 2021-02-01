<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Task;
use App\Test;
use App\Submission;
use App\Jobs\ProcessSubmission;
use Illuminate\Support\Facades\Storage;

class SubmissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $level = auth()->user()->level;
        $submissions = Submission::whereHas('task', function($query)use($level){
            $query->where('submit_level', '<=', $level)->where(function ($query)use($level){
                $query->where('published', '=', 1)
                      ->orWhere(function ($query) use ($level) {
                            $query->where('edit_level', '<=', $level);
                            if($level == 5){
                                $query->where('edit_level', '<>', 4);
                            }
                        });
            });
        })->orderBy('id', 'desc')->paginate(50);
        return view('submissions.index')->with('submissions', $submissions);
    }

    public function user(User $user)
    {
        $level = auth()->user()->level;
        $submissions = Submission::where('user_id', $user->id)->whereHas('task', function($query)use($level){
            $query->where('submit_level', '<=', $level)->where(function ($query)use($level){
                $query->where('published', '=', 1)
                      ->orWhere(function ($query) use ($level) {
                            $query->where('edit_level', '<=', $level);
                            if($level == 5){
                                $query->where('edit_level', '<>', 4);
                            }
                        });
            });
        })->orderBy('id', 'desc')->paginate(50);
        return view('submissions.index')->with('submissions', $submissions)->with('user', $user);
    }

    public function contest(Contest $contest)
    {
        $level = auth()->user()->level;
        if($level < $contest->view_level){
            //
        }
        $submissions = Submission::where('user_id', $user->id)->whereHas('task', function($query)use($level){
            $query->where('submit_level', '<=', $level)->where(function ($query)use($level){
                $query->where('published', '=', 1)
                      ->orWhere(function ($query) use ($level) {
                            $query->where('edit_level', '<=', $level);
                            if($level == 5){
                                $query->where('edit_level', '<>', 4);
                            }
                        });
            });
        })->orderBy('id', 'desc')->paginate(50);
        return view('submissions.index')->with('submissions', $submissions)->with('user', $user);
    }

    public function task(Task $task)
    {
        $level = auth()->user()->level;
        if(!($level >= $task->submit_level && ($task->published || ($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4))))){
            return abort(404);
        }
        $submissions = Submission::where('task_id', $task->id)->orderBy('id', 'desc')->paginate(50);
        return view('submissions.index')->with('submissions', $submissions)->with('task', $task);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Submission $submission)
    {
        $level = auth()->user()->level;
        $task = $submission->task;
        if(!($level >= $task->submit_level && ($task->published || ($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4))))){
            return abort(404);
        }
        return view('submissions.show')->with('task', $task)->with('submission', $submission)->with('level', $level);
    }

    public function rejudge(Submission $submission)
    {
        $level = auth()->user()->level;
        $task = $submission->task;
        if($level < $task->edit_level || !$task->published){
            return abort(404);
        }
        $submission->result = 'On Queue';
        $submission->score = 0;
        $submission->compiler_warning = '';
        $submission->save();
        $submission->runs()->delete();
        ProcessSubmission::dispatch($submission->id)->onQueue('code');
        return redirect("/submission/$submission->id")->with('success', 'Re-judging');;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
