@extends('layouts.app')

@section('content')
    <a href="/tasks" class="btn btn-secondary">Back</a><br/><br/>
    <h3>{{$task->title}}</h3>
    {{$task->task_id}}<br/>
    @if($myLevel >= $task->submit_level)
        <a href="/task/{{$task->id}}/submit" class="btn btn-primary">Submit</a>
        @if($myLevel >= $task->edit_level)
            <a href="/task/{{$task->id}}/edit" class="btn btn-primary">Edit</a>
            <a href="/task/{{$task->id}}/solution" class="btn btn-primary">Solution</a>
        @endif
    @endif
    <hr/>
    {{$task->statement}}
@endsection