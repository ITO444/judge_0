@extends('layouts.app')

@section('pageTitle', "$task->task_id - $task->title")

@section('content')
@include("tasks.top")
@include("tasks.publish_warning")
<div class="row justify-content-center"><div class="col-md-8"><div class="card">
    <div class="card-header">Edit grader</div>
    <div class="card-body">
        {{Form::open(['action' => ['TasksController@saveGrader', $task->task_id], 'method' => 'POST'])}}
        <div class='form-group'>
            {{Form::label('option', 'Grader Options')}}
            {{Form::select('option', [0 => 'Use default grader', 1 => 'Custom grader'], $task->grader_status?1:0, ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('grader', 'Grader', ['class' => 'form-label'])}}
            <div class="d-inline text-muted">{{$task->grader_status}}</div>
            <div id="editor" class="rounded editor">{{$task->grader}}</div>
            {{Form::textarea('grader', $task->grader, ['class' => 'form-control text-monospace', 'style' => 'display: none; height: 400px', 'id' => 'code'])}}
        </div>
        <div class="form-group mb-0">
            @if($task->published)
            {{Form::submit('Save', ['class' => 'btn btn-primary disabled', 'disabled'])}}
            @else
            {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
            @endif
            <a id='toggle' class='btn btn-secondary'>Toggle highlighting</a>
        </div>
        {{Form::close()}}
    </div>
</div></div></div>
@endsection

@push('scripts')
<script>
    var ace_language = "cpp";
    var ace_theme = "twilight";
</script>
<script src="/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/dptj/editor.js" type="text/javascript" charset="utf-8"></script>
@endpush