<?php if($mode == 'edit'): ?>
<?php $__env->startSection('title', $__t('Edit shopping list item')); ?>
<?php else: ?>
<?php $__env->startSection('title', $__t('Create shopping list item')); ?>
<?php endif; ?>

<?php $__env->startSection('content'); ?>
<script>
	Grocy.QuantityUnits = <?php echo json_encode($quantityUnits); ?>;
	Grocy.QuantityUnitConversionsResolved = <?php echo json_encode($quantityUnitConversionsResolved); ?>;
</script>

<div class="row">
	<div class="col">
		<h2 class="title"><?php echo $__env->yieldContent('title'); ?></h2>
	</div>
</div>

<hr class="my-2">

<div class="row">
	<div class="col-12 col-md-6 col-xl-4 pb-3">
		<script>
			Grocy.EditMode = '<?php echo e($mode); ?>';
		</script>

		<?php if($mode == 'edit'): ?>
		<script>
			Grocy.EditObjectId = <?php echo e($listItem->id); ?>;
		</script>
		<?php endif; ?>

		<form id="shoppinglist-form"
			novalidate>

			<?php if(GROCY_FEATURE_FLAG_SHOPPINGLIST_MULTIPLE_LISTS): ?>
			<div class="form-group">
				<label for="shopping_list_id"><?php echo e($__t('Shopping list')); ?></label>
				<select class="custom-control custom-select"
					id="shopping_list_id"
					name="shopping_list_id">
					<?php $__currentLoopData = $shoppingLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shoppingList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option <?php if($mode=='edit'
						&&
						$shoppingList->id == $listItem->shopping_list_id): ?> selected="selected" <?php endif; ?> value="<?php echo e($shoppingList->id); ?>"><?php echo e($shoppingList->name); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
			<?php else: ?>
			<input type="hidden"
				id="shopping_list_id"
				name="shopping_list_id"
				value="1">
			<?php endif; ?>

			<div>
				<?php if($mode == 'edit') { $productId = $listItem->product_id; } else { $productId = ''; } ?>
				<?php echo $__env->make('components.productpicker', array(
				'products' => $products,
				'barcodes' => $barcodes,
				'nextInputSelector' => '#amount',
				'isRequired' => true,
				'prefillById' => $productId,
				'validationMessage' => 'A product or a note is required'
				), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
			</div>

			<?php if($mode == 'edit') { $value = $listItem->amount; } else { $value = 1; } ?>
			<?php if($mode == 'edit') { $initialQuId = $listItem->qu_id; } else { $initialQuId = ''; } ?>
			<?php echo $__env->make('components.productamountpicker', array(
			'value' => $value,
			'initialQuId' => $initialQuId,
			'min' => $DEFAULT_MIN_AMOUNT,
			'isRequired' => false
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<div class="form-group">
				<label for="note"><?php echo e($__t('Note')); ?></label>
				<textarea class="form-control"
					required
					rows="10"
					id="note"
					name="note"><?php if($mode == 'edit'): ?><?php echo e($listItem->note); ?><?php endif; ?></textarea>
				<div class="invalid-feedback"><?php echo e($__t('A product or a note is required')); ?></div>
			</div>

			<?php echo $__env->make('components.userfieldsform', array(
			'userfields' => $userfields,
			'entity' => 'shopping_list'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<button id="save-shoppinglist-button"
				class="btn btn-success"><?php echo e($__t('Save')); ?></button>

		</form>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/shoppinglistitemform.blade.php ENDPATH**/ ?>