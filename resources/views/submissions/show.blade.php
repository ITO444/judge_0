@extends('layouts.app')

@section('content')
    <h1>Submission {{$submission->id}}</h1>
    <div class="card"><div class="card-body"><div class="row text-center">
        <div class="col"><a href="/submission/{{$submission->id}}">{{$submission->created_at}}</a></div>
        <div class="col"><a href="/user/{{$submission->user->name}}">{{$submission->user->name}} - {{$submission->user->display}}</a></div>
        <div class="col"><a href="/task/{{$submission->task->task_id}}">{{$submission->task->title}}</a></div>
        <div class="col">{{$submission->language}}</div>
        <div class="col{{$submission->result == 'Accepted' ? ' text-success font-weight-bold' : ''}}">{{$submission->result}}</div>
        <div class="col">{{$submission->runs->max('runtime') / 1000}}</div>
    </div></div></div><hr/>
    @if($myLevel >= $submission->task->edit_level || $submission->user->id == auth()->user()->id)
        Compiler message:
        <pre class="alert alert-info">{{$submission->compiler_warning}}</pre><br/>
    @endif
    <div class="table-responsive"><table class="table table-striped table-bordered table-hover text-nowrap">
        <thead><tr>
            <th class="text-center">Test</th>
            <th class="text-center">Result</th>
            <th class="text-center">Runtime</th>
            <th class="text-center">Memory</th>
            <th class="text-center">Score</th>
            @if($myLevel >= $submission->task->edit_level)
            <th>Grader Feedback</th>
            @endif
        </tr></thead><tbody>
    @foreach($submission->runs as $run)
        <tr>
            <td class="text-center">{{$loop->iteration}}</td>
            <td class="text-center{{$submission->result == 'Accepted' ? ' text-success font-weight-bold' : ''}}">{{$submission->result}}</td>
            <td class="text-center">{{$run->runtime / 1000}}</td>
            <td class="text-center">{{number_format($run->memory / 1024, 3)}}</td>
            <td class="text-center">{{$run->score / 100}}</td>
            @if($myLevel >= $submission->task->edit_level)
            <td><pre class="text-monospace">{{$run->grader_feedback}}</pre></td>
            @endif
        </tr>
    @endforeach
    </tbody></table></div>
    @if($myLevel >= $submission->task->edit_level || $submission->user->id == auth()->user()->id)
    <hr/>
    <div id="editor" class="rounded">{{$submission->source_code}}</div>
    <textarea id='code' class="form-control text-monospace" style="display: none; height: 400px">{{$submission->source_code}}</textarea>
    <br/><a id='toggle' class='btn btn-secondary'>Toggle highlighting</a>
    <script src="/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
    <script>
        var ace_modes = {"cpp": "c_cpp", "py": "python"};
        var editor = ace.edit("editor");
        var code = $('#code');
        editor.setTheme("ace/theme/twilight");
        editor.session.setMode("ace/mode/" + ace_modes["{{$submission->language}}"]);

        $("#toggle").click(function(){
            if(!code.is(":hidden")){
                editor.session.setValue(code.val());
            }else{
                //code.val(editor.getSession().getValue());
            }
            $('#editor').toggle();
            code.toggle();
        });
    </script>
    @endif
@endsection