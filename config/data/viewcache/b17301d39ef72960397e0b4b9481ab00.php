<?php $__env->startSection('title', $__t('Shopping list settings')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col">
		<h2 class="title"><?php echo $__env->yieldContent('title'); ?></h2>
	</div>
</div>

<hr class="my-2">

<div class="row">
	<div class="col-lg-4 col-md-8 col-12">
		<h4><?php echo e($__t('Shopping list')); ?></h4>

		<div class="form-group">
			<div class="custom-control custom-checkbox">
				<input type="checkbox"
					class="form-check-input custom-control-input user-setting-control"
					id="shopping_list_show_calendar"
					data-setting-key="shopping_list_show_calendar">
				<label class="form-check-label custom-control-label"
					for="shopping_list_show_calendar">
					<?php echo e($__t('Show a month-view calendar')); ?>

				</label>
			</div>
		</div>

		<div class="form-group">
			<div class="custom-control custom-checkbox">
				<input type="checkbox"
					class="form-check-input custom-control-input user-setting-control"
					id="shopping_list_round_up"
					data-setting-key="shopping_list_round_up">
				<label class="form-check-label custom-control-label"
					for="shopping_list_round_up">
					<?php echo e($__t('Round up quantity amounts to the nearest whole number')); ?>

				</label>
			</div>
		</div>

		<div class="form-group">
			<div class="custom-control custom-checkbox">
				<input type="checkbox"
					class="form-check-input custom-control-input user-setting-control"
					id="shopping_list_auto_add_below_min_stock_amount"
					data-setting-key="shopping_list_auto_add_below_min_stock_amount">
				<label class="form-check-label custom-control-label"
					for="shopping_list_auto_add_below_min_stock_amount">
					<?php echo e($__t('Automatically add products that are below their defined min. stock amount to the shopping list')); ?>

					<select class="custom-control custom-select user-setting-control"
						id="shopping_list_auto_add_below_min_stock_amount_list_id"
						data-setting-key="shopping_list_auto_add_below_min_stock_amount_list_id"
						<?php if(!boolval($userSettings['shopping_list_auto_add_below_min_stock_amount'])): ?>
						disabled
						<?php endif; ?>>
						<?php $__currentLoopData = $shoppingLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shoppingList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<option value="<?php echo e($shoppingList->id); ?>"><?php echo e($shoppingList->name); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</label>
			</div>
		</div>


		<h4 class="mt-5"><?php echo e($__t('Shopping list to stock workflow')); ?></h4>

		<div class="form-group">
			<div class="custom-control custom-checkbox">
				<input type="checkbox"
					class="form-check-input custom-control-input user-setting-control"
					id="shopping_list_to_stock_workflow_auto_submit_when_prefilled"
					data-setting-key="shopping_list_to_stock_workflow_auto_submit_when_prefilled">
				<label class="form-check-label custom-control-label"
					for="shopping_list_to_stock_workflow_auto_submit_when_prefilled">
					<?php echo e($__t('Automatically do the booking using the last price and the amount of the shopping list item, if the product has "Default due days" set')); ?>

				</label>
			</div>
		</div>

		<a href="<?php echo e($U('/shoppinglist')); ?>"
			class="btn btn-success"><?php echo e($__t('OK')); ?></a>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/shoppinglistsettings.blade.php ENDPATH**/ ?>