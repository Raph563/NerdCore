<?php if($mode == 'edit'): ?>
<?php $__env->startSection('title', $__t('Edit Barcode')); ?>
<?php else: ?>
<?php $__env->startSection('title', $__t('Create Barcode')); ?>
<?php endif; ?>

<?php $__env->startSection('content'); ?>
<script>
	Grocy.QuantityUnits = <?php echo json_encode($quantityUnits); ?>;
	Grocy.QuantityUnitConversionsResolved = <?php echo json_encode($quantityUnitConversionsResolved); ?>;
</script>

<div class="row">
	<div class="col">
		<div class="title-related-links">
			<h2 class="title">
				<?php echo $__env->yieldContent('title'); ?><br>
				<span class="text-muted small"><?php echo e($__t('Barcode for product')); ?> <strong><?php echo e($product->name); ?></strong></span>
			</h2>
		</div>
	</div>
</div>

<hr class="my-2">

<div class="row">
	<div class="col-lg-6 col-12">

		<script>
			Grocy.EditMode = '<?php echo e($mode); ?>';
			Grocy.EditObjectProduct = <?php echo json_encode($product); ?>;
		</script>

		<?php if($mode == 'edit'): ?>
		<script>
			Grocy.EditObjectId = <?php echo e($barcode->id); ?>;
			Grocy.EditObject = <?php echo json_encode($barcode); ?>;
		</script>
		<?php endif; ?>

		<form id="barcode-form"
			novalidate>

			<input type="hidden"
				name="product_id"
				value="<?php echo e($product->id); ?>">

			<div class="form-group">
				<label for="name"><?php echo e($__t('Barcode')); ?>&nbsp;<i class="fa-solid fa-barcode"></i></label>
				<div class="input-group">
					<input type="text"
						class="form-control barcodescanner-input"
						required
						id="barcode"
						name="barcode"
						value="<?php if($mode == 'edit'): ?><?php echo e($barcode->barcode); ?><?php endif; ?>"
						data-target="#barcode">
					<?php echo $__env->make('components.camerabarcodescanner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
				</div>
			</div>

			<?php if($mode == 'edit') { $value = $barcode->amount; } else { $value = ''; } ?>
			<?php echo $__env->make('components.productamountpicker', array(
			'value' => $value,
			'isRequired' => false
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<?php if(GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?>
			<div class="form-group">
				<label for="shopping_location_id_id"><?php echo e($__t('Store')); ?></label>
				<select class="custom-control custom-select"
					id="shopping_location_id"
					name="shopping_location_id">
					<option></option>
					<?php $__currentLoopData = $shoppinglocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option <?php if($mode=='edit'
						&&
						$store->id == $barcode->shopping_location_id): ?> selected="selected" <?php endif; ?> value="<?php echo e($store->id); ?>"><?php echo e($store->name); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
			<?php else: ?>
			<input type="hidden"
				name="shopping_location_id"
				id="shopping_location_id"
				value="1">
			<?php endif; ?>

			<div class="form-group">
				<label for="note"><?php echo e($__t('Note')); ?></label>
				<input type="text"
					class="form-control"
					id="note"
					name="note"
					value="<?php if($mode == 'edit'): ?><?php echo e($barcode->note); ?><?php endif; ?>">
			</div>

			<?php echo $__env->make('components.userfieldsform', array(
			'userfields' => $userfields,
			'entity' => 'product_barcodes'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<button id="save-barcode-button"
				class="btn btn-success"><?php echo e($__t('Save')); ?></button>

		</form>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/productbarcodeform.blade.php ENDPATH**/ ?>