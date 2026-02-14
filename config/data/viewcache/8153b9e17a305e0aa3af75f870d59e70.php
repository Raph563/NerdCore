<?php require_frontend_packages(['fullcalendar']); ?>



<?php $__env->startSection('title', $__t('Meal plan')); ?>

<?php $__env->startPush('pageStyles'); ?>
<style>
	.fc-event-container {
		border-bottom: 1px solid !important;
		border-color: #d6d6d6 !important;
	}

	.img-fluid {
		max-width: 90%;
		max-height: 140px;
	}

	.fc-time-grid-container,
	hr.fc-divider {
		display: none;
	}

	.fc-axis {
		width: 25px !important;
	}

	.fc-axis div {
		transform: translateX(-50%) translateY(-50%) rotate(-90deg);
		font-weight: bold;
		font-size: 1.75em;
		letter-spacing: 0.1em;
		position: absolute;
		top: 50%;
		left: 0;
		margin-left: 17px;
		min-width: 100px;
		line-height: 0.55;
		text-align: center;
	}

	.fc-axis .small {
		font-size: 60%;
		letter-spacing: normal;
	}

	.fc-content-skeleton {
		padding-bottom: 0 !important;
	}

	.calendar[data-primary-section='false'] .fc-toolbar.fc-header-toolbar,
	.calendar[data-primary-section='false'] .fc-head {
		display: none;
	}

	.calendar[data-primary-section='false'] {
		border-top: #d6d6d6 solid 5px;
	}

	@media (min-width: 400px) {
		.table-inline-menu.dropdown-menu {
			width: 200px !important;
		}
	}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<script>
	var fullcalendarEventSources = <?php echo json_encode(array($fullcalendarEventSources)); ?>;
	var internalRecipes = <?php echo json_encode($internalRecipes); ?>;
	var recipesResolved = <?php echo json_encode($recipesResolved); ?>;
	var weekRecipe = <?php echo json_encode($weekRecipe); ?>;

	Grocy.QuantityUnits = <?php echo json_encode($quantityUnits); ?>;
	Grocy.QuantityUnitConversionsResolved = <?php echo json_encode($quantityUnitConversionsResolved); ?>;

	Grocy.MealPlanFirstDayOfWeek = '<?php echo e(GROCY_MEAL_PLAN_FIRST_DAY_OF_WEEK); ?>';
</script>

<div class="row">
	<div class="col">
		<div class="title-related-links">
			<h2 class="title"><?php echo $__env->yieldContent('title'); ?></h2>
			<div class="float-right d-print-none">
				<button class="btn btn-outline-dark d-md-none mt-2 order-1 order-md-3"
					type="button"
					data-toggle="collapse"
					data-target="#related-links">
					<i class="fa-solid fa-ellipsis-v"></i>
				</button>
			</div>
			<div class="related-links collapse d-md-flex order-2 width-xs-sm-100 d-print-none"
				id="related-links">
				<a id="print-meal-plan-button"
					class="btn btn-outline-dark m-1 mt-md-0 mb-md-0 float-right">
					<?php echo e($__t('Print')); ?>

				</a>
				<a class="btn btn-outline-secondary m-1 mt-md-0 mb-md-0 float-right"
					href="<?php echo e($U('/mealplansections')); ?>">
					<?php echo e($__t('Configure sections')); ?>

				</a>
			</div>
		</div>
	</div>
</div>

<hr class="my-2">

<?php $__currentLoopData = $usedMealplanSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mealplanSection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="row">
	<div class="col">
		<div class="calendar"
			data-section-id="<?php echo e($mealplanSection->id); ?>"
			data-section-name="<?php echo e($mealplanSection->name); ?><br><span class='small text-muted'><?php echo e($mealplanSection->time_info); ?></span>"
			data-primary-section="<?php echo e(BoolToString($loop->first)); ?>"
			
			data-last-section="<?php echo e(BoolToString(array_values(array_slice($usedMealplanSections->fetchAll(), -1))[0]->id == $mealplanSection->id)); ?>">
		</div>
	</div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php if($usedMealplanSections->count() === 0): ?>
<div class="row">
	<div class="col">
		<div class="calendar"
			data-section-id="-1"
			data-section-name=""
			data-primary-section="true"
			data-last-section="true">
		</div>
	</div>
</div>
<?php endif; ?>

<div class="modal fade"
	id="add-recipe-modal"
	tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title w-100">
					<span id="add-recipe-modal-title"></span>
					<span class="text-muted float-right"><?php echo e($__t('Recipe')); ?></span>
				</h4>
			</div>
			<div class="modal-body">
				<form id="add-recipe-form"
					novalidate>

					<?php echo $__env->make('components.datetimepicker', array(
					'id' => 'day',
					'label' => 'Day',
					'format' => 'YYYY-MM-DD',
					'initWithNow' => false,
					'limitEndToNow' => false,
					'limitStartToNow' => false,
					'isRequired' => true,
					'additionalCssClasses' => 'date-only-datetimepicker',
					'invalidFeedback' => $__t('A date is required')
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

					<?php echo $__env->make('components.recipepicker', array(
					'recipes' => $recipes,
					'isRequired' => true,
					'nextInputSelector' => '#recipe_servings'
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

					<?php echo $__env->make('components.numberpicker', array(
					'id' => 'recipe_servings',
					'label' => 'Servings',
					'min' => $DEFAULT_MIN_AMOUNT,
					'decimals' => $userSettings['stock_decimal_places_amounts'],
					'value' => '1',
					'additionalCssClasses' => 'locale-number-input locale-number-quantity-amount'
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

					<div class="form-group">
						<label for="period_type"><?php echo e($__t('Section')); ?></label>
						<select class="custom-control custom-select"
							id="section_id_recipe"
							name="section_id_recipe"
							required>
							<?php $__currentLoopData = $mealplanSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mealplanSection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($mealplanSection->id); ?>"><?php echo e($mealplanSection->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>

					<input type="hidden"
						id="day"
						name="day"
						value="">
					<input type="hidden"
						name="type"
						value="recipe">

				</form>
			</div>
			<div class="modal-footer">
				<button type="button"
					class="btn btn-secondary"
					data-dismiss="modal"><?php echo e($__t('Cancel')); ?></button>
				<button id="save-add-recipe-button"
					class="btn btn-success"><?php echo e($__t('Save')); ?></button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade"
	id="add-note-modal"
	tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title w-100">
					<span id="add-note-modal-title"></span>
					<span class="text-muted float-right"><?php echo e($__t('Note')); ?></span>
				</h4>
			</div>
			<div class="modal-body">
				<form id="add-note-form"
					novalidate>

					<div class="form-group">
						<label for="note"><?php echo e($__t('Note')); ?></label>
						<textarea class="form-control"
							rows="2"
							id="note"
							name="note"></textarea>
					</div>

					<div class="form-group">
						<label for="section_id_note"><?php echo e($__t('Section')); ?></label>
						<select class="custom-control custom-select"
							id="section_id_note"
							name="section_id_note"
							required>
							<?php $__currentLoopData = $mealplanSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mealplanSection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($mealplanSection->id); ?>"><?php echo e($mealplanSection->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>

					<input type="hidden"
						name="type"
						value="note">

				</form>
			</div>
			<div class="modal-footer">
				<button type="button"
					class="btn btn-secondary"
					data-dismiss="modal"><?php echo e($__t('Cancel')); ?></button>
				<button id="save-add-note-button"
					class="btn btn-success"><?php echo e($__t('Save')); ?></button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade"
	id="add-product-modal"
	tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title w-100">
					<span id="add-product-modal-title"></span>
					<span class="text-muted float-right"><?php echo e($__t('Product')); ?></span>
				</h4>
			</div>
			<div class="modal-body">
				<form id="add-product-form"
					novalidate>

					<?php echo $__env->make('components.productpicker', array(
					'products' => $products,
					'nextInputSelector' => '#amount'
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

					<?php echo $__env->make('components.productamountpicker', array(
					'value' => 1,
					'additionalGroupCssClasses' => 'mb-0'
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

					<div class="form-group">
						<label for="period_type"><?php echo e($__t('Section')); ?></label>
						<select class="custom-control custom-select"
							id="section_id_product"
							name="section_id_product"
							required>
							<?php $__currentLoopData = $mealplanSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mealplanSection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($mealplanSection->id); ?>"><?php echo e($mealplanSection->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>

					<input type="hidden"
						name="type"
						value="product">

				</form>
			</div>
			<div class="modal-footer">
				<button type="button"
					class="btn btn-secondary"
					data-dismiss="modal"><?php echo e($__t('Cancel')); ?></button>
				<button id="save-add-product-button"
					class="btn btn-success"><?php echo e($__t('Save')); ?></button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade"
	id="copy-day-modal"
	tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 id="copy-day-modal-title"
					class="modal-title w-100"></h4>
			</div>
			<div class="modal-body">
				<form id="copy-day-form"
					novalidate>

					<?php echo $__env->make('components.datetimepicker2', array(
					'id' => 'copy_to_date',
					'label' => 'Day',
					'format' => 'YYYY-MM-DD',
					'initWithNow' => false,
					'limitEndToNow' => false,
					'limitStartToNow' => false,
					'isRequired' => true,
					'additionalCssClasses' => 'date-only-datetimepicker2',
					'invalidFeedback' => $__t('A date is required')
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

				</form>
			</div>
			<div class="modal-footer">
				<button type="button"
					class="btn btn-secondary"
					data-dismiss="modal"><?php echo e($__t('Cancel')); ?></button>
				<button id="save-copy-day-button"
					class="btn btn-primary"><?php echo e($__t('Copy')); ?></button>
			</div>
		</div>
	</div>
</div>

<?php echo $__env->make('components.productcard', [
'asModal' => true
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/mealplan.blade.php ENDPATH**/ ?>