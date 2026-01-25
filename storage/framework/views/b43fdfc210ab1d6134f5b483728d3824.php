

<?php $__env->startSection('page-content'); ?>
<?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<div class="flex items-center justify-between mb-4">
    <h2 class="text-2xl font-bold">Manage Scholars</h2>

    <div class="flex gap-2">
        <a href="<?php echo e(route('coordinator.scholars.create')); ?>"
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Add Scholar
        </a>
        <a href="<?php echo e(route('coordinator.scholars.ocr-upload')); ?>"
           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
            OCR Upload
        </a>
    </div>
</div>


<div class="bg-white border border-gray-200 rounded p-3 mb-4">
    <div class="font-semibold text-gray-700 mb-2">Scholarships</div>

    <div class="flex flex-wrap gap-2">
        <?php $__currentLoopData = $scholarships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('coordinator.scholars.by-scholarship', $s->id)); ?>"
               class="px-3 py-2 rounded border text-sm font-semibold
                      <?php echo e(isset($selectedScholarship) && $selectedScholarship->id == $s->id
                          ? 'bg-[#003366] text-white border-[#003366]'
                          : 'bg-white text-[#003366] border-[#003366] hover:bg-gray-100'); ?>">
                <?php echo e($s->scholarship_name); ?>

                <span class="ml-1 text-xs opacity-80">(<?php echo e($s->scholars_count ?? 0); ?>)</span>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>


<?php if(($mode ?? null) === 'batches'): ?>
    <div class="bg-white border border-gray-200 rounded p-4 mb-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-lg font-bold text-[#003366]">
                    <?php echo e($selectedScholarship->scholarship_name); ?> — Batch Numbers
                </div>
                <div class="text-sm text-gray-600">Click a batch to view scholars.</div>
            </div>

            <a href="<?php echo e(route('coordinator.manage-scholars')); ?>"
               class="text-sm font-semibold text-[#003366] hover:underline">
                Back to all
            </a>
        </div>

        <div class="mt-3 flex flex-wrap gap-2">
            <?php $__empty_1 = true; $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('coordinator.scholars.by-batch', $b->id)); ?>"
                   class="px-3 py-2 rounded border text-sm font-semibold
                          bg-white text-[#003366] border-[#003366] hover:bg-gray-100">
                    Batch <?php echo e($b->batch_number); ?>

                    <span class="ml-1 text-xs opacity-80">(<?php echo e($b->scholars_count ?? 0); ?>)</span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-gray-600 text-sm">
                    No current batch for this scholarship.
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>


<div class="bg-white border border-gray-200 rounded overflow-hidden">
    <div class="px-4 py-3 border-b flex items-center justify-between">
        <div class="font-semibold text-[#003366]">
            <?php if(($mode ?? null) === 'batch' && isset($selectedBatch)): ?>
                Scholars in <?php echo e($selectedScholarship->scholarship_name); ?> — Batch <?php echo e($selectedBatch->batch_number); ?>

            <?php elseif(($mode ?? null) === 'scholarship' && isset($selectedScholarship)): ?>
                Scholars under <?php echo e($selectedScholarship->scholarship_name); ?>

            <?php else: ?>
                Latest Scholars
            <?php endif; ?>
        </div>

        <?php if(($mode ?? null) !== null): ?>
            <a href="<?php echo e(route('coordinator.manage-scholars')); ?>"
               class="text-sm font-semibold text-[#003366] hover:underline">
                Clear filter
            </a>
        <?php endif; ?>
    </div>

    <table class="w-full">
        <thead>
            <tr class="bg-gray-50 text-sm">
                <th class="px-4 py-2 text-left">Student Name</th>
                <th class="px-4 py-2 text-left">Student ID</th>
                <th class="px-4 py-2 text-left">Course</th>
                <th class="px-4 py-2 text-left">Scholarship</th>
                <th class="px-4 py-2 text-left">Batch No.</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-left">Date Added</th>
            </tr>
        </thead>

        <tbody class="text-sm">
            <?php $__empty_1 = true; $__currentLoopData = $scholars ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scholar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-t">
                    <td class="px-4 py-2">
                        <?php echo e($scholar->user->firstname ?? 'N/A'); ?> <?php echo e($scholar->user->lastname ?? 'N/A'); ?>

                    </td>
                    <td class="px-4 py-2"><?php echo e($scholar->user->student_id ?? 'N/A'); ?></td>
                    <td class="px-4 py-2"><?php echo e($scholar->user->course->course_name ?? 'N/A'); ?></td>
                    <td class="px-4 py-2"><?php echo e($scholar->scholarship->scholarship_name ?? 'N/A'); ?></td>

                    
                    <td class="px-4 py-2">
                        <?php echo e($scholar->scholarshipBatch->batch_number ?? '—'); ?>

                    </td>

                    <td class="px-4 py-2"><?php echo e($scholar->status); ?></td>
                    <td class="px-4 py-2"><?php echo e($scholar->date_added); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="px-4 py-6 text-center text-gray-600">
                        No current scholar.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if(isset($scholars) && method_exists($scholars, 'links')): ?>
    <div class="mt-4">
        <?php echo e($scholars->links()); ?>

    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/manage-scholars.blade.php ENDPATH**/ ?>