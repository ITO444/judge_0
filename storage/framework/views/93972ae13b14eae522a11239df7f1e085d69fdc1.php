

<?php $__env->startSection('content'); ?>
    <h1><img src = "<?php echo e($user->avatar); ?>" width = "10%"> <?php echo e($user->name); ?></h1>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /media/sf_judge/resources/views/pages/user.blade.php ENDPATH**/ ?>