@extends('layouts.app')

@section('pageTitle', "Answer")

@section('content')
<div class="row justify-content-center"><div class="col-md-8"><div class="card">
    <div class="card-header">Answer</div>
    <div class="card-body">
        {{Form::open(['action' => 'AdminController@saveAnswer', 'method' => 'POST'])}}
        <div class="form-group">
            {{Form::textarea('answer', auth()->user()->answer, ['class' => 'form-control text-monospace'])}}
        </div>
        <div class="form-group mb-0">
            {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
        </div>
        {{Form::close()}}
    </div>
</div></div></div>
@endsection