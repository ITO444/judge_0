@extends('layouts.app')

@section('pageTitle', "$task->task_id - $task->title")

@section('content')
    <div class="row justify-content-center"><div class="col-md-10">
        @include("tasks.top")
        {!!$task->solution!!}
    </div></div>
@endsection