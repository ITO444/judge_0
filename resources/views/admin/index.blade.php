@extends('layouts.app')

@section('pageTitle', 'Admin')

@section('content')
    <div class="row justify-content-center"><div class="col-md-8">
        <h3>Admin</h3>
        <div class="list-group">
            <a href="/admin/task" class="list-group-item list-group-item-action {{$level>=4?'':'disabled'}}">
                Create Task
            </a>
            <a href="/admin/users" class="list-group-item list-group-item-action {{$level>=5?'':'disabled'}}">
                Manage Users
            </a>
            <a href="/register" class="list-group-item list-group-item-action {{$level>=7?'':'disabled'}}">
                Create User
            </a>
            <a href="/admin/contest" class="list-group-item list-group-item-action {{$level>=6?'':'disabled'}}">
                Create Contest
            </a>
            <a href="/admin/lesson/cpp" class="list-group-item list-group-item-action {{$level>=6?'':'disabled'}}">
                Lesson
            </a>
            <a href="/admin/images" class="list-group-item list-group-item-action {{$level>=4?'':'disabled'}}">
                Images
            </a>
        </div>
        <br/>
        <div class="card">
            <div class="card-header">View as another user level</div>
            <div class="card-body">
                {{Form::open(['action' => ['AdminController@changeTempLevel'], 'method' => 'POST'])}}
                    {{Form::label("Choose level")}}
                    {{Form::selectRange("level", 0, $lowerLevel, $lowerLevel, ['class'=>'form-control'])}}
                    <br/>
                    <div class="form-group mb-0">
                        {{Form::submit('Go', ['class' => 'btn btn-primary'])}}
                    </div>
                {{Form::close()}}
            </div>
        </div>
    </div></div>
@endsection