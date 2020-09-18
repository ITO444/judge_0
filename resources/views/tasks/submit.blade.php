@extends('layouts.app')

@section('content')
    @include("tasks.top")
    <div class="row justify-content-center"><div class="col-md-8"><div class="card">
        <div class="card-header">Submit</div>
        <div class="card-body">
            {{Form::open(['action' => ['TasksController@saveSubmit', $task->task_id], 'method' => 'POST'])}}
            <div class='form-group'>
                {{Form::label('language', 'Language')}}
                {{Form::select('language', ['cpp' => 'C++', 'py' => 'Python'], 'cpp', ['class' => 'form-control'])}}
            </div>
            <div class="form-group">
                {{Form::label('code', 'Source code', ['class' => 'form-label'])}}
                <div id="editor" class="rounded"></div>
                {{Form::textarea('code', '', ['class' => 'form-control text-monospace', 'style' => 'display: none; height: 400px'])}}
            </div>
            <div class="form-group mb-0">
                {{Form::submit('Submit', ['class' => 'btn btn-success'])}} <a id='toggle' class='btn btn-secondary'>Toggle highlighting</a>
            </div>
            {{Form::close()}}
        </div>
    </div></div></div>
    <script src="/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
    <script>
        var language = "cpp";
        var editor = ace.edit("editor");
        var code = $('#code');
        editor.setTheme("ace/theme/twilight");
        editor.session.setMode("ace/mode/c_cpp");
    
        editor.getSession().on("change", function(){
            code.val(editor.getSession().getValue());
        });
    
        $("#toggle").click(function(){
            if(!code.is(":hidden")){
                editor.session.setValue(code.val());
            }else{
                code.val(editor.getSession().getValue());
            }
            $('#editor').toggle();
            code.toggle();
        });
    </script>
@endsection