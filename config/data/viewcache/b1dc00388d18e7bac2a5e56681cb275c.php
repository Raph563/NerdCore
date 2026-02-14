<?php require_frontend_packages(['tempusdominus']); ?>

<?php if (! $__env->hasRenderedOnce('6f9e20a0-869b-49da-928a-864744489cc7')): $__env->markAsRenderedOnce('6f9e20a0-869b-49da-928a-864744489cc7'); ?>
<?php $__env->startPush('componentScripts'); ?>
<script src="<?php echo e($U('/viewjs/components/datetimepicker.js', true)); ?>?v=<?php echo e($version); ?>"></script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php if(!isset($isRequired)) { $isRequired = true; } ?>
<?php if(!isset($initialValue)) { $initialValue = ''; } ?>
<?php if(empty($earlierThanInfoLimit)) { $earlierThanInfoLimit = ''; } ?>
<?php if(empty($earlierThanInfoText)) { $earlierThanInfoText = ''; } ?>
<?php if(empty($additionalCssClasses)) { $additionalCssClasses = ''; } ?>
<?php if(empty($additionalGroupCssClasses)) { $additionalGroupCssClasses = ''; } ?>
<?php if(empty($invalidFeedback)) { $invalidFeedback = ''; } ?>
<?php if(!isset($isRequired)) { $isRequired = true; } ?>
<?php if(!isset($noNameAttribute)) { $noNameAttribute = false; } ?>
<?php if(!isset($nextInputSelector)) { $nextInputSelector = false; } ?>
<?php if(empty($additionalAttributes)) { $additionalAttributes = ''; } ?>
<?php if(empty($additionalGroupCssClasses)) { $additionalGroupCssClasses = ''; } ?>
<?php if(empty($activateNumberPad)) { $activateNumberPad = false; } ?>

<div class="datetimepicker-wrapper form-group <?php echo e($additionalGroupCssClasses); ?>">
	<label for="<?php echo e($id); ?>"><?php echo e($__t($label)); ?>

		<?php if(!empty($hint)): ?>
		&nbsp;<i class="fa-solid fa-question-circle text-muted"
			data-toggle="tooltip"
			data-trigger="hover click"
			title="<?php echo e($hint); ?>"></i>
		<?php endif; ?>
		<span class="small text-muted">
			<time id="datetimepicker-timeago"
				class="timeago timeago-contextual"></time>
		</span>
	</label>
	<div class="input-group">
		<div class="input-group date datetimepicker <?php if(!empty($additionalGroupCssClasses)): ?><?php echo e($additionalGroupCssClasses); ?><?php endif; ?>"
			id="<?php echo e($id); ?>"
			<?php if(!$noNameAttribute): ?>
			name="<?php echo e($id); ?>"
			<?php endif; ?>
			data-target-input="nearest">
			<input <?php echo $additionalAttributes; ?>

				type="text"
				<?php if($activateNumberPad): ?>
				inputmode="numeric"
				<?php endif; ?>
				<?php if($isRequired): ?>
				required
				<?php endif; ?>
				class="form-control datetimepicker-input <?php if(!empty($additionalCssClasses)): ?><?php echo e($additionalCssClasses); ?><?php endif; ?>"
				data-target="#<?php echo e($id); ?>"
				data-format="<?php echo e($format); ?>"
				data-init-with-now="<?php echo e(BoolToString($initWithNow)); ?>"
				data-init-value="<?php echo e($initialValue); ?>"
				data-limit-end-to-now="<?php echo e(BoolToString($limitEndToNow)); ?>"
				data-limit-start-to-now="<?php echo e(BoolToString($limitStartToNow)); ?>"
				data-next-input-selector="<?php echo e($nextInputSelector); ?>"
				data-earlier-than-limit="<?php echo e($earlierThanInfoLimit); ?>" />
			<div class="input-group-append"
				data-target="#<?php echo e($id); ?>"
				data-toggle="datetimepicker">
				<div class="input-group-text"><i class="fa-solid fa-calendar"></i></div>
			</div>
			<div class="invalid-feedback"><?php echo e($invalidFeedback); ?></div>
		</div>
		<div id="datetimepicker-earlier-than-info"
			class="form-text text-info font-italic w-100 d-none"><?php echo e($earlierThanInfoText); ?></div>
		<?php if(isset($shortcutValue) && isset($shortcutLabel)): ?>
		<div class="form-group mt-n2 mb-0">
			<div class="custom-control custom-checkbox">
				<input class="form-check-input custom-control-input"
					type="checkbox"
					id="datetimepicker-shortcut"
					name="datetimepicker-shortcut"
					value="1"
					data-datetimepicker-shortcut-value="<?php echo e($shortcutValue); ?>"
					tabindex="-1">
				<label class="form-check-label custom-control-label"
					for="datetimepicker-shortcut"><?php echo e($__t($shortcutLabel)); ?>

				</label>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php /**PATH /app/www/views/components/datetimepicker.blade.php ENDPATH**/ ?>