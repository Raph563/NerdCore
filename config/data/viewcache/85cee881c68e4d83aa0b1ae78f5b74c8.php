<?php require_frontend_packages(['datatables']); ?>



<?php if($mode == 'edit'): ?>
<?php $__env->startSection('title', $__t('Edit quantity unit')); ?>
<?php else: ?>
<?php $__env->startSection('title', $__t('Create quantity unit')); ?>
<?php endif; ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col">
		<h2 class="title"><?php echo $__env->yieldContent('title'); ?></h2>
	</div>
</div>

<hr class="my-2">

<div class="row">
	<div class="col-lg-6 col-12">
		<script>
			Grocy.EditMode = '<?php echo e($mode); ?>';
		</script>

		<?php if($mode == 'edit'): ?>
		<script>
			Grocy.EditObjectId = <?php echo e($quantityUnit->id); ?>;
		</script>
		<?php endif; ?>

		<form id="quantityunit-form"
			novalidate>

			<div class="form-group">
				<label for="name"><?php echo e($__t('Name')); ?> <span class="small text-muted"><?php echo e($__t('in singular form')); ?></span></label>
				<input type="text"
					class="form-control"
					required
					id="name"
					name="name"
					value="<?php if($mode == 'edit'): ?><?php echo e($quantityUnit->name); ?><?php endif; ?>">
				<div class="invalid-feedback"><?php echo e($__t('A name is required')); ?></div>
			</div>

			<div class="form-group">
				<label for="name_plural"><?php echo e($__t('Name')); ?> <span class="small text-muted"><?php echo e($__t('in plural form')); ?></span></label>
				<input type="text"
					class="form-control"
					id="name_plural"
					name="name_plural"
					value="<?php if($mode == 'edit'): ?><?php echo e($quantityUnit->name_plural); ?><?php endif; ?>">
			</div>

			<?php if($pluralCount > 2): ?>
			<div class="form-group">
				<label for="plural_forms">
					<?php echo e($__t('Plural forms')); ?><br>
					<span class="small text-muted">
						<?php echo e($__t('One plural form per line, the current language requires')); ?>:<br>
						<?php echo e($__t('Plural count')); ?>: <?php echo e($pluralCount); ?><br>
						<?php echo e($__t('Plural rule')); ?>: <?php echo e($pluralRule); ?>

					</span>
				</label>
				<textarea class="form-control"
					rows="3"
					id="plural_forms"
					name="plural_forms"><?php if($mode == 'edit'): ?><?php echo e($quantityUnit->plural_forms); ?><?php endif; ?></textarea>
			</div>
			<?php endif; ?>

			<div class="form-group">
				<div class="custom-control custom-checkbox">
					<input <?php if($mode=='create'
						): ?>
						checked
						<?php elseif($mode=='edit'
						&&
						$quantityUnit->active == 1): ?> checked <?php endif; ?> class="form-check-input custom-control-input" type="checkbox" id="active" name="active" value="1">
					<label class="form-check-label custom-control-label"
						for="active"><?php echo e($__t('Active')); ?></label>
				</div>
			</div>

			<div class="form-group">
				<label for="description"><?php echo e($__t('Description')); ?></label>
				<textarea class="form-control"
					rows="2"
					id="description"
					name="description"><?php if($mode == 'edit'): ?><?php echo e($quantityUnit->description); ?><?php endif; ?></textarea>
			</div>

			<?php echo $__env->make('components.userfieldsform', array(
			'userfields' => $userfields,
			'entity' => 'quantity_units'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<small class="my-2 form-text text-muted <?php if($mode == 'edit'): ?> d-none <?php endif; ?>"><?php echo e($__t('Save & continue to add conversions')); ?></small>

			<button class="save-quantityunit-button btn btn-success mb-2"
				data-location="continue"><?php echo e($__t('Save & continue')); ?></button>
			<button class="save-quantityunit-button btn btn-info mb-2"
				data-location="return"><?php echo e($__t('Save & return to quantity units')); ?></button>

			<?php if($pluralCount > 2): ?>
			<button id="test-quantityunit-plural-forms-button"
				class="btn btn-secondary"><?php echo e($__t('Test plural forms')); ?></button>
			<?php endif; ?>

		</form>
	</div>

	<div class="col-lg-6 col-12 <?php if($mode == 'create'): ?> d-none <?php endif; ?>">
		<div class="row">
			<div class="col">
				<div class="title-related-links">
					<h4>
						<?php echo e($__t('Default conversions')); ?>

						<small id="qu-conversion-headline-info"
							class="text-muted font-italic"></small>
					</h4>
					<button class="btn btn-outline-dark d-md-none mt-2 float-right order-1 order-md-3"
						type="button"
						data-toggle="collapse"
						data-target="#related-links">
						<i class="fa-solid fa-ellipsis-v"></i>
					</button>
					<div class="related-links collapse d-md-flex order-2 width-xs-sm-100"
						id="related-links">
						<a class="btn btn-outline-primary btn-sm m-1 mt-md-0 mb-md-0 float-right show-as-dialog-link"
							href="<?php echo e($U('/quantityunitconversion/new?embedded&qu-unit=' . $quantityUnit->id )); ?>">
							<?php echo e($__t('Add')); ?>

						</a>
					</div>
				</div>

				<table id="qu-conversions-table"
					class="table table-sm table-striped nowrap w-100">
					<thead>
						<tr>
							<th class="border-right"><a class="text-muted change-table-columns-visibility-button"
									data-toggle="tooltip"
									title="<?php echo e($__t('Table options')); ?>"
									data-table-selector="#qu-conversions-table"
									href="#"><i class="fa-solid fa-eye"></i></a>
							</th>
							<th><?php echo e($__t('Factor')); ?></th>
							<th><?php echo e($__t('Unit')); ?></th>
						</tr>
					</thead>
					<tbody class="d-none">
						<?php if($mode == "edit"): ?>
						<?php $__currentLoopData = $defaultQuConversions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $defaultQuConversion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td class="fit-content border-right">
								<a class="btn btn-sm btn-info show-as-dialog-link"
									href="<?php echo e($U('/quantityunitconversion/' . $defaultQuConversion->id . '?embedded&qu-unit=' . $quantityUnit->id )); ?>"
									data-qu-conversion-id="<?php echo e($defaultQuConversion->id); ?>">
									<i class="fa-solid fa-edit"></i>
								</a>
								<a class="btn btn-sm btn-danger qu-conversion-delete-button"
									href="#"
									data-qu-conversion-id="<?php echo e($defaultQuConversion->id); ?>">
									<i class="fa-solid fa-trash"></i>
								</a>
							</td>
							<td>
								<span class="locale-number locale-number-quantity-amount"><?php echo e($defaultQuConversion->factor); ?></span>
							</td>
							<td>
								<?php echo e(FindObjectInArrayByPropertyValue($quantityUnits, 'id', $defaultQuConversion->to_qu_id)->name); ?>

							</td>
						</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/quantityunitform.blade.php ENDPATH**/ ?>