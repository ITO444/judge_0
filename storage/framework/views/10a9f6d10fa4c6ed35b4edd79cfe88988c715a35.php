

<?php $__env->startSection('content'); ?>
    <div class="jumbotron text-center">
        <h1><?php echo e(config('app.name')); ?></h1>
        <p>
            When \(a \ne 0\), there are two solutions to \(ax^2 + bx + c = 0\) and they are
            \[x = {-b \pm \sqrt{b^2-4ac} \over 2a}.\]
        </p>
        <?php if(Auth::guest()): ?>
            <a href="<?php echo e(url('redirect')); ?>" class="btn btn-primary">
                Login as DGS Student/Staff
            </a>
            <a href="<?php echo e(url('login')); ?>" class="btn btn-primary">
                Login as Others
            </a>
        <?php else: ?>
            <p>Welcome <?php echo e(Auth::user()->name); ?>, what would you like to do today?</p>
            <a href="/runner" class="btn btn-primary">Runner</a>
            <a href="/users/<?php echo e(Auth::user()->id); ?>" class="btn btn-primary">User</a>
            <a href="/home" class="btn btn-primary">Home</a>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /media/sf_judge/resources/views/pages/index.blade.php ENDPATH**/ ?>