

<?php $__env->startSection('content'); ?>


<h1 class="text-2xl font-bold mb-4">Add Year Level</h1>

<?php if(session('success')): ?>
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('admin.year-levels.store')); ?>">
    <?php echo csrf_field(); ?>
    <input type="text" name="year_level_name" placeholder="Year Level Name" class="border p-2 w-full mb-4" required>
    <button type="submit" class="bg-green-500 text-black px-4 py-2 rounded">Add Year Level</button>
    <a href="<?php echo e(route('admin.dashboard', ['page' => 'year-levels'])); ?>" class="ml-4 text-gray-500">Cancel</a>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/year-levels-create.blade.php ENDPATH**/ ?>