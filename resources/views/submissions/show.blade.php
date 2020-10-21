@extends('layouts.app')

@section('content')
    <h1>
        Submission {{$submission->id}}
        @if($level >= 6 && $level >= $task->edit_level && $task->published)
        <a class="btn btn-primary float-right" onclick="rj()">Re-judge</a>
        @endif
    </h1>
    <div class="card"><div class="card-body"><div class="row text-center">
        <div class="col"><a href="/submission/{{$submission->id}}">{{$submission->created_at}}</a></div>
        <div class="col"><a href="/user/{{$submission->user->name}}">{{$submission->user->name}} - {{$submission->user->display}}</a></div>
        <div class="col"><a href="/task/{{$task->task_id}}">{{$task->title}}</a></div>
        <div class="col">{{$submission->language == 'cpp' ? "C++" : "Python 3"}}</div>
        <div class="col{{$submission->result == 'Accepted' ? ' text-success font-weight-bold' : ''}}">{{$submission->result}}</div>
        @if($submission->getAttributes()['result'] >= 0) 
            <div class="col">Runtime: {{number_format($submission->runs->max('runtime') / 1000, 3)}} s</div>
            <div class="col">Memory: {{number_format($submission->runs->max('memory') / 1024, 3)}} MB</div>
            <div class="col">Score: {{number_format($submission->score / 1000, 3)}}</div>
        @endif
    </div></div></div><hr/>
    @if($submission->user->id == auth()->user()->id || ($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4) && (!$task->published || $level >= 6)))
        <h6>Compiler message:</h6>
        <pre class="alert alert-info">{{$submission->compiler_warning}}</pre><br/>
    @endif
    @if(count($submission->runs) > 0)
    <div class="table-responsive"><table class="table table-striped table-bordered table-hover text-nowrap">
        <thead><tr>
            <th class="text-center">Test</th>
            <th class="text-center">Result</th>
            <th class="text-center">Runtime</th>
            <th class="text-center">Memory</th>
            <th class="text-center">Score</th>
            @if($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4) && (!$task->published || $level >= 6))
            <th>Grader Feedback</th>
            @endif
        </tr></thead><tbody>
    @foreach($submission->runs as $run)
        <tr>
            <td class="text-center">{{$loop->iteration}}</td>
            <td class="text-center{{$run->result == 'Accepted' ? ' text-success font-weight-bold' : ''}}">{{$run->result}}</td>
            <td class="text-center">{{number_format($run->runtime / 1000, 3)}}</td>
            <td class="text-center">{{number_format($run->memory / 1024, 3)}}</td>
            <td class="text-center">{{number_format($run->score / 1000, 3)}}</td>
            @if($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4) && (!$task->published || $level >= 6))
            <td><pre class="text-monospace">{{$run->grader_feedback}}</pre></td>
            @endif
        </tr>
    @endforeach
    </tbody></table></div>
    @endif
    @if($submission->user->id == auth()->user()->id || $task->doneBy(auth()->user()) || ($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4) && (!$task->published || $level >= 6)))
    <hr/>
    <div id="editor" class="rounded">{{$submission->source_code}}</div>
    <textarea id='code' class="form-control text-monospace" style="display: none; height: 400px">{{$submission->source_code}}</textarea>
    <br/><a id='toggle' class='btn btn-secondary'>Toggle highlighting</a>
    {{Form::open(['action' => ['SubmissionsController@rejudge', $submission->id], 'method' => 'delete', 'id' => "rejudge"])}} {{Form::close()}}
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

        function rj(){
            var rjForm = $('#rejudge');
            if(confirm('Are you sure you want to re-judge this submission?')) {
                rjForm.submit();
            }
        }
    </script>
    @endif
@endsection