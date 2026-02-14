<?php $__env->startSection('title', $__t('Page not found')); ?>

<?php $__env->startSection('content'); ?>
<meta http-equiv="refresh"
	content="5;url=<?php echo e($U('/')); ?>">

<div class="row">
	<div class="col text-center">
		<h1 class="alert alert-danger"><?php echo e($__t('This page does not exist')); ?></h1>
		<div class="alert alert-info"><?php echo e($__t('You will be redirected to the default page in %s seconds', '5')); ?></div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('errors.base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/errors/404.blade.php ENDPATH**/ ?>