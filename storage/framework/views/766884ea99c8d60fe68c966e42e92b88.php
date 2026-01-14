<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Scholarship Management Information System')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap (local) -->
    <link rel="stylesheet" href="<?php echo e(asset('bootstrap/css/bootstrap.min.css')); ?>">


    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased bg-[#f0f4f8]">  
    <div class="min-h-screen bg-[#f0f4f8]">  
        <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Page Heading (Now with Blue Background) -->
        <?php if(isset($header)): ?>
            <header class="bg-[#003366] shadow border-b border-[#007bff]">  
                <div class="max-w-7xl mx-auto py-6 px-6 sm:px-6 lg:px-8">  
                    <div class="text-white">  
                        <?php echo e($header); ?>

                    </div>
                </div>
            </header>
        <?php endif; ?>

        <!-- Page Content (Wider with Margins) -->
        <main class="py-6">
            <div class="<?php echo e(isset($fullWidth) ? 'w-full px-4' : 'mx-auto px-3 sm:px-6 lg:px-8'); ?>">  
                <div class="<?php echo e(isset($fullWidth) ? 'bg-white overflow-hidden shadow-sm sm:rounded-lg' : 'bg-white overflow-hidden shadow-sm sm:rounded-lg p-6'); ?>">
                    <?php if(isset($slot)): ?>
                        <?php echo e($slot); ?>

                    <?php endif; ?>
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </main>
    </div>

    <script src="<?php echo e(asset('bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>

</body>
</html><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/layouts/app.blade.php ENDPATH**/ ?>