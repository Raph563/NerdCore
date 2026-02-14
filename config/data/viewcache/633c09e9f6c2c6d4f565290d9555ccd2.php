<?php require_frontend_packages(['animatecss']); ?>



<?php $__env->startSection('title', $__t('Barcode scanner testing')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col">
		<h2 class="title"><?php echo $__env->yieldContent('title'); ?></h2>
	</div>
</div>

<hr class="my-2">

<div class="row">
	<div class="col-lg-6 col-12">

		<form id="barcodescannertesting-form"
			novalidate>

			<div class="form-group">
				<label for="expected_barcode"><?php echo e($__t('Expected barcode')); ?></label>
				<input type="text"
					class="form-control"
					required
					id="expected_barcode"
					name="expected_barcode"
					value="">
			</div>

			<div class="form-group">
				<label for="scanned_barcode"><?php echo e($__t('Scan field')); ?></label>
				<div class="input-group">
					<input type="text"
						class="form-control barcodescanner-input"
						id="scanned_barcode"
						name="scanned_barcode"
						value=""
						disabled
						data-target="#scanned_barcode">
				</div>
			</div>

			<div class="form-group">
				<label for="scanned_codes"><?php echo e($__t('Scanned barcodes')); ?></label>
				<div class="float-right font-weight-bold">
					<span class="text-success"><?php echo e($__t('Hit')); ?>: <span id="hit-count"
							class="locale-number locale-number-generic">0</span></span> //
					<span class="text-danger"><?php echo e($__t('Miss')); ?>: <span id="miss-count"
							class="locale-number locale-number-generic">0</span></span>
				</div>
				<select class="custom-control custom-select"
					id="scanned_codes"
					name="scanned_codes"
					multiple
					size="30"></select>
			</div>

		</form>
	</div>
</div>

<?php echo $__env->make('components.camerabarcodescanner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/barcodescannertesting.blade.php ENDPATH**/ ?>