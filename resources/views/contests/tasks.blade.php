@extends('layouts.app')

@section('pageTitle', "$contest->contest_id - $contest->name")

@section('content')
    @include("contests.top")
    @include("contests.publish_warning")
    <h1>Manage Tasks</h1>
    <hr/>
    
    <h3>Tasks</h3>
    @if(count($contest->tasksConfig()) > 0)
        <div class="table-responsive"><table class="table table-striped table-bordered table-hover text-nowrap">
            <thead><tr>
                <th>Task ID</th>
                <th>Title</th>
                <th class="text-center">Subtasks</th>
                <th class="text-center">Manage Subtasks</th>
                <th class="text-center">Remove</th>
            </tr></thead>
            <tbody>
            @foreach($contest->tasksConfig() as $task => $config)
                @php
                $task = App\Task::find($task);
                @endphp
                <tr>
                    <td>
                        {{$task->task_id}}
                    </td>
                    @if($level >= $task->view_level && ($task->published || ($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4))))
                    <td>
                        <a href="/task/{{$task->task_id}}">{{$task->title}}</a>
                    </td>
                    @else
                    <td class="text-muted">
                        Hidden
                    </td>
                    @endif
                    <td class="text-center">
                        {{count($config['subtasks'])}}
                    </td>
                    <td class="text-center">
                        <a href="/contest/{{$contest->contest_id}}/edit/task/{{$task->task_id}}" class="btn btn-sm btn-primary">></a>
                    </td>
                    <td class="text-center">
                        <a data-id="{{$task->task_id}}" class="btn btn-sm btn-danger del{{$contest->published ? ' disabled' : ''}}"><span aria-hidden="true">&times;</span></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
        {{Form::open(['method' => 'delete', 'id' => "delete"])}} {{Form::close()}}
    @else
        <p>No tasks at all</p>
    @endif
    @if(!$contest->published)
    <hr/>
    <div class="card">
        <div class="card-header">Add Task</div>
        <div class="card-body">
            {{Form::open(['action' => ['ContestsController@updateTasks', $contest->contest_id], 'method' => 'POST'])}}
                <div class="form-group">
                    {{Form::label('task_id', 'Task ID', ['class' => 'form-label'])}}
                    {{Form::text('task_id', '', ['class' => 'form-control'])}}
                </div>

                <div class="form-group">
                    {{Form::label('subtasks', 'Number of Subtasks', ['class' => 'form-label'])}}
                    {{Form::number('subtasks', 1, ['class' => 'form-control'])}}
                </div>

                <div class="form-group">
                    {{Form::submit('Add', ['class' => 'btn btn-success'])}}
                </div>
            {{Form::close()}}
        </div>
    </div>
    @endif
@endsection

@push('scripts')
<script>
    var path = "/contest/{{$contest->contest_id}}/edit/task/";
</script>
<script src="/js/dptj/delete-id.js" type="text/javascript" charset="utf-8"></script>
@endpush