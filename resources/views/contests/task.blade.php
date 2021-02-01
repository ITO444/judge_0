@extends('layouts.app')

@section('content')
    @include("contests.top")
    @include("contests.publish_warning")
    <a href="/contest/{{$contest->contest_id}}/edit/tasks" class="btn btn-secondary">Back to Manage Tasks</a>
    <br/><br/>
    <h1>Manage Subtasks</h1>
    <hr/>
    
    {{Form::open(['action' => ['ContestsController@updateTask', $contest->contest_id, $task->task_id], 'method' => 'POST'])}}
    <div class="card">
        <div class="card-header">Scores</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover text-nowrap">
                    <thead><tr>
                        <th>Subtask</th>
                        @foreach($taskConfig['subtasks'] as $subtask => $score)
                        <th class="text-center">{{$subtask}}</th>
                        @endforeach
                    </tr></thead>
                    <tbody>
                        <tr>
                            <th scope="row">Score</th>
                            @foreach($taskConfig['subtasks'] as $subtask => $score)
                            <td class="text-center">{{Form::number("subtasks[$subtask]", $score, ['class' => "form-control"])}}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr/>
    <div class="card">
        <div class="card-header">Testcases</div>
        <div class="card-body">
        @if(count($task->tests) > 0)
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover text-nowrap">
                    <thead><tr>
                        <th class="text-center">Testcase \ Subtask</th>
                        @foreach($taskConfig['subtasks'] as $subtask => $score)
                        <th class="text-center">{{$subtask}}</th>
                        @endforeach
                    </tr></thead>
                    <tbody>
                    @foreach($task->tests as $test)
                        <tr>
                            <th class="text-center" scope="row">{{$loop->iteration}}</th>
                            @foreach($taskConfig['subtasks'] as $subtask => $score)
                            <td class="text-center"><div class="form-check">{{Form::checkbox("tests[$test->id][$subtask]", true, isset($taskConfig['tests'][$test->id][$subtask]), ['class' => "form-check-input position-static"])}}</div></th>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No testcases at all</p>
        @endif
        {{Form::submit('Save', ['class' => "float-right btn btn-primary"])}}
        </div>
    </div>
    {{Form::close()}}
@endsection