<?php if (! $__env->hasRenderedOnce('6360239e-251f-4a5e-9f21-1d901af7bfef')): $__env->markAsRenderedOnce('6360239e-251f-4a5e-9f21-1d901af7bfef'); ?>
<?php $__env->startPush('componentScripts'); ?>
<script src="<?php echo e($U('/viewjs/components/chorecard.js', true)); ?>?v=<?php echo e($version); ?>"></script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php if(!isset($asModal)) { $asModal = false; } ?>

<?php if($asModal): ?>
<div class="modal fade"
	id="chorecard-modal"
	tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-body">
				<?php endif; ?>

				<div class="card chorecard">
					<div class="card-header">
						<span class="float-left"><?php echo e($__t('Chore overview')); ?></span>
						<a id="chorecard-chore-edit-button"
							class="btn btn-sm btn-outline-secondary py-0 float-right disabled"
							href="#"
							data-toggle="tooltip"
							title="<?php echo e($__t('Edit chore')); ?>">
							<i class="fa-solid fa-edit"></i>
						</a>
						<a id="chorecard-chore-journal-button"
							class="btn btn-sm btn-outline-secondary py-0 mr-1 float-right disabled show-as-dialog-link"
							href="#"
							data-dialog-type="table">
							<?php echo e($__t('Chore journal')); ?>

						</a>
					</div>
					<div class="card-body">
						<h3><span id="chorecard-chore-name"></span></h3>

						<p id="chorecard-chore-description"
							class="text-muted mt-0"></p>

						<strong><?php echo e($__t('Tracked count')); ?>:</strong> <span id="chorecard-chore-tracked-count"
							class="locale-number locale-number-generic"></span><br>
						<strong><?php echo e($__t('Average execution frequency')); ?>:</strong> <span id="chorecard-average-execution-frequency"></span><br>
						<strong><?php echo e($__t('Last tracked')); ?>:</strong> <span id="chorecard-chore-last-tracked"></span> <time id="chorecard-chore-last-tracked-timeago"
							class="timeago timeago-contextual"></time><br>
						<?php if(GROCY_FEATURE_FLAG_CHORES_ASSIGNMENTS): ?>
						<strong><?php echo e($__t('Last done by')); ?>:</strong> <span id="chorecard-chore-last-done-by"></span>
						<?php endif; ?>
					</div>
				</div>

				<?php if($asModal): ?>
			</div>
			<div class="modal-footer">
				<button type="button"
					class="btn btn-secondary"
					data-dismiss="modal"><?php echo e($__t('Close')); ?></button>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<?php /**PATH /app/www/views/components/chorecard.blade.php ENDPATH**/ ?>