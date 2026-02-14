<?php if($mode == 'edit'): ?>
<?php $__env->startSection('title', $__t('Edit recipe ingredient')); ?>
<?php else: ?>
<?php $__env->startSection('title', $__t('Add recipe ingredient')); ?>
<?php endif; ?>

<?php $__env->startSection('content'); ?>
<script>
	Grocy.DefaultMinAmount = '<?php echo e($DEFAULT_MIN_AMOUNT); ?>';
</script>

<div class="row">
	<div class="col">
		<div class="title-related-links">
			<h2 class="title">
				<?php echo $__env->yieldContent('title'); ?><br>
				<span class="text-muted small"><?php echo e($__t('Recipe')); ?> <strong><?php echo e($recipe->name); ?></strong></span>
			</h2>
		</div>
	</div>
</div>

<hr class="my-2">

<div class="row">
	<div class="col-12 col-md-6 col-xl-5 pb-3">
		<script>
			Grocy.EditMode = '<?php echo e($mode); ?>';
			Grocy.EditObjectParentId = <?php echo e($recipe->id); ?>;
			Grocy.EditObject = <?php echo json_encode($recipePos); ?>;
			Grocy.QuantityUnits = <?php echo json_encode($quantityUnits); ?>;
			Grocy.QuantityUnitConversionsResolved = <?php echo json_encode($quantityUnitConversionsResolved); ?>;
		</script>

		<?php if($mode == 'edit'): ?>
		<script>
			Grocy.EditObjectId = <?php echo e($recipePos->id); ?>;
		</script>
		<?php endif; ?>

		<form id="recipe-pos-form"
			novalidate>

			<?php echo $__env->make('components.productpicker', array(
			'products' => $products,
			'barcodes' => $barcodes,
			'nextInputSelector' => '#amount'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<div class="form-group mb-2 <?php if(!GROCY_FEATURE_FLAG_STOCK): ?> d-none <?php endif; ?>">
				<div class="custom-control custom-checkbox">
					<input <?php if($mode=='edit'
						&&
						$recipePos->only_check_single_unit_in_stock == 1): ?> checked <?php endif; ?> class="form-check-input custom-control-input" type="checkbox" id="only_check_single_unit_in_stock" name="only_check_single_unit_in_stock" value="1">
					<label class="form-check-label custom-control-label"
						for="only_check_single_unit_in_stock"><?php echo e($__t('Only check if any amount is in stock')); ?>&nbsp;<i class="fa-solid fa-question-circle text-muted"
							data-toggle="tooltip"
							data-trigger="hover click"
							title="<?php echo e($__t('A different amount/unit can then be used below while for stock fulfillment checking it is sufficient when any amount of the product in stock')); ?>"></i></label>
				</div>
			</div>

			<?php if($mode == 'edit') { $value = $recipePos->amount; } else { $value = 1; } ?>
			<?php if($mode == 'edit') { $initialQuId = $recipePos->qu_id; } else { $initialQuId = ''; } ?>
			<?php echo $__env->make('components.productamountpicker', array(
			'value' => $value,
			'initialQuId' => $initialQuId,
			'additionalGroupCssClasses' => 'mb-2'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<div class="form-group">
				<label for="variable_amount"><?php echo e($__t('Variable amount')); ?>&nbsp;<i class="fa-solid fa-question-circle text-muted"
						data-toggle="tooltip"
						data-trigger="hover click"
						title="<?php echo e($__t('When this is not empty, it will be shown instead of the amount entered above while the amount there will still be used for stock fulfillment checking')); ?>"></i></label>
				<input type="text"
					class="form-control"
					id="variable_amount"
					name="variable_amount"
					value="<?php if($mode == 'edit'): ?><?php echo e($recipePos->variable_amount); ?><?php endif; ?>">

				<div class="custom-control custom-checkbox">
					<input <?php if($mode=='edit'
						&&
						$recipePos->round_up == 1): ?> checked <?php endif; ?> class="form-check-input custom-control-input" type="checkbox" id="round_up" name="round_up" value="1">
					<label class="form-check-label custom-control-label"
						for="round_up"><?php echo e($__t('Round up quantity amounts to the nearest whole number')); ?></label>
				</div>
			</div>

			<div class="form-group <?php if(!GROCY_FEATURE_FLAG_STOCK): ?> d-none <?php endif; ?>">
				<div class="custom-control custom-checkbox">
					<input <?php if($mode=='edit'
						&&
						($recipePos->not_check_stock_fulfillment == 1 || FindObjectInArrayByPropertyValue($products, 'id', $recipePos->product_id)->not_check_stock_fulfillment_for_recipes == 1)): ?> checked <?php endif; ?> class="form-check-input custom-control-input" type="checkbox" id="not_check_stock_fulfillment" name="not_check_stock_fulfillment" value="1">
					<label class="form-check-label custom-control-label"
						for="not_check_stock_fulfillment"><?php echo e($__t('Disable stock fulfillment checking for this ingredient')); ?></label>
				</div>
			</div>

			<div class="form-group">
				<label for="ingredient_group"><?php echo e($__t('Group')); ?>&nbsp;<i class="fa-solid fa-question-circle text-muted"
						data-toggle="tooltip"
						data-trigger="hover click"
						title="<?php echo e($__t('This will be used as a headline to group ingredients together')); ?>"></i></label>
				<input type="text"
					class="form-control"
					id="ingredient_group"
					name="ingredient_group"
					value="<?php if($mode == 'edit'): ?><?php echo e($recipePos->ingredient_group); ?><?php endif; ?>">
			</div>

			<div class="form-group">
				<label for="note"><?php echo e($__t('Note')); ?></label>
				<textarea class="form-control"
					rows="2"
					id="note"
					name="note"><?php if($mode == 'edit'): ?><?php echo e($recipePos->note); ?><?php endif; ?></textarea>
			</div>

			<?php if(GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?>
			<?php if($mode == 'edit') { $value = $recipePos->price_factor; } else { $value = 1; } ?>
			<?php echo $__env->make('components.numberpicker', array(
			'id' => 'price_factor',
			'label' => 'Price factor',
			'min' => $DEFAULT_MIN_AMOUNT,
			'decimals' => $userSettings['stock_decimal_places_amounts'],
			'value' => '',
			'hint' => $__t('The resulting price of this ingredient will be multiplied by this factor'),
			'isRequired' => true,
			'value' => $value,
			'additionalCssClasses' => 'locale-number-input locale-number-quantity-amount'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
			<?php else: ?>
			<input type="hidden"
				name="price_factor"
				id="price"
				value="1">
			<?php endif; ?>

			<button id="save-recipe-pos-button"
				class="btn btn-success"><?php echo e($__t('Save')); ?></button>

		</form>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/recipeposform.blade.php ENDPATH**/ ?>