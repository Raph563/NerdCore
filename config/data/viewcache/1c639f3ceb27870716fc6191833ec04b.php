<?php $__env->startSection('title', $__t('Purchase')); ?>

<?php $__env->startPush('pageScripts'); ?>
<script src="<?php echo e($U('/js/grocy_uisound.js?v=', true)); ?><?php echo e($version); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<script>
	Grocy.QuantityUnits = <?php echo json_encode($quantityUnits); ?>;
	Grocy.QuantityUnitConversionsResolved = <?php echo json_encode($quantityUnitConversionsResolved); ?>;
	Grocy.DefaultMinAmount = '<?php echo e($DEFAULT_MIN_AMOUNT); ?>';
</script>

<div class="row">
	<div class="col-12 col-md-6 col-xl-4 pb-3">
		<div class="title-related-links">
			<h2 class="title"><?php echo $__env->yieldContent('title'); ?></h2>
			<button class="btn btn-outline-dark d-md-none mt-2 float-right order-1 order-md-3 hide-when-embedded"
				type="button"
				data-toggle="collapse"
				data-target="#related-links">
				<i class="fa-solid fa-ellipsis-v"></i>
			</button>
			<div class="related-links collapse d-md-flex order-2 width-xs-sm-100"
				id="related-links">
				<?php if(!$embedded): ?>
				<button id="scan-mode-button"
					class="btn <?php if(boolval($userSettings['scan_mode_purchase_enabled'])): ?> btn-success <?php else: ?> btn-danger <?php endif; ?> m-1 mt-md-0 mb-md-0 float-right"
					data-toggle="tooltip"
					title="<?php echo e($__t('When enabled, after changing/scanning a product and if all fields could be automatically populated (by product and/or barcode defaults), the transaction is automatically submitted')); ?>"><?php echo e($__t('Scan mode')); ?> <span id="scan-mode-status"><?php if(boolval($userSettings['scan_mode_purchase_enabled'])): ?> <?php echo e($__t('on')); ?> <?php else: ?> <?php echo e($__t('off')); ?> <?php endif; ?></span></button>
				<input id="scan-mode"
					type="checkbox"
					class="d-none user-setting-control"
					data-setting-key="scan_mode_purchase_enabled"
					<?php if(boolval($userSettings['scan_mode_purchase_enabled'])): ?>
					checked
					<?php endif; ?>>
				<?php else: ?>
				<script>
					Grocy.UserSettings.scan_mode_purchase_enabled = false;
				</script>
				<?php endif; ?>
			</div>
		</div>

		<hr class="my-2">

		<form id="purchase-form"
			novalidate>

			<?php echo $__env->make('components.productpicker', array(
			'products' => $products,
			'barcodes' => $barcodes,
			'nextInputSelector' => '#display_amount'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<?php echo $__env->make('components.productamountpicker', array(
			'value' => 1,
			'additionalHtmlContextHelp' => '<div id="tare-weight-handling-info"
				class="text-info font-italic d-none">' . $__t('Tare weight handling enabled - please weigh the whole container, the amount to be posted will be automatically calculcated') . '</div>'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<?php if(boolval($userSettings['show_purchased_date_on_purchase'])): ?>
			<?php echo $__env->make('components.datetimepicker2', array(
			'id' => 'purchased_date',
			'label' => 'Purchased date',
			'format' => 'YYYY-MM-DD',
			'initWithNow' => true,
			'limitEndToNow' => false,
			'limitStartToNow' => false,
			'invalidFeedback' => $__t('A purchased date is required'),
			'nextInputSelector' => '#best_before_date',
			'additionalCssClasses' => 'date-only-datetimepicker2',
			'activateNumberPad' => GROCY_FEATURE_FLAG_STOCK_BEST_BEFORE_DATE_FIELD_NUMBER_PAD
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
			<?php endif; ?>

			<?php if(GROCY_FEATURE_FLAG_STOCK_BEST_BEFORE_DATE_TRACKING): ?>
			<?php echo $__env->make('components.datetimepicker', array(
			'id' => 'best_before_date',
			'label' => 'Due date',
			'format' => 'YYYY-MM-DD',
			'initWithNow' => false,
			'limitEndToNow' => false,
			'limitStartToNow' => false,
			'invalidFeedback' => $__t('A due date is required'),
			'nextInputSelector' => '#price',
			'additionalCssClasses' => 'date-only-datetimepicker',
			'shortcutValue' => '2999-12-31',
			'shortcutLabel' => 'Never overdue',
			'earlierThanInfoLimit' => date('Y-m-d'),
			'earlierThanInfoText' => $__t('The given date is earlier than today, are you sure?'),
			'activateNumberPad' => GROCY_FEATURE_FLAG_STOCK_BEST_BEFORE_DATE_FIELD_NUMBER_PAD
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
			<?php endif; ?>

			<?php if(GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?>
			<?php echo $__env->make('components.numberpicker', array(
			'id' => 'price',
			'label' => 'Price',
			'min' => '0.' . str_repeat('0', $userSettings['stock_decimal_places_prices_input']),
			'decimals' => $userSettings['stock_decimal_places_prices_input'],
			'value' => '',
			'contextInfoId' => 'price-hint',
			'isRequired' => false,
			'additionalGroupCssClasses' => 'mb-1',
			'additionalCssClasses' => 'locale-number-input locale-number-currency'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<div class="custom-control custom-radio custom-control-inline mt-n2 mb-3">
				<input class="custom-control-input"
					type="radio"
					name="price-type"
					id="price-type-unit-price"
					value="unit-price"
					checked
					tabindex="-1">
				<label class="custom-control-label"
					for="price-type-unit-price"><?php echo e($__t('Unit price')); ?></label>
			</div>
			<div class="custom-control custom-radio custom-control-inline mt-n2 mb-3">
				<input class="custom-control-input"
					type="radio"
					name="price-type"
					id="price-type-total-price"
					value="total-price"
					tabindex="-1">
				<label class="custom-control-label"
					for="price-type-total-price"><?php echo e($__t('Total price')); ?></label>
			</div>
			<?php echo $__env->make('components.shoppinglocationpicker', array(
			'label' => 'Store',
			'shoppinglocations' => $shoppinglocations
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
			'isRequired' => false
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
			<?php endif; ?>

			<?php if(GROCY_FEATURE_FLAG_LABEL_PRINTER): ?>
			<div class="form-group">
				<label for="stock_label_type"><?php echo e($__t('Stock entry label')); ?></label>
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
				<label for="note"><?php echo e($__t('Note')); ?></label>
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

			<button id="save-purchase-button"
				class="btn btn-success d-block"><?php echo e($__t('OK')); ?></button>

		</form>
	</div>

	<div class="col-12 col-md-6 col-xl-4 hide-when-embedded">
		<?php echo $__env->make('components.productcard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/purchase.blade.php ENDPATH**/ ?>