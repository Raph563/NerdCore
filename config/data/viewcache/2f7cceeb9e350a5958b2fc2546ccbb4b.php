<?php if(!GROCY_FEATURE_FLAG_DISABLE_BROWSER_BARCODE_CAMERA_SCANNING): ?>

<?php require_frontend_packages(['zxing']); ?>

<?php if (! $__env->hasRenderedOnce('ef0c0869-ca4d-4435-80f7-47254174ebf8')): $__env->markAsRenderedOnce('ef0c0869-ca4d-4435-80f7-47254174ebf8'); ?>
<?php $__env->startPush('componentScripts'); ?>
<script src="<?php echo e($U('/viewjs/components/camerabarcodescanner.js', true)); ?>?v=<?php echo e($version); ?>"></script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php $__env->startPush('pageStyles'); ?>
<style>
	#camerabarcodescanner-start-button {
		position: absolute;
		right: 0;
		margin-top: 4px;
		margin-right: 5px;
		cursor: pointer;
	}

	.combobox-container #camerabarcodescanner-start-button {
		margin-right: 38px !important;
	}
</style>
<?php $__env->stopPush(); ?>

<?php endif; ?>
<?php /**PATH /app/www/views/components/camerabarcodescanner.blade.php ENDPATH**/ ?>