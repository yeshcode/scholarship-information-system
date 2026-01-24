


<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-gray-100">  
    <div class="max-w-sm mx-auto bg-white shadow-lg rounded-lg p-6">  
        <h1 class="text-xl font-bold mb-4 text-red-600">Confirm Deletion</h1>
        <p class="text-gray-700 mb-4">Are you sure you want to delete this semester? This action cannot be undone.</p>

        <div class="bg-gray-50 p-4 rounded-lg mb-4 border">
            <h2 class="font-semibold text-gray-800"><?php echo e($semester->term); ?> - <?php echo e($semester->academic_year); ?></h2>
            <p class="text-sm text-gray-600 mt-2">Start: <?php echo e($semester->start_date); ?> | End: <?php echo e($semester->end_date); ?></p>
            <p class="text-sm text-gray-600">Current: <?php echo e($semester->is_current ? 'Yes' : 'No'); ?></p>
        </div>

        <form method="POST" action="<?php echo e(route('admin.semesters.destroy', $semester->id)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <div class="flex space-x-3">
                <button type="submit" class="inline-flex items-center bg-red-600 hover:bg-red-700 text-black font-bold py-2 px-4 rounded-lg shadow-md transition duration-200 text-sm">
                    <span class="mr-1">🗑️</span> Yes, Delete
                </button>
                <a href="<?php echo e(route('admin.dashboard', ['page' => 'semesters'])); ?>" class="inline-flex items-center text-gray-600 hover:text-gray-800 font-medium py-2 px-4 transition duration-200 text-sm">
                    <span class="mr-1">❌</span> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/semesters-delete.blade.php ENDPATH**/ ?>