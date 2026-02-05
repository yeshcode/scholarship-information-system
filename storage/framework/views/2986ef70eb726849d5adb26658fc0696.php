

<?php $__env->startSection('page-content'); ?>
<h2 class="text-2xl font-bold mb-4">Confirm Delete Scholarship</h2>
<p class="mb-4 text-red-600">Are you sure you want to delete this scholarship? This action cannot be undone.</p>
<div class="bg-gray-100 p-4 rounded mb-4">
    <p><strong>Name:</strong> <?php echo e($scholarship->scholarship_name); ?></p>
    <p><strong>Description:</strong> <?php echo e($scholarship->description); ?></p>
    <p><strong>Requirements:</strong> <?php echo e($scholarship->requirements); ?></p>
    <p><strong>Status:</strong> <?php echo e($scholarship->status); ?></p>
    <p><strong>Benefactor:</strong> <?php echo e($scholarship->benefactor); ?></p>
</div>
<div class="flex space-x-4">
    <form action="<?php echo e(route('coordinator.scholarships.destroy', $scholarship->id)); ?>" method="POST" class="inline">
        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
        <button type="submit" class="bg-gray-500 text-black px-4 py-2 rounded">Yes, Delete</button>
    </form>
    <a href="<?php echo e(route('coordinator.manage-scholarships')); ?>" class="bg-gray-500 text-black px-4 py-2 rounded">Cancel</a>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/confirm-delete-scholarship.blade.php ENDPATH**/ ?>