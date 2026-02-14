<?php require_frontend_packages(['datatables']); ?>



<?php $__env->startSection('title', $__t('Stock journal')); ?>

<?php $__env->startSection('content'); ?>
<div class="title-related-links">
	<h2 class="title"><?php echo $__env->yieldContent('title'); ?></h2>
	<div class="float-right <?php if($embedded): ?> pr-5 <?php endif; ?>">
		<button class="btn btn-outline-dark d-md-none mt-2 order-1 order-md-3"
			type="button"
			data-toggle="collapse"
			data-target="#table-filter-row">
			<i class="fa-solid fa-filter"></i>
		</button>
		<button class="btn btn-outline-dark d-md-none mt-2 order-1 order-md-3 hide-when-embedded"
			type="button"
			data-toggle="collapse"
			data-target="#related-links">
			<i class="fa-solid fa-ellipsis-v"></i>
		</button>
	</div>
	<div class="related-links collapse d-md-flex order-2 width-xs-sm-100"
		id="related-links">
		<a class="btn btn-outline-dark responsive-button m-1 mt-md-0 mb-md-0 float-right hide-when-embedded"
			href="<?php echo e($U('/stockjournal/summary')); ?>">
			<?php echo e($__t('Journal summary')); ?>

		</a>
	</div>
</div>

<hr class="my-2">

<div class="row collapse d-md-flex"
	id="table-filter-row">
	<div class="col-12 col-md-6 col-xl-2">
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
	<div class="col-12 col-md-6 col-xl-3 hide-when-embedded">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="fa-solid fa-filter"></i>&nbsp;<?php echo e($__t('Product')); ?></span>
			</div>
			<select class="custom-control custom-select"
				id="product-filter">
				<option value="all"><?php echo e($__t('All')); ?></option>
				<?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<option value="<?php echo e($product->id); ?>"><?php echo e($product->name); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
		</div>
	</div>
	<div class="col-12 col-md-6 col-xl-3">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="fa-solid fa-filter"></i>&nbsp;<?php echo e($__t('Transaction type')); ?></span>
			</div>
			<select class="custom-control custom-select"
				id="transaction-type-filter">
				<option value="all"><?php echo e($__t('All')); ?></option>
				<?php $__currentLoopData = $transactionTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transactionType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<option value="<?php echo e($transactionType); ?>"><?php echo e($__t($transactionType)); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
		</div>
	</div>
	<?php if(GROCY_FEATURE_FLAG_STOCK_LOCATION_TRACKING): ?>
	<div class="col-12 col-md-6 col-xl-3">
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
	<div class="col-12 col-md-6 col-xl-2 <?php if(!$embedded): ?> mt-1 <?php endif; ?>">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="fa-solid fa-filter"></i>&nbsp;<?php echo e($__t('User')); ?></span>
			</div>
			<select class="custom-control custom-select"
				id="user-filter">
				<option value="all"><?php echo e($__t('All')); ?></option>
				<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<option value="<?php echo e($user->id); ?>"><?php echo e($user->display_name); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
		</div>
	</div>
	<div class="col-12 col-md-6 col-xl-3 mt-1">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="fa-solid fa-clock"></i>&nbsp;<?php echo e($__t('Date range')); ?></span>
			</div>
			<select class="custom-control custom-select"
				id="daterange-filter">
				<option value="1"><?php echo e($__n(1, '%s month', '%s months')); ?></option>
				<option value="6"
					selected><?php echo e($__n(6, '%s month', '%s months')); ?></option>
				<option value="12"><?php echo e($__n(1, '%s year', '%s years')); ?></option>
				<option value="24"><?php echo e($__n(2, '%s month', '%s years')); ?></option>
				<option value="9999"><?php echo e($__t('All')); ?></option>
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

<div class="row mt-2">
	<div class="col">
		<table id="stock-journal-table"
			class="table table-sm table-striped nowrap w-100">
			<thead>
				<tr>
					<th class="border-right"><a class="text-muted change-table-columns-visibility-button"
							data-toggle="tooltip"
							title="<?php echo e($__t('Table options')); ?>"
							data-table-selector="#stock-journal-table"
							href="#"><i class="fa-solid fa-eye"></i></a>
					</th>
					<th class="allow-grouping"><?php echo e($__t('Product')); ?></th>
					<th><?php echo e($__t('Amount')); ?></th>
					<th><?php echo e($__t('Transaction time')); ?></th>
					<th class="allow-grouping"><?php echo e($__t('Transaction type')); ?></th>
					<th class="<?php if(!GROCY_FEATURE_FLAG_STOCK_LOCATION_TRACKING): ?> d-none <?php endif; ?> allow-grouping"><?php echo e($__t('Location')); ?></th>
					<th class="allow-grouping"><?php echo e($__t('Done by')); ?></th>
					<th><?php echo e($__t('Note')); ?></th>

					<?php echo $__env->make('components.userfields_thead', array(
					'userfields' => $userfieldsStock
					), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
				</tr>
			</thead>
			<tbody class="d-none">
				<?php $__currentLoopData = $stockLog; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stockLogEntry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr id="stock-booking-<?php echo e($stockLogEntry->id); ?>-row"
					class="<?php if($stockLogEntry->undone == 1): ?> text-muted <?php endif; ?> stock-booking-correlation-<?php echo e($stockLogEntry->correlation_id); ?>"
					data-correlation-id="<?php echo e($stockLogEntry->correlation_id); ?>">
					<td class="fit-content border-right">
						<a class="btn btn-secondary btn-xs undo-stock-booking-button <?php if($stockLogEntry->undone == 1): ?> disabled <?php endif; ?>"
							href="#"
							data-booking-id="<?php echo e($stockLogEntry->id); ?>"
							data-toggle="tooltip"
							data-placement="left"
							title="<?php echo e($__t('Undo transaction')); ?>">
							<i class="fa-solid fa-undo"></i>
						</a>
						<div class="dropdown d-inline-block">
							<button class="btn btn-xs btn-light text-secondary"
								type="button"
								data-toggle="dropdown">
								<i class="fa-solid fa-ellipsis-v"></i>
							</button>
							<div class="table-inline-menu dropdown-menu dropdown-menu-right">
								<?php if(GROCY_FEATURE_FLAG_SHOPPINGLIST): ?>
								<a class="dropdown-item show-as-dialog-link permission-SHOPPINGLIST_ITEMS_ADD"
									type="button"
									href="<?php echo e($U('/shoppinglistitem/new?embedded&updateexistingproduct&list=1&product=' . $stockLogEntry->product_id )); ?>">
									<span class="dropdown-item-icon"><i class="fa-solid fa-shopping-cart"></i></span> <span class="dropdown-item-text"><?php echo e($__t('Add to shopping list')); ?></span>
								</a>
								<div class="dropdown-divider"></div>
								<?php endif; ?>
								<a class="dropdown-item show-as-dialog-link permission-STOCK_PURCHASE"
									type="button"
									href="<?php echo e($U('/purchase?embedded&product=' . $stockLogEntry->product_id )); ?>">
									<span class="dropdown-item-icon"><i class="fa-solid fa-cart-plus"></i></span> <span class="dropdown-item-text"><?php echo e($__t('Purchase')); ?></span>
								</a>
								<a class="dropdown-item show-as-dialog-link permission-STOCK_CONSUME"
									type="button"
									href="<?php echo e($U('/consume?embedded&product=' . $stockLogEntry->product_id )); ?>">
									<span class="dropdown-item-icon"><i class="fa-solid fa-utensils"></i></span> <span class="dropdown-item-text"><?php echo e($__t('Consume')); ?></span>
								</a>
								<?php if(GROCY_FEATURE_FLAG_STOCK_LOCATION_TRACKING): ?>
								<a class="dropdown-item show-as-dialog-link permission-STOCK_TRANSFER"
									type="button"
									href="<?php echo e($U('/transfer?embedded&product=' . $stockLogEntry->product_id)); ?>">
									<span class="dropdown-item-icon"><i class="fa-solid fa-exchange-alt"></i></span> <span class="dropdown-item-text"><?php echo e($__t('Transfer')); ?></span>
								</a>
								<?php endif; ?>
								<a class="dropdown-item show-as-dialog-link permission-STOCK_INVENTORY"
									type="button"
									href="<?php echo e($U('/inventory?embedded&product=' . $stockLogEntry->product_id )); ?>">
									<span class="dropdown-item-icon"><i class="fa-solid fa-list"></i></span> <span class="dropdown-item-text"><?php echo e($__t('Inventory')); ?></span>
								</a>
								<?php if(GROCY_FEATURE_FLAG_RECIPES): ?>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item"
									type="button"
									href="<?php echo e($U('/recipes?search=')); ?><?php echo e($stockLogEntry->product_name); ?>">
									<span class="dropdown-item-text"><?php echo e($__t('Search for recipes containing this product')); ?></span>
								</a>
								<?php endif; ?>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item productcard-trigger"
									data-product-id="<?php echo e($stockLogEntry->product_id); ?>"
									type="button"
									href="#">
									<span class="dropdown-item-text"><?php echo e($__t('Product overview')); ?></span>
								</a>
								<a class="dropdown-item show-as-dialog-link"
									type="button"
									href="<?php echo e($U('/stockentries?embedded&product=')); ?><?php echo e($stockLogEntry->product_id); ?>"
									data-dialog-type="table"
									data-product-id="<?php echo e($stockLogEntry->product_id); ?>">
									<span class="dropdown-item-text"><?php echo e($__t('Stock entries')); ?></span>
								</a>
								<a class="dropdown-item show-as-dialog-link"
									type="button"
									href="<?php echo e($U('/stockjournal/summary?embedded&product_id=')); ?><?php echo e($stockLogEntry->product_id); ?>"
									data-dialog-type="table">
									<span class="dropdown-item-text"><?php echo e($__t('Stock journal summary')); ?></span>
								</a>
								<a class="dropdown-item permission-MASTER_DATA_EDIT link-return"
									type="button"
									data-href="<?php echo e($U('/product/')); ?><?php echo e($stockLogEntry->product_id); ?>">
									<span class="dropdown-item-text"><?php echo e($__t('Edit product')); ?></span>
								</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item"
									type="button"
									href="<?php echo e($U('/product/' . $stockLogEntry->product_id . '/grocycode?download=true')); ?>">
									<?php echo str_replace('Grocycode', '<span class="ls-n1">Grocycode</span>', $__t('Download %s Grocycode', $__t('Product'))); ?>

								</a>
								<?php if(GROCY_FEATURE_FLAG_LABEL_PRINTER): ?>
								<a class="dropdown-item product-grocycode-label-print"
									data-product-id="<?php echo e($stockLogEntry->product_id); ?>"
									type="button"
									href="#">
									<?php echo str_replace('Grocycode', '<span class="ls-n1">Grocycode</span>', $__t('Print %s Grocycode on label printer', $__t('Product'))); ?>

								</a>
								<?php endif; ?>
							</div>
						</div>
					</td>
					<td class="productcard-trigger cursor-link"
						data-product-id="<?php echo e($stockLogEntry->product_id); ?>">
						<span class="name-anchor <?php if($stockLogEntry->undone == 1): ?> text-strike-through <?php endif; ?>"><?php echo e($stockLogEntry->product_name); ?></span>
						<?php if($stockLogEntry->undone == 1): ?>
						<br>
						<?php echo e($__t('Undone on') . ' ' . $stockLogEntry->undone_timestamp); ?>

						<time class="timeago timeago-contextual"
							datetime="<?php echo e($stockLogEntry->undone_timestamp); ?>"></time>
						<?php endif; ?>
					</td>
					<td>
						<span class="locale-number locale-number-quantity-amount"><?php echo e($stockLogEntry->amount); ?></span> <?php echo e($__n($stockLogEntry->amount, $stockLogEntry->qu_name, $stockLogEntry->qu_name_plural, true)); ?>

					</td>
					<td>
						<?php echo e($stockLogEntry->row_created_timestamp); ?>

						<time class="timeago timeago-contextual"
							datetime="<?php echo e($stockLogEntry->row_created_timestamp); ?>"></time>
					</td>
					<td>
						<?php echo e($__t($stockLogEntry->transaction_type)); ?>

						<?php if($stockLogEntry->spoiled == 1): ?>
						<span class="font-italic text-muted"><?php echo e($__t('Spoiled')); ?></span>
						<?php endif; ?>
					</td>
					<td class="<?php if(!GROCY_FEATURE_FLAG_STOCK_LOCATION_TRACKING): ?> d-none <?php endif; ?>">
						<?php echo e($stockLogEntry->location_name); ?>

					</td>
					<td>
						<?php echo e($stockLogEntry->user_display_name); ?>

					</td>
					<td>
						<?php echo e($stockLogEntry->note); ?>

					</td>

					<?php echo $__env->make('components.userfields_tbody', array(
					'userfields' => $userfieldsStock,
					'userfieldValues' => FindAllObjectsInArrayByPropertyValue($userfieldValuesStock, 'object_id', $stockLogEntry->stock_id)
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

<?php echo $__env->make('layout.default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/views/stockjournal.blade.php ENDPATH**/ ?>