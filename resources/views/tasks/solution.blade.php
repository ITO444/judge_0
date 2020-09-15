@extends('layouts.app')

@section('content')
    <a href="/tasks{{$task->id}}" class="btn btn-secondary">Back</a><br/><br/>
    <h3>Solution</h3>
    <h5>{{$task->title}}</h5>
    {{$task->task_id}}<br/>
    <a href="/task/{{$task->id}}" class="btn btn-primary">View</a>
    <a href="/task/{{$task->id}}/submit" class="btn btn-primary">Submit</a>
    <a href="/task/{{$task->id}}/edit" class="btn btn-primary">Edit</a>
    <hr/>
    {{$task->solution}}
@endsection