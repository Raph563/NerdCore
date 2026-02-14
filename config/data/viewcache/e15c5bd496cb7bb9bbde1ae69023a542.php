<?php if($mode == 'edit'): ?>
<?php $__env->startSection('title', $__t('Edit QU conversion')); ?>
<?php else: ?>
<?php $__env->startSection('title', $__t('Create QU conversion')); ?>
<?php endif; ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col">
		<div class="title-related-links">
			<h2 class="title">
				<?php echo $__env->yieldContent('title'); ?><br>
				<?php if($product != null): ?>
				<span class="text-muted small"><?php echo e($__t('Override for product')); ?> <strong><?php echo e($product->name); ?></strong></span>
				<?php else: ?>
				<span class="text-muted small"><?php echo e($__t('Default for QU')); ?> <strong><?php echo e($defaultQuUnit->name); ?></strong></span>
				<?php endif; ?>
			</h2>
		</div>
	</div>
</div>

<hr class="my-2">

<div class="row">
	<div class="col-lg-6 col-12">

		<script>
			Grocy.EditMode = '<?php echo e($mode); ?>';
		</script>

		<?php if($mode == 'edit'): ?>
		<script>
			Grocy.EditObjectId = <?php echo e($quConversion->id); ?>;
		</script>
		<?php endif; ?>

		<form id="quconversion-form"
			novalidate>

			<?php if($product != null): ?>
			<input type="hidden"
				name="product_id"
				value="<?php echo e($product->id); ?>">
			<?php endif; ?>

			<div class="form-group">
				<label for="from_qu_id"><?php echo e($__t('Quantity unit from')); ?></label>
				<select required
					class="custom-control custom-select input-group-qu"
					id="from_qu_id"
					name="from_qu_id">
					<option></option>
					<?php $__currentLoopData = $quantityunits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quantityunit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php
					$selected = false;
					if ($mode == 'edit')
					{
					if ($quantityunit->id == $quConversion->from_qu_id)
					{
					$selected = true;
					}
					}
					else
					{
					if ($product != null && $quantityunit->id == $product->qu_id_stock)
					{
					$selected = true;
					}
					else
					{
					if ($quantityunit->id == $defaultQuUnit->id)
					{
					$selected = true;
					}
					}
					}
					?>
					<option <?php if($selected): ?>
						selected="selected"
						<?php endif; ?>
						value="<?php echo e($quantityunit->id); ?>"
						data-plural-form="<?php echo e($quantityunit->name_plural); ?>"><?php echo e($quantityunit->name); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
				<div class="invalid-feedback"><?php echo e($__t('A quantity unit is required')); ?></div>
			</div>

			<div class="form-group">
				<label for="to_qu_id"><?php echo e($__t('Quantity unit to')); ?></label>
				<select required
					class="custom-control custom-select input-group-qu"
					id="to_qu_id"
					name="to_qu_id">
					<option></option>
					<?php $__currentLoopData = $quantityunits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quantityunit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option <?php if($mode=='edit'
						&&
						$quantityunit->id == $quConversion->to_qu_id): ?> selected="selected" <?php endif; ?> value="<?php echo e($quantityunit->id); ?>" data-plural-form="<?php echo e($quantityunit->name_plural); ?>"><?php echo e($quantityunit->name); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
				<div class="invalid-feedback"><?php echo e($__t('A quantity unit is required')); ?></div>
			</div>

			<?php if($mode == 'edit') { $value = $quConversion->factor; } else { $value = 1; } ?>
			<?php echo $__env->make('components.numberpicker', array(
			'id' => 'factor',
			'label' => 'Factor',
			'min' => $DEFAULT_MIN_AMOUNT,
			'decimals' => $userSettings['stock_decimal_places_amounts'],
			'value' => $value,
			'additionalHtmlElements' => '<p id="qu-conversion-info"
				class="form-text text-info d-none mb-0"></p>
			<p id="qu-conversion-inverse-info"
				class="form-text text-info d-none"></p>',
			'additionalCssClasses' => 'input-group-qu locale-number-input locale-number-quantity-amount'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<?php echo $__env->make('components.userfieldsform', array(
			'userfields' => $userfields,
			'entity' => 'quantity_unit_conversions'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<button id="save-quconversion-button"
				class="btn btn-success"><?php echo e($__t('Save')); ?></button>

		</form>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/quantityunitconversionform.blade.php ENDPATH**/ ?>