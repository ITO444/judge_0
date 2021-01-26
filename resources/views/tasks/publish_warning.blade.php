
@if($task->published)
    <div class="alert alert-warning">Please <a href="/task/{{$task->task_id}}/unpublish" class="alert-link">unpublish</a> to edit this task</div>
@elseif($level >= 6)
    <div class="alert alert-info">Please <a href="/task/{{$task->task_id}}/publish" class="alert-link">publish</a> this task to enable submissions</div>
@endif