

<?php $__env->startSection('page-content'); ?>
<?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<h2 class="text-2xl font-bold mb-4">Manage Stipend Releases</h2>
<a href="<?php echo e(route('coordinator.stipend-releases.create')); ?>" class="bg-blue-500 text-black px-4 py-2 rounded mb-4 inline-block">Add Release</a>
<table class="min-w-full bg-white border border-gray-200">
    <thead>
        <tr class="bg-gray-100">
            <th class="px-4 py-2">Batch</th>
            <th class="px-4 py-2">Title</th>
            <th class="px-4 py-2">Amount</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Date Release</th>
            <th class="px-4 py-2 text-right">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $releases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $release): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-t">
                <td class="px-4 py-2"><?php echo e($release->scholarshipBatch->batch_number ?? 'N/A'); ?></td>
                <td class="px-4 py-2"><?php echo e($release->title); ?></td>
                <td class="px-4 py-2"><?php echo e($release->amount); ?></td>
                <td class="px-4 py-2"><?php echo e($release->status); ?></td>
                <td class="px-4 py-2"><?php echo e($release->date_release); ?></td>
                <td class="px-4 py-2 text-right">
                    <a href="<?php echo e(route('coordinator.stipend-releases.edit', $release->id)); ?>" class="text-blue-500 mr-2">Edit</a>
                    <a href="<?php echo e(route('coordinator.stipend-releases.confirm-delete', $release->id)); ?>" class="text-red-500">Delete</a>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php echo e($releases->links()); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/manage-stipend-releases.blade.php ENDPATH**/ ?>