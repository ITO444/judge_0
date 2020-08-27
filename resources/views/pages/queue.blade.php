@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Do something</h1>
    {!! Form::open(['id' => 'form', 'method' => 'post']) !!}
        <div class="col form-group">
            {{Form::label('input', 'Input')}}
            {{Form::textarea('input', null, ['class' => 'form-control'])}}
            {{Form::number('sleep')}}
        </div>
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
    <div>
        Output:<br>
        <pre>{{$output}}</pre>
    </div>
</div>
@endsection