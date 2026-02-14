<?php require_frontend_packages(['datatables', 'daterangepicker', 'chartjs']); ?>



<?php $__env->startSection('title', $__t('Stock report') . ' / ' . $__t('Spendings')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col">
		<div class="title-related-links">
			<h2 class="title mr-2 order-0">
				<?php echo $__env->yieldContent('title'); ?>
			</h2>
			<div class="float-right <?php if($embedded): ?> pr-5 <?php endif; ?>">
				<button class="btn btn-outline-dark d-md-none mt-2 order-1 order-md-3"
					type="button"
					data-toggle="collapse"
					data-target="#table-filter-row">
					<i class="fa-solid fa-filter"></i>
				</button>
				<button class="btn btn-outline-dark d-md-none mt-2 order-1 order-md-3"
					type="button"
					data-toggle="collapse"
					data-target="#related-links">
					<i class="fa-solid fa-ellipsis-v"></i>
				</button>
			</div>
			<div class="related-links collapse d-md-flex order-2 width-xs-sm-100"
				id="related-links">
				<a class="btn btn-link responsive-button m-1 mt-md-0 mb-md-0 discrete-link disabled"
					href="#">
					<?php echo e($__t('Group by')); ?>:
				</a>
				<a class="btn btn-outline-dark responsive-button m-1 mt-md-0 mb-md-0 float-right group-by-button <?php if($groupBy == 'product'): ?> active <?php endif; ?>"
					href="#"
					data-group-by="product">
					<?php echo e($__t('Product')); ?>

				</a>
				<a class="btn btn-outline-dark responsive-button m-1 mt-md-0 mb-md-0 float-right group-by-button <?php if($groupBy == 'productgroup'): ?> active <?php endif; ?>"
					href="#"
					data-group-by="productgroup">
					<?php echo e($__t('Product group')); ?>

				</a>
				<a class="btn btn-outline-dark responsive-button m-1 mt-md-0 mb-md-0 float-right group-by-button <?php if($groupBy == 'store'): ?> active <?php endif; ?>"
					href="#"
					data-group-by="store">
					<?php echo e($__t('Store')); ?>

				</a>
			</div>
		</div>
	</div>
</div>

<hr class="my-2">

<div class="row collapse d-md-flex"
	id="table-filter-row">
	<div class="col-sm-12 col-md-6 col-xl-3">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="fa-solid fa-clock"></i>&nbsp;<?php echo e($__t('Date range')); ?></span>
			</div>
			<input type="text"
				name="date-filter"
				id="daterange-filter"
				class="custom-control custom-select"
				value="" />
		</div>
	</div>
	<?php if($groupBy == 'product'): ?>
	<div class="col-sm-12 col-md-6 col-xl-4">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="fa-solid fa-filter"></i>&nbsp;<?php echo e($__t('Product group')); ?></span>
			</div>
			<select class="custom-control custom-select"
				id="product-group-filter">
				<option value="all"><?php echo e($__t('All')); ?></option>
				<?php $__currentLoopData = $productGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<option <?php if($productGroup->id == $selectedGroup): ?> selected="selected" <?php endif; ?>
					value="<?php echo e($productGroup->id); ?>"><?php echo e($productGroup->name); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<option class="font-italic font-weight-light"
					value="ungrouped"><?php echo e($__t('Ungrouped')); ?></option>
			</select>
		</div>
	</div>
	<?php endif; ?>
	<div class="col">
		<div class="float-right">
			<button id="clear-filter-button"
				class="btn btn-sm btn-outline-info"
				data-toggle="tooltip"
				title="<?php echo e($__t('Clear filter')); ?>">
				<i class="fa-solid fa-filter-circle-xmark"></i>
			</button>
		</div>
	</div>
</div>

<div class="row mt-2">
	<div class="col-sm-12 col-md-12 col-xl-12">
		<canvas id="metrics-chart"></canvas>
	</div>
	<div class="col-sm-12 col-md-12 col-xl-12">
		<table id="metrics-table"
			class="table table-sm table-striped nowrap w-100">
			<thead>
				<tr>
					<th><?php echo e($__t('Name')); ?></th>
					<th><?php echo e($__t('Total')); ?></th>
					<?php if($groupBy == 'product'): ?>
					<th><?php echo e($__t('Product group')); ?></th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody class="d-none">
				<?php $__currentLoopData = $metrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td>
						<?php if($groupBy == 'productgroup'): ?>
						<?php if(empty($metric->name)): ?>
						<span class="font-italic font-weight-light"><?php echo e($__t('Ungrouped')); ?></span>
						<?php else: ?>
						<?php echo e($metric->name); ?>

						<?php endif; ?>
						<?php else: ?>
						<?php echo e($metric->name); ?>

						<?php endif; ?>
					</td>
					<td data-chart-value="<?php echo e($metric->total); ?>"
						data-order="<?php echo e($metric->total); ?>">
						<span class="locale-number locale-number-currency"><?php echo e($metric->total); ?></span>
					</td>
					<?php if($groupBy == 'product'): ?>
					<td>
						<?php if(empty($metric->group_name)): ?>
						<span class="font-italic font-weight-light"><?php echo e($__t('Ungrouped')); ?></span>
						<?php else: ?>
						<?php echo e($metric->group_name); ?>

						<?php endif; ?>
					</td>
					<?php endif; ?>
				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/stockreportspendings.blade.php ENDPATH**/ ?>