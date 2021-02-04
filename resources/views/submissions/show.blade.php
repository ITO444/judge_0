@extends('layouts.app')

@section('content')
    <h1>
        Submission {{$submission->id}}
        @if($level >= 6 && $level >= $task->edit_level && $task->published && $user->contestNow() == null)
        <a class="btn btn-primary float-right" id="rejudge-button">Re-judge</a>
        @endif
    </h1>
    <div class="card"><div class="card-body"><div class="row text-center">
        <div class="col"><a href="/submission/{{$submission->id}}">{{$submission->created_at}}</a></div>
        <div class="col"><a href="/user/{{$submission->user->name}}">{{$submission->user->name}} - {{$submission->user->display}}</a></div>
        <div class="col"><a href="/task/{{$task->task_id}}">{{$task->title}}</a></div>
        @if($submission->participation != null)
        <div class="col"><a href="/contest/{{$submission->participation->contest->contest_id}}">{{$submission->participation->contest->name}}</a></div>
        @endif
        <div class="col">{{$submission->language == 'cpp' ? "C++" : "Python 3"}}</div>
        @if(!(isset($noFeedback) && $noFeedback))
        <div class="col{{$submission->result == 'Accepted' ? ' text-success font-weight-bold' : ''}}">{{$submission->result}}</div>
        @if($submission->getAttributes()['result'] >= 0)
            @if($submission->participation == null || $submission->getAttributes()['result'] == 0)
            <div class="col">Runtime: {{number_format($submission->runs->max('runtime') / 1000, 3)}} s</div>
            <div class="col">Memory: {{number_format($submission->runs->max('memory') / 1024, 3)}} MB</div>
            @endif
            <div class="col">Score: {{$submission->score / 1000}}</div>
            @if($submission->participation != null)
            <div class="col">Cumulative Score: {{$submission->participation->information['tasks'][$submission->task_id]['score'] / 1000}}</div>
            @endif
        @endif
        @endif
    </div></div></div>
    @if(!(isset($noFeedback) && $noFeedback))
        <hr/>
        @if($submission->user->id == $user->id || ($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4) && (!$task->published || $level >= 6)))
            <h6>Compiler message:</h6>
            <pre class="alert alert-info">{{$submission->compiler_warning}}</pre><br/>
        @endif
        @if($submission->participation != null && ($submission->participation->user_id == $user->id || ($level >= $submission->participation->contest->edit_level && $level >= 7)) && $submission->getAttributes()['result'] >= 0)
        <table class="table table-striped table-bordered table-hover text-nowrap">
            <thead><tr>
                <th class="text-center">Subtask</th>
                <th class="text-center">Result</th>
                <th class="text-center">Score</th>
                <th class="text-center">Max</th>
            </tr></thead><tbody>
        @foreach($submission->subtaskScores()['subtasks'] as $subtask => $score)
            <tr>
                <td class="text-center">{{$subtask}}</td>
                <td class="text-center{{$verdicts[$subtask] == 'Accepted' ? ' text-success font-weight-bold' : ''}}">{{$verdicts[$subtask]}}</td>
                <td class="text-center">{{$score / 1000}}</td>
                <td class="text-center">{{$submission->participation->contest->configuration['tasks'][$task->id]['subtasks'][$subtask]}}</td>
            </tr>
        @endforeach
        </tbody></table>
        @endif
        @if($submission->participation == null || ($level >= $submission->participation->contest->edit_level && $level >= 7 && $user->contestNow() == null))
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
                <td class="text-center">{{$run->score / 1000}}</td>
                @if($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4) && (!$task->published || $level >= 6))
                <td><pre class="text-monospace">{{$run->grader_feedback}}</pre></td>
                @endif
            </tr>
        @endforeach
        </tbody></table></div>
        @endif
    @endif
    @if($submission->user->id == $user->id || $task->doneBy($user) || ($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4) && (!$task->published || $level >= 6)))
    <hr/>
    <div id="editor" class="rounded editor">{{$submission->source_code}}</div>
    <textarea id='code' class="form-control text-monospace" style="display: none; height: 400px">{{$submission->source_code}}</textarea>
    <br/><a id='toggle' class='btn btn-secondary'>Toggle highlighting</a>
    {{Form::open(['action' => ['SubmissionsController@rejudge', $submission->id], 'method' => 'delete', 'id' => "rejudge-form"])}} {{Form::close()}}
    @endif
@endsection

@push('scripts')
<script>
    var ace_language = "{{$submission->language}}";
    var ace_theme = "twilight";
</script>
<script src="/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/ace/keybinding-vscode.js"></script>
<script src="/js/dptj/editor.js" type="text/javascript" charset="utf-8"></script>
@endpush