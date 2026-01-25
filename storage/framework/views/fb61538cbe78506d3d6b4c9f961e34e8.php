

<?php $__env->startSection('page-content'); ?>
<h2 class="text-2xl font-bold mb-4">Edit Stipend</h2>
<form action="<?php echo e(route('coordinator.stipends.update', $stipend->id)); ?>" method="POST">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Scholar</label>
        <select name="scholar_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <?php $__currentLoopData = $scholars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scholar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($scholar->id); ?>" <?php echo e($stipend->scholar_id == $scholar->id ? 'selected' : ''); ?>><?php echo e($scholar->user->firstname); ?> <?php echo e($scholar->user->lastname); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Stipend Release</label>
        <select name="stipend_release_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <?php $__currentLoopData = $releases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $release): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($release->id); ?>" <?php echo e($stipend->stipend_release_id == $release->id ? 'selected' : ''); ?>><?php echo e($release->title); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Amount Received</label>
        <input type="number" step="0.01" name="amount_received" value="<?php echo e($stipend->amount_received); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Status</label>
        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="for_release" <?php echo e($stipend->status == 'for_release' ? 'selected' : ''); ?>>For Release</option>
            <option value="released" <?php echo e($stipend->status == 'released' ? 'selected' : ''); ?>>Released</option>
            <option value="returned" <?php echo e($stipend->status == 'returned' ? 'selected' : ''); ?>>Returned</option>
            <option value="waiting" <?php echo e($stipend->status == 'waiting' ? 'selected' : ''); ?>>Waiting</option>
        </select>
    </div>
    <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600 mr-2">Update</button>
    <a href="<?php echo e(route('coordinator.manage-stipends')); ?>" class="bg-gray-500 text-black px-4 py-2 rounded">Cancel</a>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/edit-stipend.blade.php ENDPATH**/ ?>