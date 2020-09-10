@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Run code</h1>
    {!! Form::open(['id' => 'form', 'method' => 'post']) !!}
    @csrf
    <div class='form-group'>
        {{Form::label('language', 'Language')}}
        {{Form::select('language', ['cpp' => 'C++', 'py' => 'Python'], 'cpp', ['class' => 'form-control'])}}
    </div>
    <div class="row">
        <div class="col-md form-group">
            {{Form::label('code', 'Code')}}
            <div id='savestatus' class="d-inline text-muted"></div>
            <div id="editor" class="rounded">{{$code}}</div>
            {{Form::textarea('code', $code, ['class' => 'form-control', 'style' => 'display: none'])}}
        </div>
        <div class="col-md form-group">
            {{Form::label('input', 'Input')}}
            {{Form::textarea('input', $input, ['class' => 'form-control'])}}
        </div>
    </div>
    <a id='toggle' class='btn btn-light'>Toggle highlighting</a>
    {{Form::submit('Run', ['class' => 'btn btn-primary'])}}
    <div id='runstatus' class="d-inline text-muted"></div>
    {!! Form::close() !!}
    <pre id='result'></pre>
<script src="/js/ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var language = "cpp";
    var ace_modes = {"cpp": "c_cpp", "py": "python"};
    var editor = ace.edit("editor");
    var code = $('#code');
    var input = $('#input');
    var timeoutId;
    var runstatus = '';
    var waiting;
    editor.setTheme("ace/theme/twilight");
    editor.session.setMode("ace/mode/" + ace_modes[language]);

    function ajaxsave(){
        $.ajax({
            type: 'POST',
            url: '/runner/save',
            data: $('form').serialize(),
            success:function(data) {
                $("#savestatus").html('Saved');
            },
            error: function(xhr){
                alert("An error occured: " + xhr.status + " " + xhr.statusText);
                $("#savestatus").html('Error');
            }
        });
    }

    function autosave(){
        if(code.is(":hidden")){
            code.val(editor.getSession().getValue());
        }
        $("#savestatus").html('Pending...');
        // Clear started timer
        if (timeoutId) clearTimeout(timeoutId);

        // Set timer to save code and input
        timeoutId = setTimeout(function () {
            ajaxsave();
        }, 750);
    }
        
    function ajaxcheck(){
        waiting = setInterval(function() {
            $.ajax({
                type: 'POST',
                url: '/runner/check',
                data: $('form').serialize(),
                success:function(data) {
                    $("#runstatus").html(data.status);
                    $("#result").html(data.result);
                    if(data.done){
                        clearInterval(waiting);
                    }
                },
                error: function(xhr){
                    alert("An error occured: " + xhr.status + " " + xhr.statusText);
                    $("#runstatus").html('Error');
                }
            });
        }, 200);
    }

    ajaxcheck();

    $('form').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/runner/run',
            data: $('form').serialize(),
            success:function(data) {
                ajaxcheck();
            },
            error: function(xhr){
                alert("An error occured: " + xhr.status + " " + xhr.statusText);
                $("#runstatus").html('Error');
            }
        });
    });

    code.keyup(function(){
        autosave();
    });
    input.keyup(function(){
        autosave();
    });
    editor.getSession().on("change", function(){
        autosave();
    });

    $('#language').change(function(){
        $("#savestatus").html('Switching...');
        language = $('#language').val();
        editor.session.setMode("ace/mode/" + ace_modes[language]);
        $.ajax({
            type: 'POST',
            url: '/runner/language',
            data: $('form').serialize(),
            success:function(data) {
                editor.session.setValue(data.code);
                code.val(data.code);
                $("#savestatus").html('Switched');
            },
            error: function(xhr){
                alert("An error occured: " + xhr.status + " " + xhr.statusText);
                $("#savestatus").html('Error');
            }
        });
    });

    $("#toggle").click(function(){
        if(!code.is(":hidden")){
            editor.session.setValue(code.val());
        }else{
            code.val(editor.getSession().getValue());
        }
        $('#editor').toggle();
        code.toggle();
    });
</script>
@endsection