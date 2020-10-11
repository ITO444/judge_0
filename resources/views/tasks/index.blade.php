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
                <th class="text-center">Solved</th>
                <th>Title</th>
                <th class="text-center">Actions</th>
            </tr></thead>
            <tbody>
            @foreach($tasks as $task)
                @if($myLevel >= $task->view_level)
                <tr class="{{$task->submissions->where('user_id', auth()->user()->id)->where('result', 'Accepted')->isNotEmpty() ? ($loop->iteration % 2 ? 'table-primary' : 'table-info') : ''}}">
                    <td>{{$task->task_id}}</td>
                <td class="text-center">{{$task->solved}}</td>
                    <td><a href="/task/{{$task->task_id}}">{{$task->title}}</a></td>
                    <td class="text-center">
                        <a href="/task/{{$task->task_id}}" class="btn btn-primary btn-sm">View{{$myLevel >= $task->edit_level ? ": $task->view_level" : ''}}</a>
                        @if($myLevel >= $task->submit_level)
                            <a href="/task/{{$task->task_id}}/submit" class="btn btn-primary btn-sm">Submit{{$myLevel >= $task->edit_level ? ": $task->submit_level" : ''}}</a>
                            @if($myLevel >= $task->edit_level)
                                <a href="/task/{{$task->task_id}}/edit" class="btn btn-primary btn-sm">Edit{{": $task->edit_level"}}</a>
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