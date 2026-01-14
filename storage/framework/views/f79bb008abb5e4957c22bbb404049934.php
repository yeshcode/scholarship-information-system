

<?php $__env->startSection('content'); ?>


<h1 class="text-2xl font-bold mb-4">Edit Year Level</h1>

<?php if(session('success')): ?>
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('admin.year-levels.update', $yearLevel->id)); ?>">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <input type="text" name="year_level_name" value="<?php echo e($yearLevel->year_level_name); ?>" class="border p-2 w-full mb-4" required>
    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update Year Level</button>
    <a href="<?php echo e(route('admin.dashboard', ['page' => 'year-levels'])); ?>" class="ml-4 text-gray-500">Cancel</a>
</form>

<form method="POST" action="<?php echo e(route('admin.year-levels.destroy', $yearLevel->id)); ?>" class="mt-4">
    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('Delete?')">Delete Year Level</button>
</form>
<?php $__env->stopSection(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/year-levels-edit.blade.php ENDPATH**/ ?>