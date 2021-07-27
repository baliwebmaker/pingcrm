<!DOCTYPE html>
<html class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">

    
    <script src="https://polyfill.io/v3/polyfill.min.js?features=smoothscroll,NodeList.prototype.forEach,Promise,Object.values,Object.assign" defer></script>

    
    <script src="https://polyfill.io/v3/polyfill.min.js?features=String.prototype.startsWith" defer></script>

    <script src="<?php echo e(mix('/js/app.js')); ?>" defer></script>
    <?php echo app('Tightenco\Ziggy\BladeRouteGenerator')->generate(); ?>
</head>
<body class="font-sans leading-none text-gray-700 antialiased">

<div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div>

</body>
</html>
<?php /**PATH D:\web\htdocs\pingcrm\resources\views/app.blade.php ENDPATH**/ ?>