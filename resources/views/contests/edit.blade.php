<?php use Carbon\Carbon; ?>
@extends('layouts.app')

@section('content')
    @include("contests.top")
    @include("contests.publish_warning")
    <div class="row justify-content-center"><div class="col-md-10"><div class="card">
        <div class="card-header"><a class="btn disabled text-dark" disabled>Edit Contest</a><a href="/contest/{{$contest->contest_id}}/edit/tasks" class="btn btn-secondary float-right">Manage tasks</a></div>
        <div class="card-body">{{Form::open(['action' => ['ContestsController@update', $contest->contest_id], 'method' => 'POST'])}}
            <div class="row form-group">
                {{Form::label('contest_id', 'Contest ID', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                <div class="col-md-6">
                    {{Form::text("contest_id", $contest->contest_id, ['class' => 'form-control'])}}
                </div>
            </div>

            <div class="row form-group">
                {{Form::label('name', 'name', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                <div class="col-md-6">
                    {{Form::text("name", $contest->name, ['class' => 'form-control'])}}
                </div>
            </div>

            <div class="row form-group">
                {{Form::label('view_level', 'View Level', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                <div class="col-md-6">
                    {{Form::selectRange("view_level", 1, $level, $contest->view_level, ['class' => 'form-control'])}}
                </div>
            </div>

            <div class="row form-group">
                {{Form::label('reg_level', 'Resgister Level', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                <div class="col-md-6">
                    {{Form::selectRange("reg_level", 1, $level, $contest->reg_level, ['class' => 'form-control'])}}
                </div>
            </div>

            <div class="row form-group">
                {{Form::label('add_level', 'Add User Level', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                <div class="col-md-6">
                    {{Form::selectRange("add_level", 4, $level, $contest->add_level, ['class' => 'form-control'])}}
                </div>
            </div>

            <div class="row form-group">
                {{Form::label('edit_level', 'Edit Level', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                <div class="col-md-6">
                    {{Form::selectRange("edit_level", $level == 5 ? 5 : 4, $level, $contest->edit_level, ['class' => 'form-control'])}}
                </div>
            </div>

            <div class="row form-group">
                {{Form::label('start', 'Start Time', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                <div class="col-md-6">
                    {{Form::input('dateTime-local', "start", Carbon::parse($contest->start)->format("Y-m-d\Th:m:s"), ['class' => 'form-control', 'step' => '1'])}}
                </div>
            </div>

            <div class="row form-group">
                {{Form::label('end', 'End Time', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                <div class="col-md-6">
                    {{Form::input('dateTime-local', "end", Carbon::parse($contest->end)->format("Y-m-d\Th:m:s"), ['class' => 'form-control', 'step' => '1'])}}
                </div>
            </div>
            
            <div class="row form-group">
                {{Form::label('duration', 'Duration (minutes)', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                <div class="col-md-6">
                    {{Form::time("duration", gmdate("H:i", $contest->duration), ['class' => 'form-control', 'step' => '60'])}}
                </div>
            </div>

            <div class="row form-group">
                {{Form::label('results', 'Results Reveal Time', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                <div class="col-md-6">
                    {{Form::input('dateTime-local', "results", Carbon::parse($contest->results)->format("Y-m-d\Th:m:s"), ['class' => 'form-control', 'step' => '1'])}}
                </div>
            </div>

            <div class="row form-group">
                {{Form::label('feedback', 'Feedback', ['class' => 'col-md-4 col-form-check-label text-md-right'])}}
                <div class="col-md-6">
                    <div class="form-check">
                        {{Form::checkbox('feedback', true, $contest->configuration["feedback"], ['class' => 'form-check-input'])}}
                    </div>
                </div>
            </div>
            
            <div class="row form-group">
                {{Form::label('cumulative', 'Cumulative Scoring', ['class' => 'col-md-4 col-form-check-label text-md-right'])}}
                <div class="col-md-6">
                    <div class="form-check">
                        {{Form::checkbox('cumulative', true, $contest->configuration["cumulative"], ['class' => 'form-check-input'])}}
                    </div>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-4 col-form-label text-md-right">
                    Tasks
                </div>
                <div class="col-md-6 col-form-label">
                    {{count($contest->tasks())}} <a href="/contest/{{$contest->contest_id}}/edit/tasks" class="btn btn-sm btn-secondary">Manage tasks</a>
                </div>
            </div>

            <hr/>
            <div class="form-group">
                {{Form::label('description', 'Description', ['class' => 'form-label'])}}
                <div id="editor" class="rounded editor">{{$contest->description}}</div>
                {{Form::textarea("description", $contest->description, ['class' => 'form-control text-monospace', 'style' => 'display: none; height: 400px', 'id' => 'code'])}}
                <br/><a id='toggle' class='btn btn-secondary'>Toggle highlighting</a>
            </div>
            
            <div class="form-group">
                {{Form::label('editorial', 'Editorial', ['class' => 'form-label'])}}
                {{Form::textarea("editorial", $contest->editorial, ['class' => 'form-control'])}}
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                    @if($contest->published)
                    {{Form::submit('Save', ['class' => 'btn btn-primary disabled', 'disabled'])}}
                    @else
                    {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
                    @endif
                    <a href="/contest/{{$contest->contest_id}}/edit/tasks" class="btn btn-secondary">Manage tasks</a>
                </div>
            </div>
        {{Form::close()}}</div>
    </div></div></div>
@endsection

@push('scripts')
<script>
    var ace_language = "latex";
    var ace_theme = "textmate";
</script>
<script src="/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/dptj/editor.js" type="text/javascript" charset="utf-8"></script>
@endpush