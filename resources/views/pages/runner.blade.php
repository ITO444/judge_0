<?php
    $code = '';
    $input = '';
    $language = 'cpp';
    $b = '0';
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $code = $_POST['code'];
        $input = $_POST['input'];
        $language = $_POST['language'];
        $b = strval($_POST['box']);
        $stuff = env('APP_PATH')."resources/stuff/$b";
        exec("isolate --cg -b $b --cleanup");
        $box = exec("isolate --cg -b $b --init").'/box';
        exec("rm $stuff/*");
        if($language != 'py'){
            $language = 'cpp';
        }
        file_put_contents("$stuff/program.$language", $code);
        file_put_contents("$stuff/program.in", $input);
        exec("mv $stuff/* $box");
        if($language != 'py'){
            putenv("PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin");
            exec("isolate --cg -b $b -t 30 -m 262144 -e -p -M $stuff/compile.txt --run -- /usr/bin/g++ program.cpp -o program.exe", $dummy, $compile);
            if($compile != 0){
                $execute = 1;
                exec("touch $box/program.out");
            }else{
                exec("isolate --cg -b $b -t 1 -m 262144 -i program.in -o program.out -M $stuff/execute.txt --run -- ./program.exe", $dummy, $execute);
            }
        }else{
            exec("isolate --cg -b $b -t 30 -m 262144 -e -p -M $stuff/compile.txt --run -- /usr/bin/py3compile program.py", $dummy, $compile);
            exec("isolate --cg -b $b -t 1 -m 262144 -i program.in -o program.out -M $stuff/execute.txt --run -- /usr/bin/python3 program.py", $dummy, $execute);
        }
        if($compile != 0){$c = 'No';}else{$c = 'Yes';}
        if($execute != 0){$e = 'No';}else{$e = 'Yes';}
        $files = shell_exec("cd $box && ls");
        exec("mv $box/program.out $stuff");
        $output = file_get_contents("$stuff/program.out");
        //exec("isolate --cg -b $b --cleanup");
    }
?>
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Run code</h1>
    {!! Form::open(['id' => 'form', 'method' => 'post']) !!}
    <div class='form-group'>
        {{Form::label('language', 'Language')}}
        {{Form::select('language', ['cpp' => 'C++', 'py' => 'Python'], $language, ['class' => 'form-control'])}}
    </div>
    <div class='form-group'>
        {{Form::label('box', 'Box')}}
        {{Form::select('box', [0, 1, 2, 3, 4, 5, 6, 7, 8], null, ['class' => 'form-control'])}}
    </div>
    <div class="row">
        <div class="col form-group">
            {{Form::label('code', 'Code')}}
            <div id="editor" class="rounded">{{$code}}</div>
            {{Form::textarea('code', $code, ['class' => 'form-control', 'style' => 'display: none'])}}
        </div>
        <div class="col form-group">
            {{Form::label('input', 'Input')}}
            {{Form::textarea('input', $input, ['class' => 'form-control'])}}
        </div>
    </div>
    <a id='toggle' class='btn btn-light'>Toggle highlighting</a>
    {{Form::submit('Run', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
    @if ($_SERVER['REQUEST_METHOD'] === 'POST')
    <div>
        Compile: {{$c}} ({{$compile}})<br>
        Execute: {{$e}} ({{$execute}})<br>
        Files: {{$files}}<br>
        Output:<br>
        <pre>{{$output}}</pre>
    </div>
    @endif
</div>
<script src="/js/ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var language = "{{$language}}";
    var ace_modes = {"cpp": "c_cpp", "py": "python"};
    var editor = ace.edit("editor");
    var code = $('#code');
    editor.setTheme("ace/theme/twilight");
    editor.session.setMode("ace/mode/" + ace_modes[language]);
    $(document).ready(function(){
        $('#language').change(
            function(){
                language = $('#language').val();
                editor.session.setMode("ace/mode/" + ace_modes[language]);
            }
        );
        editor.getSession().on("change", function(){
            if(code.is(":hidden")){
                code.val(editor.getSession().getValue());
            }
        });
        $("#toggle").click(function(){
            if(!code.is(":hidden")){
                editor.session.setValue(code.val());
            }
            $('#editor').toggle();
            code.toggle();
        });
    });
</script>
@endsection