

<?php $__env->startSection('content'); ?>
<div class="mx-auto" style="max-width: 1100px;">

    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h2 class="page-title-blue mb-0">Student Inquiry Thread</h2>
            <small class="text-muted">
                Answer once — it applies to all similar questions in this group.
            </small>
        </div>

        <div class="d-flex gap-2">
            <a href="<?php echo e(route('clusters.index', request()->only('status','q'))); ?>"
               class="btn btn-bisu-secondary btn-sm">
                ← Back to Inquiries
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
            <div class="fw-semibold mb-1">Please fix the errors below.</div>
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php
        $isAnswered = !is_null($cluster->cluster_answer) && trim($cluster->cluster_answer) !== '';
        $badgeClass = $isAnswered ? 'bg-success' : 'bg-warning text-dark';
        $badgeText  = $isAnswered ? 'Answered' : 'Needs reply';

        $topic = $cluster->label ?: \Illuminate\Support\Str::limit($cluster->representative_question, 60);
        $total = $cluster->questions->count();
    ?>

    
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div class="d-flex gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:44px;height:44px;background:#003366;color:#fff;font-weight:700;">
                        ?
                    </div>

                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <h5 class="fw-semibold mb-0" style="color:#003366;">
                                <?php echo e($topic); ?>

                            </h5>
                            <span class="badge <?php echo e($badgeClass); ?>"><?php echo e($badgeText); ?></span>
                        </div>

                        <div class="text-muted">
                            <span class="fw-semibold">Representative question:</span><br>
                            <span style="white-space: pre-line;">
                                <?php echo e($cluster->representative_question); ?>

                            </span>
                        </div>

                        <div class="mt-2 d-flex flex-wrap gap-2">
                            <span class="badge bg-light text-dark border">👥 <?php echo e($total); ?> question<?php echo e($total == 1 ? '' : 's'); ?></span>
                            <span class="badge bg-light text-dark border">🔒 Student names hidden</span>
                            <?php if(!empty($cluster->created_at)): ?>
                                <span class="badge bg-light text-dark border">🕒 Created <?php echo e($cluster->created_at->format('M d, Y')); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <div class="small text-muted">Group ID</div>
                    <div class="fw-semibold" style="color:#003366;">
                        #<?php echo e($cluster->id); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="fw-semibold mb-0" style="color:#003366;">Coordinator Answer</h5>
                <small class="text-muted">
                    This reply will be shown to all students in this group.
                </small>
            </div>

            <form action="<?php echo e(route('clusters.answer', $cluster->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <textarea
                        name="cluster_answer"
                        rows="5"
                        class="form-control <?php $__errorArgs = ['cluster_answer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="Type your official answer here..."
                        required
                    ><?php echo e(old('cluster_answer', $cluster->cluster_answer)); ?></textarea>

                    <?php $__errorArgs = ['cluster_answer'];
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
                        Tip: Include deadlines, requirements, and where to submit documents (if applicable).
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?php echo e(route('clusters.index', request()->only('status','q'))); ?>" class="btn btn-bisu-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-bisu-primary">
                        Save Answer for All
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h5 class="fw-semibold mb-0" style="color:#003366;">Questions in this Group</h5>
        <small class="text-muted">Showing <?php echo e($total); ?> total</small>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $cluster->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $qAnswered = !empty($q->answer) && trim($q->answer) !== '';
        ?>

        <div class="card border-0 shadow-sm mb-2">
            <div class="card-body p-3 p-md-4">

                
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div class="d-flex gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:36px;height:36px;background:#eaf2ff;color:#0b3a75;font-weight:800;">
                            <?php echo e($loop->iteration); ?>

                        </div>

                        <div>
                            <div class="small text-muted mb-1">
                                Student #<?php echo e($loop->iteration); ?> •
                                <?php echo e($q->created_at ? $q->created_at->format('M d, Y • h:i A') : ''); ?>

                            </div>

                            <div style="white-space: pre-line;">
                                <?php echo e($q->question_text); ?>

                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <?php if($qAnswered): ?>
                            <span class="badge bg-success">Answered</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Unanswered</span>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="mt-3">
                    <form method="POST" action="<?php echo e(route('clusters.questions.answer', $q->id)); ?>">
                        <?php echo csrf_field(); ?>

                        <textarea name="answer"
                                  rows="3"
                                  class="form-control <?php $__errorArgs = ['answer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  placeholder="Write an answer for this specific question..."><?php echo e(old('answer', $q->answer)); ?></textarea>

                        <?php $__errorArgs = ['answer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <small class="text-muted">
                                <?php if(!empty($q->answered_at)): ?>
                                    Answered: <?php echo e($q->answered_at->format('M d, Y • h:i A')); ?>

                                <?php else: ?>
                                    Not answered yet
                                <?php endif; ?>
                            </small>

                            <button type="submit" class="btn btn-bisu-primary btn-sm">
                                Save Answer (This Question)
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-5 text-muted">
            No questions in this group yet.
        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/clusters/show.blade.php ENDPATH**/ ?>