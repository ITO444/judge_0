<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Task;
use App\Test;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $myLevel = auth()->user()->level;
        if($myLevel == 4){
            $myLevel = 7;
        }
        $tasks = Task::paginate(50);
        return view('tasks.index')->with('tasks', $tasks)->with('myLevel', $myLevel);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "task_id" => ['required', 'string', "unique:tasks,task_id"],
            "title" => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return redirect('/admin/task')->withErrors($validator);
        }
        $myLevel = auth()->user()->level;
        if($myLevel == 4){
            $myLevel = 7;
        }
        $task = new Task;
        $task->task_id = $request['task_id'];
        $task->title = $request['title'];
        $task->source_size = 4096;
        $task->compile_time = 30;
        $task->runtime_limit = 1000;
        $task->memory_limit = 262144;
        $task->output_limit = 32768;
        $task->view_level = $myLevel;
        $task->edit_level = $myLevel;
        $task->submit_level = $myLevel;
        $task->task_type = 0;
        $task->date_created = $task->freshTimestamp();
        $task->origin = '';
        $task->statement = '';
        $task->checker = '';
        $task->solution = '';
        $task->save();
        return redirect("/task/$task->id/edit")->with('success', 'Task Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        $myLevel = auth()->user()->level;
        if($myLevel == 4){
            $myLevel = 7;
        }
        if($myLevel < $task->view_level){
            return abort(404);
        }
        return view('tasks.show')->with('task', $task)->with('myLevel', $myLevel);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $myLevel = auth()->user()->level;
        if($myLevel == 4){
            $myLevel = 7;
        }
        if($myLevel < $task->edit_level){
            return abort(404);
        }
        return view('tasks.edit')->with('task', $task)->with('myLevel', $myLevel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $myLevel = auth()->user()->level;
        if($myLevel == 4){
            $myLevel = 7;
        }
        if($myLevel < $task->edit_level){
            return abort(404);
        }
        $validator = Validator::make($request->all(), [
            "task_id" => ['required', 'string', "unique:tasks,task_id,$task->id"],
            "title" => ['required', 'string'],
            "source_size" => ['nullable', 'integer', "between:0, 4096"],
            "compile_time" => ['nullable', 'integer', "between:0, 30"],
            "runtime_limit" => ['nullable', 'numeric', "between:0, 10"],
            "memory_limit" => ['nullable', 'integer', "between:0, 1048576"],
            "output_limit" => ['nullable', 'integer', "between:0, 65536"],
            "view_level" => ['nullable', 'integer', "between:1, $myLevel"],
            "submit_level" => ['nullable', 'integer', "between:1, $myLevel"],
            "edit_level" => ['nullable', 'integer', "between:4, $myLevel"],
            "task_type" => ['required', 'integer', "between:0, 1"],
            "date_created" => ['required', 'date'],
            "origin" => ['nullable', 'string'],
            "statement" => ['nullable', 'string'],
            "checker" => ['nullable', 'string'],
            "solution" => ['nullable', 'string'],
        ]);
        if ($validator->fails()) {
            return redirect("/task/$task->id/edit")->withErrors($validator);
        }
        $task->task_id = $request["task_id"];
        $task->title = $request["title"];
        $task->source_size = $request["source_size"] ?: 4096;
        $task->compile_time = $request["compile_time"] ?: 30;
        $task->runtime_limit = $request["runtime_limit"] ? (int)($request["runtime_limit"] * 1000) : 1000;
        $task->memory_limit = $request["memory_limit"] ?: 262144;
        $task->output_limit = $request["output_limit"] ?: 32768;
        $task->view_level = $request["view_level"] ?: $myLevel;
        $task->submit_level = $request["submit_level"] ?: $myLevel;
        if($task->submit_level < $task->view_level) $task->submit_level = $task->view_level;
        $task->edit_level = $request["edit_level"] ?: $myLevel;
        if($task->edit_level < $task->submit_level) $task->edit_level = $task->submit_level;
        $task->task_type = $request["task_type"];
        $task->date_created = $request["date_created"];
        $task->origin = $request["origin"] ?: '';
        $task->statement = $request["statement"] ?: '';
        $task->checker = $request["checker"] ?: '';
        $task->solution = $request["solution"] ?: '';
        $task->save();
        return redirect("/task/$task->id/edit")->with('success', 'Saved');
    }

    public function submit(Task $task)
    {
        $myLevel = auth()->user()->level;
        if($myLevel == 4){
            $myLevel = 7;
        }
        if($myLevel < $task->submit_level){
            return abort(404);
        }
        return view('tasks.submit')->with('task', $task)->with('myLevel', $myLevel);
    }

    public function solution(Task $task)
    {
        $myLevel = auth()->user()->level;
        if($myLevel == 4){
            $myLevel = 7;
        }
        if($myLevel < $task->edit_level){
            return abort(404);
        }
        return view('tasks.solution')->with('task', $task)->with('myLevel', $myLevel);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tests(Task $task, Test $test = NULL)
    {
        $myLevel = auth()->user()->level;
        if($myLevel == 4){
            $myLevel = 7;
        }
        if($test && $test->task->id != $task->id){
            return abort(404);
        }
        if($myLevel < $task->edit_level){
            return abort(404);
        }
        return view('tasks.tests')->with('task', $task)->with('myLevel', $myLevel)->with('testChange', $test?$test->id:NULL);
    }

    public function saveTest(Request $request, Task $task, Test $test = NULL)
    {
        $myLevel = auth()->user()->level;
        if($myLevel == 4){
            $myLevel = 7;
        }
        if($test && $test->task->id != $task->id){
            return abort(404);
        }
        if($myLevel < $task->edit_level){
            return abort(404);
        }
        $validator = Validator::make($request->all(), [
            "inputFile" => ['nullable', 'file', 'max:65536', 'mimes:txt'],
            "inputText" => ['nullable', 'string', 'max:67108864'],
            "outputFile" => ['nullable', 'file', 'max:65536', 'mimes:txt'],
            "outputText" => ['nullable', 'string', 'max:67108864'],
        ]);
        if ($validator->fails()) {
            return redirect("/task/$task->id/tests")->withErrors($validator);
        }
        $new = false;
        if(!$test){
            $test = new Test;
            $test->task_id = $task->id;
            $test->save();
            $new = true;
        }else $test->touch();
        if($request["inputFile"]){
            Storage::putFileAs('tests', $request["inputFile"], "$test->id.in");
        }else{
            Storage::put("tests/$test->id.in", $request["inputText"]);
        }
        if($request["outputFile"]){
            Storage::putFileAs('tests', $request["outputFile"], "$test->id.out");
        }else{
            Storage::put("tests/$test->id.out", $request["outputText"]);
        }
        if($new){
            return redirect("/task/$task->id/tests")->with('success', "Test case added");
        }
        return redirect("/task/$task->id/tests/$test->id")->with('success', "Test case changed");
    }

    public function deleteTest(Task $task, Test $test = NULL)
    {
        $myLevel = auth()->user()->level;
        if($myLevel == 4){
            $myLevel = 7;
        }
        if($test && $test->task->id != $task->id){
            return abort(404);
        }
        if($myLevel < $task->edit_level){
            return abort(404);
        }
        return redirect("/task/$task->id/tests")->with('error', 'ITO hasn\'t implemented this yet');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }
}
