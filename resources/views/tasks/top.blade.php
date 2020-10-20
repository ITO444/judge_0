<h1>
    <a href="/task/{{$task->task_id}}" class="text-body">{{$task->title}}</a>
    @if(!$task->published)
    <span class="badge badge-danger">WIP</span>
    @endif
    @if($task->submissions->where('user_id', auth()->user()->id)->where('result', 'Accepted')->isNotEmpty())
    <span class="badge badge-success">Done</span>
    @endif
</h1>
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
<div class="btn-group">
    <a href="/task/{{$task->task_id}}" class="btn btn-outline-primary">View{{($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4)) ? ": $task->view_level" : ''}}</a>
    <a href="/task/{{$task->task_id}}/submit" class="btn btn-outline-primary {{($task->published && $level >= $task->submit_level)?'':'disabled'}}">Submit{{($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4)) ? ": $task->submit_level" : ''}}</a>
    @if($level >= $task->edit_level && ($level != 5 || $task->edit_level != 4))
        <a href="/task/{{$task->task_id}}/edit" class="btn btn-outline-primary {{$task->published?'disabled':''}}">Edit{{": $task->edit_level"}}</a>
        <a href="/task/{{$task->task_id}}/tests" class="btn btn-outline-primary">Test Cases</a>
        <a href="/task/{{$task->task_id}}/solution" class="btn btn-outline-primary">Solution</a>
        @if($level >= 6)
            <a href="/task/{{$task->task_id}}/{{$task->published?'unpublish':'publish'}}" class="btn btn-outline-primary">{{$task->published?'Unpublish':'Publish'}}</a>
        @endif
    @endif
    <a href="/submissions/task/{{$task->task_id}}" class="btn btn-outline-primary">Submissions</a>
</div>
<hr/>