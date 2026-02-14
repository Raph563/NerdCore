<?php require_frontend_packages(['datatables']); ?>



<?php $__env->startSection('title', $__t('Users')); ?>

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
			<div class="related-links collapse d-md-flex order-2 width-xs-sm-100 m-1 mt-md-0 mb-md-0 float-right"
				id="related-links">
				<?php if(!defined('GROCY_EXTERNALLY_MANAGED_AUTHENTICATION')): ?>
				<a class="btn btn-primary responsive-button"
					href="<?php echo e($U('/user/new')); ?>">
					<?php echo e($__t('Add')); ?>

				</a>
				<?php endif; ?>
				<a class="btn btn-outline-secondary m-1 mt-md-0 mb-md-0 float-right"
					href="<?php echo e($U('/userfields?entity=users')); ?>">
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
		<table id="users-table"
			class="table table-sm table-striped nowrap w-100">
			<thead>
				<tr>
					<th class="border-right"><a class="text-muted change-table-columns-visibility-button"
							data-toggle="tooltip"
							title="<?php echo e($__t('Table options')); ?>"
							data-table-selector="#users-table"
							href="#"><i class="fa-solid fa-eye"></i></a>
					</th>
					<th><?php echo e($__t('Username')); ?></th>
					<th><?php echo e($__t('First name')); ?></th>
					<th><?php echo e($__t('Last name')); ?></th>

					<?php echo $__env->make('components.userfields_thead', array(
					'userfields' => $userfields
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
				</tr>
			</thead>
			<tbody class="d-none">
				<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td class="fit-content border-right">
						<a class="btn btn-info btn-sm"
							href="<?php echo e($U('/user/')); ?><?php echo e($user->id); ?>"
							data-toggle="tooltip"
							title="<?php echo e($__t('Edit this item')); ?>">
							<i class="fa-solid fa-edit"></i>
						</a>
						<?php if(!GROCY_IS_EMBEDDED_INSTALL && !GROCY_DISABLE_AUTH): ?>
						<a class="btn btn-info btn-sm"
							href="<?php echo e($U('/user/' . $user->id . '/permissions')); ?>"
							data-toggle="tooltip"
							title="<?php echo e($__t('Configure user permissions')); ?>">
							<i class="fa-solid fa-lock"></i>
						</a>
						<?php endif; ?>
						<a class="btn btn-danger btn-sm user-delete-button <?php if($user->id == GROCY_USER_ID): ?> disabled <?php endif; ?>"
							href="#"
							data-user-id="<?php echo e($user->id); ?>"
							data-user-username="<?php echo e($user->username); ?>"
							data-toggle="tooltip"
							title="<?php echo e($__t('Delete this item')); ?>">
							<i class="fa-solid fa-trash"></i>
						</a>
					</td>
					<td>
						<?php echo e($user->username); ?>

					</td>
					<td>
						<?php echo e($user->first_name); ?>

					</td>
					<td>
						<?php echo e($user->last_name); ?>

					</td>

					<?php echo $__env->make('components.userfields_tbody', array(
					'userfields' => $userfields,
					'userfieldValues' => FindAllObjectsInArrayByPropertyValue($userfieldValues, 'object_id', $user->id)
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/users.blade.php ENDPATH**/ ?>