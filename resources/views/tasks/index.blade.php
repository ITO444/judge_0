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
                @if($level >= $task->view_level)
                <tr class="{{$task->submissions->where('user_id', auth()->user()->id)->where('result', 'Accepted')->isNotEmpty() ? ($loop->iteration % 2 ? 'table-primary' : 'table-info') : ''}}">
                    <td>{{$task->task_id}}</td>
                    <td class="text-center">{{($level >= $task->submit_level) ? $task->solved : ''}}</td>
                    <td>
                        @if(!$task->published)
                        <span class="badge badge-danger">WIP</span>
                        @endif
                        <a href="/task/{{$task->task_id}}">{{$task->title}}</a>
                    </td>
                    <td class="text-center">
                        <a href="/task/{{$task->task_id}}" class="btn btn-primary btn-sm">View{{($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4)) ? ": $task->view_level" : ''}}</a>
                        <a href="/task/{{$task->task_id}}/submit" class="btn btn-primary btn-sm {{($task->published && $level >= $task->submit_level)?'':'disabled'}}">Submit{{($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4)) ? ": $task->submit_level" : ''}}</a>
                        @if($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4))
                            <a href="/task/{{$task->task_id}}/edit" class="btn btn-primary btn-sm {{$task->published?'disabled':''}}">Edit{{": $task->edit_level"}}</a>
                            <a href="/task/{{$task->task_id}}/tests" class="btn btn-primary btn-sm">Test Cases</a>
                            <a href="/task/{{$task->task_id}}/solution" class="btn btn-primary btn-sm">Solution</a>
                            @if($level >= 6)
                                <a href="/task/{{$task->task_id}}/{{$task->published?'unpublish':'publish'}}" class="btn btn-primary btn-sm">{{$task->published?'Unpublish':'Publish'}}</a>
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