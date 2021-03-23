@extends('layouts.app')

@section('pageTitle', "$task->task_id - $task->title")

@section('content')
    @include("tasks.top")
    <div class="row justify-content-center"><div class="col-md-8"><div class="card">
        <div class="card-header">Submit</div>
        <div class="card-body">
            {{Form::open(['action' => ['TasksController@saveSubmit', $task->task_id], 'method' => 'POST'])}}
            <div class='form-group'>
                {{Form::label('language', 'Language')}}
                {{Form::select('language', ['cpp' => 'C++', 'py' => 'Python 3'], 'py', ['class' => 'form-control'])}}
            </div>
            <div class="form-group">
                {{Form::label('code', 'Source code', ['class' => 'form-label'])}}
                <div id="editor" class="rounded editor"></div>
                {{Form::textarea('code', '', ['class' => 'form-control text-monospace', 'style' => 'display: none; height: 400px'])}}
            </div>
            <div class="form-group mb-0">
                {{Form::submit('Submit', ['class' => 'btn btn-success'])}} <a id='toggle' class='btn btn-secondary'>Toggle highlighting</a>
            </div>
            {{Form::close()}}
        </div>
    </div></div></div>
@endsection

@push('scripts')
<script>
    var ace_language = "py";
    var ace_theme = "twilight";
</script>
<script src="/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/dptj/editor.js" type="text/javascript" charset="utf-8"></script>
@endpush