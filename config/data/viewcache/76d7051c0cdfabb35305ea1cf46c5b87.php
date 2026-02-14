<?php if($mode == 'edit'): ?>
<?php $__env->startSection('title', $__t('Edit user')); ?>
<?php else: ?>
<?php $__env->startSection('title', $__t('Create user')); ?>
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
			Grocy.EditObjectId = <?php echo e($user->id); ?>;

			<?php if(!empty($user->picture_file_name)): ?>
			Grocy.UserPictureFileName = '<?php echo e($user->picture_file_name); ?>';
			<?php endif; ?>
		</script>
		<?php endif; ?>

		<form id="user-form"
			novalidate>

			<div class="form-group">
				<label for="username"><?php echo e($__t('Username')); ?></label>
				<input type="text"
					class="form-control"
					required
					id="username"
					name="username"
					value="<?php if($mode == 'edit'): ?><?php echo e($user->username); ?><?php endif; ?>">
				<div class="invalid-feedback"><?php echo e($__t('A username is required')); ?></div>
			</div>

			<div class="form-group">
				<label for="first_name"><?php echo e($__t('First name')); ?></label>
				<input type="text"
					class="form-control"
					id="first_name"
					name="first_name"
					value="<?php if($mode == 'edit'): ?><?php echo e($user->first_name); ?><?php endif; ?>">
			</div>

			<div class="form-group">
				<label for="last_name"><?php echo e($__t('Last name')); ?></label>
				<input type="text"
					class="form-control"
					id="last_name"
					name="last_name"
					value="<?php if($mode == 'edit'): ?><?php echo e($user->last_name); ?><?php endif; ?>">
			</div>

			<?php if(!GROCY_IS_EMBEDDED_INSTALL && !GROCY_DISABLE_AUTH): ?>
			<?php if(!defined('GROCY_EXTERNALLY_MANAGED_AUTHENTICATION')): ?>
			<?php if($mode == 'edit'): ?>
			<div class="form-group mb-1">
				<div class="custom-control custom-checkbox">
					<input class="form-check-input custom-control-input"
						type="checkbox"
						id="change_password"
						name="change_password"
						value="1">
					<label class="form-check-label custom-control-label"
						for="change_password"><?php echo e($__t('Change password')); ?>

					</label>
				</div>
			</div>
			<?php endif; ?>

			<div class="form-group">
				<label for="password"><?php echo e($__t('Password')); ?></label>
				<input type="password"
					class="form-control"
					required
					id="password"
					name="password"
					<?php if($mode=='edit'
					): ?>
					disabled
					<?php endif; ?>>
			</div>

			<div class="form-group">
				<label for="password_confirm"><?php echo e($__t('Confirm password')); ?></label>
				<input type="password"
					class="form-control"
					required
					id="password_confirm"
					name="password_confirm"
					<?php if($mode=='edit'
					): ?>
					disabled
					<?php endif; ?>>
				<div class="invalid-feedback"><?php echo e($__t('Passwords do not match')); ?></div>
			</div>
			<?php endif; ?>
			<?php else: ?>
			<input type="hidden"
				name="password"
				id="password"
				value="x">
			<input type="hidden"
				name="password_confirm"
				id="password_confirm"
				value="x">
			<?php endif; ?>

			<?php echo $__env->make('components.userfieldsform', array(
			'userfields' => $userfields,
			'entity' => 'users'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<button id="save-user-button"
				class="btn btn-success"><?php echo e($__t('Save')); ?></button>

		</form>
	</div>

	<div class="col-lg-6 col-12">
		<div class="title-related-links">
			<h4>
				<?php echo e($__t('Picture')); ?>

			</h4>
			<div class="form-group w-75 m-0">
				<div class="input-group">
					<div class="custom-file">
						<input type="file"
							class="custom-file-input"
							id="user-picture"
							accept="image/*">
						<label id="user-picture-label"
							class="custom-file-label <?php if(empty($user->picture_file_name)): ?> d-none <?php endif; ?>"
							for="user-picture">
							<?php echo e($user->picture_file_name); ?>

						</label>
						<label id="user-picture-label-none"
							class="custom-file-label <?php if(!empty($user->picture_file_name)): ?> d-none <?php endif; ?>"
							for="user-picture">
							<?php echo e($__t('No file selected')); ?>

						</label>
					</div>
					<div class="input-group-append">
						<span class="input-group-text"><i class="fa-solid fa-trash"
								id="delete-current-user-picture-button"></i></span>
					</div>
				</div>
			</div>
		</div>
		<?php if(!empty($user->picture_file_name)): ?>
		<img id="current-user-picture"
			src="<?php echo e($U('/api/files/userpictures/' . base64_encode($user->picture_file_name) . '?force_serve_as=picture&best_fit_width=400')); ?>"
			class="img-fluid img-thumbnail mt-2 mb-5"
			loading="lazy">
		<p id="delete-current-user-picture-on-save-hint"
			class="form-text text-muted font-italic d-none mb-5"><?php echo e($__t('The current picture will be deleted on save')); ?></p>
		<?php else: ?>
		<p id="no-current-user-picture-hint"
			class="form-text text-muted font-italic mb-5"><?php echo e($__t('No picture available')); ?></p>
		<?php endif; ?>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/userform.blade.php ENDPATH**/ ?>