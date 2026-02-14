<div class="custom-control custom-checkbox">
	<input type="checkbox"
		name="<?php echo e($perm->permission_name); ?>"
		class="permission-cb form-check-input custom-control-input"
		data-perm-id="<?php echo e($perm->permission_id); ?>"
		id="perm-<?php echo e($perm->permission_id); ?>"
		<?php if($perm->has_permission): ?> checked <?php endif; ?>
	<?php if(isset($permParent) && $permParent->has_permission): ?> disabled <?php endif; ?>>
	<label class="form-check-label custom-control-label"
		for="perm-<?php echo e($perm->permission_id); ?>">
		<?php echo e($__t($perm->permission_name)); ?>

	</label>
</div>
<div id="permission-sub-<?php echo e($perm->permission_name); ?>">
	<ul>
		<?php $__currentLoopData = $perm->uihelper_user_permissionsList(array('user_id' => $user->id))->via('parent'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<li>
			<?php echo $__env->make('components.userpermission_select', array(
			'perm' => $p,
			'permParent' => $perm
			), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
		</li>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</ul>
</div>
<?php /**PATH /app/www/views/components/userpermission_select.blade.php ENDPATH**/ ?>