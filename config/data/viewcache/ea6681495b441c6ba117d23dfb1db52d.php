<?php require_frontend_packages(['datatables', 'bwipjs']); ?>



<?php $__env->startSection('title', $__t('API keys')); ?>

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
				<a id="add-api-key-button"
					class="btn btn-primary responsive-button m-1 mt-md-0 mb-md-0 float-right"
					href="#">
					<?php echo e($__t('Add')); ?>

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
		<table id="apikeys-table"
			class="table table-sm table-striped nowrap w-100">
			<thead>
				<tr>
					<th class="border-right"><a class="text-muted change-table-columns-visibility-button"
							data-toggle="tooltip"
							title="<?php echo e($__t('Table options')); ?>"
							data-table-selector="#apikeys-table"
							href="#"><i class="fa-solid fa-eye"></i></a>
					</th>
					<th><?php echo e($__t('Description')); ?></th>
					<th><?php echo e($__t('API key')); ?></th>
					<th class="allow-grouping"><?php echo e($__t('User')); ?></th>
					<th><?php echo e($__t('Expires')); ?></th>
					<th><?php echo e($__t('Last used')); ?></th>
					<th><?php echo e($__t('Created')); ?></th>
					<th class="allow-grouping"><?php echo e($__t('Key type')); ?></th>
				</tr>
			</thead>
			<tbody class="d-none">
				<?php $__currentLoopData = $apiKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $apiKey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr class="<?php if($apiKey->id == $selectedKeyId): ?> table-info <?php endif; ?>">
					<td class="fit-content border-right">
						<a class="btn btn-danger btn-sm apikey-delete-button"
							href="#"
							data-apikey-id="<?php echo e($apiKey->id); ?>"
							data-apikey-key="<?php echo e($apiKey->api_key); ?>"
							data-apikey-description="<?php echo e($apiKey->description); ?>"
							data-toggle="tooltip"
							title="<?php echo e($__t('Delete this item')); ?>">
							<i class="fa-solid fa-trash"></i>
						</a>
						<a class="btn btn-info btn-sm apikey-show-qr-button"
							href="#"
							data-apikey-key="<?php echo e($apiKey->api_key); ?>"
							data-apikey-type="<?php echo e($apiKey->key_type); ?>"
							data-apikey-description="<?php echo e($apiKey->description); ?>"
							data-toggle="tooltip"
							title="<?php echo e($__t('Show a QR-Code for this API key')); ?>">
							<i class="fa-solid fa-qrcode"></i>
						</a>
					</td>
					<td>
						<?php echo e($apiKey->description); ?>

					</td>
					<td>
						<?php echo e($apiKey->api_key); ?>

					</td>
					<td>
						<?php echo e(GetUserDisplayName(FindObjectInArrayByPropertyValue($users, 'id', $apiKey->user_id))); ?>

					</td>
					<td>
						<?php echo e($apiKey->expires); ?>

						<time class="timeago timeago-contextual"
							datetime="<?php echo e($apiKey->expires); ?>"></time>
					</td>
					<td>
						<?php if(empty($apiKey->last_used)): ?><?php echo e($__t('never')); ?><?php else: ?><?php echo e($apiKey->last_used); ?><?php endif; ?>
						<time class="timeago timeago-contextual"
							datetime="<?php echo e($apiKey->last_used); ?>"></time>
					</td>
					<td>
						<?php echo e($apiKey->row_created_timestamp); ?>

						<time class="timeago timeago-contextual"
							datetime="<?php echo e($apiKey->row_created_timestamp); ?>"></time>
					</td>
					<td>
						<?php echo e($apiKey->key_type); ?>

					</td>
				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>
</div>

<div class="modal fade"
	id="add-api-key-modal"
	tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title w-100"><?php echo e($__t('Create new API key')); ?></h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="name"><?php echo e($__t('Description')); ?></label>
					<input type="text"
						class="form-control"
						id="description"
						name="description">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button"
					class="btn btn-secondary"
					data-dismiss="modal"><?php echo e($__t('Cancel')); ?></button>
				<button id="new-api-key-button"
					class="btn btn-primary"><?php echo e($__t('OK')); ?></button>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/manageapikeys.blade.php ENDPATH**/ ?>