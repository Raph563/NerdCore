<?php require_frontend_packages(['datatables']); ?>



<?php $__env->startSection('title', $__t('Recipes')); ?>

<?php $__env->startPush('pageStyles'); ?>
<style>
	.card-img-top {
		max-height: 250px !important;
		object-fit: cover !important;
	}

	@media (min-width: 576px) {
		.card-columns {
			column-count: 1;
		}
	}

	@media (min-width: 768px) {
		.card-columns {
			column-count: 2;
		}
	}

	@media (min-width: 1200px) {
		.card-columns {
			column-count: 2;
		}
	}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<script>
	Grocy.QuantityUnits = <?php echo json_encode($quantityUnits); ?>;
	Grocy.QuantityUnitConversionsResolved = <?php echo json_encode($quantityUnitConversionsResolved); ?>;
</script>

<div class="row">
	<div class="<?php if(boolval($userSettings['recipes_show_list_side_by_side']) || $embedded): ?> col-12 col-md-6 <?php else: ?> col <?php endif; ?> d-print-none">
		<div class="title-related-links border-bottom mb-2 py-1">
			<h2 class="title"><?php echo $__env->yieldContent('title'); ?></h2>
			<div class="float-right <?php if($embedded): ?> pr-5 <?php endif; ?>">
				<button class="btn btn-outline-dark d-md-none mt-2 order-1 order-md-3"
					type="button"
					data-toggle="collapse"
					data-target="#table-filter-row">
					<i class="fa-solid fa-filter"></i>
				</button>
				<button class="btn btn-outline-dark d-md-none mt-2 order-1 order-md-3"
					type="button"
					data-toggle="collapse"
					data-target="#related-links">
					<i class="fa-solid fa-ellipsis-v"></i>
				</button>
			</div>
			<div class="related-links collapse d-md-flex order-2 width-xs-sm-100"
				id="related-links">
				<a class="btn btn-primary responsive-button m-1 mt-md-0 mb-md-0 float-right"
					href="<?php echo e($U('/recipe/new')); ?>">
					<?php echo e($__t('Add')); ?>

				</a>
			</div>
		</div>

		<div class="row collapse d-md-flex"
			id="table-filter-row">
			<div class="col-12 col-md-5">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa-solid fa-search"></i></span>
					</div>
					<input type="text"
						id="search"
						class="form-control"
						placeholder="<?php echo e($__t('Search')); ?>">
				</div>
			</div>

			<div class="col-12 col-md-5">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa-solid fa-filter"></i>&nbsp;<?php echo e($__t('Status')); ?></span>
					</div>
					<select class="custom-control custom-select"
						id="status-filter">
						<option value="all"><?php echo e($__t('All')); ?></option>
						<option value="Xenoughinstock"><?php echo e($__t('Enough in stock')); ?></option>
						<option value="enoughinstockwithshoppinglist"><?php echo e($__t('Not enough in stock, but already on the shopping list')); ?></option>
						<option value="notenoughinstock"><?php echo e($__t('Not enough in stock')); ?></option>
					</select>
				</div>
			</div>

			<div class="col">
				<div class="float-right mt-1">
					<button id="clear-filter-button"
						class="btn btn-sm btn-outline-info"
						data-toggle="tooltip"
						title="<?php echo e($__t('Clear filter')); ?>">
						<i class="fa-solid fa-filter-circle-xmark"></i>
					</button>
				</div>
			</div>
		</div>

		<ul class="nav nav-tabs grocy-tabs">
			<li class="nav-item">
				<a class="nav-link active"
					id="list-tab"
					data-toggle="tab"
					href="#list"><?php echo e($__t('List')); ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link"
					id="gallery-tab"
					data-toggle="tab"
					href="#gallery"><?php echo e($__t('Gallery')); ?></a>
			</li>
		</ul>

		<div class="tab-content grocy-tabs">
			<div class="tab-pane show active"
				id="list">
				<table id="recipes-table"
					class="table table-sm table-striped nowrap w-100">
					<thead>
						<tr>
							<th class="border-right"><a class="text-muted change-table-columns-visibility-button"
									data-toggle="tooltip"
									title="<?php echo e($__t('Table options')); ?>"
									data-table-selector="#recipes-table"
									href="#"><i class="fa-solid fa-eye"></i></a>
							</th>
							<th><?php echo e($__t('Name')); ?></th>
							<th class="allow-grouping"><?php echo e($__t('Desired servings')); ?></th>
							<th class="allow-grouping">
								<?php echo e($__t('Due score')); ?>

								<i class="fa-solid fa-question-circle text-muted small"
									data-toggle="tooltip"
									data-trigger="hover click"
									title="<?php echo e($__t('The higher this number is, the more ingredients currently in stock are due soon, overdue or already expired')); ?>"></i>
							</th>
							<th data-shadow-rowgroup-column="8"
								class="<?php if(!GROCY_FEATURE_FLAG_STOCK): ?> d-none <?php endif; ?> allow-grouping"><?php echo e($__t('Requirements fulfilled')); ?></th>
							<th class="d-none">Hidden status for sorting of "Requirements fulfilled" column</th>
							<th class="d-none">Hidden status for filtering by status</th>
							<th class="d-none">Hidden recipe ingredient product names</th>
							<th class="d-none">Hidden status for grouping by status</th>

							<?php echo $__env->make('components.userfields_thead', array(
							'userfields' => $userfields
							), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

						</tr>
					</thead>
					<tbody class="d-none">
						<?php $__currentLoopData = $recipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr id="recipe-row-<?php echo e($recipe->id); ?>"
							data-recipe-id="<?php echo e($recipe->id); ?>">
							<td class="fit-content border-right">
								<a class="btn btn-info btn-sm hide-when-embedded hide-on-fullscreen-card recipe-edit-button"
									href="<?php echo e($U('/recipe/')); ?><?php echo e($recipe->id); ?>"
									data-toggle="tooltip"
									title="<?php echo e($__t('Edit this item')); ?>">
									<i class="fa-solid fa-edit"></i>
								</a>
								<div class="dropdown d-inline-block">
									<button class="btn btn-sm btn-light text-secondary"
										type="button"
										data-toggle="dropdown">
										<i class="fa-solid fa-ellipsis-v"></i>
									</button>
									<div class="table-inline-menu dropdown-menu dropdown-menu-right hide-on-fullscreen-card hide-when-embedded">
										<a class="dropdown-item add-to-mealplan-button"
											type="button"
											href="#"
											data-recipe-id="<?php echo e($recipe->id); ?>">
											<span class="dropdown-item-text"><?php echo e($__t('Add to meal plan')); ?></span>
										</a>
										<a class="dropdown-item recipe-delete"
											type="button"
											href="#"
											data-recipe-id="<?php echo e($recipe->id); ?>"
											data-recipe-name="<?php echo e($recipe->name); ?>">
											<span class="dropdown-item-text"><?php echo e($__t('Delete this item')); ?></span>
										</a>
										<a class="dropdown-item recipe-copy"
											type="button"
											href="#"
											data-recipe-id="<?php echo e($recipe->id); ?>">
											<span class="dropdown-item-text"><?php echo e($__t('Copy recipe')); ?></span>
										</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item"
											type="button"
											href="<?php echo e($U('/recipe/' . $recipe->id . '/grocycode?download=true')); ?>">
											<span class="dropdown-item-text"><?php echo str_replace('Grocycode', '<span class="ls-n1">Grocycode</span>', $__t('Download %s Grocycode', $__t('Recipe'))); ?></span>
										</a>
										<?php if(GROCY_FEATURE_FLAG_LABEL_PRINTER): ?>
										<a class="dropdown-item recipe-grocycode-label-print"
											data-recipe-id="<?php echo e($recipe->id); ?>"
											type="button"
											href="#">
											<span class="dropdown-item-text"><?php echo str_replace('Grocycode', '<span class="ls-n1">Grocycode</span>', $__t('Print %s Grocycode on label printer', $__t('Recipe'))); ?></span>
										</a>
										<?php endif; ?>
									</div>
								</div>
							</td>
							<td>
								<?php echo e($recipe->name); ?>

							</td>
							<td>
								<?php echo e($recipe->desired_servings); ?>

							</td>
							<td>
								<?php echo e(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->due_score); ?>

							</td>
							<td class="<?php if(!GROCY_FEATURE_FLAG_STOCK): ?> d-none <?php endif; ?>">
								<?php if(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->need_fulfilled == 1): ?><i class="fa-solid fa-check text-success"></i><?php elseif(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->need_fulfilled_with_shopping_list == 1): ?><i class="fa-solid fa-exclamation text-warning"></i><?php else: ?><i class="fa-solid fa-times text-danger"></i><?php endif; ?>
								<span class="timeago-contextual"><?php if(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->need_fulfilled == 1): ?><?php echo e($__t('Enough in stock')); ?><?php elseif(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->need_fulfilled_with_shopping_list == 1): ?><?php echo e($__n(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->missing_products_count, 'Not enough in stock, %s ingredient missing but already on the shopping list', 'Not enough in stock, %s ingredients missing but already on the shopping list')); ?><?php else: ?><?php echo e($__n(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->missing_products_count, 'Not enough in stock, %s ingredient missing', 'Not enough in stock, %s ingredients missing')); ?><?php endif; ?></span>
							</td>
							<td class="d-none">
								<?php echo e(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->missing_products_count); ?>

							</td>
							<td class="d-none">
								<?php if(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->need_fulfilled == 1): ?> Xenoughinstock <?php elseif(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->need_fulfilled_with_shopping_list == 1): ?> enoughinstockwithshoppinglist <?php else: ?> notenoughinstock <?php endif; ?>
							</td>
							<td class="d-none">
								<?php echo e(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->product_names_comma_separated); ?>

							</td>
							<td class="d-none">
								<?php if(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->need_fulfilled == 1): ?> <?php echo e($__t('Enough in stock')); ?> <?php elseif(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->need_fulfilled_with_shopping_list == 1): ?> <?php echo e($__t('Not enough in stock, but already on the shopping list')); ?> <?php else: ?> <?php echo e($__t('Not enough in stock')); ?> <?php endif; ?>
							</td>

							<?php echo $__env->make('components.userfields_tbody', array(
							'userfields' => $userfields,
							'userfieldValues' => FindAllObjectsInArrayByPropertyValue($userfieldValues, 'object_id', $recipe->id)
							), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

						</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</tbody>
				</table>
			</div>

			<div class="tab-pane show"
				id="gallery">
				<div class="card-columns no-gutters mt-1">
					<?php $__currentLoopData = $recipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<div class="cursor-link recipe-gallery-item <?php if(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->need_fulfilled == 1): ?> recipe-enoughinstock <?php elseif(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->need_fulfilled_with_shopping_list == 1): ?> recipe-enoughinstockwithshoppinglist <?php else: ?> recipe-notenoughinstock <?php endif; ?>"
						data-recipe-id="<?php echo e($recipe->id); ?>"
						href="#">
						<div id="RecipeGalleryCard-<?php echo e($recipe->id); ?>"
							class="card recipe-card">
							<?php if(!empty($recipe->picture_file_name)): ?>
							<img src="<?php echo e($U('/api/files/recipepictures/' . base64_encode($recipe->picture_file_name) . '?force_serve_as=picture&best_fit_width=400')); ?>"
								class="card-img-top"
								loading="lazy">
							<?php endif; ?>
							<div class="card-body text-center">
								<h5 class="card-title mb-1"><?php echo e($recipe->name); ?></h5>
								<span class="card-title-search d-none">
									<?php echo e($recipe->name); ?>

									<?php echo e(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->product_names_comma_separated); ?>

								</span>
								<p class="card-text">
									<?php if(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->need_fulfilled == 1): ?><i class="fa-solid fa-check text-success"></i><?php elseif(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->need_fulfilled_with_shopping_list == 1): ?><i class="fa-solid fa-exclamation text-warning"></i><?php else: ?><i class="fa-solid fa-times text-danger"></i><?php endif; ?>
									<span class="timeago-contextual"><?php if(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->need_fulfilled == 1): ?><?php echo e($__t('Enough in stock')); ?><?php elseif(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->need_fulfilled_with_shopping_list == 1): ?><?php echo e($__n(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->missing_products_count, 'Not enough in stock, %s ingredient missing but already on the shopping list', 'Not enough in stock, %s ingredients missing but already on the shopping list')); ?><?php else: ?><?php echo e($__n(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->missing_products_count, 'Not enough in stock, %s ingredient missing', 'Not enough in stock, %s ingredients missing')); ?><?php endif; ?></span>
								</p>
								<p class="card-text mt-2">
									<a class="btn btn-xs btn-outline-danger hide-when-embedded hide-on-fullscreen-card recipe-delete"
										href="#"
										data-recipe-id="<?php echo e($recipe->id); ?>"
										data-recipe-name="<?php echo e($recipe->name); ?>"
										data-toggle="tooltip"
										title="<?php echo e($__t('Delete this item')); ?>">
										<i class="fa-solid fa-trash"></i>
									</a>
									<a class="btn btn-outline-info btn-xs hide-when-embedded hide-on-fullscreen-card recipe-edit-button"
										href="<?php echo e($U('/recipe/')); ?><?php echo e($recipe->id); ?>"
										data-toggle="tooltip"
										title="<?php echo e($__t('Edit this item')); ?>">
										<i class="fa-solid fa-edit"></i>
									</a>
								</p>
							</div>
						</div>
					</div>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</div>
			</div>
		</div>
	</div>

	<?php if($selectedRecipe !== null && (boolval($userSettings['recipes_show_list_side_by_side']) || $embedded)): ?>
	<?php
	$allRecipes = $selectedRecipeSubRecipes;
	array_unshift($allRecipes, $selectedRecipe);
	?>
	<div class="col-12 col-md-6 print-view">
		<div id="selectedRecipeCard"
			class="card grocy-card">
			<?php if(count($allRecipes) > 1): ?>
			<div class="card-header card-header-fullscreen mb-1 pt-0 d-print-none">
				<ul class="nav nav-tabs grocy-tabs card-header-tabs">
					<?php $__currentLoopData = $allRecipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index=>$recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<li class="nav-item">
						<a class="nav-link <?php if($index == 0): ?> active <?php endif; ?>"
							data-toggle="tab"
							href="#recipe-<?php echo e($index + 1); ?>"><?php echo e($recipe->name); ?></a>
					</li>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</ul>
			</div>
			<?php endif; ?>

			<div class="tab-content grocy-tabs print break">
				<?php $__currentLoopData = $allRecipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index=>$recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<div class="tab-pane <?php if($index == 0): ?> active <?php endif; ?>"
					id="recipe-<?php echo e($index + 1); ?>"
					role="tabpanel">
					<?php if(!empty($recipe->picture_file_name)): ?>
					<img class="card-img-top"
						src="<?php echo e($U('/api/files/recipepictures/' . base64_encode($recipe->picture_file_name) . '?force_serve_as=picture')); ?>"
						loading="lazy">
					<?php endif; ?>
					<div class="card-body">
						<div class="shadow p-4 mb-5 bg-white rounded mt-n5 d-print-none <?php if(empty($recipe->picture_file_name)): ?> d-none <?php endif; ?>">
							<div class="d-flex justify-content-between align-items-center">
								<h3 class="card-title mb-0"><?php echo e($recipe->name); ?></h3>
								<div class="card-icons d-flex flex-wrap justify-content-end flex-shrink-1">
									<a class="btn <?php if(!GROCY_FEATURE_FLAG_STOCK): ?> d-none <?php endif; ?> recipe-consume"
										href="#"
										data-toggle="tooltip"
										title="<?php echo e($__t('Consume all ingredients needed by this recipe')); ?>"
										data-recipe-id="<?php echo e($recipe->id); ?>"
										data-recipe-name="<?php echo e($recipe->name); ?>">
										<i class="fa-solid fa-utensils"></i>
									</a>
									<a class="btn <?php if(!GROCY_FEATURE_FLAG_SHOPPINGLIST): ?> d-none <?php endif; ?> recipe-shopping-list <?php if(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->need_fulfilled_with_shopping_list == 1): ?> disabled <?php endif; ?>"
										href="#"
										data-toggle="tooltip"
										title="<?php echo e($__t('Put missing products on shopping list')); ?>"
										data-recipe-id="<?php echo e($recipe->id); ?>"
										data-recipe-name="<?php echo e($recipe->name); ?>">
										<i class="fa-solid fa-cart-plus"></i>
									</a>
									<a class="btn recipe-fullscreen hide-when-embedded"
										id="selectedRecipeToggleFullscreenButton"
										href="#"
										data-toggle="tooltip"
										title="<?php echo e($__t('Expand to fullscreen')); ?>">
										<i class="fa-solid fa-expand-arrows-alt"></i>
									</a>
									<a class="btn recipe-print"
										href="#"
										data-toggle="tooltip"
										title="<?php echo e($__t('Print')); ?>">
										<i class="fa-solid fa-print"></i>
									</a>
								</div>
							</div>
						</div>

						<div class="mb-4 <?php if(!empty($recipe->picture_file_name)): ?> d-none <?php else: ?> d-flex <?php endif; ?> d-print-block justify-content-between align-items-center">
							<h1 class="card-title mb-0"><?php echo e($recipe->name); ?></h1>
							<div class="card-icons d-flex flex-wrap justify-content-end flex-shrink-1 d-print-none">
								<a class="btn recipe-consume"
									href="#"
									data-toggle="tooltip"
									title="<?php echo e($__t('Consume all ingredients needed by this recipe')); ?>"
									data-recipe-id="<?php echo e($recipe->id); ?>"
									data-recipe-name="<?php echo e($recipe->name); ?>">
									<i class="fa-solid fa-utensils"></i>
								</a>
								<a class="btn recipe-shopping-list <?php if(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->need_fulfilled_with_shopping_list == 1): ?> disabled <?php endif; ?>"
									href="#"
									data-toggle="tooltip"
									title="<?php echo e($__t('Put missing products on shopping list')); ?>"
									data-recipe-id="<?php echo e($recipe->id); ?>"
									data-recipe-name="<?php echo e($recipe->name); ?>">
									<i class="fa-solid fa-cart-plus"></i>
								</a>
								<a class=" btn recipe-fullscreen hide-when-embedded"
									href="#"
									data-toggle="tooltip"
									title="<?php echo e($__t('Expand to fullscreen')); ?>">
									<i class="fa-solid fa-expand-arrows-alt"></i>
								</a>
								<a class="btn recipe-print PrintRecipe"
									href="#"
									data-toggle="tooltip"
									title="<?php echo e($__t('Print')); ?>">
									<i class="fa-solid fa-print"></i>
								</a>
							</div>
						</div>

						<?php
						$calories = FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->calories;
						$costs = FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->costs;
						?>

						<div class="row ml-1">
							<?php if(!empty($calories) && $calories > 0): ?>
							<div class="col-4">
								<label><?php echo e(GROCY_ENERGY_UNIT); ?></label>&nbsp;
								<i class="fa-solid fa-question-circle text-muted d-print-none"
									data-toggle="tooltip"
									data-trigger="hover click"
									title="<?php echo e($__t('per serving')); ?>"></i>
								<h3 class="locale-number locale-number-generic pt-0"><?php echo e($calories); ?></h3>
							</div>
							<?php endif; ?>
							<?php if(GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?>
							<div class="col-4">
								<label><?php echo e($__t('Costs')); ?>&nbsp;
									<i class="fa-solid fa-question-circle text-muted d-print-none"
										data-toggle="tooltip"
										data-trigger="hover click"
										title="<?php echo e($__t('Based on the prices of the default consume rule (Opened first, then first due first, then first in first out) for in stock ingredients and on the last price for missing ones')); ?>"></i>
								</label>
								<h3>
									<span class="locale-number locale-number-currency pt-0"><?php echo e($costs); ?></span>
									<?php if(FindObjectInArrayByPropertyValue($recipesResolved, 'recipe_id', $recipe->id)->prices_incomplete): ?>
									<i class="small fa-solid fa-exclamation text-danger"
										data-toggle="tooltip"
										data-trigger="hover click"
										title="<?php echo e($__t('No price information is available for at least one ingredient')); ?>"></i>
									<?php endif; ?>
								</h3>
							</div>
							<?php endif; ?>

							<?php if($index == 0): ?>
							<div class="col-4 d-print-none">
								<?php echo $__env->make('components.numberpicker', array(
								'id' => 'servings-scale',
								'label' => 'Desired servings',
								'min' => $DEFAULT_MIN_AMOUNT,
								'decimals' => $userSettings['stock_decimal_places_amounts'],
								'value' => $recipe->desired_servings,
								'additionalAttributes' => 'data-recipe-id="' . $recipe->id . '"',
								'additionalCssClasses' => 'locale-number-input locale-number-quantity-amount'
								), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
							</div>
							<?php endif; ?>
						</div>

						<?php
						$recipePositionsFiltered = FindAllObjectsInArrayByPropertyValue($allRecipePositions[$recipe->id], 'recipe_id', $recipe->id);
						?>

						<ul class="nav nav-tabs grocy-tabs mb-3 d-print-none hide-on-fullscreen-card"
							role="tablist">
							<?php if(count($recipePositionsFiltered) > 0): ?>
							<li class="nav-item">
								<a class="nav-link active"
									data-toggle="tab"
									href="#ingredients-<?php echo e($index); ?>"
									role="tab"><?php echo e($__t('Ingredients')); ?></a>
							</li>
							<?php endif; ?>
							<?php if(!empty($recipe->description)): ?>
							<li class="nav-item">
								<a class="nav-link <?php if(count($recipePositionsFiltered) == 0): ?> active <?php endif; ?>"
									data-toggle="tab"
									href="#prep-<?php echo e($index); ?>"
									role="tab"><?php echo e($__t('Preparation')); ?></a>
							</li>
							<?php endif; ?>
						</ul>

						<div class="tab-content grocy-tabs p-2 print recipe-content-container">
							<?php if(count($recipePositionsFiltered) > 0): ?>
							<div class="tab-pane active ingredients"
								id="ingredients-<?php echo e($index); ?>"
								role="tabpanel">
								<div class="mb-2 d-none d-print-block recipe-headline">
									<h3 class="mb-0"><?php echo e($__t('Ingredients')); ?></h3>
								</div>
								<ul class="list-group list-group-flush mb-5">
									<?php
									$lastIngredientGroup = 'undefined';
									$lastProductGroup = 'undefined';
									$hasIngredientGroups = false;
									$hasProductGroups = false;
									?>
									<?php $__currentLoopData = $recipePositionsFiltered; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selectedRecipePosition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<?php if($lastIngredientGroup != $selectedRecipePosition->ingredient_group && !empty($selectedRecipePosition->ingredient_group)): ?>
									<?php $hasIngredientGroups = true; ?>
									<h5 class="mb-2 mt-2 ml-1"><strong><?php echo e($selectedRecipePosition->ingredient_group); ?></strong></h5>
									<?php endif; ?>
									<?php if(boolval($userSettings['recipe_ingredients_group_by_product_group']) && $lastProductGroup != $selectedRecipePosition->product_group && !empty($selectedRecipePosition->product_group)): ?>
									<?php $hasProductGroups = true; ?>
									<h6 class="mb-2 mt-2 <?php if($hasIngredientGroups): ?> ml-3 <?php else: ?> ml-1 <?php endif; ?>"><strong><?php echo e($selectedRecipePosition->product_group); ?></strong></h6>
									<?php endif; ?>
									<li class="list-group-item px-0 <?php if($hasIngredientGroups && $hasProductGroups): ?> ml-4 <?php elseif($hasIngredientGroups || $hasProductGroups): ?> ml-2 <?php else: ?> ml-0 <?php endif; ?>">
										<?php if($selectedRecipePosition->product_active == 0): ?>
										<div class="small text-muted font-italic"><?php echo e($__t('Disabled')); ?></div>
										<?php endif; ?>
										<?php if($userSettings['recipes_show_ingredient_checkbox']): ?>
										<a class="btn btn-light btn-sm ingredient-done-button"
											href="#"
											data-toggle="tooltip"
											data-placement="right"
											title="<?php echo e($__t('Mark this item as done')); ?>">
											<i class="fa-solid fa-check-circle text-primary"></i>
										</a>
										<?php endif; ?>
										<?php
										$product = FindObjectInArrayByPropertyValue($products, 'id', $selectedRecipePosition->product_id);
										$productQuConversions = FindAllObjectsInArrayByPropertyValue($quantityUnitConversionsResolved, 'product_id', $product->id);
										$productQuConversions = FindAllObjectsInArrayByPropertyValue($productQuConversions, 'from_qu_id', $product->qu_id_stock);
										$productQuConversion = FindObjectInArrayByPropertyValue($productQuConversions, 'to_qu_id', $selectedRecipePosition->qu_id);
										if ($productQuConversion && $selectedRecipePosition->only_check_single_unit_in_stock == 0)
										{
										$selectedRecipePosition->recipe_amount = $selectedRecipePosition->recipe_amount * $productQuConversion->factor;
										}
										?>
										<span class="productcard-trigger cursor-link <?php if($selectedRecipePosition->due_score == 20): ?> text-danger <?php elseif($selectedRecipePosition->due_score == 10): ?> text-secondary <?php elseif($selectedRecipePosition->due_score == 1): ?> text-warning <?php endif; ?>"
											data-product-id="<?php echo e($selectedRecipePosition->product_id); ?>">
											<?php if(!empty($selectedRecipePosition->recipe_variable_amount)): ?>
											<?php echo e($selectedRecipePosition->recipe_variable_amount); ?>

											<?php else: ?>
											<span class="locale-number locale-number-quantity-amount"><?php if($selectedRecipePosition->recipe_amount == round($selectedRecipePosition->recipe_amount, 2)): ?><?php echo e(round($selectedRecipePosition->recipe_amount, 2)); ?><?php else: ?><?php echo e($selectedRecipePosition->recipe_amount); ?><?php endif; ?></span>
											<?php echo e($__n($selectedRecipePosition->recipe_amount, FindObjectInArrayByPropertyValue($quantityUnits, 'id', $selectedRecipePosition->qu_id)->name, FindObjectInArrayByPropertyValue($quantityUnits, 'id', $selectedRecipePosition->qu_id)->name_plural)); ?>

											<?php endif; ?>
											<?php echo e(FindObjectInArrayByPropertyValue($products, 'id', $selectedRecipePosition->product_id)->name); ?>

										</span>
										<?php if(GROCY_FEATURE_FLAG_STOCK): ?>
										<span class="
												d-print-none">
											<?php if(FindObjectInArrayByPropertyValue($recipePositionsResolved, 'recipe_pos_id', $selectedRecipePosition->id)->need_fulfilled == 1): ?><i class="fa-solid fa-check text-success"></i><?php elseif(FindObjectInArrayByPropertyValue($recipePositionsResolved, 'recipe_pos_id', $selectedRecipePosition->id)->need_fulfilled_with_shopping_list == 1): ?><i class="fa-solid fa-exclamation text-warning"></i><?php else: ?><i class="fa-solid fa-times text-danger"></i><?php endif; ?>
											<span class="timeago-contextual"><?php if(FindObjectInArrayByPropertyValue($recipePositionsResolved, 'recipe_pos_id', $selectedRecipePosition->id)->need_fulfilled == 1): ?> <?php echo e($__t('Enough in stock')); ?> (<span class="locale-number locale-number-quantity-amount"><?php echo e($selectedRecipePosition->stock_amount); ?></span> <?php echo e($__n($selectedRecipePosition->stock_amount, FindObjectInArrayByPropertyValue($quantityUnits, 'id', $product->qu_id_stock)->name, FindObjectInArrayByPropertyValue($quantityUnits, 'id', $product->qu_id_stock)->name_plural)); ?>) <?php else: ?> <?php echo e($__t('Not enough in stock, %1$s missing, %2$s already on shopping list', round($selectedRecipePosition->missing_amount, 2), round($selectedRecipePosition->amount_on_shopping_list, 2))); ?> <?php endif; ?></span>
										</span>
										<?php endif; ?>
										<?php if($selectedRecipePosition->product_id != $selectedRecipePosition->product_id_effective): ?>
										<br class="d-print-none">
										<span class="productcard-trigger cursor-link text-muted d-print-none"
											data-product-id="<?php echo e($selectedRecipePosition->product_id_effective); ?>"
											data-toggle="tooltip"
											data-trigger="hover click"
											title="<?php echo e($__t('The parent product %1$s is currently not in stock, %2$s is the current next sub product based on the default consume rule (Opened first, then first due first, then first in first out)', FindObjectInArrayByPropertyValue($products, 'id', $selectedRecipePosition->product_id)->name, FindObjectInArrayByPropertyValue($products, 'id', $selectedRecipePosition->product_id_effective)->name)); ?>">
											<i class="fa-solid fa-exchange-alt"></i> <?php echo e(FindObjectInArrayByPropertyValue($products, 'id', $selectedRecipePosition->product_id_effective)->name); ?>

										</span>
										<?php endif; ?>
										<?php if(GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?> <span class="float-right font-italic ml-2 locale-number locale-number-currency"><?php echo e($selectedRecipePosition->costs); ?></span> <?php endif; ?>
										<span class="float-right font-italic"><span class="locale-number locale-number-generic"><?php echo e($selectedRecipePosition->calories); ?></span> <?php echo e($__t('Calories')); ?></span>
										<?php if(!empty($selectedRecipePosition->recipe_variable_amount)): ?>
										<div class="small text-muted font-italic"><?php echo e($__t('Variable amount')); ?></div>
										<?php endif; ?>

										<?php if(!empty($selectedRecipePosition->note)): ?>
										<div class="text-muted"><?php echo nl2br($selectedRecipePosition->note ?? ''); ?></div>
										<?php endif; ?>
									</li>
									<?php $lastProductGroup = $selectedRecipePosition->product_group; ?>
									<?php $lastIngredientGroup = $selectedRecipePosition->ingredient_group; ?>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</ul>
							</div>
							<?php endif; ?>
							<div class="tab-pane <?php if(count($recipePositionsFiltered) == 0): ?> active <?php endif; ?> preparation"
								id="prep-<?php echo e($index); ?>"
								role="tabpanel">
								<div class="mb-2 d-none d-print-block recipe-headline">
									<h3 class="mb-0"><?php echo e($__t('Preparation')); ?></h3>
								</div>
								<?php if(!empty($recipe->description)): ?>
								<?php echo $recipe->description; ?>

								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>

				<div id="missing-recipe-pos-list"
					class="list-group d-none mt-3">
					<?php $__currentLoopData = $recipePositionsResolved; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipePos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php if(in_array($recipePos->recipe_id, $includedRecipeIdsAbsolute) && $recipePos->missing_amount > 0): ?>
					<a href="#"
						class="list-group-item list-group-item-action list-group-item-primary missing-recipe-pos-select-button">
						<div class="form-check form-check-inline">
							<input class="form-check-input missing-recipe-pos-product-checkbox"
								type="checkbox"
								data-product-id="<?php echo e($recipePos->product_id); ?>"
								checked>
						</div>
						<?php echo e(FindObjectInArrayByPropertyValue($products, 'id', $recipePos->product_id)->name); ?>

					</a>
					<?php endif; ?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</div>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>

<div class="modal fade"
	id="add-to-mealplan-modal"
	tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title w-100">
					<span><?php echo e($__t('Add meal plan entry')); ?></span>
					<span class="text-muted float-right"><?php echo e($__t('Recipe')); ?></span>
				</h4>
			</div>
			<div class="modal-body">
				<form id="add-to-mealplan-form"
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
						<label for="section_id"><?php echo e($__t('Section')); ?></label>
						<select class="custom-control custom-select"
							id="section_id"
							name="section_id"
							required>
							<?php $__currentLoopData = $mealplanSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mealplanSection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($mealplanSection->id); ?>"><?php echo e($mealplanSection->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>

					<input type="hidden"
						name="type"
						value="recipe">
				</form>
			</div>
			<div class="modal-footer">
				<button type="button"
					class="btn btn-secondary"
					data-dismiss="modal"><?php echo e($__t('Cancel')); ?></button>
				<button id="save-add-to-mealplan-button"
					class="btn btn-success"><?php echo e($__t('Save')); ?></button>
			</div>
		</div>
	</div>
</div>

<?php echo $__env->make('components.productcard', [
'asModal' => true
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/recipes.blade.php ENDPATH**/ ?>