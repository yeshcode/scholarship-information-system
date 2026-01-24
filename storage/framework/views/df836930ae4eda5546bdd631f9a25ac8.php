
<?php $fullWidth = true; ?>  


<?php $__env->startSection('content'); ?>
<div class="p-6">  
    <?php if(session('success')): ?>
        <div class="bg-green-100 text-green-800 p-4 mb-4 rounded-lg shadow-sm"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <!-- Add Semester Button (Upper Right, Enhanced Design) -->
    <div class="flex justify-end mb-6">
        <a href="<?php echo e(route('admin.semesters.create')); ?>" class="inline-flex items-center bg-black text-black hover:bg-gray-800 font-bold py-3 px-6 rounded-lg shadow-md transition duration-200">
            <span class="mr-2">+</span> Add Semester
        </a>
    </div>

    <!-- Table Card (Full-width, internal scrolling, compressed rows) -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto max-h-[calc(100vh-200px)] overflow-y-auto">  
            <table class="table-auto w-full border-collapse text-center min-w-full">
                <thead class="bg-blue-200 text-black sticky top-0">  
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Term</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Academic Year</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Start Date</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">End Date</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Current</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $semesters ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition duration-150 even:bg-gray-25">
                            <td class="border border-gray-300 px-3 py-2 text-gray-800"><?php echo e($semester->term); ?></td>
                            <td class="border border-gray-300 px-3 py-2 text-gray-800"><?php echo e($semester->academic_year); ?></td>
                            <td class="border border-gray-300 px-3 py-2 text-gray-800"><?php echo e($semester->start_date); ?></td>
                            <td class="border border-gray-300 px-3 py-2 text-gray-800"><?php echo e($semester->end_date); ?></td>
                            <td class="border border-gray-300 px-3 py-2 text-gray-800"><?php echo e($semester->is_current ? 'Yes' : 'No'); ?></td>
                            <td class="border border-gray-300 px-3 py-2 space-x-2">
                                <a href="<?php echo e(route('admin.semesters.edit', $semester->id)); ?>" class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-black font-medium py-1 px-3 rounded shadow transition duration-200 text-sm">
                                    <span class="mr-1">✏️</span> Edit
                                </a>
                                <a href="<?php echo e(route('admin.semesters.delete', $semester->id)); ?>" class="inline-flex items-center bg-red-500 hover:bg-red-600 text-black font-medium py-1 px-3 rounded shadow transition duration-200 text-sm">
                                    <span class="mr-1">🗑️</span> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if(empty($semesters)): ?>
                        <tr>
                            <td colspan="6" class="px-3 py-4 text-gray-500 text-center">No semesters found. <a href="<?php echo e(route('admin.semesters.create')); ?>" class="text-blue-500 underline hover:text-blue-700">Add one now</a>.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/semesters.blade.php ENDPATH**/ ?>