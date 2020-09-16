@extends('layouts.app')

@section('content')
    @include("tasks.top")
    {!!$task->statement!!}
@endsection