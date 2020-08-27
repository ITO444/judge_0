@extends('layouts.app')

@section('content')
    <h1><img src = "{{$user->avatar}}" width = "10%"> {{$user->name}}</h1>
@endsection