<?php require_frontend_packages(['datatables']); ?>



<?php $__env->startSection('title', $__t('QU conversions resolved')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col">
		<div class="title-related-links">
			<h2 class="title">
				<?php echo $__env->yieldContent('title'); ?><br>
				<?php if($product != null): ?>
				<span class="text-muted font-italic small"><?php echo e($__t('Product')); ?> <strong><?php echo e($product->name); ?></strong></span>
				<?php endif; ?>
			</h2>
			<div class="float-right <?php if($embedded): ?> pr-5 <?php endif; ?>">
				<button class="btn btn-outline-dark d-md-none mt-2"
					type="button"
					data-toggle="collapse"
					data-target="#table-filter-row">
					<i class="fa-solid fa-filter"></i>
				</button>
			</div>
		</div>
	</div>
</div>

<hr class="my-2">

<div class="row collapse d-md-flex"
	id="table-filter-row">
	<div class="col-12 col-md-6 col-xl-2">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="fa-solid fa-filter"></i>&nbsp;<?php echo e($__t('Quantity unit')); ?></span>
			</div>
			<select class="custom-control custom-select"
				id="quantity-unit-filter">
				<option value="all"><?php echo e($__t('All')); ?></option>
				<?php $__currentLoopData = $quantityUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quantityUnit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<option value="<?php echo e($quantityUnit->id); ?>"><?php echo e($quantityUnit->name); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
		</div>
	</div>
	<div class="col">
		<div class="float-right mt-1">
			<button id="clear-filter-button"
				class="btn btn-sm btn-outline-info"
				data-toggle="tooltip"
				title="<?php echo e($__t('Clear filter')); ?>">
				<i class="fa-solid fa-filter-circle-xmark"></i>
			</button>
		</div>
	</div>
</div>

<div class="row">
	<div class="col">

		<table id="qu-conversions-resolved-table"
			class="table table-sm table-striped nowrap w-100">
			<thead>
				<tr>
					<th class="border-right"><a class="text-muted change-table-columns-visibility-button"
							data-toggle="tooltip"
							title="<?php echo e($__t('Table options')); ?>"
							data-table-selector="#qu-conversions-resolved-table"
							href="#"><i class="fa-solid fa-eye"></i></a>
					</th>
					<th class="allow-grouping"><?php echo e($__t('Quantity unit from')); ?></th>
					<th class="allow-grouping"><?php echo e($__t('Quantity unit to')); ?></th>
					<th><?php echo e($__t('Factor')); ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody class="d-none">
				<?php $__currentLoopData = $quantityUnitConversionsResolved; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quConversion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td class="fit-content border-right"></td>
					<td>
						<?php echo e(FindObjectInArrayByPropertyValue($quantityUnits, 'id', $quConversion->from_qu_id)->name); ?>

					</td>
					<td>
						<?php echo e(FindObjectInArrayByPropertyValue($quantityUnits, 'id', $quConversion->to_qu_id)->name); ?>

					</td>
					<td>
						<span class="locale-number locale-number-quantity-amount"><?php echo e($quConversion->factor); ?></span>
					</td>
					<td class="font-italic">
						<?php echo $__t('This means 1 %1$s is the same as %2$s %3$s', FindObjectInArrayByPropertyValue($quantityUnits, 'id', $quConversion->from_qu_id)->name, '<span class="locale-number locale-number-quantity-amount">' . $quConversion->factor . '</span>', $__n($quConversion->factor, FindObjectInArrayByPropertyValue($quantityUnits, 'id', $quConversion->to_qu_id)->name, FindObjectInArrayByPropertyValue($quantityUnits, 'id', $quConversion->to_qu_id)->name_plural, true)); ?>

					</td>
				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>

	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/quantityunitconversionsresolved.blade.php ENDPATH**/ ?>