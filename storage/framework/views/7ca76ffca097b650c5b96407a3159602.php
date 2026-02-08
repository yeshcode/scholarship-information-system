

<?php $__env->startSection('page-content'); ?>
<h2 class="text-2xl font-bold mb-4">Confirm Delete Stipend Release</h2>
<p class="mb-4 text-red-600">Are you sure you want to delete this release? This action cannot be undone.</p>
<div class="bg-gray-100 p-4 rounded mb-4">
    <p><strong>Batch:</strong> <?php echo e($release->scholarshipBatch->batch_number ?? 'N/A'); ?></p>
    <p><strong>Title:</strong> <?php echo e($release->title); ?></p>
    <p><strong>Amount:</strong> <?php echo e($release->amount); ?></p>
    <p><strong>Status:</strong> <?php echo e($release->status); ?></p>
    <p><strong>Date Release:</strong> <?php echo e($release->date_release); ?></p>
    <p><strong>Notes:</strong> <?php echo e($release->notes ?: 'None'); ?></p>
</div>
<div class="flex space-x-4">
    <form action="<?php echo e(route('coordinator.stipend-releases.destroy', $release->id)); ?>" method="POST" class="inline">
        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
        <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded">Yes, Delete</button>
    </form>
    <a href="<?php echo e(route('coordinator.manage-stipend-releases')); ?>" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/confirm-delete-stipend-release.blade.php ENDPATH**/ ?>