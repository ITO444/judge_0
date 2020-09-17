@extends('layouts.app')

@section('content')
    @include("tasks.top")
    <h3>Solution</h3>
    <p>{{$task->solution}}</p>
@endsection