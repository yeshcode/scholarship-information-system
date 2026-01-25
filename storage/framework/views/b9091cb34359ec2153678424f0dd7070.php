

<?php $__env->startSection('page-content'); ?>
<h2 class="text-2xl font-bold mb-4">Edit Stipend Release</h2>
<form action="<?php echo e(route('coordinator.stipend-releases.update', $release->id)); ?>" method="POST">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Batch</label>
        <select name="batch_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($batch->id); ?>" <?php echo e($release->batch_id == $batch->id ? 'selected' : ''); ?>><?php echo e($batch->batch_number); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Title</label>
        <input type="text" name="title" value="<?php echo e($release->title); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Amount</label>
        <input type="number" step="0.01" name="amount" value="<?php echo e($release->amount); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Status</label>
        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="pending" <?php echo e($release->status == 'pending' ? 'selected' : ''); ?>>Pending</option>
            <option value="released" <?php echo e($release->status == 'released' ? 'selected' : ''); ?>>Released</option>
        </select>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Date Release</label>
        <input type="date" name="date_release" value="<?php echo e($release->date_release); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Notes</label>
        <textarea name="notes" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo e($release->notes); ?></textarea>
    </div>
    <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600 mr-2">Update</button>
    <a href="<?php echo e(route('coordinator.manage-stipend-releases')); ?>" class="bg-gray-500 text-black px-4 py-2 rounded">Cancel</a>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/edit-stipend-release.blade.php ENDPATH**/ ?>