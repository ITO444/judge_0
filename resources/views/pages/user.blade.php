@extends('layouts.app')

@section('content')
    <h1><img src = "{{$user->avatar}}" width = "10%"> {{$user->name}} - {{$user->display}}</h1>
    <h2>Real name: {{$user->real_name}}</h2>
@endsection