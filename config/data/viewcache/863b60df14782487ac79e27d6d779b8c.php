<?php require_frontend_packages(['fullcalendar', 'bwipjs']); ?>



<?php $__env->startSection('title', $__t('Calendar')); ?>

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
			<div class="related-links collapse d-md-flex order-2 width-xs-sm-100 d-print-none"
				id="related-links">
				<a id="ical-button"
					class="btn btn-outline-dark m-1 mt-md-0 mb-md-0 float-right"
					href="#">
					<?php echo e($__t('Share/Integrate calendar (iCal)')); ?>

				</a>
				<a id="configure-colors-button"
					class="btn btn-outline-secondary m-1 mt-md-0 mb-md-0 float-right"
					href="#">
					<?php echo e($__t('Configure colors')); ?>

				</a>
			</div>
		</div>
	</div>
</div>

<hr class="my-2">

<script>
	var fullcalendarEventSources = <?php echo json_encode(array($fullcalendarEventSources)); ?>

</script>

<div class="row">
	<div class="col">
		<div id="calendar"></div>
	</div>
</div>

<div class="modal fade"
	id="configure-colors-modal"
	tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title w-100"><?php echo e($__t('Configure colors')); ?></h4>
			</div>
			<div class="modal-body">
				<?php if(GROCY_FEATURE_FLAG_STOCK_BEST_BEFORE_DATE_TRACKING): ?>
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><?php echo e($__t('Products')); ?></span>
						</div>
						<input id="calendar_color_products"
							data-setting-key="calendar_color_products"
							class="form-control user-setting-control"
							type="color"
							value=<?php echo e($userSettings['calendar_color_products']); ?>>
					</div>
				</div>
				<?php endif; ?>

				<?php if(GROCY_FEATURE_FLAG_TASKS): ?>
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><?php echo e($__t('Tasks')); ?></span>
						</div>
						<input id="calendar_color_tasks"
							data-setting-key="calendar_color_tasks"
							class="form-control user-setting-control"
							type="color"
							value=<?php echo e($userSettings['calendar_color_tasks']); ?>>
					</div>
				</div>
				<?php endif; ?>

				<?php if(GROCY_FEATURE_FLAG_CHORES): ?>
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><?php echo e($__t('Chores')); ?></span>
						</div>
						<input id="calendar_color_chores"
							data-setting-key="calendar_color_chores"
							class="form-control user-setting-control"
							type="color"
							value=<?php echo e($userSettings['calendar_color_chores']); ?>>
					</div>
				</div>
				<?php endif; ?>

				<?php if(GROCY_FEATURE_FLAG_BATTERIES): ?>
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><?php echo e($__t('Batteries')); ?></span>
						</div>
						<input id="calendar_color_batteries"
							data-setting-key="calendar_color_batteries"
							class="form-control user-setting-control"
							type="color"
							value=<?php echo e($userSettings['calendar_color_batteries']); ?>>
					</div>
				</div>
				<?php endif; ?>

				<?php if(GROCY_FEATURE_FLAG_RECIPES_MEALPLAN): ?>
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><?php echo e($__t('Meal plan')); ?></span>
						</div>
						<input id="calendar_color_meal_plan"
							data-setting-key="calendar_color_meal_plan"
							class="form-control user-setting-control"
							type="color"
							value=<?php echo e($userSettings['calendar_color_meal_plan']); ?>>
					</div>
				</div>
				<?php endif; ?>
			</div>
			<div class="modal-footer">
				<button type="button"
					class="btn btn-success"
					data-dismiss="modal"><?php echo e($__t('OK')); ?></button>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/calendar.blade.php ENDPATH**/ ?>