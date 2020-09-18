@extends('layouts.app')

@section('content')
@include("tasks.top")
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
            <div id="editor" class="rounded">{{$task->grader}}</div>
            {{Form::textarea('grader', $task->grader, ['class' => 'form-control text-monospace', 'style' => 'display: none; height: 400px'])}}
        </div>
        <div class="form-group mb-0">
            {{Form::submit('Save', ['class' => 'btn btn-primary'])}} <a id='toggle' class='btn btn-secondary'>Toggle highlighting</a>
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

    editor.getSession().on("change", function(){
        grader.val(editor.getSession().getValue());
    });

    $("#toggle").click(function(){
        if(!grader.is(":hidden")){
            editor.session.setValue(grader.val());
        }else{
            grader.val(editor.getSession().getValue());
        }
        $('#editor').toggle();
        grader.toggle();
    });
</script>
@endsection