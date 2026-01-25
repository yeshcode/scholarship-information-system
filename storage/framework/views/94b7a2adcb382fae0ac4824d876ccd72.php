

<?php $__env->startSection('page-content'); ?>
<h2 class="text-2xl font-bold mb-4">Create Scholarship Batch</h2>
<form action="<?php echo e(route('coordinator.scholarship-batches.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Scholarship</label>
        <select name="scholarship_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <?php $__currentLoopData = $scholarships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scholarship): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($scholarship->id); ?>"><?php echo e($scholarship->scholarship_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Semester</label>
        <select name="semester_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($semester->id); ?>"><?php echo e($semester->term); ?> <?php echo e($semester->academic_year); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Batch Number</label>
        <input type="text" name="batch_number" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mr-2">Create</button>
    <a href="<?php echo e(route('coordinator.scholarship-batches')); ?>" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/create-scholarship-batch.blade.php ENDPATH**/ ?>