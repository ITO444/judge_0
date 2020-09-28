<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Task;
use App\Test;
use App\Submission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessSubmission;

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
            "task_id" => ['required', 'string', "unique:tasks,task_id", "max:10"],
            "title" => ['required', 'string', "max:255"],
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
        $task->source_size = 128;
        $task->compile_time = 30;
        $task->runtime_limit = 1000;
        $task->memory_limit = 262144;
        $task->view_level = $myLevel;
        $task->edit_level = $myLevel;
        $task->submit_level = $myLevel;
        $task->task_type = 0;
        $task->date_created = $task->freshTimestamp();
        $task->author = auth()->user()->real_name;
        $task->origin = '';
        $task->statement = '';
        $task->grader = '';
        $task->grader_status = '';
        $task->solution = '';
        $task->save();
        return redirect("/task/$task->task_id/edit")->with('success', 'Task Created');
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
            "task_id" => ['required', 'string', "unique:tasks,task_id,$task->id", 'max:10'],
            "title" => ['required', 'string', 'max:255'],
            "source_size" => ['nullable', 'integer', "between:0, 128"],
            "compile_time" => ['nullable', 'integer', "between:0, 30"],
            "runtime_limit" => ['nullable', 'numeric', "between:0, 10"],
            "memory_limit" => ['nullable', 'integer', "between:0, 1048576"],
            "view_level" => ['nullable', 'integer', "between:1, $myLevel"],
            "submit_level" => ['nullable', 'integer', "between:1, $myLevel"],
            "edit_level" => ['nullable', 'integer', "between:4, $myLevel"],
            "task_type" => ['required', 'integer', "between:0, 1"],
            "date_created" => ['required', 'date'],
            "author" => ['nullable', 'string', 'max:255'],
            "origin" => ['nullable', 'string', 'max:255'],
            "statement" => ['nullable', 'string', 'max:65535'],
            "grader" => ['nullable', 'string', 'max:131072'],
            "solution" => ['nullable', 'string', 'max: 65535'],
        ]);
        if ($validator->fails()) {
            return redirect("/task/$task->task_id/edit")->withErrors($validator);
        }
        $task->task_id = $request["task_id"];
        $task->title = $request["title"];
        $task->source_size = $request["source_size"] ?: 128;
        $task->compile_time = $request["compile_time"] ?: 30;
        $task->runtime_limit = $request["runtime_limit"] ? (int)($request["runtime_limit"] * 1000) : 1000;
        $task->memory_limit = $request["memory_limit"] ?: 262144;
        $task->view_level = $request["view_level"] ?: $myLevel;
        $task->submit_level = $request["submit_level"] ?: $myLevel;
        if($task->submit_level < $task->view_level) $task->submit_level = $task->view_level;
        $task->edit_level = $request["edit_level"] ?: $myLevel;
        if($task->edit_level < $task->submit_level) $task->edit_level = $task->submit_level;
        $task->task_type = $request["task_type"];
        $task->date_created = $request["date_created"];
        $task->author = $request["author"] ?: '';
        $task->origin = $request["origin"] ?: '';
        $task->statement = $request["statement"] ?: '';
        $task->grader = $request["grader"] ?: '';
        $task->solution = $request["solution"] ?: '';
        $task->save();
        return redirect("/task/$task->task_id/edit")->with('success', 'Saved');
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
        $testChange = NULL;
        $input = "The input is not shown here as the file is over 1K, please remember to upload the input file even if you are just changing the output.";
        $output = "The output is not shown here as the file is over 1K, please remember to upload the output file even if you are just changing the input.";
        if($test){
            $testChange = $test->id;
            if($test->size('in') <= 1024){
                $input = Storage::get("tests/$test->id.in");
            }
            if($test->size('out') <= 1024){
                $output = Storage::get("tests/$test->id.out");
            }
        }
        return view('tasks.tests')->with('task', $task)->with('myLevel', $myLevel)->with('testChange', $testChange)->with('input', $input)->with('output', $output);
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
            "inputFile" => ['required_without:inputText', 'file', 'max:65536', 'mimes:txt'],
            "inputText" => ['nullable', 'string', 'max:67108864'],
            "outputFile" => ['required_without:outputText', 'file', 'max:65536', 'mimes:txt'],
            "outputText" => ['nullable', 'string', 'max:67108864'],
        ]);
        if ($validator->fails()) {
            return redirect("/task/$task->task_id/tests")->withErrors($validator);
        }
        $new = false;
        if(!$test){
            $test = new Test;
            $test->task_id = $task->id;
            $test->input_status = '';
            $test->output_status = '';
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
            return redirect("/task/$task->task_id/tests")->with('success', "Test case added");
        }
        return redirect("/task/$task->task_id/tests/$test->id")->with('success', "Test case changed");
    }

    public function deleteTest(Task $task, Test $test)
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
        Storage::delete(["$test->id.in", "$test->id.out"]);
        $test->delete();
        return redirect("/task/$task->task_id/tests")->with('success', 'Should I use green for a successful delete?')->with('error', 'Or should I use red since it\'s a delete?');
    }

    public function downloadTest(Task $task, int $testNumber, $ext)
    {
        if($ext !== 'in' && $ext !== 'out'){
            return abort(404);
        }
        $myLevel = auth()->user()->level;
        if($myLevel == 4){
            $myLevel = 7;
        }
        if($myLevel < $task->edit_level){
            return abort(404);
        }
        $tests = $task->tests;
        if($testNumber > $tests->count() || $testNumber <= 0){
            return abort(404);
        }
        $test = $tests->offsetGet($testNumber - 1);
        return Storage::download("tests/$test->id.$ext", $task->task_id."_".$testNumber.".".$ext);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function grader(Task $task)
    {
        $myLevel = auth()->user()->level;
        if($myLevel == 4){
            $myLevel = 7;
        }
        if($myLevel < $task->edit_level){
            return abort(404);
        }
        return view('tasks.grader')->with('task', $task)->with('myLevel', $myLevel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveGrader(Request $request, Task $task)
    {
        $myLevel = auth()->user()->level;
        if($myLevel == 4){
            $myLevel = 7;
        }
        if($myLevel < $task->edit_level){
            return abort(404);
        }
        $validator = Validator::make($request->all(), [
            "option" => ['required', 'integer', "between:0, 1"],
            "grader" => ['nullable', 'string', 'max:131072'],
        ]);
        if ($validator->fails()) {
            return redirect("/task/$task->task_id/grader")->withErrors($validator);
        }
        if($request->option){
            $task->grader = $request["grader"] ?: '';
            $task->grader_status = "Saved";
            $task->save();
        }else{
            $task->grader = $request["grader"] ?: '';
            $task->grader_status = "";
            $task->save();
        }
        return redirect("/task/$task->task_id/grader")->with('success', 'Saved');
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

    public function saveSubmit(Request $request, Task $task)
    {
        $myLevel = auth()->user()->level;
        if($myLevel == 4){
            $myLevel = 7;
        }
        if($myLevel < $task->submit_level){
            return abort(404);
        }
        $sourceSize = $task->source_size * 1024;
        $validator = Validator::make($request->all(), [
            "language" => ['required', 'string', "in:cpp,py"],
            "code" => ['nullable', 'string', "max:$sourceSize"],
        ]);
        if ($validator->fails()) {
            return redirect("/task/$task->task_id/submit")->withErrors($validator);
        }
        $submission = new Submission;
        $submission->user_id = auth()->user()->id;
        $submission->task_id = $task->id;
        $submission->language = $request['language'];
        $submission->result = '';
        $submission->score = 0;
        $submission->source_code = $request['code'] ?: '';
        $submission->compiler_warning = '';
        $submission->save();
        ProcessSubmission::dispatch($submission->id)->onQueue('code');
        return redirect("/task/$task->task_id/submit")->with('success', 'Submitted');
    }
}
