<h1>
    <a href="/task/{{$task->task_id}}" class="text-body">{{$task->title}}</a>
    @if(!$task->published)
    <span class="badge badge-danger">WIP</span>
    @endif
    @if($task->doneBy(auth()->user()))
    <span class="badge badge-success">Done</span>
    @endif
</h1>
<div class="btn-group p-1">
    <div class="btn btn-outline-secondary disabled">
        {{$task->task_id}}
    </div>
    <div class="btn btn-outline-secondary disabled">
        Time Limit: {{$task->runtime_limit / 1000}} s
    </div>
    <div class="btn btn-outline-secondary disabled">
        Memory Limit: {{$task->memory_limit / 1024}} MB
    </div>
    <div class="btn btn-outline-secondary disabled">
        Date Created: {{$task->date_created}}
    </div>
    @if($task->author)
    <div class="btn btn-outline-secondary disabled">
        By: {{$task->author}}
    </div>
    @endif
    @if($task->origin)
    <div class="btn btn-outline-secondary disabled">
        From: {{$task->origin}}
    </div>
    @endif
</div>
<div class="btn-group p-1">
    <a href="/task/{{$task->task_id}}/submit" class="btn btn-success{{($task->published && $level >= $task->submit_level)?'':' disabled'}}">Submit</a>
    @if($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4) && (!$task->published || $level >= 6))
        <a href="/task/{{$task->task_id}}/edit" class="btn btn-primary">Edit</a>
        <a href="/task/{{$task->task_id}}/solution" class="btn btn-secondary">Solution</a>
    @endif
    <a href="/submissions/task/{{$task->task_id}}" class="btn btn-info{{($task->published && $level >= $task->submit_level)?'':' disabled'}}">Submissions</a>
</div>
<hr/>