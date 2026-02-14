<?php if($mode == 'edit'): ?>
<?php $__env->startSection('title', $__t('Edit location')); ?>
<?php else: ?>
<?php $__env->startSection('title', $__t('Create location')); ?>
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
			Grocy.EditObjectId = <?php echo e($location->id); ?>;
		</script>
		<?php endif; ?>

		<form id="location-form"
			novalidate>

			<div class="form-group">
				<label for="name"><?php echo e($__t('Name')); ?></label>
				<input type="text"
					class="form-control"
					required
					id="name"
					name="name"
					value="<?php if($mode == 'edit'): ?><?php echo e($location->name); ?><?php endif; ?>">
				<div class="invalid-feedback"><?php echo e($__t('A name is required')); ?></div>
			</div>

			<div class="form-group">
				<div class="custom-control custom-checkbox">
					<input <?php if($mode=='create'
						): ?>
						checked
						<?php elseif($mode=='edit'
						&&
						$location->active == 1): ?> checked <?php endif; ?> class="form-check-input custom-control-input" type="checkbox" id="active" name="active" value="1">
					<label class="form-check-label custom-control-label"
						for="active"><?php echo e($__t('Active')); ?></label>
				</div>
			</div>

			<div class="form-group">
				<label for="description"><?php echo e($__t('Description')); ?></label>
				<textarea class="form-control"
					rows="2"
					id="description"
					name="description"><?php if($mode == 'edit'): ?><?php echo e($location->description); ?><?php endif; ?></textarea>
			</div>

			<?php if(GROCY_FEATURE_FLAG_STOCK_PRODUCT_FREEZING): ?>
			<div class="form-group">
				<div class="custom-control custom-checkbox">
					<input <?php if($mode=='edit'
						&&
						$location->is_freezer == 1): ?> checked <?php endif; ?> class="form-check-input custom-control-input" type="checkbox" id="is_freezer" name="is_freezer" value="1">
					<label class="form-check-label custom-control-label"
						for="is_freezer"><?php echo e($__t('Is freezer')); ?>

						&nbsp;<i class="fa-solid fa-question-circle text-muted"
							data-toggle="tooltip"
							data-trigger="hover click"
							title="<?php echo e($__t('When moving products from/to a freezer location, the products due date is automatically adjusted according to the product settings')); ?>"></i>
					</label>
				</div>
			</div>
			<?php else: ?>
			<input type="hidden"
				name="is_freezer"
				value="0">
			<?php endif; ?>

			<?php echo $__env->make('components.userfieldsform', array(
			'userfields' => $userfields,
			'entity' => 'locations'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<button id="save-location-button"
				class="btn btn-success"><?php echo e($__t('Save')); ?></button>

		</form>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/locationform.blade.php ENDPATH**/ ?>