@extends('layouts.app')

@section('content')
    <a href="/" class="btn btn-secondary">Back</a><br/><br/>
    <h3>Admin</h3>
    <div class="list-group">
        <a href="/admin/task" class="list-group-item list-group-item-action {{$level>=4?:'disabled'}}">
            Create Task
        </a>
        <a href="/admin/users" class="list-group-item list-group-item-action {{$level>=5?:'disabled'}}">
            Manage Users
        </a>
        <a href="/register" class="list-group-item list-group-item-action {{$level>=7?:'disabled'}}">
            Create User
        </a>
        <a href="/admin/contest" class="list-group-item list-group-item-action {{$level>=8?:'disabled'}}">
            Create Contest
        </a>
    </div>
@endsection