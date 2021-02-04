@extends('layouts.app')

@section('pageTitle', "Runner")

@section('content')
<div class="container">
    <h1>Run code</h1>
    {!! Form::open(['id' => 'form', 'method' => 'post']) !!}
    @csrf
    <div class='form-group'>
        {{Form::label('language', 'Language')}}
        {{Form::select('language', ['cpp' => 'C++', 'py' => 'Python 3'], 'cpp', ['class' => 'form-control'])}}
    </div>
    <div class="row">
        <div class="col-md form-group">
            {{Form::label('code', 'Code')}}
            <div id='savestatus' class="d-inline text-muted"></div>
            <div id="editor" class="rounded editor">{{$code}}</div>
            {{Form::textarea('code', $code, ['class' => 'form-control text-monospace', 'style' => 'display: none; height: 400px'])}}
        </div>
        <div class="col-md form-group">
            {{Form::label('input', 'Input')}}
            {{Form::textarea('input', $input, ['class' => 'form-control text-monospace', 'style' => 'height: 400px'])}}
        </div>
    </div>
    {{Form::submit('Run', ['class' => 'btn btn-success'])}}
    <a id='toggle' class='btn btn-secondary'>Toggle highlighting</a>
    <div id='runstatus' class="d-inline text-muted">{{auth()->user()->runner_status/*?'Loading...':''*/}}</div>
    {!! Form::close() !!}
<pre id='result' class='text-monospace'>{{$output}}</pre>
@endsection

@push('scripts')
<script>
    var ace_language = "cpp";
    var ace_theme = "twilight";
    var user_id = "{{auth()->user()->id}}";
</script>
<script src="/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/dptj/editor.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/dptj/runner.js" type="text/javascript" charset="utf-8"></script>
@endpush