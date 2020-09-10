@extends('layouts.app')

@section('content')
    <a href="/" class="btn btn-secondary">Back</a><br/><br/>
    <h3>Users</h3>
    <div class="card">
        <div class="card-header">
            Sort and Filter (dummy, not implemented yet)
        </div>
        <div class="card-body">
            {!! Form::open([/*'action' => '', */'method' => 'GET']) !!}
                <h4>Sort</h4>
                <select id="order1" name="order1" class="form-control">
                    <option value="id">Default</option>
                    <option value="username">Username</option>
                    <option value="level">Admin Level</option>
                </select>
                <select id="order2" name="order2" class="form-control">
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
                <h4>Filter</h4>
                <select id="filter" name="filter" class="form-control">
                    <option value="all">All</option>
                    <option value="dgs">DGS</option>
                    <option value="others">Others</option>
                </select>
            <input name="page" type="hidden" value={{'$page'}}>
                {!!Form::submit('Go', ['class' => 'btn btn-primary'])!!}
            {!! Form::close() !!}
        </div>
    </div>
    @if(count($users) > 0)
        <br/>
        {{$users->links()}}
        <br/>
        <div class="">
            <div class="row">
                <div class="col">Username</div>
                <div class="col">Display name</div>
                <div class="col">Email</div>
                <div class="col">Account Type</div>
                <div class="col">User Level</div>
                <div class="col">Save</div>
            </div>
            @foreach($users as $user)
                @if(auth()->user()->level <= $user->level)
                    <div class="row">
                        <div class="col">{{$user->name}}</div>
                        <div class="col">{{$user->display}}</div>
                        <div class="col text-truncate">{{$user->email}}</div>
                        <div class="col">{{$user->google_id?"DGS":"Others"}}</div>
                        <div class="col">{{$user->level}}</div>
                        <div class="col"><button type="button" class="btn btn-secondary" disabled>Go</button></div>
                    </div>
                @else
                    {{Form::open(['action' => ['AdminController@saveUser', $user->id], 'method' => 'POST', 'class' => 'row'])}}
                        <div class="col">{{Form::text("name", $user->name, ['class'=>'w-100'])}}</div>
                        <div class="col">{{Form::text("display", $user->display, ['class'=>'w-100'])}}</div>
                        <div class="col text-truncate">{{$user->email}}</div>
                        <div class="col">{{$user->google_id?"DGS":"Others"}}</div>
                        <div class="col">{{Form::text("level", $user->level, ['class'=>'w-100'])}}</div>
                        <div class="col">{{Form::submit('Go', ['class' => 'btn btn-primary'])}}</div>
                    {{Form::close()}}
                @endif
            @endforeach
        </div>
        {{$users->links()}}
    @else
        <p>No users found</p>
    @endif
@endsection