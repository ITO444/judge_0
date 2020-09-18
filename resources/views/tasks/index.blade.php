@extends('layouts.app')

@section('content')
    <h1>Tasks
    @if($myLevel >= 4)
        <a href="/admin/task" class="btn btn-primary float-right">New Task</a>
    @endif
    </h1>
    <br/>
    @if(count($tasks) > 0)
        {{$tasks->links()}}
        <div class="table-responsive"><table class="table table-striped table-bordered table-hover text-nowrap">
            <thead><tr>
                <th>Task ID</th>
                <th>Title</th>
                <th>Actions</th>
            </tr></thead>
            <tbody>
            @foreach($tasks as $task)
                @if($myLevel >= $task->view_level)
                <tr>
                    <td>{{$task->task_id}}</td>
                    <td><a href="/task/{{$task->task_id}}">{{$task->title}}</a></td>
                    <td>
                        <a href="/task/{{$task->task_id}}" class="btn btn-primary btn-sm">View</a>
                        @if($myLevel >= $task->submit_level)
                            <a href="/task/{{$task->task_id}}/submit" class="btn btn-primary btn-sm">Submit</a>
                            @if($myLevel >= $task->edit_level)
                                <a href="/task/{{$task->task_id}}/edit" class="btn btn-primary btn-sm">Edit</a>
                                <a href="/task/{{$task->task_id}}/solution" class="btn btn-primary btn-sm">Solution</a>
                            @endif
                        @endif
                    </td>
                </tr>
                @endif
            @endforeach
            </tbody>
        </table></div>
        {{$tasks->links()}}
    @else
        <p>No tasks found</p>
    @endif
@endsection