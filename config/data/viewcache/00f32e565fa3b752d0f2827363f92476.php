<?php require_frontend_packages(['datatables']); ?>



<?php $__env->startSection('title', $__t('Chores')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col">
		<div class="title-related-links">
			<h2 class="title"><?php echo $__env->yieldContent('title'); ?></h2>
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
				<a class="btn btn-primary responsive-button m-1 mt-md-0 mb-md-0 float-right"
					href="<?php echo e($U('/chore/new')); ?>">
					<?php echo e($__t('Add')); ?>

				</a>
				<a class="btn btn-outline-secondary m-1 mt-md-0 mb-md-0 float-right"
					href="<?php echo e($U('/userfields?entity=chores')); ?>">
					<?php echo e($__t('Configure userfields')); ?>

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
	<div class="col-12 col-md-6 col-xl-3">
		<div class="form-check custom-control custom-checkbox">
			<input class="form-check-input custom-control-input"
				type="checkbox"
				id="show-disabled">
			<label class="form-check-label custom-control-label"
				for="show-disabled">
				<?php echo e($__t('Show disabled')); ?>

			</label>
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
		<table id="chores-table"
			class="table table-sm table-striped nowrap w-100">
			<thead>
				<tr>
					<th class="border-right"><a class="text-muted change-table-columns-visibility-button"
							data-toggle="tooltip"
							title="<?php echo e($__t('Table options')); ?>"
							data-table-selector="#chores-table"
							href="#"><i class="fa-solid fa-eye"></i></a>
					</th>
					<th><?php echo e($__t('Name')); ?></th>
					<th class="allow-grouping"><?php echo e($__t('Period type')); ?></th>
					<th><?php echo e($__t('Description')); ?></th>

					<?php echo $__env->make('components.userfields_thead', array(
					'userfields' => $userfields
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

				</tr>
			</thead>
			<tbody class="d-none">
				<?php $__currentLoopData = $chores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chore): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr class="<?php if($chore->active == 0): ?> text-muted <?php endif; ?>">
					<td class="fit-content border-right">
						<a class="btn btn-info btn-sm"
							href="<?php echo e($U('/chore/')); ?><?php echo e($chore->id); ?>"
							data-toggle="tooltip"
							title="<?php echo e($__t('Edit this item')); ?>">
							<i class="fa-solid fa-edit"></i>
						</a>
						<a class="btn btn-danger btn-sm chore-delete-button"
							href="#"
							data-chore-id="<?php echo e($chore->id); ?>"
							data-chore-name="<?php echo e($chore->name); ?>"
							data-toggle="tooltip"
							title="<?php echo e($__t('Delete this item')); ?>">
							<i class="fa-solid fa-trash"></i>
						</a>
						<div class="dropdown d-inline-block">
							<button class="btn btn-sm btn-light text-secondary"
								type="button"
								data-toggle="dropdown">
								<i class="fa-solid fa-ellipsis-v"></i>
							</button>
							<div class="table-inline-menu dropdown-menu dropdown-menu-right">
								<a class="dropdown-item merge-chores-button"
									data-chore-id="<?php echo e($chore->id); ?>"
									type="button"
									href="#">
									<span class="dropdown-item-text"><?php echo e($__t('Merge')); ?></span>
								</a>
							</div>
						</div>
					</td>
					<td>
						<?php echo e($chore->name); ?>

					</td>
					<td>
						<?php echo e($__t($chore->period_type)); ?>

					</td>
					<td>
						<?php echo e($chore->description); ?>

					</td>

					<?php echo $__env->make('components.userfields_tbody', array(
					'userfields' => $userfields,
					'userfieldValues' => FindAllObjectsInArrayByPropertyValue($userfieldValues, 'object_id', $chore->id)
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>
</div>

<div class="modal fade"
	id="merge-chores-modal"
	tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-header">
				<h4 class="modal-title w-100"><?php echo e($__t('Merge chores')); ?></h4>
			</div>
			<div class="modal-body">
				<form id="merge-chores-form"
					novalidate>

					<div class="form-group">
						<label for="merge-chores-keep"><?php echo e($__t('Chore to keep')); ?>&nbsp;<i class="fa-solid fa-question-circle text-muted"
								data-toggle="tooltip"
								data-trigger="hover click"
								title="<?php echo e($__t('After merging, this chore will be kept')); ?>"></i>
						</label>
						<select class="custom-control custom-select"
							id="merge-chores-keep"
							required>
							<option></option>
							<?php $__currentLoopData = $chores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chore): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($chore->id); ?>"><?php echo e($chore->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
					<div class="form-group">
						<label for="merge-chores-remove"><?php echo e($__t('Chore to remove')); ?>&nbsp;<i class="fa-solid fa-question-circle text-muted"
								data-toggle="tooltip"
								data-trigger="hover click"
								title="<?php echo e($__t('After merging, all occurences of this chore will be replaced by the kept chore (means this chore will not exist anymore)')); ?>"></i>
						</label>
						<select class="custom-control custom-select"
							id="merge-chores-remove"
							required>
							<option></option>
							<?php $__currentLoopData = $chores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chore): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($chore->id); ?>"><?php echo e($chore->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>

				</form>
			</div>
			<div class="modal-footer">
				<button type="button"
					class="btn btn-secondary"
					data-dismiss="modal"><?php echo e($__t('Cancel')); ?></button>
				<button id="merge-chores-save-button"
					type="button"
					class="btn btn-primary"><?php echo e($__t('OK')); ?></button>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/chores.blade.php ENDPATH**/ ?>