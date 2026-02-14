<?php $__env->startSection('title', $__t('Stock settings')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col">
		<h2 class="title"><?php echo $__env->yieldContent('title'); ?></h2>
	</div>
</div>

<hr class="my-2">

<div class="row">
	<div class="col-lg-4 col-md-8 col-12">
		<div id="productpresets">
			<h4><?php echo e($__t('Presets for new products')); ?></h4>

			<?php if(GROCY_FEATURE_FLAG_STOCK_LOCATION_TRACKING): ?>
			<div class="form-group">
				<label for="product_presets_location_id"><?php echo e($__t('Location')); ?></label>
				<select class="custom-control custom-select user-setting-control"
					id="product_presets_location_id"
					data-setting-key="product_presets_location_id">
					<option value="-1"></option>
					<?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($location->id); ?>"><?php echo e($location->name); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
			<?php endif; ?>

			<div class="form-group">
				<label for="product_presets_product_group_id"><?php echo e($__t('Product group')); ?></label>
				<select class="custom-control custom-select user-setting-control"
					id="product_presets_product_group_id"
					data-setting-key="product_presets_product_group_id">
					<option value="-1"></option>
					<?php $__currentLoopData = $productGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($productGroup->id); ?>"><?php echo e($productGroup->name); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>

			<div class="form-group">
				<label for="product_presets_qu_id"><?php echo e($__t('Quantity unit')); ?></label>
				<select class="custom-control custom-select user-setting-control"
					id="product_presets_qu_id"
					data-setting-key="product_presets_qu_id">
					<option value="-1"></option>
					<?php $__currentLoopData = $quantityunits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quantityunit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($quantityunit->id); ?>"><?php echo e($quantityunit->name); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>

			<?php echo $__env->make('components.numberpicker', array(
			'id' => 'product_presets_default_due_days',
			'additionalAttributes' => 'data-setting-key="product_presets_default_due_days"',
			'label' => 'Default due days',
			'min' => -1,
			'additionalCssClasses' => 'user-setting-control'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<?php if(GROCY_FEATURE_FLAG_STOCK_PRODUCT_OPENED_TRACKING): ?>
			<div class="form-group">
				<div class="custom-control custom-checkbox">
					<input type="checkbox"
						class="form-check-input custom-control-input user-setting-control"
						id="product_presets_treat_opened_as_out_of_stock"
						data-setting-key="product_presets_treat_opened_as_out_of_stock">
					<label class="form-check-label custom-control-label"
						for="product_presets_treat_opened_as_out_of_stock">
						<?php echo e($__t('Treat opened as out of stock')); ?>

					</label>
				</div>
			</div>
			<?php endif; ?>

			<?php if(GROCY_FEATURE_FLAG_LABEL_PRINTER): ?>
			<div class="form-group">
				<label for="product_presets_default_stock_label_type"><?php echo e($__t('Default stock entry label')); ?></label>
				<select class="custom-control custom-select user-setting-control"
					id="product_presets_default_stock_label_type"
					data-setting-key="product_presets_default_stock_label_type">
					<option value="0"><?php echo e($__t('No label')); ?></option>
					<option value="1"><?php echo e($__t('Single label')); ?></option>
					<option value="2"><?php echo e($__t('Label per unit')); ?></option>
				</select>
			</div>
			<?php endif; ?>
		</div>

		<h4 class="mt-5"><?php echo e($__t('Stock overview')); ?></h4>
		<?php echo $__env->make('components.numberpicker', array(
		'id' => 'stock_due_soon_days',
		'additionalAttributes' => 'data-setting-key="stock_due_soon_days"',
		'label' => 'Due soon days',
		'min' => 1,
		'additionalCssClasses' => 'user-setting-control'
		), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

		<div class="form-group">
			<div class="custom-control custom-checkbox">
				<input type="checkbox"
					class="form-check-input custom-control-input user-setting-control"
					id="show_icon_on_stock_overview_page_when_product_is_on_shopping_list"
					data-setting-key="show_icon_on_stock_overview_page_when_product_is_on_shopping_list">
				<label class="form-check-label custom-control-label"
					for="show_icon_on_stock_overview_page_when_product_is_on_shopping_list">
					<?php echo e($__t('Show an icon if the product is already on the shopping list')); ?>

				</label>
			</div>
		</div>

		<div class="form-group">
			<div class="custom-control custom-checkbox">
				<input type="checkbox"
					class="form-check-input custom-control-input user-setting-control"
					id="stock_overview_show_all_out_of_stock_products"
					data-setting-key="stock_overview_show_all_out_of_stock_products">
				<label class="form-check-label custom-control-label"
					for="stock_overview_show_all_out_of_stock_products">
					<?php echo e($__t('Show all out of stock products')); ?>

					<i class="fa-solid fa-question-circle text-muted"
						data-toggle="tooltip"
						data-trigger="hover click"
						title="<?php echo e($__t('By default the stock overview page lists all products which are currently in stock or below their min. stock amount - when this is enabled, all (active) products are always shown')); ?>"></i>
				</label>
			</div>
		</div>

		<h4 class="mt-5"><?php echo e($__t('Purchase')); ?></h4>
		<?php echo $__env->make('components.numberpicker', array(
		'id' => 'stock_default_purchase_amount',
		'additionalAttributes' => 'data-setting-key="stock_default_purchase_amount"',
		'label' => 'Default amount for purchase',
		'min' => '0.',
		'decimals' => $userSettings['stock_decimal_places_amounts'],
		'additionalCssClasses' => 'user-setting-control locale-number-input locale-number-quantity-amount',
		), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

		<div class="form-group">
			<div class="custom-control custom-checkbox">
				<input type="checkbox"
					class="form-check-input custom-control-input user-setting-control"
					id="show_purchased_date_on_purchase"
					data-setting-key="show_purchased_date_on_purchase">
				<label class="form-check-label custom-control-label"
					for="show_purchased_date_on_purchase">
					<?php echo e($__t('Show purchased date on purchase and inventory page (otherwise the purchased date defaults to today)')); ?>

				</label>
			</div>
		</div>

		<div class="form-group">
			<div class="custom-control custom-checkbox">
				<input type="checkbox"
					class="form-check-input custom-control-input user-setting-control"
					id="show_warning_on_purchase_when_due_date_is_earlier_than_next"
					data-setting-key="show_warning_on_purchase_when_due_date_is_earlier_than_next">
				<label class="form-check-label custom-control-label"
					for="show_warning_on_purchase_when_due_date_is_earlier_than_next">
					<?php echo e($__t('Show a warning when the due date of the purchased product is earlier than the next due date in stock')); ?>

				</label>
			</div>
		</div>

		<h4 class="mt-5"><?php echo e($__t('Consume')); ?></h4>
		<?php echo $__env->make('components.numberpicker', array(
		'id' => 'stock_default_consume_amount',
		'additionalAttributes' => 'data-setting-key="stock_default_consume_amount"',
		'label' => 'Default amount for consume',
		'min' => 0,
		'decimals' => $userSettings['stock_decimal_places_amounts'],
		'additionalCssClasses' => 'user-setting-control locale-number-input locale-number-quantity-amount',
		'additionalGroupCssClasses' => 'mb-0'
		), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

		<div class="form-group">
			<div class="custom-control custom-checkbox">
				<input type="checkbox"
					class="form-check-input custom-control-input user-setting-control"
					id="stock_default_consume_amount_use_quick_consume_amount"
					data-setting-key="stock_default_consume_amount_use_quick_consume_amount">
				<label class="form-check-label custom-control-label"
					for="stock_default_consume_amount_use_quick_consume_amount">
					<?php echo e($__t('Use the products "Quick consume amount"')); ?>

				</label>
			</div>
		</div>

		<h4 class="mt-5"><?php echo e($__t('Common')); ?></h4>

		<?php echo $__env->make('components.numberpicker', array(
		'id' => 'stock_decimal_places_amounts',
		'additionalAttributes' => 'data-setting-key="stock_decimal_places_amounts"',
		'label' => 'Decimal places allowed for amounts',
		'min' => 0,
		'max' => 10,
		'additionalCssClasses' => 'user-setting-control'
		), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

		<?php if(GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?>

		<?php echo $__env->make('components.numberpicker', array(
		'id' => 'stock_decimal_places_prices_input',
		'additionalAttributes' => 'data-setting-key="stock_decimal_places_prices_input"',
		'label' => 'Decimal places allowed for prices (input)',
		'min' => 0,
		'max' => 10,
		'additionalCssClasses' => 'user-setting-control'
		), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

		<?php echo $__env->make('components.numberpicker', array(
		'id' => 'stock_decimal_places_prices_display',
		'additionalAttributes' => 'data-setting-key="stock_decimal_places_prices_display"',
		'label' => 'Decimal places allowed for prices (display)',
		'min' => 0,
		'max' => 10,
		'additionalCssClasses' => 'user-setting-control'
		), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

		<div class="form-group mt-n3">
			<div class="custom-control custom-checkbox">
				<input type="checkbox"
					class="form-check-input custom-control-input user-setting-control"
					id="stock_auto_decimal_separator_prices"
					data-setting-key="stock_auto_decimal_separator_prices">
				<label class="form-check-label custom-control-label"
					for="stock_auto_decimal_separator_prices">
					<?php echo e($__t('Add decimal separator automatically for price inputs')); ?>

					<i class="fa-solid fa-question-circle text-muted"
						data-toggle="tooltip"
						data-trigger="hover click"
						title="<?php echo e($__t('When enabled, you always have to enter the value including decimal places, the decimal separator will be automatically added based on the amount of allowed decimal places')); ?>"></i>
				</label>
			</div>
		</div>

		<?php endif; ?>

		<a href="<?php echo e($U('/stockoverview')); ?>"
			class="btn btn-success"><?php echo e($__t('OK')); ?></a>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/stocksettings.blade.php ENDPATH**/ ?>