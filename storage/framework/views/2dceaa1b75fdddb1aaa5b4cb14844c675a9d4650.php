
<?php $__env->startSection('content'); ?>
    <iframe width="100%" style="height: calc(100vh - 30px)" src="<?php echo e(route(config('chatify.path'),['user_id'=>request('user_id')])); ?>" frameborder="0"></iframe>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layout::user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\mamp\htdocs\flouka\Imkan\Imkan8-6\v1\themes/Base/User/Views/frontend/chat/index.blade.php ENDPATH**/ ?>