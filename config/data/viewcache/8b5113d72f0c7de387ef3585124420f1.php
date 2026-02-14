<?php require_frontend_packages(['summernote']); ?>



<?php if($mode == 'edit'): ?>
<?php $__env->startSection('title', $__t('Edit equipment')); ?>
<?php else: ?>
<?php $__env->startSection('title', $__t('Create equipment')); ?>
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
			Grocy.EditObjectId = <?php echo e($equipment->id); ?>;
		</script>

		<?php if(!empty($equipment->instruction_manual_file_name)): ?>
		<script>
			Grocy.InstructionManualFileNameName = '<?php echo e($equipment->instruction_manual_file_name); ?>';
		</script>
		<?php endif; ?>
		<?php endif; ?>

		<form id="equipment-form"
			novalidate>

			<div class="form-group">
				<label for="name"><?php echo e($__t('Name')); ?></label>
				<input type="text"
					class="form-control"
					required
					id="name"
					name="name"
					value="<?php if($mode == 'edit'): ?><?php echo e($equipment->name); ?><?php endif; ?>">
				<div class="invalid-feedback"><?php echo e($__t('A name is required')); ?></div>
			</div>

			<div class="form-group">
				<label for="description"><?php echo e($__t('Notes')); ?></label>
				<textarea class="form-control wysiwyg-editor"
					id="description"
					name="description"><?php if($mode == 'edit'): ?><?php echo e($equipment->description); ?><?php endif; ?></textarea>
			</div>

			<?php echo $__env->make('components.userfieldsform', array(
			'userfields' => $userfields,
			'entity' => 'equipment'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<button id="save-equipment-button"
				class="btn btn-success"><?php echo e($__t('Save')); ?></button>

		</form>
	</div>

	<div class="col-lg-6 col-12">
		<div class="row">
			<div class="col">
				<div class="title-related-links mb-3">
					<h4>
						<?php echo e($__t('Instruction manual')); ?>

					</h4>
					<div class="form-group w-75 m-0">
						<div class="input-group">
							<div class="custom-file">
								<input type="file"
									class="custom-file-input"
									id="instruction-manual"
									accept="application/pdf">
								<label id="instruction-manual-label"
									class="custom-file-label <?php if(empty($equipment->instruction_manual_file_name)): ?> d-none <?php endif; ?>"
									for="instruction-manual">
									<?php echo e($equipment->instruction_manual_file_name); ?>

								</label>
								<label id="instruction-manual-label-none"
									class="custom-file-label <?php if(!empty($equipment->instruction_manual_file_name)): ?> d-none <?php endif; ?>"
									for="instruction-manual">
									<?php echo e($__t('No file selected')); ?>

								</label>
							</div>
							<div class="input-group-append">
								<span class="input-group-text"><i class="fa-solid fa-trash"
										id="delete-current-instruction-manual-button"></i></span>
							</div>
						</div>
					</div>
				</div>
				<?php if(!empty($equipment->instruction_manual_file_name)): ?>
				<embed id="current-equipment-instruction-manual"
					class="embed-responsive embed-responsive-4by3"
					src="<?php echo e($U('/api/files/equipmentmanuals/' . base64_encode($equipment->instruction_manual_file_name))); ?>"
					type="application/pdf">
				<p id="delete-current-instruction-manual-on-save-hint"
					class="form-text text-muted font-italic d-none"><?php echo e($__t('The current file will be deleted on save')); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/equipmentform.blade.php ENDPATH**/ ?>