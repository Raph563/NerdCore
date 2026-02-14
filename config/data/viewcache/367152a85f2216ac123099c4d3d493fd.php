<?php require_frontend_packages(['datatables', 'animatecss']); ?>



<?php $__env->startSection('title', $__t('Chores overview')); ?>

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
			<div class="related-links collapse d-md-flex order-2 width-xs-sm-100"
				id="related-links">
				<a class="btn btn-outline-dark responsive-button m-1 mt-md-0 mb-md-0 float-right"
					href="<?php echo e($U('/choresjournal')); ?>">
					<?php echo e($__t('Journal')); ?>

				</a>
			</div>
		</div>
		<div class="border-top border-bottom my-2 py-1">
			<div id="info-overdue-chores"
				data-status-filter="overdue"
				class="error-message status-filter-message responsive-button mr-2"></div>
			<div id="info-due-today-chores"
				data-status-filter="duetoday"
				class="normal-message status-filter-message responsive-button mr-2"></div>
			<div id="info-due-soon-chores"
				data-status-filter="duesoon"
				data-next-x-days="<?php echo e($nextXDays); ?>"
				class="warning-message status-filter-message responsive-message mr-2 <?php if($nextXDays == 0): ?> d-none <?php endif; ?>"></div>
			<?php if(GROCY_FEATURE_FLAG_CHORES_ASSIGNMENTS): ?>
			<div id="info-assigned-to-me-chores"
				data-user-filter="xx<?php echo e(GROCY_USER_ID); ?>xx"
				class="secondary-message user-filter-message responsive-button"></div>
			<?php endif; ?>
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
	<?php if(GROCY_FEATURE_FLAG_CHORES_ASSIGNMENTS): ?>
	<div class="col-12 col-md-6 col-xl-3">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="fa-solid fa-filter"></i>&nbsp;<?php echo e($__t('Assignment')); ?></span>
			</div>
			<select class="custom-control custom-select"
				id="user-filter">
				<option></option>
				<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<option data-user-id="<?php echo e($user->id); ?>"
					value="xx<?php echo e($user->id); ?>xx"><?php echo e($user->display_name); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
		</div>
	</div>
	<?php endif; ?>
</div>

<div class="row">
	<div class="col">
		<table id="chores-overview-table"
			class="table table-sm table-striped nowrap w-100">
			<thead>
				<tr>
					<th class="border-right"><a class="text-muted change-table-columns-visibility-button"
							data-toggle="tooltip"
							title="<?php echo e($__t('Table options')); ?>"
							data-table-selector="#chores-overview-table"
							href="#"><i class="fa-solid fa-eye"></i></a>
					</th>
					<th><?php echo e($__t('Chore')); ?></th>
					<th><?php echo e($__t('Next estimated tracking')); ?></th>
					<th><?php echo e($__t('Last tracked')); ?></th>
					<th class="<?php if(!GROCY_FEATURE_FLAG_CHORES_ASSIGNMENTS): ?> d-none <?php endif; ?> allow-grouping"><?php echo e($__t('Assigned to')); ?></th>
					<th class="d-none">Hidden status</th>
					<th class="d-none">Hidden assigned to user id</th>

					<?php echo $__env->make('components.userfields_thead', array(
					'userfields' => $userfields
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

				</tr>
			</thead>
			<tbody class="d-none">
				<?php $__currentLoopData = $currentChores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $curentChoreEntry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr id="chore-<?php echo e($curentChoreEntry->chore_id); ?>-row"
					class="<?php if($curentChoreEntry->due_type == 'overdue'): ?> table-danger <?php elseif($curentChoreEntry->due_type == 'duetoday'): ?> table-info <?php elseif($curentChoreEntry->due_type == 'duesoon'): ?> table-warning <?php endif; ?>">
					<td class="fit-content border-right">
						<a class="btn btn-success btn-sm track-chore-button permission-CHORE_TRACK_EXECUTION <?php if(boolval($userSettings['chores_overview_swap_tracking_buttons'])): ?> now <?php endif; ?>"
							href="#"
							data-toggle="tooltip"
							data-placement="left"
							<?php if(boolval($userSettings['chores_overview_swap_tracking_buttons'])): ?>
							title="<?php echo e($__t('Track chore execution now')); ?>"
							<?php else: ?>
							title="<?php echo e($__t('Track next chore schedule')); ?>"
							<?php endif; ?>
							data-chore-id="<?php echo e($curentChoreEntry->chore_id); ?>"
							data-chore-name="<?php echo e(FindObjectInArrayByPropertyValue($chores, 'id', $curentChoreEntry->chore_id)->name); ?>">
							<i class="fa-solid fa-play"></i>
						</a>
						<a class="btn btn-secondary btn-sm track-chore-button skip permission-CHORE_TRACK_EXECUTION <?php if(FindObjectInArrayByPropertyValue($chores, 'id', $curentChoreEntry->chore_id)->period_type == \Grocy\Services\ChoresService::CHORE_PERIOD_TYPE_MANUALLY): ?> disabled <?php endif; ?>"
							href="#"
							data-toggle="tooltip"
							data-placement="left"
							title="<?php echo e($__t('Skip next chore schedule')); ?>"
							data-chore-id="<?php echo e($curentChoreEntry->chore_id); ?>"
							data-chore-name="<?php echo e(FindObjectInArrayByPropertyValue($chores, 'id', $curentChoreEntry->chore_id)->name); ?>">
							<i class="fa-solid fa-forward"></i>
						</a>
						<div class="dropdown d-inline-block">
							<button class="btn btn-sm btn-light text-secondary"
								type="button"
								data-toggle="dropdown">
								<i class="fa-solid fa-ellipsis-v"></i>
							</button>
							<div class="table-inline-menu dropdown-menu dropdown-menu-right">
								<a class="dropdown-item track-chore-button permission-CHORE_TRACK_EXECUTION <?php if(!boolval($userSettings['chores_overview_swap_tracking_buttons'])): ?> now <?php endif; ?>"
									data-chore-id="<?php echo e($curentChoreEntry->chore_id); ?>"
									type="button"
									href="#">
									<?php if(boolval($userSettings['chores_overview_swap_tracking_buttons'])): ?>
									<span><?php echo e($__t('Track next chore schedule')); ?></span>
									<?php else: ?>
									<span><?php echo e($__t('Track chore execution now')); ?></span>
									<?php endif; ?>
								</a>
								<a class="dropdown-item reschedule-chore-button permission-CHORE_TRACK_EXECUTION"
									data-chore-id="<?php echo e($curentChoreEntry->chore_id); ?>"
									type="button"
									href="#">
									<span><?php echo e($__t('Reschedule next execution')); ?></span>
								</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item chorecard-trigger"
									data-chore-id="<?php echo e($curentChoreEntry->chore_id); ?>"
									type="button"
									href="#">
									<span class="dropdown-item-text"><?php echo e($__t('Chore overview')); ?></span>
								</a>
								<a class="dropdown-item show-as-dialog-link"
									type="button"
									href="<?php echo e($U('/choresjournal?embedded&chore=')); ?><?php echo e($curentChoreEntry->chore_id); ?>"
									data-dialog-type="table">
									<span class="dropdown-item-text"><?php echo e($__t('Chore journal')); ?></span>
								</a>
								<a class="dropdown-item permission-MASTER_DATA_EDIT"
									type="button"
									href="<?php echo e($U('/chore/')); ?><?php echo e($curentChoreEntry->chore_id); ?>">
									<span class="dropdown-item-text"><?php echo e($__t('Edit chore')); ?></span>
								</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item"
									type="button"
									href="<?php echo e($U('/chore/' . $curentChoreEntry->chore_id . '/grocycode?download=true')); ?>">
									<?php echo str_replace('Grocycode', '<span class="ls-n1">Grocycode</span>', $__t('Download %s Grocycode', $__t('Chore'))); ?>

								</a>
								<?php if(GROCY_FEATURE_FLAG_LABEL_PRINTER): ?>
								<a class="dropdown-item chore-grocycode-label-print"
									data-chore-id="<?php echo e($curentChoreEntry->chore_id); ?>"
									type="button"
									href="#">
									<?php echo str_replace('Grocycode', '<span class="ls-n1">Grocycode</span>', $__t('Print %s Grocycode on label printer', $__t('Chore'))); ?>

								</a>
								<?php endif; ?>
							</div>
						</div>
					</td>
					<td class="chorecard-trigger cursor-link"
						data-chore-id="<?php echo e($curentChoreEntry->chore_id); ?>">
						<?php echo e(FindObjectInArrayByPropertyValue($chores, 'id', $curentChoreEntry->chore_id)->name); ?>

					</td>
					<td>
						<?php if(!empty($curentChoreEntry->next_estimated_execution_time)): ?>
						<span id="chore-<?php echo e($curentChoreEntry->chore_id); ?>-next-execution-time"><?php echo e($curentChoreEntry->next_estimated_execution_time); ?></span>
						<time id="chore-<?php echo e($curentChoreEntry->chore_id); ?>-next-execution-time-timeago"
							class="timeago timeago-contextual <?php if($curentChoreEntry->track_date_only == 1): ?> timeago-date-only <?php endif; ?>"
							datetime="<?php echo e($curentChoreEntry->next_estimated_execution_time); ?>"></time>
						<?php else: ?>
						<span id="chore-<?php echo e($curentChoreEntry->chore_id); ?>-next-execution-time">-</span>
						<time id="chore-<?php echo e($curentChoreEntry->chore_id); ?>-next-execution-time-timeago"
							class="timeago timeago-contextual <?php if($curentChoreEntry->track_date_only == 1): ?> timeago-date-only <?php endif; ?>"></time>
						<?php endif; ?>
						<?php if($curentChoreEntry->is_rescheduled == 1): ?>
						<span id="chore-<?php echo e($curentChoreEntry->chore_id); ?>-rescheduled-icon"
							class="text-muted"
							data-toggle="tooltip"
							title="<?php echo e($__t('Rescheduled')); ?>">
							<i class="fa-solid fa-clock"></i>
						</span>
						<?php endif; ?>
					</td>
					<td>
						<?php if(!empty($curentChoreEntry->last_tracked_time)): ?>
						<span id="chore-<?php echo e($curentChoreEntry->chore_id); ?>-last-tracked-time"><?php echo e($curentChoreEntry->last_tracked_time); ?></span>
						<time id="chore-<?php echo e($curentChoreEntry->chore_id); ?>-last-tracked-time-timeago"
							class="timeago timeago-contextual <?php if($curentChoreEntry->track_date_only == 1): ?> timeago-date-only <?php endif; ?>"
							datetime="<?php echo e($curentChoreEntry->last_tracked_time); ?>"></time>
						<?php else: ?>
						<span id="chore-<?php echo e($curentChoreEntry->chore_id); ?>-last-tracked-time">-</span>
						<time id="chore-<?php echo e($curentChoreEntry->chore_id); ?>-last-tracked-time-timeago"
							class="timeago timeago-contextual <?php if($curentChoreEntry->track_date_only == 1): ?> timeago-date-only <?php endif; ?>"></time>
						<?php endif; ?>
					</td>

					<td class="<?php if(!GROCY_FEATURE_FLAG_CHORES_ASSIGNMENTS): ?> d-none <?php endif; ?>">
						<span id="chore-<?php echo e($curentChoreEntry->chore_id); ?>-next-execution-assigned-user">
							<?php if(!empty($curentChoreEntry->next_execution_assigned_to_user_id)): ?>
							<?php echo e(FindObjectInArrayByPropertyValue($users, 'id', $curentChoreEntry->next_execution_assigned_to_user_id)->display_name); ?>

							<?php else: ?>
							<span>-</span>
							<?php endif; ?>
							<?php if($curentChoreEntry->is_reassigned == 1): ?>
							<span id="chore-<?php echo e($curentChoreEntry->chore_id); ?>-reassigned-icon"
								class="text-muted"
								data-toggle="tooltip"
								title="<?php echo e($__t('Reassigned')); ?>">
								<i class="fa-solid fa-exchange-alt"></i>
							</span>
							<?php endif; ?>
						</span>
					</td>
					<td id="chore-<?php echo e($curentChoreEntry->chore_id); ?>-due-filter-column"
						class="d-none">
						<?php echo e($curentChoreEntry->due_type); ?>

						<?php if($curentChoreEntry->due_type == 'duetoday'): ?>
						duesoon
						<?php endif; ?>
					</td>
					<td class="d-none">
						<?php if(!empty($curentChoreEntry->next_execution_assigned_to_user_id)): ?>
						xx<?php echo e($curentChoreEntry->next_execution_assigned_to_user_id); ?>xx
					</td>
					<?php endif; ?>

					<?php echo $__env->make('components.userfields_tbody', array(
					'userfields' => $userfields,
					'userfieldValues' => FindAllObjectsInArrayByPropertyValue($userfieldValues, 'object_id', $curentChoreEntry->chore_id)
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>
</div>

<?php echo $__env->make('components.chorecard', [
'asModal' => true
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="modal fade"
	id="reschedule-chore-modal"
	tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-header d-block">
				<h4 class="modal-title"><?php echo e($__t('Reschedule next execution')); ?></h4>
				<h5 id="reschedule-chore-modal-title"
					class="text-muted"></h5>
			</div>
			<div class="modal-body">
				<form id="reschedule-chore-form"
					novalidate>

					<?php echo $__env->make('components.datetimepicker', array(
					'id' => 'reschedule_time',
					'label' => 'Next estimated tracking',
					'format' => 'YYYY-MM-DD HH:mm:ss',
					'initWithNow' => false,
					'limitEndToNow' => false,
					'limitStartToNow' => false,
					'invalidFeedback' => $__t('This can only be in the future')
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

					<?php echo $__env->make('components.userpicker', array(
					'label' => 'Assigned to',
					'users' => $users
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

				</form>
			</div>
			<div class="modal-footer">
				<button id="reschedule-chore-clear-button"
					type="button"
					class="btn btn-success mr-auto"><?php echo e($__t('Reset')); ?></button>
				<button type="button"
					class="btn btn-secondary"
					data-dismiss="modal"><?php echo e($__t('Cancel')); ?></button>
				<button id="reschedule-chore-save-button"
					type="button"
					class="btn btn-primary"><?php echo e($__t('OK')); ?></button>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/choresoverview.blade.php ENDPATH**/ ?>