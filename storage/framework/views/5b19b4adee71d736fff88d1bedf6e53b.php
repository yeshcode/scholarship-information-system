

<?php $__env->startSection('content'); ?>
<div class="mx-auto" style="max-width: 820px;">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="page-title-blue mb-0">My Questions</h2>
            <small class="text-muted">All questions you have submitted.</small>
        </div>

        <a href="<?php echo e(route('questions.create')); ?>" class="btn btn-bisu-secondary">
            Back to Ask
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success border-0 shadow-sm">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php $__empty_1 = true; $__currentLoopData = $myQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card border-0 shadow-sm mb-2">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div class="w-100">
                        <div class="text-muted small mb-1">
                            <?php echo e($q->created_at ? $q->created_at->format('M d, Y • h:i A') : ''); ?>

                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <div class="fw-semibold" style="color:#003366;">
                                Question
                            </div>

                            <?php
                                $status = strtolower($q->status ?? 'pending');
                                $badge = match(true) {
                                    str_contains($status, 'answered') => 'bg-success',
                                    str_contains($status, 'pending') => 'bg-warning text-dark',
                                    str_contains($status, 'closed') => 'bg-secondary',
                                    default => 'bg-light text-dark border',
                                };
                            ?>

                            <span class="badge <?php echo e($badge); ?>">
                                <?php echo e(ucfirst($q->status ?? 'Pending')); ?>

                            </span>

                            <?php if(is_null($q->cluster_id)): ?>
                                <span class="badge bg-secondary">
                                    Thread removed by coordinator
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="text-muted" style="white-space: pre-line;">
                            <?php echo e($q->question_text); ?>

                        </div>
                    </div>

                    <form action="<?php echo e(route('questions.destroy', $q->id)); ?>" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this question?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            Delete
                        </button>
                    </form>
                </div>

                <?php if(!empty($q->answer)): ?>
                    <div class="mt-3 p-3 rounded" style="background:#f8f9fa;">
                        <div class="small fw-semibold mb-1" style="color:#003366;">Answer</div>
                        <div class="text-muted small" style="white-space: pre-line;">
                            <?php echo e($q->answer); ?>

                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-5 text-muted">
            You have no questions yet.
        </div>
    <?php endif; ?>

    <?php if(method_exists($myQuestions, 'links')): ?>
        <div class="d-flex justify-content-center mt-3">
            <?php echo e($myQuestions->links()); ?>

        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/my-questions.blade.php ENDPATH**/ ?>