

<?php $__env->startSection('content'); ?>
    <div class="jumbotron text-center">
        <h1>Judge</h1>
        <?php if(Auth::guest()): ?>
            <p>[insert some text here]</p>
            <a href="<?php echo e(url('redirect')); ?>">
                Login as DGS Student/Staff
            </a>
            <a href="<?php echo e(url('login')); ?>">
                Login as Others
            </a>
        <?php else: ?>
            <p>Welcome <?php echo e(Auth::user()->name); ?>, what would you like to do today?</p>
            <a href="/test" class="btn btn-primary">Test</a>
            <a href="/queue" class="btn btn-primary">Queue</a>
            <a href="/users/<?php echo e(Auth::user()->id); ?>" class="btn btn-primary">User</a>
            <a href="/home" class="btn btn-primary">Home</a>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /media/sf_judge/resources/views/pages/index.blade.php ENDPATH**/ ?>