@extends('layouts.app')

@section('content')
    <a href="/tasks{{$task->id}}" class="btn btn-secondary">Back</a><br/><br/>
    <h3>Submit</h3>
    <h5>{{$task->title}}</h5>
    {{$task->task_id}}<br/>
    <a href="/task/{{$task->id}}" class="btn btn-primary">View</a>
    @if($myLevel >= $task->edit_level)
        <a href="/task/{{$task->id}}/edit" class="btn btn-primary">Edit</a>
        <a href="/task/{{$task->id}}/solution" class="btn btn-primary">Solution</a>
    @endif
    <hr/>
    Working in progress
@endsection