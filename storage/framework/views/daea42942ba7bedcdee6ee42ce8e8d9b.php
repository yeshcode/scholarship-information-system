

<?php $__env->startSection('content'); ?>
<div class="mx-auto" style="max-width: 820px;">

    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h2 class="page-title-blue mb-0">Ask a Question</h2>
            <small class="text-muted">Send your inquiry to the Scholarship Office. Please be clear and specific.</small>
        </div>

        <div class="d-flex gap-2">
            <a href="<?php echo e(route('questions.my')); ?>" class="btn btn-bisu-secondary">
                My Questions
            </a>
        </div>

    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success border-0 shadow-sm">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger border-0 shadow-sm">
            <div class="fw-semibold mb-1">Please fix the following:</div>
            <ul class="mb-0 ps-3">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="small"><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <form action="<?php echo e(route('questions.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <label for="question_text" class="form-label fw-semibold" style="color:#003366;">
                        Your question
                    </label>
                    <textarea
                        name="question_text"
                        id="question_text"
                        rows="5"
                        class="form-control <?php $__errorArgs = ['question_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="Example: What are the requirements for TDP scholarship this semester?"
                        required
                    ><?php echo e(old('question_text')); ?></textarea>

                    <?php $__errorArgs = ['question_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <div class="form-text text-muted mt-1">
                        Tip: Include scholarship name, semester, and your concern to get a faster answer.
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end">
                    <button type="reset" class="btn btn-bisu-secondary">
                        Clear
                    </button>
                    <button type="submit" class="btn btn-bisu-primary">
                        Submit Question
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <?php if(isset($myQuestions)): ?>
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h5 class="fw-semibold mb-0" style="color:#003366;">My Questions</h5>
            <small class="text-muted">Your recent inquiries</small>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $myQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card border-0 shadow-sm mb-2">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div class="w-100">
                            <div class="text-muted small mb-1">
                                <?php echo e($q->created_at ? $q->created_at->format('M d, Y • h:i A') : ''); ?>

                            </div>
                            <div style="white-space: pre-line;">
                                <?php echo e($q->question_text); ?>

                            </div>
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
                    </div>

                    
                    <?php if(!empty($q->answer)): ?>
                        <div class="mt-2 p-2 rounded" style="background:#f8f9fa;">
                            <div class="small fw-semibold" style="color:#003366;">Answer:</div>
                            <div class="text-muted small" style="white-space: pre-line;">
                                <?php echo e($q->answer); ?>

                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-4 text-muted">
                You have no questions yet.
            </div>
        <?php endif; ?>

        <?php if(method_exists($myQuestions, 'links')): ?>
            <div class="d-flex justify-content-center mt-3">
                <?php echo e($myQuestions->links()); ?>

            </div>
        <?php endif; ?>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/ask.blade.php ENDPATH**/ ?>