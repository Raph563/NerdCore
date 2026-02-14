<?php require_frontend_packages(['datatables', 'animatecss']); ?>



<?php $__env->startSection('title', $__t('Tasks')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col">
		<div class="title-related-links">
			<h2 class="title"><?php echo $__env->yieldContent('title'); ?></h2>
			<button class="btn btn-outline-dark d-md-none mt-2 float-right order-1 order-md-3"
				type="button"
				data-toggle="collapse"
				data-target="#related-links">
				<i class="fa-solid fa-ellipsis-v"></i>
			</button>
			<div class="related-links collapse d-md-flex order-2 width-xs-sm-100 m-1 mt-md-0 mb-md-0 float-right"
				id="related-links">
				<a class="btn btn-primary responsive-button show-as-dialog-link"
					href="<?php echo e($U('/task/new?embedded')); ?>">
					<?php echo e($__t('Add')); ?>

				</a>
			</div>
		</div>
		<div class="border-top border-bottom my-2 py-1">
			<div id="info-overdue-tasks"
				data-status-filter="overdue"
				class="error-message status-filter-message responsive-button mr-2"></div>
			<div id="info-due-today-tasks"
				data-status-filter="duetoday"
				class="normal-message status-filter-message responsive-button mr-2"></div>
			<div id="info-due-soon-tasks"
				data-status-filter="duesoon"
				data-next-x-days="<?php echo e($nextXDays); ?>"
				class="warning-message status-filter-message responsive-button <?php if($nextXDays == 0): ?> d-none <?php endif; ?>"></div>
			<div class="float-right mt-1 <?php if($embedded): ?> pr-5 <?php endif; ?>">
				<a class="btn btn-sm btn-outline-info d-md-none"
					data-toggle="collapse"
					href="#table-filter-row"
					role="button">
					<i class="fa-solid fa-filter"></i>
				</a>
				<button id="clear-filter-button"
					class="btn btn-sm btn-outline-info"
					data-toggle="tooltip"
					title="<?php echo e($__t('Clear filter')); ?>">
					<i class="fa-solid fa-filter-circle-xmark"></i>
				</button>
			</div>
		</div>
	</div>
</div>

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
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="fa-solid fa-filter"></i>&nbsp;<?php echo e($__t('Status')); ?></span>
			</div>
			<select class="custom-control custom-select"
				id="status-filter">
				<option value="all"><?php echo e($__t('All')); ?></option>
				<option value="overdue"><?php echo e($__t('Overdue')); ?></option>
				<option value="duetoday"><?php echo e($__t('Due today')); ?></option>
				<?php if($nextXDays > 0): ?>
				<option value="duesoon"><?php echo e($__t('Due soon')); ?></option>
				<?php endif; ?>
			</select>
		</div>
	</div>
	<div class="col-12 col-md-6 col-xl-3">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="fa-solid fa-filter"></i>&nbsp;<?php echo e($__t('Category')); ?></span>
			</div>
			<select class="custom-control custom-select"
				id="category-filter">
				<option value="all"><?php echo e($__t('All')); ?></option>
				<?php $__currentLoopData = $taskCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $taskCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<option value="<?php echo e($taskCategory->name); ?>"><?php echo e($taskCategory->name); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<option class="font-italic font-weight-light"
					value="<?php echo e($__t('Uncategorized')); ?>"><?php echo e($__t('Uncategorized')); ?></option>
			</select>
		</div>
	</div>
	<div class="col-12 col-md-6 col-xl-3">
		<div class="form-check custom-control custom-checkbox">
			<input class="form-check-input custom-control-input"
				type="checkbox"
				id="show-done-tasks">
			<label class="form-check-label custom-control-label"
				for="show-done-tasks">
				<?php echo e($__t('Show done tasks')); ?>

			</label>
		</div>
	</div>
</div>

<div class="row">
	<div class="col">
		<table id="tasks-table"
			class="table table-sm table-striped nowrap w-100">
			<thead>
				<tr>
					<th class="border-right"><a class="text-muted change-table-columns-visibility-button"
							data-toggle="tooltip"
							title="<?php echo e($__t('Table options')); ?>"
							data-table-selector="#tasks-table"
							href="#"><i class="fa-solid fa-eye"></i></a>
					</th>
					<th><?php echo e($__t('Task')); ?></th>
					<th class="allow-grouping"><?php echo e($__t('Due')); ?></th>
					<th class="allow-grouping"
						data-shadow-rowgroup-column="6"><?php echo e($__t('Category')); ?></th>
					<th class="allow-grouping"><?php echo e($__t('Assigned to')); ?></th>
					<th class="d-none">Hidden status</th>
					<th class="d-none">Hidden category_id</th>

					<?php echo $__env->make('components.userfields_thead', array(
					'userfields' => $userfields
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

				</tr>
			</thead>
			<tbody class="d-none">
				<?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr id="task-<?php echo e($task->id); ?>-row"
					class="<?php if($task->due_type == 'overdue'): ?> table-danger <?php elseif($task->due_type == 'duetoday'): ?> table-info <?php elseif($task->due_type == 'duesoon'): ?> table-warning <?php endif; ?>">
					<td class="fit-content border-right">
						<?php if($task->done == 0): ?>
						<a class="btn btn-success btn-sm do-task-button"
							href="#"
							data-toggle="tooltip"
							data-placement="left"
							title="<?php echo e($__t('Mark task as completed')); ?>"
							data-task-id="<?php echo e($task->id); ?>"
							data-task-name="<?php echo e($task->name); ?>">
							<i class="fa-solid fa-check"></i>
						</a>
						<?php else: ?>
						<a class="btn btn-secondary btn-sm undo-task-button"
							href="#"
							data-toggle="tooltip"
							data-placement="left"
							title="<?php echo e($__t('Undo task', $task->name)); ?>"
							data-task-id="<?php echo e($task->id); ?>"
							data-task-name="<?php echo e($task->name); ?>">
							<i class="fa-solid fa-undo"></i>
						</a>
						<?php endif; ?>
						<a class="btn btn-info btn-sm show-as-dialog-link"
							href="<?php echo e($U('/task/')); ?><?php echo e($task->id); ?>?embedded"
							data-toggle="tooltip"
							title="<?php echo e($__t('Edit this item')); ?>">
							<i class="fa-solid fa-edit"></i>
						</a>
						<a class="btn btn-sm btn-danger delete-task-button"
							href="#"
							data-task-id="<?php echo e($task->id); ?>"
							data-task-name="<?php echo e($task->name); ?>"
							data-toggle="tooltip"
							title="<?php echo e($__t('Delete this item')); ?>">
							<i class="fa-solid fa-trash"></i>
						</a>
					</td>
					<td id="task-<?php echo e($task->id); ?>-name"
						class="<?php if($task->done == 1): ?> text-strike-through <?php endif; ?>">
						<?php echo e($task->name); ?>

					</td>
					<td>
						<span><?php echo e($task->due_date); ?></span>
						<time class="timeago timeago-contextual"
							datetime="<?php echo e($task->due_date); ?>"></time>
					</td>
					<td>
						<?php if($task->category_id != null): ?> <span><?php echo e(FindObjectInArrayByPropertyValue($taskCategories, 'id', $task->category_id)->name); ?></span> <?php else: ?> <span class="font-italic font-weight-light"><?php echo e($__t('Uncategorized')); ?></span><?php endif; ?>
					</td>
					<td>
						<?php if($task->assigned_to_user_id != null): ?> <span><?php echo e(GetUserDisplayName(FindObjectInArrayByPropertyValue($users, 'id', $task->assigned_to_user_id))); ?></span> <?php endif; ?>
					</td>
					<td class="d-none">
						<?php echo e($task->due_type); ?>

						<?php if($task->due_type == 'duetoday'): ?>
						duesoon
						<?php endif; ?>
					</td>
					<td class="d-none">
						<?php if($task->category_id != null): ?> <?php echo e(FindObjectInArrayByPropertyValue($taskCategories, 'id', $task->category_id)->name); ?> <?php else: ?> <?php echo e($__t('Uncategorized')); ?> <?php endif; ?>
					</td>
					<?php echo $__env->make('components.userfields_tbody',
					array( 'userfields'=> $userfields,
					'userfieldValues' => FindAllObjectsInArrayByPropertyValue($userfieldValues, 'object_id', $task->id)
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/tasks.blade.php ENDPATH**/ ?>