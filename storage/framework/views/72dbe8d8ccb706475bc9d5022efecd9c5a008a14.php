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
        exec('rm stuff/*');
        $box = exec("isolate --cg -b $b --init").'/box';
        $stuff = env('APP_PATH')."resources/stuff/$b";
        if($language != 'py'){
            $language = 'cpp';
        }
        file_put_contents("$stuff/program.$language", $code);
        file_put_contents("$stuff/program.in", $input);
        exec("mv $stuff/* $box");
        if($language != 'py'){
            exec("isolate --cg -b $b -t 30 -m 262144 -e -p -M $stuff/compile.txt --run -- /usr/bin/g++ program.cpp -o program.exe", $dummy, $compile);
            exec("isolate --cg -b $b -t 1 -m 262144 -i program.in -o program.out -M $stuff/execute.txt --run -- ./program.exe", $dummy, $execute);
        }else{
            exec("isolate --cg -b $b -t 30 -m 262144 -e -p -M $stuff/compile.txt --run -- /usr/bin/py3compile program.py", $dummy, $compile);
            exec("isolate --cg -b $b -t 1 -m 262144 -i program.in -o program.out -M $stuff/execute.txt --run -- /usr/bin/python3 program.py", $dummy, $execute);
        }
        if($compile != 0){$c = 'No';}else{$c = 'Yes';}
        if($execute != 0){$e = 'No';}else{$e = 'Yes';}
        $files = shell_exec("cd $box && ls");
        exec("mv $box/program.out $stuff");
        $output = file_get_contents("$stuff/program.out");
        exec("isolate --cg -b $b --cleanup");
    }
?>


<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Run code</h1>
    <?php echo Form::open(['id' => 'form', 'method' => 'post']); ?>

    <div class='form-group'>
        <?php echo e(Form::label('language', 'Language')); ?>

        <?php echo e(Form::select('language', ['cpp' => 'C++', 'py' => 'Python'], $language, ['class' => 'form-control'])); ?>

    </div>
    <div class='form-group'>
        <?php echo e(Form::label('box', 'Box')); ?>

        <?php echo e(Form::select('box', [0, 1, 2, 3, 4, 5, 6, 7, 8], null, ['class' => 'form-control'])); ?>

    </div>
    <div class="row">
        <div class="col form-group">
            <?php echo e(Form::label('code', 'Code')); ?>

            <div id="editor" class="rounded"><?php echo e($code); ?></div>
            <?php echo e(Form::textarea('code', $code, ['class' => 'form-control', 'style' => 'display: none'])); ?>

        </div>
        <div class="col form-group">
            <?php echo e(Form::label('input', 'Input')); ?>

            <?php echo e(Form::textarea('input', $input, ['class' => 'form-control'])); ?>

        </div>
    </div>
    <a id='toggle' class='btn btn-light'>Toggle highlighting</a>
    <?php echo e(Form::submit('Submit', ['class' => 'btn btn-primary'])); ?>

    <?php echo Form::close(); ?>

    <?php if($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <div>
        Compile: <?php echo e($c); ?> (<?php echo e($compile); ?>)<br>
        Execute: <?php echo e($e); ?> (<?php echo e($execute); ?>)<br>
        Files: <?php echo e($files); ?><br>
        Output:<br>
        <pre><?php echo e($output); ?></pre>
    </div>
    <?php endif; ?>
</div>
<script src="/js/ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var language = "<?php echo e($language); ?>";
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /media/sf_judge/resources/views/test/index.blade.php ENDPATH**/ ?>