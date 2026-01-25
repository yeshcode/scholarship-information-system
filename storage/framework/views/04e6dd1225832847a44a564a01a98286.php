

<?php $__env->startSection('page-content'); ?>
<?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<h2 class="text-2xl font-bold mb-4">Manage Scholarships</h2>
<a href="<?php echo e(route('coordinator.scholarships.create')); ?>" class="bg-blue-500 text-black px-4 py-2 rounded mb-4 inline-block">Add Scholarship</a>
<table class="min-w-full bg-white border border-gray-200">
    <thead>
        <tr class="bg-gray-100">
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Description</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Benefactor</th>
            <th class="px-4 py-2 text-right">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $scholarships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scholarship): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-t">
                <td class="px-4 py-2"><?php echo e($scholarship->scholarship_name); ?></td>
                <td class="px-4 py-2"><?php echo e(Str::limit($scholarship->description, 50)); ?></td>
                <td class="px-4 py-2"><?php echo e($scholarship->status); ?></td>
                <td class="px-4 py-2"><?php echo e($scholarship->benefactor); ?></td>
                <td class="px-4 py-2 text-right">
                    <a href="<?php echo e(route('coordinator.scholarships.edit', $scholarship->id)); ?>" class="text-blue-500 mr-2">Edit</a>
                    <a href="<?php echo e(route('coordinator.scholarships.confirm-delete', $scholarship->id)); ?>" class="text-red-500">Delete</a>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php echo e($scholarships->links()); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/manage-scholarships.blade.php ENDPATH**/ ?>