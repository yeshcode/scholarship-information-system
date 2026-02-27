

<?php $__env->startSection('page-content'); ?>
<h2 class="text-2xl font-bold mb-4">Confirm Delete Stipend</h2>
<p class="mb-4 text-red-600">Are you sure you want to delete this stipend? This action cannot be undone.</p>
<div class="bg-gray-100 p-4 rounded mb-4">
    <p><strong>Scholar:</strong> <?php echo e($stipend->scholar->user->firstname ?? 'N/A'); ?> <?php echo e($stipend->scholar->user->lastname ?? ''); ?></p>
    <p><strong>Release Title:</strong> <?php echo e($stipend->stipendRelease->title ?? 'N/A'); ?></p>
    <p><strong>Amount Received:</strong> <?php echo e($stipend->amount_received); ?></p>
    <p><strong>Status:</strong> <?php echo e($stipend->status); ?></p>
</div>
<div class="flex space-x-4">
    <form action="<?php echo e(route('coordinator.stipends.destroy', $stipend->id)); ?>" method="POST" class="inline">
        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
        <button type="submit" class="bg-gray-500 text-black px-4 py-2 rounded">Yes, Delete</button>
    </form>
    <a href="<?php echo e(route('coordinator.manage-stipends')); ?>" class="bg-gray-500 text-black px-4 py-2 rounded">Cancel</a>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/confirm-delete-stipend.blade.php ENDPATH**/ ?>