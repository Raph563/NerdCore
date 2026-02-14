<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport"
		content="width=device-width, initial-scale=1">

	<meta name="robots"
		content="noindex,nofollow">

	<link rel="icon"
		type="image/png"
		sizes="32x32"
		href="<?php echo e($U('/img/icon-32.png?v=', true)); ?><?php echo e($version); ?>">

	<title><?php echo e($__t('REST API browser')); ?> | Grocy</title>

	<link href="<?php echo e($U('/packages/swagger-ui-dist/swagger-ui.css?v=', true)); ?><?php echo e($version); ?>"
		rel="stylesheet">

	<?php if(file_exists(GROCY_DATAPATH . '/custom_css.html')): ?>
	<?php include GROCY_DATAPATH . '/custom_css.html' ?>
	<?php endif; ?>

	<script>
		var Grocy = { };
		Grocy.OpenApi = { };
		Grocy.OpenApi.SpecUrl = '<?php echo e($U('/api/openapi/specification')); ?>';
	</script>

	<style>
		.servers-title,
		.servers,
		.url {
			display: none !important;
		}

		.swagger-ui .info {
			margin-bottom: 0 !important;
		}

		.scheme-container {
			padding-top: 0 !important;
		}

		.swagger-ui .scheme-container {
			box-shadow: none !important;
			border-bottom: 1px solid rgba(59, 65, 81, 0.3) !important;
		}
	</style>
</head>

<body>
	<div id="swagger-ui"></div>

	<script src="<?php echo e($U('/packages/swagger-ui-dist/swagger-ui.js?v=', true)); ?><?php echo e($version); ?>"></script>
	<script src="<?php echo e($U('/packages/swagger-ui-dist/swagger-ui-bundle.js?v=', true)); ?><?php echo e($version); ?>"></script>
	<script src="<?php echo e($U('/packages/swagger-ui-dist/swagger-ui-standalone-preset.js?v=', true)); ?><?php echo e($version); ?>"></script>
	<script src="<?php echo e($U('/viewjs', true)); ?>/openapiui.js?v=<?php echo e($version); ?>"></script>

	<?php if(file_exists(GROCY_DATAPATH . '/custom_js.html')): ?>
	<?php include GROCY_DATAPATH . '/custom_js.html' ?>
	<?php endif; ?>
</body>

</html>
<?php /**PATH /app/www/views/openapiui.blade.php ENDPATH**/ ?>