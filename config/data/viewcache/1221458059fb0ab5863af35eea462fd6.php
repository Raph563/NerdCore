<?php $__env->startSection('title', $__t('Consume')); ?>

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
					class="btn <?php if(boolval($userSettings['scan_mode_consume_enabled'])): ?> btn-success <?php else: ?> btn-danger <?php endif; ?> m-1 mt-md-0 mb-md-0 float-right"
					data-toggle="tooltip"
					title="<?php echo e($__t('When enabled, after changing/scanning a product and if all fields could be automatically populated (by product and/or barcode defaults), the transaction is automatically submitted')); ?>"><?php echo e($__t('Scan mode')); ?> <span id="scan-mode-status"><?php if(boolval($userSettings['scan_mode_consume_enabled'])): ?> <?php echo e($__t('on')); ?> <?php else: ?> <?php echo e($__t('off')); ?> <?php endif; ?></span></button>
				<input id="scan-mode"
					type="checkbox"
					class="d-none user-setting-control"
					data-setting-key="scan_mode_consume_enabled"
					<?php if(boolval($userSettings['scan_mode_consume_enabled'])): ?>
					checked
					<?php endif; ?>>
				<?php else: ?>
				<script>
					Grocy.UserSettings.scan_mode_consume_enabled = false;
				</script>
				<?php endif; ?>
			</div>
		</div>

		<hr class="my-2">

		<form id="consume-form"
			novalidate>

			<?php echo $__env->make('components.productpicker', array(
			'products' => $products,
			'barcodes' => $barcodes,
			'nextInputSelector' => '#amount',
			'disallowAddProductWorkflows' => true
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<div id="consume-exact-amount-group"
				class="form-group d-none">
				<div class="custom-control custom-checkbox">
					<input class="form-check-input custom-control-input"
						type="checkbox"
						id="consume-exact-amount"
						name="consume-exact-amount"
						value="1">
					<label class="form-check-label custom-control-label"
						for="consume-exact-amount"><?php echo e($__t('Consume exact amount')); ?>

					</label>
				</div>
			</div>

			<?php echo $__env->make('components.productamountpicker', array(
			'value' => 1,
			'additionalHtmlContextHelp' => '<div id="tare-weight-handling-info"
				class="text-info font-italic d-none">' . $__t('Tare weight handling enabled - please weigh the whole container, the amount to be posted will be automatically calculcated') . '</div>'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<div class="form-group <?php if(!GROCY_FEATURE_FLAG_STOCK_LOCATION_TRACKING): ?> d-none <?php endif; ?>">
				<label for="location_id"><?php echo e($__t('Location')); ?></label>
				<select required
					class="custom-control custom-select location-combobox"
					id="location_id"
					name="location_id">
					<option></option>
					<?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($location->id); ?>"><?php echo e($location->name); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
				<div class="invalid-feedback"><?php echo e($__t('A location is required')); ?></div>
			</div>

			<div class="form-group">
				<div class="custom-control custom-checkbox">
					<input class="form-check-input custom-control-input"
						type="checkbox"
						id="spoiled"
						name="spoiled"
						value="1">
					<label class="form-check-label custom-control-label"
						for="spoiled"><?php echo e($__t('Spoiled')); ?>

					</label>
				</div>
			</div>

			<div class="form-group">
				<div class="custom-control custom-checkbox">
					<input class="form-check-input custom-control-input"
						type="checkbox"
						id="use_specific_stock_entry"
						name="use_specific_stock_entry"
						value="1">
					<label class="form-check-label custom-control-label"
						for="use_specific_stock_entry"><?php echo e($__t('Use a specific stock item')); ?>

						&nbsp;<i class="fa-solid fa-question-circle text-muted"
							data-toggle="tooltip"
							data-trigger="hover click"
							title="<?php echo e($__t('The first item in this list would be picked by the default rule consume rule (Opened first, then first due first, then first in first out)')); ?>"></i>
					</label>
				</div>
				<select disabled
					class="custom-control custom-select mt-2"
					id="specific_stock_entry"
					name="specific_stock_entry">
					<option></option>
				</select>
			</div>

			<?php if(GROCY_FEATURE_FLAG_RECIPES): ?>
			<?php echo $__env->make('components.recipepicker', array(
			'recipes' => $recipes,
			'isRequired' => false,
			'hint' => $__t('This is for statistical purposes only')
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
			<?php endif; ?>

			<button id="save-consume-button"
				class="btn btn-success"><?php echo e($__t('OK')); ?></button>

			<?php if(GROCY_FEATURE_FLAG_STOCK_PRODUCT_OPENED_TRACKING): ?>
			<button id="save-mark-as-open-button"
				class="btn btn-secondary permission-STOCK_OPEN"><?php echo e($__t('Mark as opened')); ?></button>
			<?php endif; ?>

		</form>
	</div>

	<div class="col-12 col-md-6 col-xl-4 hide-when-embedded">
		<?php echo $__env->make('components.productcard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/consume.blade.php ENDPATH**/ ?>