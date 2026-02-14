<?php require_frontend_packages(['datatables', 'animatecss']); ?>



<?php $__env->startSection('title', $__t('Stock entries')); ?>

<?php $__env->startPush('pageScripts'); ?>
<script src="<?php echo e($U('/viewjs/purchase.js?v=', true)); ?><?php echo e($version); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col">
		<h2 class="title"><?php echo $__env->yieldContent('title'); ?></h2>
		<div class="float-right <?php if($embedded): ?> pr-5 <?php endif; ?>">
			<button class="btn btn-outline-dark d-md-none mt-2 order-1 order-md-3"
				type="button"
				data-toggle="collapse"
				data-target="#table-filter-row">
				<i class="fa-solid fa-filter"></i>
			</button>
		</div>
	</div>
</div>

<hr class="my-2">

<div class="row collapse d-md-flex"
	id="table-filter-row">
	<div class="col-12 col-md-6 col-xl-3 hide-when-embedded">
		<?php echo $__env->make('components.productpicker', array(
		'products' => $products,
		'disallowAllProductWorkflows' => true,
		'isRequired' => false,
		'additionalGroupCssClasses' => 'mb-0'
		), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
	</div>
	<?php if(GROCY_FEATURE_FLAG_STOCK_LOCATION_TRACKING): ?>
	<div class="col-12 col-md-6 col-xl-3 mt-auto">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="fa-solid fa-filter"></i>&nbsp;<?php echo e($__t('Location')); ?></span>
			</div>
			<select class="custom-control custom-select"
				id="location-filter">
				<option value="all"><?php echo e($__t('All')); ?></option>
				<?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<option value="<?php echo e($location->id); ?>"><?php echo e($location->name); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
		</div>
	</div>
	<?php endif; ?>
	<div class="col mt-auto">
		<div class="float-right mt-3">
			<button id="clear-filter-button"
				class="btn btn-sm btn-outline-info"
				data-toggle="tooltip"
				title="<?php echo e($__t('Clear filter')); ?>">
				<i class="fa-solid fa-filter-circle-xmark"></i>
			</button>
		</div>
	</div>
</div>

<div class="row">
	<div class="col">
		<table id="stockentries-table"
			class="table table-sm table-striped nowrap w-100">
			<thead>
				<tr>
					<th class="border-right"><a class="text-muted change-table-columns-visibility-button"
							data-toggle="tooltip"
							title="<?php echo e($__t('Table options')); ?>"
							data-table-selector="#stockentries-table"
							href="#"><i class="fa-solid fa-eye"></i></a>
					</th>
					<th class="d-none">Hidden product_id</th>
					<th class="allow-grouping"><?php echo e($__t('Product')); ?></th>
					<th><?php echo e($__t('Amount')); ?></th>
					<th class="<?php if(!GROCY_FEATURE_FLAG_STOCK_BEST_BEFORE_DATE_TRACKING): ?> d-none <?php endif; ?> allow-grouping"><?php echo e($__t('Due date')); ?></th>
					<th class="<?php if(!GROCY_FEATURE_FLAG_STOCK_LOCATION_TRACKING): ?> d-none <?php endif; ?> allow-grouping"><?php echo e($__t('Location')); ?></th>
					<th class="<?php if(!GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?> d-none <?php endif; ?> allow-grouping"><?php echo e($__t('Store')); ?></th>
					<th class="<?php if(!GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?> d-none <?php endif; ?>"><?php echo e($__t('Price')); ?></th>
					<th class="allow-grouping"
						data-shadow-rowgroup-column="9"><?php echo e($__t('Purchased date')); ?></th>
					<th class="d-none">Hidden purchased_date</th>
					<th><?php echo e($__t('Timestamp')); ?></th>
					<th><?php echo e($__t('Note')); ?></th>

					<?php echo $__env->make('components.userfields_thead', array(
					'userfields' => $userfieldsProducts
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

					<?php echo $__env->make('components.userfields_thead', array(
					'userfields' => $userfieldsStock
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
				</tr>
			</thead>
			<tbody class="d-none">
				<?php $__currentLoopData = $stockEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stockEntry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr id="stock-<?php echo e($stockEntry->id); ?>-row"
					data-due-type="<?php echo e(FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->due_type); ?>"
					class="<?php if(GROCY_FEATURE_FLAG_STOCK_BEST_BEFORE_DATE_TRACKING && $stockEntry->best_before_date < date('Y-m-d 23:59:59', strtotime('-1 days')) && $stockEntry->amount > 0): ?> <?php if(FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->due_type == 1): ?> table-secondary <?php else: ?> table-danger <?php endif; ?> <?php elseif(GROCY_FEATURE_FLAG_STOCK_BEST_BEFORE_DATE_TRACKING && $stockEntry->best_before_date < date('Y-m-d 23:59:59', strtotime('+' . $nextXDays . ' days'))
					&&
					$stockEntry->amount > 0): ?> table-warning <?php endif; ?>">
					<td class="fit-content border-right">
						<a class="btn btn-danger btn-sm stock-consume-button"
							href="#"
							data-toggle="tooltip"
							data-placement="left"
							title="<?php echo e($__t('Consume this stock entry')); ?>"
							data-product-id="<?php echo e($stockEntry->product_id); ?>"
							data-stock-id="<?php echo e($stockEntry->stock_id); ?>"
							data-stockrow-id="<?php echo e($stockEntry->id); ?>"
							data-location-id="<?php echo e($stockEntry->location_id); ?>"
							data-product-name="<?php echo e(FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->name); ?>"
							data-product-qu-name="<?php echo e(FindObjectInArrayByPropertyValue($quantityunits, 'id', FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->qu_id_stock)->name); ?>"
							data-consume-amount="<?php echo e($stockEntry->amount); ?>">
							<i class="fa-solid fa-utensils"></i>
						</a>
						<?php if(GROCY_FEATURE_FLAG_STOCK_PRODUCT_OPENED_TRACKING): ?>
						<a class="btn btn-success btn-sm product-open-button <?php if($stockEntry->open == 1 || FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->enable_tare_weight_handling == 1 || FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->disable_open == 1): ?> disabled <?php endif; ?>"
							href="#"
							data-toggle="tooltip"
							data-placement="left"
							title="<?php echo e($__t('Mark this stock entry as open')); ?>"
							data-product-id="<?php echo e($stockEntry->product_id); ?>"
							data-product-name="<?php echo e(FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->name); ?>"
							data-product-qu-name="<?php echo e(FindObjectInArrayByPropertyValue($quantityunits, 'id', FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->qu_id_stock)->name); ?>"
							data-stock-id="<?php echo e($stockEntry->stock_id); ?>"
							data-stockrow-id="<?php echo e($stockEntry->id); ?>"
							data-open-amount="<?php echo e($stockEntry->amount); ?>">
							<i class="fa-solid fa-box-open"></i>
						</a>
						<?php endif; ?>
						<a class="btn btn-info btn-sm show-as-dialog-link"
							href="<?php echo e($U('/stockentry/' . $stockEntry->id . '?embedded')); ?>"
							data-toggle="tooltip"
							data-placement="left"
							title="<?php echo e($__t('Edit stock entry')); ?>">
							<i class="fa-solid fa-edit"></i>
						</a>
						<div class="dropdown d-inline-block">
							<button class="btn btn-sm btn-light text-secondary"
								type="button"
								data-toggle="dropdown">
								<i class="fa-solid fa-ellipsis-v"></i>
							</button>
							<div class="dropdown-menu">
								<?php if(GROCY_FEATURE_FLAG_SHOPPINGLIST): ?>
								<a class="dropdown-item show-as-dialog-link"
									type="button"
									href="<?php echo e($U('/shoppinglistitem/new?embedded&updateexistingproduct&list=1&product=' . $stockEntry->product_id )); ?>">
									<i class="fa-solid fa-shopping-cart"></i> <?php echo e($__t('Add to shopping list')); ?>

								</a>
								<div class="dropdown-divider"></div>
								<?php endif; ?>
								<a class="dropdown-item show-as-dialog-link"
									type="button"
									href="<?php echo e($U('/purchase?embedded&product=' . $stockEntry->product_id )); ?>">
									<i class="fa-solid fa-cart-plus"></i> <?php echo e($__t('Purchase')); ?>

								</a>
								<a class="dropdown-item show-as-dialog-link"
									type="button"
									href="<?php echo e($U('/consume?embedded&product=' . $stockEntry->product_id . '&locationId=' . $stockEntry->location_id . '&stockId=' . $stockEntry->stock_id)); ?>">
									<i class="fa-solid fa-utensils"></i> <?php echo e($__t('Consume')); ?>

								</a>
								<?php if(GROCY_FEATURE_FLAG_STOCK_LOCATION_TRACKING): ?>
								<a class="dropdown-item show-as-dialog-link"
									type="button"
									href="<?php echo e($U('/transfer?embedded&product=' . $stockEntry->product_id . '&locationId=' . $stockEntry->location_id . '&stockId=' . $stockEntry->stock_id)); ?>">
									<i class="fa-solid fa-exchange-alt"></i> <?php echo e($__t('Transfer')); ?>

								</a>
								<?php endif; ?>
								<a class="dropdown-item show-as-dialog-link"
									type="button"
									href="<?php echo e($U('/inventory?embedded&product=' . $stockEntry->product_id )); ?>">
									<i class="fa-solid fa-list"></i> <?php echo e($__t('Inventory')); ?>

								</a>
								<a class="dropdown-item stock-consume-button stock-consume-button-spoiled"
									type="button"
									href="#"
									data-product-id="<?php echo e($stockEntry->product_id); ?>"
									data-product-name="<?php echo e(FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->name); ?>"
									data-product-qu-name="<?php echo e(FindObjectInArrayByPropertyValue($quantityunits, 'id', FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->qu_id_stock)->name); ?>"
									data-stock-id="<?php echo e($stockEntry->stock_id); ?>"
									data-stockrow-id="<?php echo e($stockEntry->id); ?>"
									data-location-id="<?php echo e($stockEntry->location_id); ?>"
									data-consume-amount="<?php echo e($stockEntry->amount); ?>">
									<?php echo e($__t('Consume this stock entry as spoiled', '1 ' . FindObjectInArrayByPropertyValue($quantityunits, 'id', FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->qu_id_stock)->name, FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->name)); ?>

								</a>
								<?php if(GROCY_FEATURE_FLAG_RECIPES): ?>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item"
									type="button"
									href="<?php echo e($U('/recipes?search=')); ?><?php echo e(FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->name); ?>">
									<?php echo e($__t('Search for recipes containing this product')); ?>

								</a>
								<?php endif; ?>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item productcard-trigger"
									data-product-id="<?php echo e($stockEntry->product_id); ?>"
									type="button"
									href="#">
									<?php echo e($__t('Product overview')); ?>

								</a>
								<a class="dropdown-item show-as-dialog-link"
									type="button"
									href="<?php echo e($U('/stockjournal?embedded&product=')); ?><?php echo e($stockEntry->product_id); ?>"
									data-dialog-type="table">
									<?php echo e($__t('Stock journal')); ?>

								</a>
								<a class="dropdown-item show-as-dialog-link"
									type="button"
									href="<?php echo e($U('/stockjournal/summary?embedded&product=')); ?><?php echo e($stockEntry->product_id); ?>"
									data-dialog-type="table">
									<?php echo e($__t('Stock journal summary')); ?>

								</a>
								<a class="dropdown-item link-return"
									type="button"
									data-href="<?php echo e($U('/product/')); ?><?php echo e($stockEntry->product_id); ?>">
									<?php echo e($__t('Edit product')); ?>

								</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item"
									type="button"
									href="<?php echo e($U('/stockentry/' . $stockEntry->id . '/grocycode?download=true')); ?>">
									<?php echo str_replace('Grocycode', '<span class="ls-n1">Grocycode</span>', $__t('Download %s Grocycode', $__t('Stock entry'))); ?>

								</a>
								<?php if(GROCY_FEATURE_FLAG_LABEL_PRINTER): ?>
								<a class="dropdown-item stockentry-grocycode-label-print"
									data-stock-id="<?php echo e($stockEntry->id); ?>"
									type="button"
									href="#">
									<?php echo str_replace('Grocycode', '<span class="ls-n1">Grocycode</span>', $__t('Print %s Grocycode on label printer', $__t('Stock entry'))); ?>

								</a>
								<?php endif; ?>
								<a class="dropdown-item stockentry-label-link"
									type="button"
									target="_blank"
									href="<?php echo e($U('/stockentry/' . $stockEntry->id . '/label')); ?>">
									<?php echo e($__t('Open stock entry label in new window')); ?>

								</a>
							</div>
						</div>
					</td>
					<td class="d-none"
						data-product-id="<?php echo e($stockEntry->product_id); ?>">
						<?php echo e($stockEntry->product_id); ?>

					</td>
					<td class="productcard-trigger cursor-link"
						data-product-id="<?php echo e($stockEntry->product_id); ?>">
						<?php echo e(FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->name); ?>

					</td>
					<td>
						<span class="custom-sort d-none"><?php echo e($stockEntry->amount); ?></span>
						<span id="stock-<?php echo e($stockEntry->id); ?>-amount"
							class="locale-number locale-number-quantity-amount"><?php echo e($stockEntry->amount); ?></span> <span id="product-<?php echo e($stockEntry->product_id); ?>-qu-name"><?php echo e($__n($stockEntry->amount, FindObjectInArrayByPropertyValue($quantityunits, 'id', FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->qu_id_stock)->name, FindObjectInArrayByPropertyValue($quantityunits, 'id', FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->qu_id_stock)->name_plural, true)); ?></span>
						<span id="stock-<?php echo e($stockEntry->id); ?>-opened-amount"
							class="small font-italic"><?php if($stockEntry->open == 1): ?><?php echo e($__n($stockEntry->amount, 'Opened', 'Opened')); ?><?php endif; ?></span>
					</td>
					<td class="<?php if(!GROCY_FEATURE_FLAG_STOCK_BEST_BEFORE_DATE_TRACKING): ?> d-none <?php endif; ?>">
						<span id="stock-<?php echo e($stockEntry->id); ?>-due-date"><?php echo e($stockEntry->best_before_date); ?></span>
						<time id="stock-<?php echo e($stockEntry->id); ?>-due-date-timeago"
							class="timeago timeago-contextual"
							<?php if($stockEntry->best_before_date != ""): ?> datetime="<?php echo e($stockEntry->best_before_date); ?> 23:59:59" <?php endif; ?>></time>
					</td>
					<td id="stock-<?php echo e($stockEntry->id); ?>-location"
						class="<?php if(!GROCY_FEATURE_FLAG_STOCK_LOCATION_TRACKING): ?> d-none <?php endif; ?>"
						data-location-id="<?php echo e($stockEntry->location_id); ?>">
						<?php echo e(FindObjectInArrayByPropertyValue($locations, 'id', $stockEntry->location_id)->name); ?>

					</td>
					<td id="stock-<?php echo e($stockEntry->id); ?>-shopping-location"
						class="<?php if(!GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?> d-none <?php endif; ?>"
						data-shopping-location-id="<?php echo e($stockEntry->shopping_location_id); ?>">
						<?php if(FindObjectInArrayByPropertyValue($shoppinglocations, 'id', $stockEntry->shopping_location_id) !== null): ?>
						<?php echo e(FindObjectInArrayByPropertyValue($shoppinglocations, 'id', $stockEntry->shopping_location_id)->name); ?>

						<?php endif; ?>
					</td>
					<td class="<?php if(!GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?> d-none <?php endif; ?>">
						<span class="custom-sort d-none"><?php echo e($stockEntry->price); ?></span>
						<span id="stock-<?php echo e($stockEntry->id); ?>-price"
							data-toggle="tooltip"
							data-trigger="hover click"
							data-html="true"
							title="<?php echo $__t('%1$s per %2$s', '<span class=\'locale-number locale-number-currency\'>' . $stockEntry->price . '</span>', FindObjectInArrayByPropertyValue($quantityunits, 'id', FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->qu_id_stock)->name); ?>">
							<?php echo $__t('%1$s per %2$s', '<span class="locale-number locale-number-currency">' . $stockEntry->price * $stockEntry->qu_factor_price_to_stock . '</span>', FindObjectInArrayByPropertyValue($quantityunits, 'id', FindObjectInArrayByPropertyValue($products, 'id', $stockEntry->product_id)->qu_id_price)->name); ?>

						</span>
					</td>
					<td>
						<span id="stock-<?php echo e($stockEntry->id); ?>-purchased-date"><?php echo e($stockEntry->purchased_date); ?></span>
						<time id="stock-<?php echo e($stockEntry->id); ?>-purchased-date-timeago"
							class="timeago timeago-contextual"
							<?php if(!empty($stockEntry->purchased_date)): ?> datetime="<?php echo e($stockEntry->purchased_date); ?> 23:59:59" <?php endif; ?>></time>
					</td>
					<td class="d-none"><?php echo e($stockEntry->purchased_date); ?></td>
					<td>
						<span><?php echo e($stockEntry->row_created_timestamp); ?></span>
						<time class="timeago timeago-contextual"
							datetime="<?php echo e($stockEntry->row_created_timestamp); ?>"></time>
					</td>
					<td>
						<span id="stock-<?php echo e($stockEntry->id); ?>-note"><?php echo e($stockEntry->note); ?></span>
					</td>

					<?php echo $__env->make('components.userfields_tbody', array(
					'userfields' => $userfieldsProducts,
					'userfieldValues' => FindAllObjectsInArrayByPropertyValue($userfieldValuesProducts, 'object_id', $stockEntry->product_id)
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

					<?php echo $__env->make('components.userfields_tbody', array(
					'userfields' => $userfieldsStock,
					'userfieldValues' => FindAllObjectsInArrayByPropertyValue($userfieldValuesStock, 'object_id', $stockEntry->stock_id)
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>
</div>

<?php echo $__env->make('components.productcard', [
'asModal' => true
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/stockentries.blade.php ENDPATH**/ ?>