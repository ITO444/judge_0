@extends('layouts.app')

@section('pageTitle', 'Manage Lesson')

@section('content')
    <a href="/admin" class="btn btn-secondary">Back</a><br/><br/>
    <h3>Lesson</h3>
    @if(count($users) > 0)
        <br/>
        {{$users->links()}}
        <br/>
        <div class="">
            <div class="row">
                <div class="col">User</div>
                <div class="col">Answer</div>
                <div class="col">Code</div>
            </div>
            <hr/>
            @foreach($users as $user)
                <div class="row">
                    <div class="col">{{$user->name}} - {{$user->real_name}} - {{$user->display}}</div>
                    <div class="col overflow-auto" style="max-height:400px"><pre>{{$user->answer}}</pre></div>
                    <div class="col overflow-auto" style="max-height:400px"><pre>{{Storage::get("/usercode/$user->id/program.$language")}}</pre></div>
                </div>
                <hr/>
            @endforeach
        </div>
        {{$users->links()}}
    @else
        <p>No users attending</p>
    @endif
@endsection