@extends('layouts.app')

@section('content')
    <h1>Submissions
    </h1>
    <br/>
    @if(count($submissions) > 0)
        {{$submissions->links()}}
        <div class="table-responsive"><table class="table table-striped table-bordered table-hover text-nowrap">
            <thead><tr>
                <th>Submission ID</th>
                <th>User</th>
                <th>Task</th>
                <th>Language</th>
                <th>Result</th>
                <th>Time</th>
            </tr></thead>
            <tbody>
            @foreach($submissions as $submission)
                <tr>
                    <td><a href="/submission/{{$submission->id}}">{{$submission->id}}</a></td>
                    <td><a href="/user/{{$submission->user->name}}">{{$submission->user->name}} - {{$submission->user->display}}</a></td>
                    <td><a href="/task/{{$submission->task->task_id}}">{{$submission->task->title}}</a></td>
                    <td>{{$submission->language}}</td>
                    <td>{{$submission->result}}</td>
                    <td>{{$submission->runs->max('runtime') / 1000}}</td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
        {{$submissions->links()}}
    @else
        <p>No submissions found</p>
    @endif
@endsection