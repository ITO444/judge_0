@extends('layouts.app')

@section('content')
    <h1>Submission
    </h1>
    <hr/>
    <div class="card"><div class="card-body"><div class="row text-center">
        <div class="col"><a href="/submission/{{$submission->id}}">{{$submission->id}}</a></div>
        <div class="col"><a href="/user/{{$submission->user->name}}">{{$submission->user->name}}</a></div>
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
            <th>Grader Feedback</th>
        </tr></thead><tbody>
    @foreach($submission->runs as $run)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$run->result}}</td>
            <td>{{$run->runtime / 1000}}</td>
            <td>{{$run->memory}}</td>
            <td>{{$run->score / 100}}</td>
            <td>{{$run->grader_feedback}}</td>
        </tr>
    @endforeach
    </tbody></table></div>
    <pre>{{$submission->compiler_warning}}</pre><br/>
    <pre>{{$submission->source_code}}</pre>
@endsection