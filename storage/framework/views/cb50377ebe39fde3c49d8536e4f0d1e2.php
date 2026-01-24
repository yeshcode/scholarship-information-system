

<?php $__env->startSection('content'); ?>
<div class="mx-auto" style="max-width: 920px;">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h2 class="page-title-blue mb-0">Scholarships</h2>
            <small class="text-muted">Browse available scholarships and view details.</small>
        </div>

        
        
    </div>

    <hr class="mt-2 mb-3">

    <div class="row g-3">
        <?php $__empty_1 = true; $__currentLoopData = $scholarships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scholarship): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div class="w-100">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                    <h5 class="mb-0 fw-semibold" style="color:#003366;">
                                        <?php echo e($scholarship->scholarship_name); ?>

                                    </h5>

                                    
                                    <?php
                                        $status = strtolower($scholarship->status ?? '');
                                        $badge = match(true) {
                                            str_contains($status, 'open') => 'bg-success',
                                            str_contains($status, 'ongoing') => 'bg-success',
                                            str_contains($status, 'available') => 'bg-success',
                                            str_contains($status, 'closed') => 'bg-danger',
                                            str_contains($status, 'pending') => 'bg-warning text-dark',
                                            default => 'bg-secondary',
                                        };
                                    ?>
                                    <?php if(!empty($scholarship->status)): ?>
                                        <span class="badge <?php echo e($badge); ?>"><?php echo e($scholarship->status); ?></span>
                                    <?php endif; ?>
                                </div>

                                
                                <?php if(!empty($scholarship->description)): ?>
                                    <p class="text-muted mb-2" style="white-space: pre-line;">
                                        <?php echo e(\Illuminate\Support\Str::limit($scholarship->description, 160)); ?>

                                    </p>
                                <?php endif; ?>

                                
                                <div class="d-flex flex-wrap gap-2">
                                    <?php if(!empty($scholarship->benefactor)): ?>
                                        <span class="badge bg-light text-dark border">
                                            Benefactor: <?php echo e(\Illuminate\Support\Str::limit($scholarship->benefactor, 40)); ?>

                                        </span>
                                    <?php endif; ?>

                                    <?php if(!empty($scholarship->requirements)): ?>
                                        <span class="badge bg-light text-dark border">
                                            Requirements: <?php echo e(\Illuminate\Support\Str::limit($scholarship->requirements, 50)); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                            <div class="text-end flex-shrink-0">
                                <a href="<?php echo e(route('student.scholarships.show', $scholarship->id)); ?>"
                                   class="btn btn-bisu-primary btn-sm">
                                    View details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-5">
                <div class="mb-2" style="font-size: 2rem;">🎓</div>
                <h5 class="fw-semibold mb-1" style="color:#003366;">No scholarships posted yet</h5>
                <p class="text-muted mb-0">Please check again later.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if(method_exists($scholarships, 'links')): ?>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($scholarships->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/scholarships/index.blade.php ENDPATH**/ ?>