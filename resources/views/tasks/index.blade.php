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
        <table class="table table-striped table-bordered table-hover">
            <tr>
                <th>Task ID</th>
                <th>Title</th>
                <th>Actions</th>
            </tr>
        @foreach($tasks as $task)
            @if($myLevel >= $task->view_level)
            <tr>
                <td>{{$task->task_id}}</td>
                <td><a href="/task/{{$task->id}}" class="btn btn-link">{{$task->title}}</a></td>
                <td>
                    <a href="/task/{{$task->id}}" class="btn btn-primary">View</a>
                    @if($myLevel >= $task->submit_level)
                        <a href="/task/{{$task->id}}/submit" class="btn btn-primary">Submit</a>
                        @if($myLevel >= $task->edit_level)
                            <a href="/task/{{$task->id}}/edit" class="btn btn-primary">Edit</a>
                            <a href="/task/{{$task->id}}/solution" class="btn btn-primary">Solution</a>
                        @endif
                    @endif
                </td>
            </tr>
            @endif
        @endforeach
        </table>
        {{$tasks->links()}}
    @else
        <p>No tasks found</p>
    @endif
@endsection