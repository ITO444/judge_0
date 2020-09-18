

<?php $__env->startSection('content'); ?>
    <h1><img src = "<?php echo e($user->avatar); ?>" width = "10%"> <?php echo e($user->name); ?> - <?php echo e($user->display); ?></h1>
    <hr/>
    <h2>Real name: <?php echo e($user->real_name); ?></h2>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /media/sf_judge/resources/views/pages/user.blade.php ENDPATH**/ ?>