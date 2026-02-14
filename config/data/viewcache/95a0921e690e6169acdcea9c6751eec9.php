<?php require_frontend_packages(['datatables']); ?>



<?php $__env->startSection('title', $__t('Location Content Sheet')); ?>

<?php $__env->startPush('pageStyles'); ?>
<style>
	@media print {
		.page:not(:last-child) {
			page-break-after: always !important;
		}

		.page.no-page-break {
			page-break-after: avoid !important;
		}

		/*
				Workaround because of Firefox bug
				see https://github.com/twbs/bootstrap/issues/22753
				and https://bugzilla.mozilla.org/show_bug.cgi?id=1413121
		*/
		.row {
			display: inline !important;
		}
	}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="title-related-links d-print-none">
	<h2 class="title">
		<?php echo $__env->yieldContent('title'); ?>
		<i class="fa-solid fa-question-circle text-muted small"
			data-toggle="tooltip"
			data-trigger="hover click"
			title="<?php echo e($__t('Here you can print a page per location with the current stock, maybe to hang it there and note the consumed things on it')); ?>"></i>
	</h2>
	<div class="form-check custom-control custom-checkbox">
		<input class="form-check-input custom-control-input"
			type="checkbox"
			id="include-out-of-stock"
			checked>
		<label class="form-check-label custom-control-label"
			for="include-out-of-stock">
			<?php echo e($__t('Show only in stock products')); ?>

			<i class="fa-solid fa-question-circle text-muted"
				data-toggle="tooltip"
				data-trigger="hover click"
				title="<?php echo e($__t('Out of stock items will be shown at the products default location')); ?>"></i>
		</label>
	</div>
	<div class="float-right">
		<button class="btn btn-outline-dark d-md-none mt-2 order-1 order-md-3"
			type="button"
			data-toggle="collapse"
			data-target="#related-links">
			<i class="fa-solid fa-ellipsis-v"></i>
		</button>
	</div>
	<div class="related-links collapse d-md-flex order-2 width-xs-sm-100"
		id="related-links">
		<a class="btn btn-outline-dark responsive-button m-1 mt-md-0 mb-md-0 float-right print-all-locations-button"
			href="#">
			<?php echo e($__t('Print') . ' (' . $__t('all locations') . ')'); ?>

		</a>
	</div>
</div>

<hr class="my-2 d-print-none">

<?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php if(FindAllObjectsInArrayByPropertyValue($currentStockLocationContent, 'location_id', $location->id) == null): ?>
<?php continue; ?>
<?php endif; ?>
<div class="page">
	<h1 class="pt-4 text-center">
		<img src="<?php echo e($U('/img/logo.svg?v=', true)); ?><?php echo e($version); ?>"
			width="114"
			height="30"
			class="d-none d-print-flex mx-auto">
		<?php echo e($location->name); ?>

		<a class="btn btn-outline-dark btn-sm responsive-button print-single-location-button d-print-none"
			href="#">
			<?php echo e($__t('Print') . ' (' . $__t('this location') . ')'); ?>

		</a>
	</h1>
	<h6 class="mb-4 d-none d-print-block text-center">
		<?php echo e($__t('Time of printing')); ?>:
		<span class="d-inline print-timestamp"></span>
	</h6>
	<div class="row">
		<div class="col">
			<table class="table">
				<thead>
					<tr>
						<th><?php echo e($__t('Product')); ?></th>
						<th><?php echo e($__t('Amount')); ?></th>
						<th><?php echo e($__t('Consumed amount') . ' / ' . $__t('Notes')); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php $currentStockEntriesForLocation = FindAllObjectsInArrayByPropertyValue($currentStockLocationContent, 'location_id', $location->id); ?>
					<?php $__currentLoopData = $currentStockEntriesForLocation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currentStockEntry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr>
						<td class="fit-content">
							<?php echo e(FindObjectInArrayByPropertyValue($products, 'id', $currentStockEntry->product_id)->name); ?>

						</td>
						<td class="fit-content">
							<span class="locale-number locale-number-quantity-amount"><?php echo e($currentStockEntry->amount); ?></span> <span id="product-<?php echo e($currentStockEntry->product_id); ?>-qu-name"><?php echo e($__n($currentStockEntry->amount, FindObjectInArrayByPropertyValue($quantityunits, 'id', FindObjectInArrayByPropertyValue($products, 'id', $currentStockEntry->product_id)->qu_id_stock)->name, FindObjectInArrayByPropertyValue($quantityunits, 'id', FindObjectInArrayByPropertyValue($products, 'id', $currentStockEntry->product_id)->qu_id_stock)->name_plural, true)); ?></span>
							<span class="small font-italic"><?php if($currentStockEntry->amount_opened > 0): ?><?php echo e($__t('%s opened', $currentStockEntry->amount_opened)); ?><?php endif; ?></span>
						</td>
						<td class=""></td>
					</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/locationcontentsheet.blade.php ENDPATH**/ ?>