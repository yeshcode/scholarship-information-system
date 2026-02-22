<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Scholarship Management Information System')); ?></title>

    <!-- Fonts (optional; keep if you like it) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- ✅ Bootstrap (local) -->
    <link rel="stylesheet" href="<?php echo e(asset('bootstrap/css/bootstrap.min.css')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('vendor/bootstrap-icons/bootstrap-icons.css')); ?>">

    <style>
        :root {
            --bisu-blue: #003366;
            --bisu-blue-2: #002244;
            --page-bg: #f0f4f8;
            --line: #e5e7eb;
        }

        body{
            font-family: Figtree, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background: var(--page-bg);
        }

        /* Header bar (replaces Tailwind bg-[#003366]) */
        .app-header{
            background: var(--bisu-blue);
            border-bottom: 1px solid rgba(255,255,255,.12);
        }

        /* Reusable title style */
        .page-title-blue {
            color: var(--bisu-blue);
            font-weight: 800;
            font-size: 1.8rem;
            margin-bottom: 0.75rem;
        }

        /* Reusable BISU primary button */
        .btn-bisu-primary {
            background-color: var(--bisu-blue);
            color: #ffffff;
            border: none;
            font-weight: 700;
        }
        .btn-bisu-primary:hover {
            background-color: var(--bisu-blue-2);
            color: #ffffff;
        }

        /* Optional: subtle gray secondary button */
        .btn-bisu-secondary {
            background-color: #f1f1f1;
            color: #333333;
            border: 1px solid #d0d0d0;
            font-weight: 600;
        }
        .btn-bisu-secondary:hover {
            background-color: #e7e7e7;
            color: #222222;
        }

        /* Content card wrapper */
        .content-card{
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 12px;
            box-shadow: 0 10px 24px rgba(11,46,94,.06);
        }
    </style>
</head>

<body>
    <div class="min-vh-100">

        <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <?php if(isset($header)): ?>
            <header class="app-header shadow-sm">
                <div class="container-fluid <?php echo e(isset($fullWidth) ? 'px-3 px-md-4' : 'container'); ?> py-3">
                    <div class="text-white">
                        <?php echo e($header); ?>

                    </div>
                </div>
            </header>
        <?php endif; ?>

        
        <main class="py-4">
            <?php if(isset($fullWidth)): ?>
                <div class="container-fluid px-3 px-md-4">
                    <div class="content-card p-3 p-md-4">
                        <?php if(isset($slot)): ?>
                            <?php echo e($slot); ?>

                        <?php endif; ?>

                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="container">
                    <div class="content-card p-3 p-md-4">
                        <?php if(isset($slot)): ?>
                            <?php echo e($slot); ?>

                        <?php endif; ?>

                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                </div>
            <?php endif; ?>
        </main>

    </div>

    <!-- ✅ Bootstrap JS (local) -->
    <script src="<?php echo e(asset('bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/layouts/app.blade.php ENDPATH**/ ?>