

<?php $__env->startSection('page-content'); ?>
<h2 class="text-2xl font-bold mb-4">Confirm Delete Scholarship Batch</h2>
<p class="mb-4 text-red-600">Are you sure you want to delete this batch? This action cannot be undone.</p>
<div class="bg-gray-100 p-4 rounded mb-4">
    <p><strong>Scholarship:</strong> <?php echo e($batch->scholarship->scholarship_name ?? 'N/A'); ?></p>
    <p><strong>Semester:</strong> <?php echo e($batch->semester->term ?? 'N/A'); ?> <?php echo e($batch->semester->academic_year ?? ''); ?></p>
    <p><strong>Batch Number:</strong> <?php echo e($batch->batch_number); ?></p>
</div>
<div class="flex space-x-4">
    <form action="<?php echo e(route('coordinator.scholarship-batches.destroy', $batch->id)); ?>" method="POST" class="inline">
        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
        <button type="submit" class="bg-gray-500 text-black px-4 py-2 rounded">Yes, Delete</button>
    </form>
    <a href="<?php echo e(route('coordinator.scholarship-batches')); ?>" class="bg-gray-500 text-black px-4 py-2 rounded">Cancel</a>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/confirm-delete-scholarship-batch.blade.php ENDPATH**/ ?>