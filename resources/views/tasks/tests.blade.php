@extends('layouts.app')

@section('content')
    @include("tasks.top")
    <h3>Manage Test Data <a href="/task/{{$task->id}}/grader" class="btn btn-primary float-right">Edit Grader</a></h3>
    <br/>
    @if(count($task->tests) > 0)
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover text-center text-nowrap">
            <thead><tr>
                <th>Test</th>
                <th>Input</th>
                <th>Output</th>
                <th>Last Touched</th>
                <th>Actions</th>
            </tr></thead><tbody>
        @foreach($task->tests as $test)
            <tr>
                <td><div class="col-form-label">{{$loop->iteration}}</div></td>
                <td><div class="col-form-label"><a href="/task/{{$task->id}}/test/{{$test->id}}/download/in" class="">Download</a> ({{$test->size('in')}}B)</div></td>
                <td><div class="col-form-label"><a href="/task/{{$task->id}}/test/{{$test->id}}/download/out" class="">Download</a> ({{$test->size('out')}}B)</div></td>
                <td><div class="col-form-label">{{$test->updated_at}}</div></td>
                <td>
                    <a href="/task/{{$task->id}}/tests/{{$test->id}}" class="btn btn-primary btn">Edit</a>
                    <a class="btn btn-primary btn" onclick="del({{$test->id}})">Delete</a>
                </td>
            </tr>
            @if($test->id == $testChange)
        </tbody></table><hr/>
            <div class="card">
                <h3 class="card-header text-center" type="button" data-toggle="collapse" data-target="#collapse" aria-expanded="{{session('success')?'false':'true'}}" aria-controls="collapse">
                    Change Test {{$loop->iteration}} <span class="dropdown-toggle float-right"></span>
                </h3>
                <div class="collapse {{session('success')?'':'show'}}" id="collapse"><div class="card-body">
                    {{Form::open(['action' => ['TasksController@saveTest', $task->id, $test->id], 'files' => 'true'])}}
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <h3 class="col-md-4 text-md-right">Input</h3>
                            </div><br/>
                            <div class="row form-group">
                                {{Form::label('inputFile', 'Upload', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                                <div class="col-md-6 col-form-label">
                                    {{Form::file("inputFile", ['class' => 'form-control-file'])}}
                                </div>
                            </div>
                            <p class="text-center">OR</p>
                            <div class="row form-group">
                                {{Form::label('inputText', 'Text', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                                <div class="col-md-6">
                                    {{Form::textarea("inputText", $input, ['class' => 'form-control text-monospace'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row">
                                <h3 class="col-md-4 text-md-right">Output</h3>
                            </div><br/>
                            <div class="row form-group">
                                {{Form::label('outputFile', 'Upload', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                                <div class="col-md-6 col-form-label">
                                    {{Form::file("outputFile", ['class' => 'form-control-file'])}}
                                </div>
                            </div>
                            <p class="text-center">OR</p>
                            <div class="row form-group">
                                {{Form::label('outputText', 'Text', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                                <div class="col-md-6">
                                    {{Form::textarea("outputText", $output, ['class' => 'form-control text-monospace'])}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">{{Form::submit('Save', ['class' => 'btn btn-lg btn-primary'])}}</div>
                    {{Form::close()}}
                </div></div>
            </div><hr/>
        <table class="table table-striped table-bordered table-hover text-center text-nowrap">
            <thead><tr>
                <th>Test</th>
                <th>Input</th>
                <th>Output</th>
                <th>Last Touched</th>
                <th>Actions</th>
            </tr></thead><tbody>
            @endif
        @endforeach
        </tbody></table>
    </div>
    @else
        <p>No test cases found</p>
    @endif
    <div class="card">
        <h3 class="card-header text-center">Add Test</h3><div class="card-body">
        {{Form::open(['action' => ['TasksController@saveTest', $task->id], 'files' => 'true'])}}
        <div class="row">
            <div class="col">
                <div class="row">
                    <h3 class="col-md-4 text-md-right">Input</h3>
                </div><br/>
                <div class="row form-group">
                    {{Form::label('inputFile', 'Upload', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                    <div class="col-md-6 col-form-label">
                        {{Form::file("inputFile", ['class' => 'form-control-file'])}}
                    </div>
                </div>
                <p class="text-center">OR</p>
                <div class="row form-group">
                    {{Form::label('inputText', 'Text', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                    <div class="col-md-6">
                        {{Form::textarea("inputText", '', ['class' => 'form-control text-monospace'])}}
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <h3 class="col-md-4 text-md-right">Output</h3>
                </div><br/>
                <div class="row form-group">
                    {{Form::label('outputFile', 'Upload', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                    <div class="col-md-6 col-form-label">
                        {{Form::file("outputFile", ['class' => 'form-control-file'])}}
                    </div>
                </div>
                <p class="text-center">OR</p>
                <div class="row form-group">
                    {{Form::label('outputText', 'Text', ['class' => 'col-md-4 col-form-label text-md-right'])}}
                    <div class="col-md-6">
                        {{Form::textarea("outputText", '', ['class' => 'form-control text-monospace'])}}
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">{{Form::submit('Save', ['class' => 'btn btn-lg btn-primary'])}}</div>
        {{Form::close()}}
    </div></div>
    {{Form::open(['method' => 'delete', 'id' => "delete"])}} {{Form::close()}}
    <script>
        function del(id){
            var delForm = $('#delete');
            if(confirm('Are you sure you want to delete this test case?')) {
                delForm.attr("action", "/task/{{$task->id}}/tests/"+id);
                delForm.submit();
            }
        }
    </script>
@endsection