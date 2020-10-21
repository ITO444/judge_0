@extends('layouts.app')

@section('content')
    <h1>Tasks
    @if($level >= 4)
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
                <tr class="{{$task->doneBy(auth()->user()) ? ($loop->iteration % 2 ? 'table-primary' : 'table-info') : ''}}">
                    <td>{{$task->task_id}}</td>
                    <td class="text-center">{{($level >= $task->submit_level) ? $task->solved : ''}}</td>
                    <td>
                        @if(!$task->published)
                        <span class="badge badge-danger">WIP</span>
                        @endif
                        <a href="/task/{{$task->task_id}}">{{$task->title}}</a>
                    </td>
                    <td class="text-center">
                        <a href="/task/{{$task->task_id}}/submit" class="btn btn-success btn-sm {{($task->published && $level >= $task->submit_level)?'':'disabled'}}">Submit</a>
                        @if($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4) && (!$task->published || $level >= 6))
                            <a href="/task/{{$task->task_id}}/edit" class="btn btn-primary btn-sm">Edit</a>
                            <a href="/task/{{$task->task_id}}/solution" class="btn btn-secondary btn-sm">Solution</a>
                        @endif
                        <a href="/submissions/task/{{$task->task_id}}" class="btn btn-info btn-sm">Submissions</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
        {{$tasks->links()}}
    @else
        <p>No tasks found</p>
    @endif
@endsection