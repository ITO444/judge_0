@extends('layouts.app')

@section('pageTitle', 'Manage Users')

@section('content')
    <a href="/admin" class="btn btn-secondary">Back</a><br/><br/>
    <h3>Users</h3>
    <div class="card">
        <div class="card-header btn" type="button" data-toggle="collapse" data-target="#collapse" aria-expanded="false" aria-controls="collapse">
            Sort and Filter (dummy, not implemented yet) <span class="dropdown-toggle float-right"></span>
        </div>
        <div class="collapse" id="collapse">
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
    </div>
    @if(count($users) > 0)
        <br/>
        {{$users->links()}}
        <br/>
        <div class="">
            <div class="row">
                <div class="col">Username</div>
                <div class="col">Real Name</div>
                <div class="col">Display Name</div>
                <div class="col">Email</div>
                <div class="col">Account Type</div>
                <div class="col">User Level</div>
                <div class="col">Save</div>
            </div>
            <hr/>
            @foreach($users as $user)
                @if(auth()->user()->level <= $user->level || (auth()->user()->level <= 5 && !$user->google_id))
                    <div class="row form-group">
                        <div class="col">{{Form::text("name", $user->name, ['class'=>'form-control', 'readonly'])}}</div>
                        <div class="col">{{Form::text("real_name", $user->real_name, ['class'=>'form-control', 'readonly'])}}</div>
                        <div class="col">{{Form::text("display", $user->display, ['class'=>'form-control', 'readonly'])}}</div>
                        <div class="col">{{Form::email("email", $user->email, ['class'=>'form-control', 'readonly'])}}</div>
                        <div class="col">{{Form::text("type", $user->google_id ? "DGS" : "Others", ['class'=>'form-control', 'disabled'])}}</div>
                        <div class="col">{{Form::number("level", 0, auth()->user()->level, $user->level, ['class'=>'form-control', 'readonly'])}}</div>
                        <div class="col">{{Form::submit('Go', ['class' => 'btn btn-primary disabled', 'disabled'])}}</div>
                    </div>
                @else
                    {{Form::open(['action' => ['AdminController@saveUser', $user->name], 'method' => 'POST', 'class' => 'row form-group'])}}
                        <div class="col">{{Form::text("name", $user->name, ['class'=>'form-control'])}}</div>
                        <div class="col">{{Form::text("real_name", $user->real_name, ['class'=>'form-control'])}}</div>
                        <div class="col">{{Form::text("display", $user->display, ['class'=>'form-control'])}}</div>
                        <div class="col">{{Form::email("email", $user->email, ['class'=>'form-control'])}}</div>
                        <div class="col">{{Form::text("type", $user->google_id ? "DGS" : "Others", ['class'=>'form-control', 'disabled'])}}</div>
                        <div class="col">{{Form::selectRange("level", 0, auth()->user()->level, $user->level, ['class'=>'form-control'])}}</div>
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