@extends('layouts.app')

@section('content')
<div class="row justify-content-center"><div class="col-md-8"><div class="card">
    <div class="card-header">New Contest</div>
    <div class="card-body">
        {{Form::open(['action' => 'ContestsController@store', 'method' => 'POST'])}}
        <div class="row form-group">
            {{Form::label('contest_id', 'Contest ID', ['class' => 'col-md-4 col-form-label text-md-right'])}}
            <div class="col-md-6">
                {{Form::text("contest_id", '', ['class' => 'form-control'])}}
            </div>
        </div>
        <div class="row form-group">
            {{Form::label('name', 'Name', ['class' => 'col-md-4 col-form-label text-md-right'])}}
            <div class="col-md-6">
                {{Form::text("name", '', ['class' => 'form-control'])}}
            </div>
        </div>
        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
                {{Form::submit('Create', ['class' => 'btn btn-primary'])}}
            </div>
        </div>
        {{Form::close()}}
    </div>
</div></div></div>
@endsection