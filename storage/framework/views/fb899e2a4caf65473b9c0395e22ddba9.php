

<?php $__env->startSection('content'); ?>
<div class="mx-auto" style="max-width: 920px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="page-title-blue mb-0"><?php echo e($scholarship->scholarship_name); ?></h2>
            <small class="text-muted">Full scholarship details</small>
        </div>

        <a href="<?php echo e(route('student.scholarships.index')); ?>" class="btn btn-bisu-secondary">
            Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 p-md-4">

            <?php if(!empty($scholarship->status)): ?>
                <div class="mb-3">
                    <span class="badge bg-light text-dark border">
                        <strong>Status:</strong> <?php echo e($scholarship->status); ?>

                    </span>
                </div>
            <?php endif; ?>

            <?php if(!empty($scholarship->benefactor)): ?>
                <p class="mb-2">
                    <strong>Benefactor:</strong> <?php echo e($scholarship->benefactor); ?>

                </p>
            <?php endif; ?>

            <?php if(!empty($scholarship->description)): ?>
                <div class="mb-3">
                    <strong>Description</strong>
                    <div class="text-muted mt-1" style="white-space: pre-line;">
                        <?php echo e($scholarship->description); ?>

                    </div>
                </div>
            <?php endif; ?>

            <?php if(!empty($scholarship->requirements)): ?>
                <div class="mb-3">
                    <strong>Requirements</strong>
                    <div class="text-muted mt-1" style="white-space: pre-line;">
                        <?php echo e($scholarship->requirements); ?>

                    </div>
                </div>
            <?php endif; ?>

            
            
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/scholarships/show.blade.php ENDPATH**/ ?>