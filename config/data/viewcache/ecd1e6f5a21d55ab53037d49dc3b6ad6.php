<?php require_frontend_packages(['datatables', 'summernote', 'animatecss', 'bwipjs']); ?>



<?php $__env->startSection('title', $__t('Shopping list')); ?>

<?php $__env->startPush('pageStyles'); ?>
<style>
	#shopping-list-print-shadow-table_wrapper .dataTable>thead>tr>th[class*="sort"]:before,
	#shopping-list-print-shadow-table_wrapper .dataTable>thead>tr>th[class*="sort"]:after {
		content: "" !important;
	}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('pageScripts'); ?>
<script src="<?php echo e($U('/viewjs/purchase.js?v=', true)); ?><?php echo e($version); ?>"></script>
<?php $__env->stopPush(); ?>

<?php
if(boolval($userSettings['shopping_list_round_up']))
{
foreach($listItems as $listItem)
{
$listItem->amount = ceil($listItem->amount);
$listItem->last_price_total = $listItem->price * $listItem->amount;
}
}
?>

<?php $__env->startSection('content'); ?>
<div class="row d-print-none hide-on-fullscreen-card">
	<div class="col">
		<div class="title-related-links">
			<h2 class="title mr-2 order-0">
				<?php echo $__env->yieldContent('title'); ?>
			</h2>
			<?php if(GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?>
			<h2 class="mb-0 mr-auto order-3 order-md-1 width-xs-sm-100">
				<span class="text-muted small"><?php echo $__t('%s total value', '<span class="locale-number locale-number-currency">' . SumArrayValue($listItems, 'last_price_total') . '</span>'); ?></span>
			</h2>
			<?php endif; ?>
			<div class="float-right <?php if($embedded): ?> pr-5 <?php endif; ?>">
				<button class="btn btn-primary responsive-button d-md-none mt-2 order-1 order-md-3 show-as-dialog-link"
					href="<?php echo e($U('/shoppinglistitem/new?embedded&list=' . $selectedShoppingListId)); ?>">
					<?php echo e($__t('Add item')); ?>

				</button>
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
				<?php if(GROCY_FEATURE_FLAG_SHOPPINGLIST_MULTIPLE_LISTS): ?>
				<div class="my-auto float-right">
					<select class="custom-control custom-select bg-light font-weight-bold mt-md-0 mt-1"
						id="selected-shopping-list">
						<?php $__currentLoopData = $shoppingLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shoppingList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<option <?php if($shoppingList->id == $selectedShoppingListId): ?> selected="selected" <?php endif; ?> value="<?php echo e($shoppingList->id); ?>" data-shoppinglist-name="<?php echo e($shoppingList->name); ?>"><?php echo e($shoppingList->name); ?> (<?php echo e($shoppingList->item_count); ?>)</option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
				<div class="dropdown">
					<a class="btn btn-outline-dark responsive-button m-1 mt-md-0 mb-md-0 float-right dropdown-toggle"
						href="#"
						data-toggle="dropdown">
						<?php echo e($__t('List actions')); ?>

					</a>
					<div class="dropdown-menu">
						<a class="dropdown-item show-as-dialog-link"
							href="<?php echo e($U('/shoppinglist/new?embedded')); ?>">
							<?php echo e($__t('New shopping list')); ?>

						</a>
						<a class="dropdown-item show-as-dialog-link"
							href="<?php echo e($U('/shoppinglist/' . $selectedShoppingListId . '?embedded')); ?>">
							<?php echo e($__t('Edit shopping list')); ?>

						</a>
						<a id="delete-selected-shopping-list"
							class="dropdown-item text-danger <?php if($selectedShoppingListId == 1): ?> disabled text-muted <?php endif; ?>"
							href="#">
							<?php echo e($__t('Delete shopping list')); ?>

						</a>
						<div class="dropdown-divider"></div>
						<a id="print-shopping-list-button"
							class="dropdown-item"
							href="#">
							<?php echo e($__t('Print')); ?>

						</a>
					</div>
				</div>
				<?php else: ?>
				<input type="hidden"
					name="selected-shopping-list"
					id="selected-shopping-list"
					value="1">
				<?php endif; ?>
			</div>
		</div>
		<div id="filter-container"
			class="border-top border-bottom my-2 py-1">
			<div id="table-filter-row"
				data-status-filter="belowminstockamount"
				class="collapse normal-message status-filter-message responsive-button <?php if(!GROCY_FEATURE_FLAG_STOCK): ?> d-none <?php else: ?> d-md-inline-block <?php endif; ?>"><span class="d-block d-md-none"><?php echo e(count($missingProducts)); ?> <i class="fa-solid fa-exclamation-circle"></i></span><span class="d-none d-md-block"><?php echo e($__n(count($missingProducts), '%s product is below defined min. stock amount', '%s products are below defined min. stock amount')); ?></span></div>
			<div id="related-links"
				class="float-right mt-1 collapse d-md-block">
				<a class="btn btn-primary responsive-button btn-sm mb-1 show-as-dialog-link d-none d-md-inline-block"
					href="<?php echo e($U('/shoppinglistitem/new?embedded&list=' . $selectedShoppingListId)); ?>">
					<?php echo e($__t('Add item')); ?>

				</a>
				<div class="btn-group">
					<a id="clear-shopping-list"
						class="btn btn-outline-danger btn-sm mb-1 responsive-button <?php if($listItems->count() == 0): ?> disabled <?php endif; ?>"
						href="#">
						<?php echo e($__t('Clear list')); ?>

					</a>
					<a id="clear-done-items"
						class="btn btn-outline-danger btn-sm mb-1 responsive-button <?php if($listItems->count() == 0): ?> disabled <?php endif; ?>"
						href="#">
						<?php echo e($__t('Clear done items')); ?>

					</a>
				</div>

				<?php if(GROCY_FEATURE_FLAG_STOCK): ?>
				<div class="dropdown d-inline">
					<a class="btn btn-sm btn-outline-dark responsive-button mb-1 dropdown-toggle"
						href="#"
						data-toggle="dropdown">
						<?php echo e($__t('Stock actions')); ?>

					</a>
					<div class="dropdown-menu text-right">
						<a id="add-all-items-to-stock-button"
							class="dropdown-item"
							href="#"><?php echo e($__t('Add all list items to stock')); ?></a>
						<?php if(!boolval($userSettings['shopping_list_auto_add_below_min_stock_amount'])): ?>
						<a id="add-products-below-min-stock-amount"
							class="dropdown-item"
							href="#"><?php echo e($__t('Add products that are below defined min. stock amount')); ?></a>
						<?php endif; ?>
						<a id="add-overdue-expired-products"
							class="dropdown-item"
							href="#"><?php echo e($__t('Add overdue/expired products')); ?></a>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<div class="row collapse d-md-flex d-print-none hide-on-fullscreen-card"
	id="table-filter-row">
	<div class="col-12 col-md-6 col-xl-3">
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
	<div class="col-12 col-md-6 col-xl-3">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="fa-solid fa-filter"></i>&nbsp;<?php echo e($__t('Status')); ?></span>
			</div>
			<select class="custom-control custom-select"
				id="status-filter">
				<option value="all"><?php echo e($__t('All')); ?></option>
				<option class="<?php if(!GROCY_FEATURE_FLAG_STOCK): ?> d-none <?php endif; ?>"
					value="belowminstockamount"><?php echo e($__t('Below min. stock amount')); ?></option>
				<option value="xxDONExx"><?php echo e($__t('Only done items')); ?></option>
				<option value="xxUNDONExx"><?php echo e($__t('Only undone items')); ?></option>
			</select>
		</div>
	</div>
	<div class="col">
		<div class="float-right">
			<button id="clear-filter-button"
				class="btn btn-sm btn-outline-info"
				data-toggle="tooltip"
				title="<?php echo e($__t('Clear filter')); ?>">
				<i class="fa-solid fa-filter-circle-xmark"></i>
			</button>
		</div>
	</div>
</div>

<div id="shoppinglist-main"
	class="row d-print-none">
	<div class="<?php if(boolval($userSettings['shopping_list_show_calendar'])): ?> col-12 col-md-8 <?php else: ?> col-12 <?php endif; ?> pb-3">
		<table id="shoppinglist-table"
			class="table table-sm table-striped nowrap w-100">
			<thead>
				<tr>
					<th class="border-right"><a class="text-muted change-table-columns-visibility-button"
							data-toggle="tooltip"
							title="<?php echo e($__t('Table options')); ?>"
							data-table-selector="#shoppinglist-table"
							href="#"><i class="fa-solid fa-eye"></i></a>
					</th>
					<th class="allow-grouping"><?php echo e($__t('Product')); ?> / <em><?php echo e($__t('Note')); ?></em></th>
					<th><?php echo e($__t('Amount')); ?></th>
					<th class="allow-grouping"><?php echo e($__t('Product group')); ?></th>
					<th class="d-none">Hidden status</th>
					<th class="<?php if(!GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?> d-none <?php endif; ?>"><?php echo e($__t('Last price (Unit)')); ?></th>
					<th class="<?php if(!GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?> d-none <?php endif; ?>"><?php echo e($__t('Last price (Total)')); ?></th>
					<th class="<?php if(!GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?> d-none <?php endif; ?> allow-grouping"><?php echo e($__t('Default store')); ?></th>
					<th><?php echo e($__t('Barcodes')); ?></th>

					<?php echo $__env->make('components.userfields_thead', array(
					'userfields' => $userfields
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
					<?php echo $__env->make('components.userfields_thead', array(
					'userfields' => $productUserfields
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

				</tr>
			</thead>
			<tbody class="d-none">
				<?php $__currentLoopData = $listItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $listItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr id="shoppinglistitem-<?php echo e($listItem->id); ?>-row"
					class="<?php if(FindObjectInArrayByPropertyValue($missingProducts, 'id', $listItem->product_id) !== null): ?> table-info <?php endif; ?> <?php if($listItem->done == 1): ?> text-muted text-strike-through <?php endif; ?>">
					<td class="fit-content border-right">
						<a class="btn btn-success btn-sm order-listitem-button"
							href="#"
							data-item-id="<?php echo e($listItem->id); ?>"
							data-item-done="<?php echo e($listItem->done); ?>"
							data-toggle="tooltip"
							data-placement="right"
							title="<?php echo e($__t('Mark this item as done')); ?>">
							<i class="fa-solid fa-check"></i>
						</a>
						<a class="btn btn-sm btn-info show-as-dialog-link"
							href="<?php echo e($U('/shoppinglistitem/' . $listItem->id . '?embedded&list=' . $selectedShoppingListId )); ?>"
							data-toggle="tooltip"
							data-placement="right"
							title="<?php echo e($__t('Edit this item')); ?>">
							<i class="fa-solid fa-edit"></i>
						</a>
						<a class="btn btn-sm btn-danger shoppinglist-delete-button"
							href="#"
							data-shoppinglist-id="<?php echo e($listItem->id); ?>"
							data-toggle="tooltip"
							data-placement="right"
							title="<?php echo e($__t('Delete this item')); ?>">
							<i class="fa-solid fa-trash"></i>
						</a>
						<a class="btn btn-sm btn-primary <?php if(!GROCY_FEATURE_FLAG_STOCK): ?> d-none <?php endif; ?> <?php if(empty($listItem->product_id)): ?> disabled <?php else: ?> shopping-list-stock-add-workflow-list-item-button <?php endif; ?>"
							href="<?php echo e($U('/purchase?embedded&flow=shoppinglistitemtostock&product=')); ?><?php echo e($listItem->product_id); ?>&amount=<?php echo e($listItem->amount); ?>&listitemid=<?php echo e($listItem->id); ?>&quId=<?php echo e($listItem->qu_id); ?>"
							<?php if(!empty($listItem->product_id)): ?> data-toggle="tooltip" title="<?php echo e($__t('Add this item to stock')); ?>" <?php endif; ?>>
							<i class="fa-solid fa-box"></i>
						</a>
					</td>
					<td class="productcard-trigger cursor-link"
						data-product-id="<?php echo e($listItem->product_id); ?>">
						<?php if(!empty($listItem->product_id)): ?> <?php echo e($listItem->product_name); ?><br><?php endif; ?><em><?php echo nl2br($listItem->note ?? ''); ?></em>
					</td>
					<?php if(!empty($listItem->product_id)): ?>
					<?php
					$listItem->amount_origin_qu = $listItem->amount;
					$product = FindObjectInArrayByPropertyValue($products, 'id', $listItem->product_id);
					$productQuConversions = FindAllObjectsInArrayByPropertyValue($quantityUnitConversionsResolved, 'product_id', $product->id);
					$productQuConversions = FindAllObjectsInArrayByPropertyValue($productQuConversions, 'from_qu_id', $product->qu_id_stock);
					$productQuConversion = FindObjectInArrayByPropertyValue($productQuConversions, 'to_qu_id', $listItem->qu_id);
					if ($productQuConversion)
					{
					$listItem->amount = $listItem->amount * $productQuConversion->factor;
					}
					?>
					<?php endif; ?>
					<td>
						<span class="custom-sort d-none"><?php echo e($listItem->amount); ?></span>
						<span class="locale-number locale-number-quantity-amount"><?php echo e($listItem->amount); ?></span> <?php if(!empty($listItem->product_id)): ?><?php echo e($__n($listItem->amount, $listItem->qu_name, $listItem->qu_name_plural, true)); ?><?php endif; ?>
					</td>
					<td>
						<?php if(!empty($listItem->product_group_name)): ?> <?php echo e($listItem->product_group_name); ?> <?php else: ?> <span class="font-italic font-weight-light"><?php echo e($__t('Ungrouped')); ?></span> <?php endif; ?>
					</td>
					<td id="shoppinglistitem-<?php echo e($listItem->id); ?>-status-info"
						class="d-none">
						<?php if(FindObjectInArrayByPropertyValue($missingProducts, 'id', $listItem->product_id) !== null): ?> belowminstockamount <?php endif; ?>
						<?php if($listItem->done == 1): ?> xxDONExx <?php else: ?> xxUNDONExx <?php endif; ?>
					</td>
					<td class="<?php if(!GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?> d-none <?php endif; ?>">
						<span class="locale-number locale-number-currency"><?php echo e($listItem->last_price_unit); ?></span>
					</td>
					<td class="<?php if(!GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?> d-none <?php endif; ?>">
						<span class="locale-number locale-number-currency"><?php echo e($listItem->last_price_total); ?></span>
					</td>
					<td class="<?php if(!GROCY_FEATURE_FLAG_STOCK_PRICE_TRACKING): ?> d-none <?php endif; ?>">
						<?php echo e($listItem->default_shopping_location_name); ?>

					</td>
					<td>
						<?php if($listItem->product_barcodes != null): ?>
						<?php $__currentLoopData = explode(',', $listItem->product_barcodes); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barcode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php if(!empty($barcode)): ?>
						<img class="barcode img-fluid pr-2"
							data-barcode="<?php echo e($barcode); ?>">
						<?php endif; ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php endif; ?>
					</td>

					<?php echo $__env->make('components.userfields_tbody', array(
					'userfields' => $userfields,
					'userfieldValues' => FindAllObjectsInArrayByPropertyValue($userfieldValues, 'object_id', $listItem->id)
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
					<?php echo $__env->make('components.userfields_tbody', array(
					'userfields' => $productUserfields,
					'userfieldValues' => FindAllObjectsInArrayByPropertyValue($productUserfieldValues, 'object_id', $listItem->product_id)
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>

	<?php if(boolval($userSettings['shopping_list_show_calendar'])): ?>
	<div class="col-12 col-md-4 mt-md-2 d-print-none">
		<?php echo $__env->make('components.calendarcard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
	</div>
	<?php endif; ?>

	<div class="<?php if(boolval($userSettings['shopping_list_show_calendar'])): ?> col-12 col-md-8 <?php else: ?> col-12 <?php endif; ?> d-print-none pt-2">
		<div class="form-group">
			<label class="text-larger font-weight-bold"
				for="notes"><?php echo e($__t('Notes')); ?></label>
			<a id="save-description-button"
				class="btn btn-success btn-sm ml-1 mb-2"
				href="#"><?php echo e($__t('Save')); ?></a>
			<a id="clear-description-button"
				class="btn btn-danger btn-sm ml-1 mb-2"
				href="#"><?php echo e($__t('Clear')); ?></a>
			<textarea class="form-control wysiwyg-editor"
				id="description"
				name="description"><?php echo e(FindObjectInArrayByPropertyValue($shoppingLists, 'id', $selectedShoppingListId)->description); ?></textarea>
		</div>
	</div>
</div>

<div class="modal fade"
	id="shopping-list-stock-add-workflow-modal"
	tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-body">
				<iframe id="shopping-list-stock-add-workflow-purchase-form-frame"
					class="embed-responsive">
				</iframe>
			</div>
			<div class="modal-footer d-none">
				<span id="shopping-list-stock-add-workflow-purchase-item-count"
					class="d-none mr-auto"></span>
				<button id="shopping-list-stock-add-workflow-skip-button"
					type="button"
					class="btn btn-primary"><?php echo e($__t('Skip')); ?></button>
			</div>
		</div>
	</div>
</div>

<div class="d-none d-print-block">
	<div id="print-header">
		<h1 class="text-center">
			<img src="<?php echo e($U('/img/logo.svg?v=', true)); ?><?php echo e($version); ?>"
				width="114"
				height="30"
				class="d-print-flex mx-auto">
			<?php echo e($__t("Shopping list")); ?>

		</h1>
		<?php if(FindObjectInArrayByPropertyValue($shoppingLists, 'id', $selectedShoppingListId)->name != $__t("Shopping list")): ?>
		<h3 class="text-center">
			<?php echo e(FindObjectInArrayByPropertyValue($shoppingLists, 'id', $selectedShoppingListId)->name); ?>

		</h3>
		<?php endif; ?>
		<h6 class="text-center mb-4">
			<?php echo e($__t('Time of printing')); ?>:
			<span class="d-inline print-timestamp"></span>
		</h6>
	</div>
	<div class="w-75 print-layout-container print-layout-type-table d-none">
		<div>
			<table id="shopping-list-print-shadow-table"
				class="table table-sm table-striped nowrap">
				<thead>
					<tr>
						<th><?php echo e($__t('Product')); ?> / <em><?php echo e($__t('Note')); ?></em></th>
						<th><?php echo e($__t('Amount')); ?></th>
						<th><?php echo e($__t('Product group')); ?></th>

						<?php echo $__env->make('components.userfields_thead', array(
						'userfields' => $userfields
						), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
						<?php echo $__env->make('components.userfields_thead', array(
						'userfields' => $productUserfields
						), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
						<?php echo $__env->make('components.userfields_thead', array(
						'userfields' => $productGroupUserfields
						), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
					</tr>
				</thead>
				<tbody>
					<?php $__currentLoopData = $listItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $listItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr>
						<td>
							<?php if(!empty($listItem->product_id)): ?> <?php echo e($listItem->product_name); ?><br><?php endif; ?><em><?php echo nl2br($listItem->note ?? ''); ?></em>
						</td>
						<td>
							<span class="locale-number locale-number-quantity-amount"><?php echo e($listItem->amount); ?></span> <?php if(!empty($listItem->product_id)): ?><?php echo e($__n($listItem->amount, $listItem->qu_name, $listItem->qu_name_plural, true)); ?><?php endif; ?>
						</td>
						<td>
							<?php if(!empty($listItem->product_group_name)): ?> <?php echo e($listItem->product_group_name); ?> <?php else: ?> <span class="font-italic font-weight-light"><?php echo e($__t('Ungrouped')); ?></span> <?php endif; ?>
						</td>

						<?php echo $__env->make('components.userfields_tbody', array(
						'userfields' => $userfields,
						'userfieldValues' => FindAllObjectsInArrayByPropertyValue($userfieldValues, 'object_id', $listItem->id)
						), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
						<?php echo $__env->make('components.userfields_tbody', array(
						'userfields' => $productUserfields,
						'userfieldValues' => FindAllObjectsInArrayByPropertyValue($productUserfieldValues, 'object_id', $listItem->product_id)
						), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
						<?php echo $__env->make('components.userfields_tbody', array(
						'userfields' => $productGroupUserfields,
						'userfieldValues' => FindAllObjectsInArrayByPropertyValue($productGroupUserfieldValues, 'object_id', $listItem->product_group_id)
						), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

					</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="w-75 print-layout-container print-layout-type-list d-none">
		<?php $__currentLoopData = $listItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $listItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<div class="py-0">
			<span class="locale-number locale-number-quantity-amount"><?php echo e($listItem->amount); ?></span> <?php if(!empty($listItem->product_id)): ?><?php echo e($__n($listItem->amount, $listItem->qu_name, $listItem->qu_name_plural, true)); ?><?php endif; ?>
			<?php if(!empty($listItem->product_id)): ?> <?php echo e($listItem->product_name); ?><br><?php endif; ?><em><?php echo nl2br($listItem->note ?? ''); ?></em>
		</div><br>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</div>
	<div class="w-75 pt-3">
		<div>
			<h5><?php echo e($__t('Notes')); ?></h5>
			<p id="description-for-print"></p>
		</div>
	</div>
</div>

<?php echo $__env->make('components.productcard', [
'asModal' => true
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/shoppinglist.blade.php ENDPATH**/ ?>