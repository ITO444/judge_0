@extends('layouts.app')

@section('content')
    <div class="row justify-content-center"><div class="col-md-10">
        @include("tasks.top")
        <div id="result">
            {{$task->grader_status}}
        </div>
        <div id="message">
        </div>
    </div></div>
    <script>
        $( document ).ready(function() {
            Echo.private('update.publish.{{$task->id}}')
            .listen('UpdatePublish', (e) => {
                if(e.result == "Published"){
                    window.location.replace("/task/{{$task->task_id}}");
                }
                $("#result").html(e.result);
                $("#message").html(e.message);
            });
        });
    </script>
@endsection