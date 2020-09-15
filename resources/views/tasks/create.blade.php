@extends('layouts.app')

@section('content')
    <h3>New Task</h3>
    {{Form::open(['action' => 'TasksController@store', 'method' => 'POST'])}}
    <div class="row form-group">
        {{Form::label('task_id', 'Task ID', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::text("task_id", '', ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="row form-group">
        {{Form::label('title', 'Title', ['class' => 'col-md-4 col-form-label text-md-right'])}}
        <div class="col-md-6">
            {{Form::text("title", '', ['class' => 'form-control'])}}
        </div>
    </div>
    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
        </div>
    </div>
    {{Form::close()}}
@endsection