<h1><a href="/task/{{$task->id}}" class="text-body">{{$task->title}}</a></h1>
<div class="btn-group">
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
    <div class="btn btn-outline-secondary disabled">
        By: {{$task->author}}
    </div>
    @if($task->origin)
    <div class="btn btn-outline-secondary disabled">
        From: {{$task->origin}}
    </div>
    @endif
</div>
<div class="btn-group">
    <a href="/task/{{$task->id}}" class="btn btn-outline-primary">View</a>
    @if($myLevel >= $task->submit_level)
        <a href="/task/{{$task->id}}/submit" class="btn btn-outline-primary">Submit</a>
        @if($myLevel >= $task->edit_level)
            <a href="/task/{{$task->id}}/edit" class="btn btn-outline-primary">Edit</a>
            <a href="/task/{{$task->id}}/solution" class="btn btn-outline-primary">Solution</a>
        @endif
    @endif
</div>
<hr/>