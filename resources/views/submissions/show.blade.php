@extends('layouts.app')

@section('content')
    <h1>Submission
    </h1>
    <hr/>
    <div class="card"><div class="card-body"><div class="row text-center">
        <div class="col"><a href="/submission/{{$submission->id}}">{{$submission->id}}</a></div>
        <div class="col"><a href="/user/{{$submission->user->name}}">{{$submission->user->name}} - {{$submission->user->display}}</a></div>
        <div class="col"><a href="/task/{{$submission->task->task_id}}">{{$submission->task->title}}</a></div>
        <div class="col">{{$submission->language}}</div>
        <div class="col">{{$submission->result}}</div>
        <div class="col">{{$submission->runs->max('runtime') / 1000}}</div>
    </div></div></div><hr/>
    <div class="table-responsive"><table class="table table-striped table-bordered table-hover text-center text-nowrap">
        <thead><tr>
            <th>Test</th>
            <th>Result</th>
            <th>Runtime</th>
            <th>Memory</th>
            <th>Score</th>
            @if($myLevel >= $submission->task->edit_level)
            <th>Grader Feedback</th>
            @endif
        </tr></thead><tbody>
    @foreach($submission->runs as $run)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$run->result}}</td>
            <td>{{$run->runtime / 1000}}</td>
            <td>{{number_format($run->memory / 1024, 3)}}</td>
            <td>{{$run->score / 100}}</td>
            @if($myLevel >= $submission->task->edit_level)
            <td>{{$run->grader_feedback}}</td>
            @endif
        </tr>
    @endforeach
    </tbody></table></div>
    @if($myLevel >= $submission->task->edit_level || $submission->user->id == auth()->user()->id)
    <pre>{{$submission->compiler_warning}}</pre><br/>
    <pre>{{$submission->source_code}}</pre>
    @endif
@endsection