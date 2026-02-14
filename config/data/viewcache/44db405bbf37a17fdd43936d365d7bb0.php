<?php $__env->startSection('title', $__t('Inventory')); ?>

<?php $__env->startSection('content'); ?>
<script>
	Grocy.QuantityUnits = <?php echo json_encode($quantityUnits); ?>;
	Grocy.QuantityUnitConversionsResolved = <?php echo json_encode($quantityUnitConversionsResolved); ?>;
	Grocy.DefaultMinAmount = '<?php echo e($DEFAULT_MIN_AMOUNT); ?>';
</script>

<div class="row">
	<div class="col-12 col-md-6 col-xl-4 pb-3">
		<h2 class="title"><?php echo $__env->yieldContent('title'); ?></h2>

		<hr class="my-2">

		<form id="inventory-form"
			novalidate>

			<?php echo $__env->make('components.productpicker', array(
			'products' => $products,
			'barcodes' => $barcodes,
			'nextInputSelector' => '#new_amount'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<?php echo $__env->make('components.productamountpicker', array(
			'value' => 1,
			'label' => 'New stock amount',
			'additionalHtmlElements' => '<div id="inventory-change-info"
				class="form-text text-muted d-none ml-3 my-0 w-100"></div>',
			'additionalHtmlContextHelp' => '<div id="tare-weight-handling-info"
				class="text-info font-italic d-none">' . $__t('Tare weight handling enabled - please weigh the whole container, the amount to be posted will be automatically calculcated') . '</div>'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<?php if(boolval($userSettings['show_purchased_date_on_purchase'])): ?>
			<?php echo $__env->make('components.datetimepicker2', array(
			'id' => 'purchased_date',
			'label' => 'Purchased date',
			'format' => 'YYYY-MM-DD',
			'hint' => $__t('This will apply to added products'),
			'initWithNow' => true,
			'limitEndToNow' => false,
			'limitStartToNow' => false,
			'invalidFeedback' => $__t('A purchased date is required'),
			'nextInputSelector' => '#best_before_date',
			'additionalCssClasses' => 'date-only-datetimepicker2',
			'activateNumberPad' => GROCY_FEATURE_FLAG_STOCK_BEST_BEFORE_DATE_FIELD_NUMBER_PAD
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
			<?php endif; ?>

			<?php
			$additionalGroupCssClasses = '';
			if (!GROCY_FEATURE_FLAG_STOCK_BEST_BEFORE_DATE_TRACKING)
			{
			$additionalGroupCssClasses = 'd-none';
			}
			?>
			<?php echo $__env->make('components.datetimepicker', array(
			'id' => 'best_before_date',
			'label' => 'Due date',
			'hint' => $__t('This will apply to added products'),
			'format' => 'YYYY-MM-DD',
			'initWithNow' => false,
			'limitEndToNow' => false,
			'limitStartToNow' => false,
			'invalidFeedback' => $__t('A due date is required'),
			'nextInputSelector' => '#best_before_date',
			'additionalGroupCssClasses' => 'date-only-datetimepicker',
			'shortcutValue' => '2999-12-31',
			'shortcutLabel' => 'Never overdue',
			'earlierThanInfoLimit' => date('Y-m-d'),
			'earlierThanInfoText' => $__t('The given date is earlier than today, are you sure?'),
			'additionalGroupCssClasses' => $additionalGroupCssClasses,
			'activateNumberPad' => GROCY_FEATURE_FLAG_STOCK_BEST_BEFORE_DATE_FIELD_NUMBER_PAD
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
			<?php $additionalGroupCssClasses = ''; ?>

			<?php if(GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?>
			<?php echo $__env->make('components.numberpicker', array(
			'id' => 'price',
			'label' => 'Price',
			'min' => '0.' . str_repeat('0', $userSettings['stock_decimal_places_prices_input']),
			'decimals' => $userSettings['stock_decimal_places_prices_input'],
			'value' => '',
			'contextInfoId' => 'price-hint',
			'hint' => $__t('This will apply to added products'),
			'isRequired' => false,
			'additionalCssClasses' => 'locale-number-input locale-number-currency'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<?php echo $__env->make('components.shoppinglocationpicker', array(
			'label' => 'Store',
			'shoppinglocations' => $shoppinglocations,
			'hint' => $__t('This will apply to added products'),
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
			<?php else: ?>
			<input type="hidden"
				name="price"
				id="price"
				value="0">
			<?php endif; ?>

			<?php if(GROCY_FEATURE_FLAG_STOCK_LOCATION_TRACKING): ?>
			<?php echo $__env->make('components.locationpicker', array(
			'locations' => $locations,
			'hint' => $__t('This will apply to added products')
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
			<?php endif; ?>

			<?php if(GROCY_FEATURE_FLAG_LABEL_PRINTER): ?>
			<div class="form-group">
				<label for="stock_label_type">
					<?php echo e($__t('Stock entry label')); ?>

					<i class="fa-solid fa-question-circle text-muted"
						data-toggle="tooltip"
						data-trigger="hover click"
						title="<?php echo e($__t('This will apply to added products')); ?>"></i>
				</label>
				<select class="custom-control custom-select"
					id="stock_label_type"
					name="stock_label_type">
					<option value="0"><?php echo e($__t('No label')); ?></option>
					<option value="1"><?php echo e($__t('Single label')); ?></option>
					<option value="2"><?php echo e($__t('Label per unit')); ?></option>
				</select>
				<div id="stock-entry-label-info"
					class="form-text text-info"></div>
			</div>
			<?php endif; ?>

			<div class="form-group">
				<label for="note">
					<?php echo e($__t('Note')); ?>

					<i class="fa-solid fa-question-circle text-muted"
						data-toggle="tooltip"
						data-trigger="hover click"
						title="<?php echo e($__t('This will apply to added products')); ?>"></i>
				</label>
				<div class="input-group">
					<input type="text"
						class="form-control"
						id="note"
						name="note">
				</div>
			</div>

			<?php echo $__env->make('components.userfieldsform', array(
			'userfields' => $userfields,
			'entity' => 'stock'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<button id="save-inventory-button"
				class="btn btn-success"><?php echo e($__t('OK')); ?></button>

		</form>
	</div>

	<div class="col-12 col-md-6 col-xl-4 hide-when-embedded">
		<?php echo $__env->make('components.productcard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/inventory.blade.php ENDPATH**/ ?>