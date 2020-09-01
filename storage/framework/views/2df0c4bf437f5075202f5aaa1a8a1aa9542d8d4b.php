

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Do something</h1>
    <?php echo Form::open(['id' => 'form', 'method' => 'post']); ?>

        <div class="col form-group">
            <?php echo e(Form::label('input', 'Input')); ?>

            <?php echo e(Form::textarea('input', null, ['class' => 'form-control'])); ?>

            <?php echo e(Form::number('sleep')); ?>

        </div>
        <?php echo e(Form::submit('Submit', ['class' => 'btn btn-primary'])); ?>

    <?php echo Form::close(); ?>

    <div>
        Output:<br>
        <pre><?php echo e($output); ?></pre>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /media/sf_judge/resources/views/pages/queue.blade.php ENDPATH**/ ?>