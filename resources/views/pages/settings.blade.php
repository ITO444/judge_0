@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Settings</div>
    <div class="card-body">
    {{Form::open(['action' => ['PagesController@saveSettings', $user->id], 'method' => 'POST'])}}
        <div class="row form-group">
            <div class="col-md-4 col-form-label text-md-right">
                Username
            </div>
            <div class="col-md-6">
                {{Form::text("name", $user->name, ['class' => 'form-control', 'disabled'])}}
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-4 col-form-label text-md-right">
                Real Name
            </div>
            <div class="col-md-6">
                {{Form::text("real_name", $user->real_name, ['class' => 'form-control', 'disabled'])}}
            </div>
        </div>
        <div class="row form-group">
            {{Form::label('display', 'Display name', ['class' => 'col-md-4 col-form-label text-md-right'])}}
            <div class="col-md-6">
                {{Form::text("display", $user->display, ['class' => 'form-control', $user->level <= 1 ? 'disabled' : ''])}}
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-4 col-form-label text-md-right">
                Email
            </div>
            <div class="col-md-6">
                {{Form::text("email", $user->email, ['class' => 'form-control', 'disabled'])}}
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-4 col-form-label text-md-right">
                Account Type
            </div>
            <div class="col-md-6">
                {{Form::text("type", $user->google_id?"DGS":"Others", ['class' => 'form-control', 'disabled'])}}
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-4 col-form-label text-md-right">
                User Level
            </div>
            <div class="col-md-6">
                {{Form::number("type", $user->level, ['class' => 'form-control', 'disabled'])}}
            </div>
        </div>
        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
                {{Form::submit('Save', ['class' => 'btn btn-primary', $user->level <= 1 ? 'disabled' : ''])}}
            </div>
        </div>
    {{Form::close()}}
    </div>
</div>
@endsection