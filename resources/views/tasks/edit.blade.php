@extends('layouts.app')

@section('content')
    @include("tasks.top")
    <h3>Edit Task</h3>
    {{Form::open(['action' => ['TasksController@update', $task->id], 'method' => 'POST'])}}
    <div class="row form-group">
        {{Form::label('task_id', 'Task ID', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::text("task_id", $task->task_id, ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="row form-group">
        {{Form::label('title', 'Title', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::text("title", $task->title, ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="row form-group">
        {{Form::label('source_size', 'Source Size Limit (K)', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::number("source_size", $task->source_size, ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="row form-group">
        {{Form::label('compile_time', 'Compile Time Limit (s)', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::number("compile_time", $task->compile_time, ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="row form-group">
        {{Form::label('runtime_limit', 'Run Time Limit (s, up to 3 dp)', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::text("runtime_limit", $task->runtime_limit / 1000, ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="row form-group">
        {{Form::label('memory_limit', 'Memory Limit (K)', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::number("memory_limit", $task->memory_limit, ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="row form-group">
        {{Form::label('output_limit', 'Output Size Limit (K)', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::number("output_limit", $task->output_limit, ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="row form-group">
        {{Form::label('view_level', 'View Level', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::selectRange("view_level", 1, $myLevel, $task->view_level, ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="row form-group">
        {{Form::label('submit_level', 'Submit Level', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::selectRange("submit_level", 1, $myLevel, $task->submit_level, ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="row form-group">
        {{Form::label('edit_level', 'Edit Level', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::selectRange("edit_level", 4, $myLevel, $task->edit_level, ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="row form-group">
        {{Form::label('task_type', 'Task Type', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::select("task_type", [0 => 'Batch', 1 => 'Interative'], $task->task_type, ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="row form-group">
        {{Form::label('date_created', 'Date Created', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::date("date_created", $task->date_created, ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="row form-group">
        {{Form::label('origin', 'Origin', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::text("origin", $task->origin, ['class' => 'form-control'])}}
        </div>
    </div>
    
    <div class="row form-group">
        {{Form::label('statement', 'Statement', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::textarea("statement", $task->statement, ['class' => 'form-control'])}}
        </div>
    </div>
    
    <div class="row form-group">
        {{Form::label('checker', 'Checker', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::textarea("checker", $task->checker, ['class' => 'form-control'])}}
        </div>
    </div>
    
    <div class="row form-group">
        {{Form::label('solution', 'Solution', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::textarea("solution", $task->solution, ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-4 col-form-label text-md-right">
            Test Cases
        </div>
        <div class="col-md-6 col-form-label">
            {{$task->tests()->count()}}
        </div>
    </div>
    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
            <a href="/task/{{$task->id}}/tests" class="btn btn-secondary">Manage test data</a>
        </div>
    </div>
    {{Form::close()}}
@endsection