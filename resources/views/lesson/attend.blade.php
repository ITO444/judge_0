@extends('layouts.app')

@section('content')
<div class="row justify-content-center"><div class="col-md-8"><div class="card">
    <div class="card-header">Attend</div>
    <div class="card-body">
        {{Form::open(['action' => 'AdminController@saveAttend', 'method' => 'POST'])}}
        <div class='form-group'>
            {{Form::label('attendance', $attend)}}
            {{Form::select('attendance', [1 => 'Attend training', 0 => 'Leave training'], auth()->user()->attendance, ['class' => 'form-control'])}}
        </div>
        <br/>
        <div class="form-group mb-0">
            {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
        </div>
        {{Form::close()}}
    </div>
</div></div></div>
@endsection