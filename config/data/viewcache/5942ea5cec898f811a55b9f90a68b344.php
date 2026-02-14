<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col">
		<div class="alert alert-dark py-1">
			<h4><?php echo e($__t('Error source')); ?></h4>
			<pre class="my-0"><code><?php echo $exception->getFile(); ?>:<?php echo $exception->getLine(); ?></code></pre>
		</div>
		<div class="alert alert-dark py-1">
			<h4><?php echo e($__t('Error message')); ?></h4>
			<pre class="my-0"><code><?php echo $exception->getMessage(); ?></code></pre>
		</div>
		<div class="alert alert-dark py-1">
			<h4><?php echo e($__t('Stack trace')); ?></h4>
			<pre class="my-0"><code><?php echo $exception->getTraceAsString(); ?></code></pre>
		</div>
		<div class="alert alert-dark py-1">
			<h4><?php echo e($__t('Easy error info copy & paste (for reporting)')); ?></h4>
			<textarea class="form-control easy-link-copy-textbox text-monospace mt-1"
				rows="20">
Error source:
```
<?php echo $exception->getFile(); ?>:<?php echo $exception->getLine(); ?>

```

Error message:
```
<?php echo $exception->getMessage(); ?>

```

Stack trace:
```
<?php echo $exception->getTraceAsString(); ?>

```

System info:
```
<?php echo json_encode($systemInfo, JSON_PRETTY_PRINT); ?>

```
			</textarea>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/errors/base.blade.php ENDPATH**/ ?>