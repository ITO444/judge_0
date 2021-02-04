@extends('layouts.app')

@section('content')
    @if(isset($user))
    User: <a href="/user/{{$user->name}}" class="text-body">{{$user->name}} - {{$user->display}}</a>
    @elseif(isset($task))
    @include('tasks.top')
    @elseif(isset($contest))
    @include('contests.top')
    @endif
    <h1>Submissions</h1>
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
                @if($submission->participation !== null && $submission->participation->user_id != auth()->user()->id && ($level < 7 || $level < $submission->participation->contest->edit_level))
                    <tr>
                        <td class="text-center text-muted">Hidden</td>
                        <td class="text-muted">Hidden</td>
                        <td class="text-muted">Hidden</td>
                        <td class="text-center text-muted">Hidden</td>
                        <td class="text-center text-muted">Hidden</td>
                        <td class="text-center text-muted">Hidden</td>
                    </tr>
                @else
                    <tr class="{{$submission->user->id == auth()->user()->id ? ($loop->iteration % 2 ? 'table-primary' : 'table-info') : ''}}">
                        <td class="text-center"><a href="/submission/{{$submission->id}}">{{$submission->created_at}}</a></td>
                        <td><a href="/user/{{$submission->user->name}}">{{$submission->user->name}} - {{$submission->user->display}}</a></td>
                        <td><a href="/task/{{$submission->task->task_id}}">{{$submission->task->title}}</a></td>
                        <td class="text-center">{{$submission->language == 'cpp' ? "C++" : "Python 3"}}</td>
                        @if(isset($noFeedback) && $noFeedback)
                        <td class="text-center text-muted">Hidden</td>
                        <td class="text-center text-muted">Hidden</td>
                        @else
                        <td class="text-center{{$submission->result == 'Accepted' ? ' text-success font-weight-bold' : ''}}">{{$submission->result}}</td>
                        @if($submission->participation_id !== null)
                        <td class="text-center">Score: {{$submission->score / 1000}}</td>
                        @else
                        <td class="text-center">{{$submission->getAttributes()['result'] < 0 ? '' : number_format($submission->runs->max('runtime') / 1000, 3)}}</td>
                        @endif
                        @endif
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table></div>
        {{$submissions->links()}}
    @else
        <p>No submissions found</p>
    @endif
@endsection