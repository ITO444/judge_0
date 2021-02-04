@extends('layouts.app')

@section('pageTitle', "$task->task_id - $task->title")

@section('content')
    <div class="row justify-content-center"><div class="col-md-10">
        @include("tasks.top")
        <div id="result">
            {{$task->grader_status}}
        </div>
        <div id="message">
        </div>
    </div></div>
@endsection

@push('scripts')
<script>
    var id = "{{$task->id}}";
    var task_id = "{{$task->task_id}}";
</script>
<script src="/js/dptj/publish-task.js" type="text/javascript" charset="utf-8"></script>
@endpush