

<?php $__env->startSection('content'); ?>
<div class="mx-auto" style="max-width: 720px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="page-title-blue mb-0">Announcement</h2>
            <small class="text-muted">
                <?php echo e($announcement->posted_at ? $announcement->posted_at->format('M d, Y • h:i A') : ''); ?>

            </small>
        </div>

        <a href="<?php echo e(route('student.announcements')); ?>" class="btn btn-bisu-secondary btn-sm">
            Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 p-md-4">
            <h4 class="fw-semibold mb-2" style="color:#003366;">
                <?php echo e($announcement->title); ?>

            </h4>

            <div class="text-muted" style="white-space: pre-line;">
                <?php echo e($announcement->description); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/announcement-show.blade.php ENDPATH**/ ?>