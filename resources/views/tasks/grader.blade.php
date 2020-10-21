@extends('layouts.app')

@section('content')
@include("tasks.top")
@if($task->published)
    <div class="alert alert-warning">Please unpublish to edit this task <a href="/task/{{$task->task_id}}/unpublish" class="btn btn-warning btn-sm float-right">Unpublish</a></div>
@elseif($level >= 6)
    <div class="alert alert-info">Please publish this task to enable submissions <a href="/task/{{$task->task_id}}/publish" class="btn btn-info btn-sm float-right">Publish</a></div>
@endif
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
            <div id="editor" class="rounded">{{$task->grader}}</div>
            {{Form::textarea('grader', $task->grader, ['class' => 'form-control text-monospace', 'style' => 'display: none; height: 400px'])}}
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
<script src="/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var language = "cpp";
    var editor = ace.edit("editor");
    var grader = $('#grader');
    editor.setTheme("ace/theme/twilight");
    editor.session.setMode("ace/mode/c_cpp");

    $("#toggle").click(function(){
        if(!grader.is(":hidden")){
            editor.session.setValue(grader.val());
        }else{
            grader.val(editor.getSession().getValue());
        }
        $('#editor').toggle();
        grader.toggle();
    });

    $('form').on('submit', function(e){
        if(grader.is(":hidden")){
            grader.val(editor.getSession().getValue());
        }
    });
</script>
@endsection