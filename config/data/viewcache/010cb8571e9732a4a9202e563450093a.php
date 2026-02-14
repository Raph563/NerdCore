<?php $__env->startSection('title', $__t('Recipes settings')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col">
		<h2 class="title"><?php echo $__env->yieldContent('title'); ?></h2>
	</div>
</div>

<hr class="my-2">

<div class="row">
	<div class="col-lg-4 col-md-8 col-12">
		<div class="form-group">
			<div class="custom-control custom-checkbox">
				<input type="checkbox"
					class="form-check-input custom-control-input user-setting-control"
					id="recipes_show_list_side_by_side"
					data-setting-key="recipes_show_list_side_by_side">
				<label class="form-check-label custom-control-label"
					for="recipes_show_list_side_by_side">
					<?php echo e($__t('Show the recipe list and the recipe side by side')); ?>

				</label>
			</div>
		</div>

		<div class="form-group">
			<div class="custom-control custom-checkbox">
				<input type="checkbox"
					class="form-check-input custom-control-input user-setting-control"
					id="recipes_show_ingredient_checkbox"
					data-setting-key="recipes_show_ingredient_checkbox">
				<label class="form-check-label custom-control-label"
					for="recipes_show_ingredient_checkbox">
					<?php echo e($__t('Show a little checkbox next to each ingredient to mark it as done')); ?>

					<i class="fa-solid fa-question-circle text-muted"
						data-toggle="tooltip"
						data-trigger="hover click"
						title="<?php echo e($__t('The ingredient is crossed out when clicked, the status is not saved, means reset when the page is reloaded')); ?>"></i>
				</label>
			</div>
		</div>

		<h4 class="mt-5"><?php echo e($__t('Recipe card')); ?></h4>
		<div class="form-group">
			<div class="custom-control custom-checkbox">
				<input type="checkbox"
					class="form-check-input custom-control-input user-setting-control"
					id="recipe_ingredients_group_by_product_group"
					data-setting-key="recipe_ingredients_group_by_product_group">
				<label class="form-check-label custom-control-label"
					for="recipe_ingredients_group_by_product_group">
					<?php echo e($__t('Group ingredients by their product group')); ?>

				</label>
			</div>
		</div>

		<a href="<?php echo e($U('/recipes')); ?>"
			class="btn btn-success"><?php echo e($__t('OK')); ?></a>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/recipessettings.blade.php ENDPATH**/ ?>