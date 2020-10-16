@extends('layouts.app')

@section('content')
    <h1>Submissions
    </h1>
    <br/>
    @if(count($submissions) > 0)
        {{$submissions->links()}}
        <div class="table-responsive"><table class="table table-striped table-bordered table-hover text-nowrap">
            <thead><tr>
                <th class="text-center">Date</th>
                <th>User</th>
                <th>Task</th>
                <th class="text-center">Language</th>
                <th class="text-center">Result</th>
                <th class="text-center">Time</th>
            </tr></thead>
            <tbody>
            @foreach($submissions as $submission)
                <tr class="{{$submission->user->id == auth()->user()->id ? ($loop->iteration % 2 ? 'table-primary' : 'table-info') : ''}}">
                    <td class="text-center"><a href="/submission/{{$submission->id}}">{{$submission->created_at}}</a></td>
                    <td><a href="/user/{{$submission->user->name}}">{{$submission->user->name}} - {{$submission->user->display}}</a></td>
                    <td><a href="/task/{{$submission->task->task_id}}">{{$submission->task->title}}</a></td>
                    <td class="text-center">{{$submission->language == 'cpp' ? "C++" : "Python 3"}}</td>
                    <td class="text-center{{$submission->result == 'Accepted' ? ' text-success font-weight-bold' : ''}}">{{$submission->result}}</td>
                    <td class="text-center">{{$submission->getAttributes()['result'] < 0 ? '' : number_format($submission->runs->max('runtime') / 1000, 3)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
        {{$submissions->links()}}
    @else
        <p>No submissions found</p>
    @endif
@endsection