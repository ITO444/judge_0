<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
            <?php echo e(config('app.name')); ?>

        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="<?php echo e(__('Toggle navigation')); ?>">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <?php if(auth()->guard()->check()): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/runner">Code Runner</a>
                </li>
                <li class="nav-item">
                    <a href="/users/<?php echo e(auth()->user()->id); ?>" class="nav-link">My Page</a>
                </li>
                <?php if(auth()->user()->level >= 2): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/tasks">Tasks</a>
                </li>
                <?php endif; ?>
                <?php if(auth()->user()->level >= 4): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/admin">Admin</a>
                </li>
                <?php endif; ?>
                <?php endif; ?>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                <?php if(auth()->guard()->guest()): ?>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <?php echo e(__('Login')); ?> <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/redirect"><?php echo e(__('DGS Student/Staff')); ?></a>
                            <a class="dropdown-item" href="<?php echo e(route('login')); ?>"><?php echo e(__('Others')); ?></a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item dropdown">
                        <li>
                            <a class="nav-link" href="/user/<?php echo e(Auth::user()->id); ?>">
                                <?php echo e(Auth::user()->name); ?>

                            </a>
                        </li>
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <!--span class="caret"></span-->
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/settings">
                                <?php echo e(__('Settings')); ?>

                            </a>
                            <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                                onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                <?php echo e(__('Logout')); ?>

                            </a>

                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav><?php /**PATH /media/sf_judge/resources/views/inc/navbar.blade.php ENDPATH**/ ?>