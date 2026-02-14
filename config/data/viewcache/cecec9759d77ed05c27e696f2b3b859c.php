<?php if($mode == 'edit'): ?>
<?php $__env->startSection('title', $__t('Edit userfield')); ?>
<?php else: ?>
<?php $__env->startSection('title', $__t('Create userfield')); ?>
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
			Grocy.EditObjectId = <?php echo e($userfield->id); ?>;
		</script>
		<?php endif; ?>

		<form id="userfield-form"
			novalidate>

			<div class="form-group">
				<label for="entity"><?php echo e($__t('Entity')); ?></label>
				<select required
					class="custom-control custom-select"
					id="entity"
					name="entity">
					<option></option>
					<?php $__currentLoopData = $entities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option <?php if($mode=='edit'
						&&
						$userfield->entity == $entity): ?> selected="selected" <?php endif; ?> value="<?php echo e($entity); ?>"><?php echo e($entity); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
				<div class="invalid-feedback"><?php echo e($__t('A entity is required')); ?></div>
			</div>

			<div class="form-group">
				<label for="name">
					<?php echo e($__t('Name')); ?>

					<i class="fa-solid fa-question-circle text-muted"
						data-toggle="tooltip"
						data-trigger="hover click"
						title="<?php echo e($__t('This is the internal field name, e. g. for the API')); ?>"></i>
				</label>
				<input type="text"
					class="form-control"
					required
					pattern="^[a-zA-Z0-9_]*$"
					id="name"
					name="name"
					value="<?php if($mode == 'edit'): ?><?php echo e($userfield->name); ?><?php endif; ?>">
				<div class="invalid-feedback"><?php echo e($__t('This is required and can only contain letters and numbers')); ?></div>
			</div>

			<div class="form-group">
				<label for="name">
					<?php echo e($__t('Caption')); ?>

					<i class="fa-solid fa-question-circle text-muted"
						data-toggle="tooltip"
						data-trigger="hover click"
						title="<?php echo e($__t('This is used to display the field on the frontend')); ?>"></i>
				</label>
				<input type="text"
					class="form-control"
					required
					id="caption"
					name="caption"
					value="<?php if($mode == 'edit'): ?><?php echo e($userfield->caption); ?><?php endif; ?>">
				<div class="invalid-feedback"><?php echo e($__t('A caption is required')); ?></div>
			</div>

			<?php if($mode == 'edit' && !empty($userfield->sort_number)) { $value = $userfield->sort_number; } else { $value = ''; } ?>
			<?php echo $__env->make('components.numberpicker', array(
			'id' => 'sort_number',
			'label' => 'Sort number',
			'min' => 0,
			'value' => $value,
			'isRequired' => false,
			'hint' => $__t('Multiple Userfields will be ordered by that number on the input form')
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<div class="form-group">
				<label for="type"><?php echo e($__t('Type')); ?></label>
				<select required
					class="custom-control custom-select"
					id="type"
					name="type">
					<option></option>
					<?php $__currentLoopData = $userfieldTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userfieldType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option <?php if($mode=='edit'
						&&
						$userfield->type == $userfieldType): ?> selected="selected" <?php endif; ?> value="<?php echo e($userfieldType); ?>"><?php echo e($__t($userfieldType)); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
				<div class="invalid-feedback"><?php echo e($__t('A type is required')); ?></div>
			</div>

			<div class="form-group d-none">
				<label for="config"><?php echo e($__t('Configuration')); ?> <span id="config-hint"
						class="small text-muted"></span></label>
				<textarea class="form-control"
					rows="10"
					id="config"
					name="config"><?php if($mode == 'edit'): ?><?php echo e($userfield->config); ?><?php endif; ?></textarea>
			</div>

			<div id="default-value-group"
				class="form-group d-none userfield-type-date userfield-type-datetime">
				<label for="entity"><?php echo e($__t('Default value')); ?></label>
				<select class="custom-control custom-select"
					id="default_value"
					name="default_value">
					<option></option>
					<option value="now"
						<?php if($mode=='edit'
						&&
						$userfield->default_value == 'now'): ?> selected="selected" <?php endif; ?>><?php echo e($__t('Now / today')); ?></option>
				</select>
			</div>

			<div class="form-group">
				<div class="custom-control custom-checkbox">
					<input <?php if($mode=='edit'
						&&
						$userfield->show_as_column_in_tables == 1): ?> checked <?php endif; ?> class="form-check-input custom-control-input" type="checkbox" id="show_as_column_in_tables" name="show_as_column_in_tables" value="1">
					<label class="form-check-label custom-control-label"
						for="show_as_column_in_tables"><?php echo e($__t('Show as column in tables')); ?></label>
				</div>
			</div>

			<div class="form-group">
				<div class="custom-control custom-checkbox">
					<input <?php if($mode=='edit'
						&&
						$userfield->input_required == 1): ?> checked <?php endif; ?> class="form-check-input custom-control-input" type="checkbox" id="input_required" name="input_required" value="1">
					<label class="form-check-label custom-control-label"
						for="input_required">
						<?php echo e($__t('Mandatory')); ?>

						&nbsp;<i class="fa-solid fa-question-circle text-muted"
							data-toggle="tooltip"
							data-trigger="hover click"
							title="<?php echo e($__t('When enabled, then this field must be filled on the destination form')); ?>"></i>
					</label>
				</div>
			</div>

			<button id="save-userfield-button"
				class="btn btn-success"><?php echo e($__t('Save')); ?></button>

		</form>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/userfieldform.blade.php ENDPATH**/ ?>