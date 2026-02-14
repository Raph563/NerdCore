<?php $__env->startSection('title', $__t('Chores settings')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col">
		<h2 class="title"><?php echo $__env->yieldContent('title'); ?></h2>
	</div>
</div>

<hr class="my-2">

<div class="row">
	<div class="col-lg-4 col-md-8 col-12">
		<h4 "><?php echo e($__t('Chores overview')); ?></h4>

		<?php echo $__env->make('components.numberpicker', array(
		'id' => 'chores_due_soon_days',
		'additionalAttributes' => 'data-setting-key=" chores_due_soon_days"', 'label'=> 'Due soon days',
			'min' => 0,
			'additionalCssClasses' => 'user-setting-control',
			'hint' => $__t('Set to 0 to hide due soon filters/highlighting')
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<div class="form-group">
				<div class="custom-control custom-checkbox">
					<input type="checkbox"
						class="form-check-input custom-control-input user-setting-control"
						id="chores_overview_swap_tracking_buttons"
						data-setting-key="chores_overview_swap_tracking_buttons">
					<label class="form-check-label custom-control-label"
						for="chores_overview_swap_tracking_buttons">
						<?php echo e($__t('Swap track next schedule / track now buttons')); ?>

					</label>
				</div>
			</div>

			<a href="<?php echo e($U('/choresoverview')); ?>"
				class="btn btn-success"><?php echo e($__t('OK')); ?></a>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/choressettings.blade.php ENDPATH**/ ?>