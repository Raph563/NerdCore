<?php require_frontend_packages(['datatables']); ?>



<?php $__env->startSection('title', $userentity->caption); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col">
		<div class="title-related-links">
			<h2 class="title mr-2 order-0">
				<?php echo $__env->yieldContent('title'); ?>
			</h2>
			<h2 class="mb-0 mr-auto order-3 order-md-1 width-xs-sm-100">
				<span class="text-muted small"><?php echo e($userentity->description); ?></span>
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
			<div class="related-links collapse d-md-flex order-2 width-xs-sm-100 m-1 mt-md-0 mb-md-0 float-right"
				id="related-links">
				<a class="btn btn-primary responsive-button mr-1 show-as-dialog-link"
					href="<?php echo e($U('/userobject/' . $userentity->name . '/new?embedded')); ?>">
					<?php echo e($__t('Add')); ?>

				</a>
				<a class="btn btn-outline-secondary d-print-none"
					href="<?php echo e($U('/userfields?entity=' . 'userentity-' . $userentity->name)); ?>">
					<?php echo e($__t('Configure fields')); ?>

				</a>
			</div>
		</div>
	</div>
</div>

<hr class="my-2">

<div class="row collapse d-md-flex"
	id="table-filter-row">
	<div class="col-12 col-md-6 col-xl-3">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="fa-solid fa-search"></i></span>
			</div>
			<input type="text"
				id="search"
				class="form-control"
				placeholder="<?php echo e($__t('Search')); ?>">
		</div>
	</div>
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

<div class="row">
	<div class="col">
		<table id="userobjects-table-<?php echo e($userentity->id); ?>"
			class="table table-sm table-striped nowrap w-100 userobjects-table">
			<thead>
				<tr>
					<th class="border-right d-print-none">
						<a class="text-muted change-table-columns-visibility-button"
							data-toggle="tooltip"
							title="<?php echo e($__t('Table options')); ?>"
							data-table-selector="#userobjects-table-<?php echo e($userentity->id); ?>"
							href="#"><i class="fa-solid fa-eye"></i>
						</a>
					</th>

					<?php echo $__env->make('components.userfields_thead', array(
					'userfields' => $userfields
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

				</tr>
			</thead>
			<tbody class="d-none">
				<?php $__currentLoopData = $userobjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userobject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td class="fit-content border-right d-print-none">
						<a class="btn btn-info btn-sm show-as-dialog-link"
							href="<?php echo e($U('/userobject/' . $userentity->name . '/')); ?><?php echo e($userobject->id); ?>?embedded"
							data-toggle="tooltip"
							title="<?php echo e($__t('Edit this item')); ?>">
							<i class="fa-solid fa-edit"></i>
						</a>
						<a class="btn btn-danger btn-sm userobject-delete-button"
							href="#"
							data-userobject-id="<?php echo e($userobject->id); ?>"
							data-toggle="tooltip"
							title="<?php echo e($__t('Delete this item')); ?>">
							<i class="fa-solid fa-trash"></i>
						</a>
					</td>

					<?php echo $__env->make('components.userfields_tbody', array(
					'userfields' => $userfields,
					'userfieldValues' => FindAllObjectsInArrayByPropertyValue($userfieldValues, 'object_id', $userobject->id)
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/userobjects.blade.php ENDPATH**/ ?>