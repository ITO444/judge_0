@extends('layouts.app')

@section('content')
    <h1><img src = "{{$user->avatar}}" width = "10%"> {{$user->name}} - {{$user->display}}</h1>
@endsection