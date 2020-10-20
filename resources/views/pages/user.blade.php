@extends('layouts.app')

@section('content')
    <h1><img src = "{{$user->avatar}}" width = "10%"/> {{$user->name}} - {{$user->display}}</h1>
    <hr/>
    <h2>Real name: {{$user->real_name}} <span class="badge badge-success">Solved: {{$user->solved}}</span> <a href="/submissions/user/{{$user->name}}" class="btn btn-primary">Submissions</a></h2>
@endsection