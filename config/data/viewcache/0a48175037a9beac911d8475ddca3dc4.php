<?php require_frontend_packages(['datatables', 'summernote']); ?>



<?php if($mode == 'edit'): ?>
<?php $__env->startSection('title', $__t('Edit recipe')); ?>
<?php else: ?>
<?php $__env->startSection('title', $__t('Create recipe')); ?>
<?php endif; ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col">
		<h2 class="title"><?php echo $__env->yieldContent('title'); ?></h2>

		<script>
			Grocy.EditMode = '<?php echo e($mode); ?>';
			Grocy.QuantityUnits = <?php echo json_encode($quantityunits); ?>;
			Grocy.QuantityUnitConversionsResolved = <?php echo json_encode($quantityUnitConversionsResolved); ?>;
		</script>

		<?php if($mode == 'edit'): ?>
		<script>
			Grocy.EditObjectId = <?php echo e($recipe->id); ?>;
		</script>

		<?php if(!empty($recipe->picture_file_name)): ?>
		<script>
			Grocy.RecipePictureFileName = '<?php echo e($recipe->picture_file_name); ?>';
		</script>
		<?php endif; ?>
		<?php endif; ?>
	</div>
</div>

<hr class="my-2">

<div class="row">
	<div class="col-12 col-md-7 pb-3">
		<form id="recipe-form"
			novalidate>

			<div class="form-group">
				<label for="name"><?php echo e($__t('Name')); ?></label>
				<input type="text"
					class="form-control"
					required
					id="name"
					name="name"
					value="<?php if($mode == 'edit'): ?><?php echo e($recipe->name); ?><?php endif; ?>">
				<div class="invalid-feedback"><?php echo e($__t('A name is required')); ?></div>
			</div>

			<?php if($mode == 'edit') { $value = $recipe->base_servings; } else { $value = 1; } ?>
			<?php echo $__env->make('components.numberpicker', array(
			'id' => 'base_servings',
			'label' => 'Servings',
			'min' => $DEFAULT_MIN_AMOUNT,
			'decimals' => $userSettings['stock_decimal_places_amounts'],
			'value' => $value,
			'hint' => $__t('The ingredients listed here result in this amount of servings'),
			'additionalCssClasses' => 'locale-number-input locale-number-quantity-amount'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<div class="form-group">
				<div class="custom-control custom-checkbox">
					<input <?php if($mode=='edit'
						&&
						$recipe->not_check_shoppinglist == 1): ?> checked <?php endif; ?> class="form-check-input custom-control-input" type="checkbox" id="not_check_shoppinglist" name="not_check_shoppinglist" value="1">
					<label class="form-check-label custom-control-label"
						for="not_check_shoppinglist">
						<?php echo e($__t('Do not check against the shopping list when adding missing items to it')); ?>&nbsp;
						<i class="fa-solid fa-question-circle text-muted"
							data-toggle="tooltip"
							data-trigger="hover click"
							title="<?php echo e($__t('By default the amount to be added to the shopping list is "needed amount - stock amount - shopping list amount" - when this is enabled, it is only checked against the stock amount, not against what is already on the shopping list')); ?>"></i>
					</label>
				</div>
			</div>

			<?php echo $__env->make('components.productpicker', array(
			'products' => $products,
			'isRequired' => false,
			'label' => 'Produces product',
			'prefillById' => $mode == 'edit' ? $recipe->product_id : '',
			'hint' => $__t('When a product is selected, one unit (per serving in stock quantity unit) will be added to stock on consuming this recipe'),
			'disallowAllProductWorkflows' => true,
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<?php echo $__env->make('components.userfieldsform', array(
			'userfields' => $userfields,
			'entity' => 'recipes'
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

			<div class="form-group">
				<label for="description"><?php echo e($__t('Preparation')); ?></label>
				<textarea id="description"
					class="form-control wysiwyg-editor"
					name="description"><?php if($mode == 'edit'): ?><?php echo e($recipe->description); ?><?php endif; ?></textarea>
			</div>

			<small class="my-2 form-text text-muted <?php if($mode == 'edit'): ?> d-none <?php endif; ?>"><?php echo e($__t('Save & continue to add ingredients and included recipes')); ?></small>

			<button class="save-recipe btn btn-success mb-2"
				data-location="continue"><?php echo e($__t('Save & continue')); ?></button>
			<button class="save-recipe btn btn-info mb-2"
				data-location="return"><?php echo e($__t('Save & return to recipes')); ?></button>

		</form>
	</div>

	<div class="col-12 col-md-5 pb-3 <?php if($mode == 'create'): ?> d-none <?php endif; ?>">
		<div class="row">
			<div class="col">
				<div class="title-related-links">
					<h4>
						<?php echo e($__t('Ingredients list')); ?>

					</h4>
					<button class="btn btn-outline-dark d-md-none mt-2 float-right order-1 order-md-3"
						type="button"
						data-toggle="collapse"
						data-target="#related-links">
						<i class="fa-solid fa-ellipsis-v"></i>
					</button>
					<div class="related-links collapse d-md-flex order-2 width-xs-sm-100"
						id="related-links">
						<a id="recipe-pos-add-button"
							class="btn btn-outline-primary btn-sm recipe-pos-add-button m-1 mt-md-0 mb-md-0 float-right"
							type="button"
							href="#">
							<?php echo e($__t('Add')); ?>

						</a>
					</div>
				</div>

				<table id="recipes-pos-table"
					class="table table-sm table-striped nowrap w-100">
					<thead>
						<tr>
							<th class="border-right"><a class="text-muted change-table-columns-visibility-button"
									data-toggle="tooltip"
									title="<?php echo e($__t('Table options')); ?>"
									data-table-selector="#recipes-pos-table"
									href="#"><i class="fa-solid fa-eye"></i></a>
							</th>
							<th><?php echo e($__t('Product')); ?></th>
							<th><?php echo e($__t('Amount')); ?></th>
							<th class="fit-content"><?php echo e($__t('Note')); ?></th>
							<th class="allow-grouping"><?php echo e($__t('Ingredient group')); ?></th>
						</tr>
					</thead>
					<tbody class="d-none">
						<?php if($mode == "edit"): ?>
						<?php $__currentLoopData = $recipePositions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipePosition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td class="fit-content border-right">
								<a class="btn btn-sm btn-info recipe-pos-edit-button"
									type="button"
									href="#"
									data-recipe-pos-id="<?php echo e($recipePosition->id); ?>"
									data-product-id="<?php echo e($recipePosition->product_id); ?>">
									<i class="fa-solid fa-edit"></i>
								</a>
								<a class="btn btn-sm btn-danger recipe-pos-delete-button"
									href="#"
									data-recipe-pos-id="<?php echo e($recipePosition->id); ?>"
									data-recipe-pos-name="<?php echo e(FindObjectInArrayByPropertyValue($products, 'id', $recipePosition->product_id)->name); ?>">
									<i class="fa-solid fa-trash"></i>
								</a>
							</td>
							<td>
								<?php echo e(FindObjectInArrayByPropertyValue($products, 'id', $recipePosition->product_id)->name); ?>

							</td>
							<td>
								<?php
								// The amount can't be non-numeric when using the frontend,
								// but some users decide to edit the database manually and
								// enter something like "4 or 5" in the amount column (brilliant)
								// => So at least don't crash this view by just assuming 0 if that's the case
								if (!is_numeric($recipePosition->amount))
								{
								$recipePosition->amount = 0;
								}

								$product = FindObjectInArrayByPropertyValue($products, 'id', $recipePosition->product_id);
								$productQuConversions = FindAllObjectsInArrayByPropertyValue($quantityUnitConversionsResolved, 'product_id', $product->id);
								$productQuConversions = FindAllObjectsInArrayByPropertyValue($productQuConversions, 'from_qu_id', $product->qu_id_stock);
								$productQuConversion = FindObjectInArrayByPropertyValue($productQuConversions, 'to_qu_id', $recipePosition->qu_id);
								if ($productQuConversion && $recipePosition->only_check_single_unit_in_stock == 0)
								{
								$recipePosition->amount = $recipePosition->amount * $productQuConversion->factor;
								}
								?>
								<?php if(!empty($recipePosition->variable_amount)): ?>
								<?php echo e($recipePosition->variable_amount); ?>

								<?php else: ?>
								<span class="locale-number locale-number-quantity-amount"><?php if($recipePosition->amount == round($recipePosition->amount)): ?><?php echo e(round($recipePosition->amount)); ?><?php else: ?><?php echo e($recipePosition->amount); ?><?php endif; ?></span>
								<?php endif; ?>
								<?php echo e($__n($recipePosition->amount, FindObjectInArrayByPropertyValue($quantityunits, 'id', $recipePosition->qu_id)->name, FindObjectInArrayByPropertyValue($quantityunits, 'id', $recipePosition->qu_id)->name_plural, true)); ?>


								<?php if(!empty($recipePosition->variable_amount)): ?>
								<div class="small text-muted font-italic"><?php echo e($__t('Variable amount')); ?></div>
								<?php endif; ?>
							</td>
							<td class="fit-content">
								<a class="btn btn-sm btn-info recipe-pos-show-note-button <?php if(empty($recipePosition->note)): ?> disabled <?php endif; ?>"
									href="#"
									data-toggle="tooltip"
									data-placement="top"
									title="<?php echo e($__t('Show notes')); ?>"
									data-recipe-pos-note="<?php echo e($recipePosition->note); ?>">
									<i class="fa-solid fa-eye"></i>
								</a>
							</td>
							<td>
								<?php echo e($recipePosition->ingredient_group); ?>

							</td>
						</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row mt-5">
			<div class="col">
				<div class="title-related-links">
					<h4>
						<?php echo e($__t('Included recipes')); ?>

					</h4>
					<button class="btn btn-outline-dark d-md-none mt-2 float-right order-1 order-md-3"
						type="button"
						data-toggle="collapse"
						data-target="#related-links">
						<i class="fa-solid fa-ellipsis-v"></i>
					</button>
					<div class="related-links collapse d-md-flex order-2 width-xs-sm-100"
						id="related-links">
						<a id="recipe-include-add-button"
							class="btn btn-outline-primary btn-sm m-1 mt-md-0 mb-md-0 float-right"
							href="#">
							<?php echo e($__t('Add')); ?>

						</a>
					</div>
				</div>
				<table id="recipes-includes-table"
					class="table table-sm table-striped nowrap w-100">
					<thead>
						<tr>
							<th class="border-right"><a class="text-muted change-table-columns-visibility-button"
									data-toggle="tooltip"
									title="<?php echo e($__t('Table options')); ?>"
									data-table-selector="#recipes-includes-table"
									href="#"><i class="fa-solid fa-eye"></i></a>
							</th>
							<th><?php echo e($__t('Recipe')); ?></th>
							<th><?php echo e($__t('Servings')); ?></th>
						</tr>
					</thead>
					<tbody class="d-none">
						<?php if($mode == "edit"): ?>
						<?php $__currentLoopData = $recipeNestings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipeNesting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td class="fit-content border-right">
								<a class="btn btn-sm btn-info recipe-include-edit-button"
									href="#"
									data-recipe-include-id="<?php echo e($recipeNesting->id); ?>"
									data-recipe-included-recipe-id="<?php echo e($recipeNesting->includes_recipe_id); ?>"
									data-recipe-included-recipe-servings="<?php echo e($recipeNesting->servings); ?>">
									<i class="fa-solid fa-edit"></i>
								</a>
								<a class="btn btn-sm btn-danger recipe-include-delete-button"
									href="#"
									data-recipe-include-id="<?php echo e($recipeNesting->id); ?>"
									data-recipe-include-name="<?php echo e(FindObjectInArrayByPropertyValue($recipes, 'id', $recipeNesting->includes_recipe_id)->name); ?>">
									<i class="fa-solid fa-trash"></i>
								</a>
							</td>
							<td>
								<?php echo e(FindObjectInArrayByPropertyValue($recipes, 'id', $recipeNesting->includes_recipe_id)->name); ?>

							</td>
							<td>
								<?php echo e($recipeNesting->servings); ?>

							</td>
						</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row mt-5">
			<div class="col">
				<div class="title-related-links">
					<h4>
						<?php echo e($__t('Picture')); ?>

					</h4>
					<div class="form-group w-75 m-0">
						<div class="input-group">
							<div class="custom-file">
								<input type="file"
									class="custom-file-input"
									id="recipe-picture"
									accept="image/*">
								<label id="recipe-picture-label"
									class="custom-file-label <?php if(empty($recipe->picture_file_name)): ?> d-none <?php endif; ?>"
									for="recipe-picture">
									<?php echo e($recipe->picture_file_name); ?>

								</label>
								<label id="recipe-picture-label-none"
									class="custom-file-label <?php if(!empty($recipe->picture_file_name)): ?> d-none <?php endif; ?>"
									for="recipe-picture">
									<?php echo e($__t('No file selected')); ?>

								</label>
							</div>
							<div class="input-group-append">
								<span class="input-group-text"><i class="fa-solid fa-trash"
										id="delete-current-recipe-picture-button"></i></span>
							</div>
						</div>
					</div>
				</div>
				<?php if(!empty($recipe->picture_file_name)): ?>
				<img id="current-recipe-picture"
					src="<?php echo e($U('/api/files/recipepictures/' . base64_encode($recipe->picture_file_name) . '?force_serve_as=picture&best_fit_width=400')); ?>"
					class="img-fluid img-thumbnail mt-2 mb-5"
					loading="lazy">
				<p id="delete-current-recipe-picture-on-save-hint"
					class="form-text text-muted font-italic d-none mb-5"><?php echo e($__t('The current picture will be deleted on save')); ?></p>
				<?php else: ?>
				<p id="no-current-recipe-picture-hint"
					class="form-text text-muted font-italic mb-5"><?php echo e($__t('No picture available')); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<div class="title-related-links">
					<h4>
						<span class="ls-n1"><?php echo e($__t('Grocycode')); ?></span>
						<i class="fa-solid fa-question-circle text-muted"
							data-toggle="tooltip"
							data-trigger="hover click"
							title="<?php echo e($__t('Grocycode is a unique referer to this %s in your Grocy instance - print it onto a label and scan it like any other barcode', $__t('Recipe'))); ?>"></i>
					</h4>
					<p>
						<?php if($mode == 'edit'): ?>
						<img src="<?php echo e($U('/recipe/' . $recipe->id . '/grocycode?size=60')); ?>"
							class="float-lg-left"
							loading="lazy">
						<?php endif; ?>
					</p>
					<p>
						<a class="btn btn-outline-primary btn-sm"
							href="<?php echo e($U('/recipe/' . $recipe->id . '/grocycode?download=true')); ?>"><?php echo e($__t('Download')); ?></a>
						<?php if(GROCY_FEATURE_FLAG_LABEL_PRINTER): ?>
						<a class="btn btn-outline-primary btn-sm recipe-grocycode-label-print"
							data-recipe-id="<?php echo e($recipe->id); ?>"
							href="#">
							<?php echo e($__t('Print on label printer')); ?>

						</a>
						<?php endif; ?>
					</p>
				</div>
			</div>
		</div>
	</div>

</div>

<div class="modal fade"
	id="recipe-include-editform-modal"
	tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-header">
				<h4 id="recipe-include-editform-title"
					class="modal-title w-100"></h4>
			</div>
			<div class="modal-body">
				<form id="recipe-include-form"
					novalidate>

					<?php echo $__env->make('components.recipepicker', array(
					'recipes' => $recipes,
					'isRequired' => true
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

					<?php echo $__env->make('components.numberpicker', array(
					'id' => 'includes_servings',
					'label' => 'Servings',
					'min' => $DEFAULT_MIN_AMOUNT,
					'decimals' => $userSettings['stock_decimal_places_amounts'],
					'value' => '1',
					'additionalCssClasses' => 'locale-number-input locale-number-quantity-amount'
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

				</form>
			</div>
			<div class="modal-footer">
				<button type="button"
					class="btn btn-secondary"
					data-dismiss="modal"><?php echo e($__t('Cancel')); ?></button>
				<button id="save-recipe-include-button"
					class="btn btn-success"><?php echo e($__t('Save')); ?></button>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/recipeform.blade.php ENDPATH**/ ?>