$(function(){
    var editor = ace.edit("editor");
    var code = $('#code');
    var input = $('#input');
    var timeoutId;
    
    Echo.private('update.runner.' + user_id)
    .listen('UpdateRunner', (e) => {
        console.log(e.status);
        $("#runstatus").html(e.status);
        $("#result").html(e.output);
    });
        
    function ajaxsave(){
        $.ajax({
            type: 'POST',
            url: '/runner/save',
            data: $('form').serialize(),
            success:function(data) {
                $("#savestatus").html(data.status);
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

    $('form').on('submit', function(e){
        e.preventDefault();
        $("#runstatus").html("Waiting...");
        $.ajax({
            type: 'POST',
            url: '/runner/run',
            data: $('form').serialize(),
            success:function(data) {
                if(data.status){
                    $("#runstatus").html("Loading...");
                    alert(data.status);
                }else{
                    $("#runstatus").html("Loading...");
                    $("#result").html('');
                }
            },
            error: function(xhr){
                alert("An error occured: " + xhr.status + " " + xhr.statusText);
                $("#runstatus").html('Error');
            }
        });
    });

    code.on("keyup", function(){
        autosave();
    });
    input.on("keyup", function(){
        autosave();
    });
    editor.getSession().on("change", function(){
        autosave();
    });

    $('#language').on("change", function(){
        $("#savestatus").html('Switching...');
        $.ajax({
            type: 'POST',
            url: '/runner/language',
            data: $('form').serialize(),
            success:function(data) {
                editor.session.setValue(data.code);
                code.val(data.code);
                $("#savestatus").html(data.status);
            },
            error: function(xhr){
                alert("An error occured: " + xhr.status + " " + xhr.statusText);
                $("#savestatus").html('Error');
            }
        });
    });
});