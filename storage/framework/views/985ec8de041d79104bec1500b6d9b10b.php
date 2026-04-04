


<?php $__env->startSection('content'); ?>

<style>
    .page-title-blue {
        font-weight: 700;
        font-size: 1.9rem;
        color: #003366;
    }

    .ay-row {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px;
        background: #fff;
        cursor: pointer;
        transition: .15s;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .ay-row:hover {
        background: #f3f7ff;
        border-color: #c7d7ff;
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid py-4" style="max-width: 1000px;">

    <h2 class="page-title-blue mb-3">Enrollment Records</h2>
    <p class="text-muted mb-4">Click an academic year to view the 1st and 2nd semester records.</p>

    <div class="d-flex flex-column gap-2">
        <?php $__empty_1 = true; $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('admin.enrollments.records.year', $year)); ?>"
               class="ay-row">

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?php echo e($year); ?></strong><br>
                        <small class="text-muted">View records</small>
                    </div>
                    <span class="badge bg-primary">Open</span>
                </div>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-muted">No academic years found.</div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/enrollment-records.blade.php ENDPATH**/ ?>