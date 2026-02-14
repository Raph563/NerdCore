<?php $__env->startSection('title', $__t('Edit stock entry')); ?>

<?php $__env->startSection('content'); ?>
<script>
	Grocy.EditObjectId = "<?php echo e($stockEntry->stock_id); ?>";
	Grocy.EditObjectRowId = <?php echo e($stockEntry->id); ?>;
	Grocy.EditObjectProductId = <?php echo e($stockEntry->product_id); ?>;
</script>

<div class="row">
	<div class="col">
		<h2 class="title"><?php echo $__env->yieldContent('title'); ?></h2>
	</div>
</div>

<hr class="my-2">

<div class="row">
	<div class="col-12 col-md-6 col-xl-4 pb-3">

		<form id="stockentry-form"
			novalidate>
			<?php
			$product = FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id);
			?>

			<?php echo $__env->make('components.numberpicker', array(
			'id' => 'amount',
			'value' => $stockEntry->amount,
			'min' => $DEFAULT_MIN_AMOUNT,
			'decimals' => $userSettings['stock_decimal_places_amounts'],
			'label' => 'Amount',
			'contextInfoId' => 'amount_qu_unit',
			'additionalCssClasses' => 'locale-number-input locale-number-quantity-amount'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<?php echo $__env->make('components.datetimepicker2', array(
			'id' => 'purchase_date',
			'initialValue' => $stockEntry->purchased_date,
			'label' => 'Purchased date',
			'format' => 'YYYY-MM-DD',
			'initWithNow' => false,
			'limitEndToNow' => false,
			'limitStartToNow' => false,
			'invalidFeedback' => $__t('A purchased date is required'),
			'nextInputSelector' => '#save-stockentry-button',
			'additionalGroupCssClasses' => 'date-only-datetimepicker'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<?php
			$additionalGroupCssClasses = '';
			if (!GROCY_FEATURE_FLAG_STOCK_BEST_BEFORE_DATE_TRACKING)
			{
			$additionalGroupCssClasses = 'd-none';
			}
			?>
			<?php echo $__env->make('components.datetimepicker', array(
			'id' => 'best_before_date',
			'initialValue' => $stockEntry->best_before_date,
			'label' => 'Due date',
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
			<?php
			if (empty($stockEntry->price))
			{
			$price = '';
			}
			else
			{
			$price = $stockEntry->price;
			}
			?>
			<?php echo $__env->make('components.numberpicker', array(
			'id' => 'price',
			'value' => $price,
			'label' => 'Price',
			'min' => '0.' . str_repeat('0', $userSettings['stock_decimal_places_prices_input']),
			'decimals' => $userSettings['stock_decimal_places_prices_input'],
			'hint' => $__t('Per stock quantity unit'),
			'isRequired' => false,
			'additionalCssClasses' => 'locale-number-input locale-number-currency'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
			<?php echo $__env->make('components.shoppinglocationpicker', array(
			'label' => 'Store',
			'shoppinglocations' => $shoppinglocations,
			'prefillById' => $stockEntry->shopping_location_id
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
			'prefillById' => $stockEntry->location_id
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
			<?php else: ?>
			<input type="hidden"
				name="location_id"
				id="location_id"
				value="1">
			<?php endif; ?>

			<div class="form-group">
				<label for="note"><?php echo e($__t('Note')); ?></label>
				<div class="input-group">
					<input type="text"
						class="form-control"
						id="note"
						name="note"
						value="<?php echo e($stockEntry->note); ?>">
				</div>
			</div>

			<?php if(GROCY_FEATURE_FLAG_STOCK_PRODUCT_OPENED_TRACKING): ?>
			<div class="form-group">
				<div class="custom-control custom-checkbox">
					<input <?php if($stockEntry->open == 1): ?> checked <?php endif; ?> class="form-check-input custom-control-input" type="checkbox" id="open" name="open" value="1">
					<label class="form-check-label custom-control-label"
						for="open"><?php echo e($__n(1, 'Opened', 'Opened')); ?></label>
				</div>
			</div>
			<?php endif; ?>

			<?php echo $__env->make('components.userfieldsform', array(
			'userfields' => $userfields,
			'entity' => 'stock'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<?php if(GROCY_FEATURE_FLAG_LABEL_PRINTER): ?>
			<div class="form-group">
				<div class="custom-control custom-checkbox">
					<input class="form-check-input custom-control-input"
						type="checkbox"
						id="print-label"
						value="1">
					<label class="form-check-label custom-control-label"
						for="print-label"><?php echo e($__t('Reprint stock entry label')); ?></label>
				</div>
			</div>
			<?php endif; ?>

			<button id="save-stockentry-button"
				class="btn btn-success"><?php echo e($__t('OK')); ?></button>

		</form>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/stockentryform.blade.php ENDPATH**/ ?>