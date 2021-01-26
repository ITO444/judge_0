$(function(){
    var ace_modes = {"cpp": "c_cpp", "py": "python", "latex": "latex"};
    var editor = ace.edit("editor");
    var code = $('#code');
    editor.setTheme("ace/theme/" + ace_theme);
    editor.session.setMode("ace/mode/" + ace_modes[ace_language]);

    editor.getSession().on("change", function(){
        code.val(editor.getSession().getValue());
    });

    $("#toggle").on("click", function(){
        if(!code.is(":hidden")){
            editor.session.setValue(code.val());
        }else{
            code.val(editor.getSession().getValue());
        }
        $('#editor').toggle();
        code.toggle();
    });

    $('#language').on("change", function(){
        ace_language = $('#language').val();
        editor.session.setMode("ace/mode/" + ace_modes[ace_language]);
    });

    
    $('form').on('submit', function(e){
        if(code.is(":hidden")){
            code.val(editor.getSession().getValue());
        }
    });

    $('#rejudge-button').on("click", function(){
        if(confirm('Are you sure you want to re-judge this submission?')) {
            $('#rejudge-form').trigger("submit");
        }
    });
});