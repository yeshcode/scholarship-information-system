

<?php $__env->startSection('content'); ?>
<div class="mx-auto" style="max-width: 1100px;">

<?php
    $isAnswered = !is_null($cluster->cluster_answer) && trim($cluster->cluster_answer) !== '';
    $badgeClass = $isAnswered ? 'bg-dark' : 'bg-warning text-dark';
    $badgeText  = $isAnswered ? 'Answered' : 'Needs reply';

    $topic = $cluster->label ?: \Illuminate\Support\Str::limit($cluster->representative_question, 60);
    $total = $cluster->questions->count();

    $threshold = $threshold ?? 0.40;
?>


<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h2 class="page-title-blue mb-0">Student Inquiry Thread</h2>
        <small class="text-muted">Open forum style. Similarity threshold: <span class="fw-semibold"><?php echo e(number_format((float)$threshold, 2)); ?></span></small>
    </div>

    <div class="d-flex gap-2">
        <a href="<?php echo e(route('clusters.index', request()->only('status','q','search_mode','threshold'))); ?>"
           class="btn btn-bisu-secondary btn-sm">
            ← Back
        </a>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success border-0 shadow-sm"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger border-0 shadow-sm"><?php echo e(session('error')); ?></div>
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


<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3 p-md-4">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div class="d-flex gap-3">

                
                <div class="rounded-circle flex-shrink-0"
                     style="width:12px;height:12px;margin-top:7px; <?php echo e($isAnswered ? 'background:#111827;' : 'background:#f59e0b;'); ?>">
                </div>

                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <h5 class="fw-semibold mb-0" style="color:#003366;"><?php echo e($topic); ?></h5>
                        <span class="badge <?php echo e($badgeClass); ?>"><?php echo e($badgeText); ?></span>
                    </div>

                    <div class="text-muted">
                        <span class="fw-semibold">Representative question:</span><br>
                        <span style="white-space: pre-line;"><?php echo e($cluster->representative_question); ?></span>
                    </div>

                    <div class="mt-2 d-flex flex-wrap gap-2">
                        <span class="badge bg-light text-dark border">👥 <?php echo e($total); ?> post<?php echo e($total == 1 ? '' : 's'); ?></span>
                        <span class="badge bg-light text-dark border">🔒 Anonymous</span>
                        <?php if(!empty($cluster->created_at)): ?>
                            <span class="badge bg-light text-dark border">🕒 <?php echo e($cluster->created_at->format('M d, Y')); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <div class="small text-muted">Thread ID</div>
                <div class="fw-semibold" style="color:#003366;">#<?php echo e($cluster->id); ?></div>
            </div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3 p-md-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-semibold mb-0" style="color:#003366;">Coordinator Answer</h5>
            <small class="text-muted">Saved answer can be applied to selected new posts.</small>
        </div>

        <form action="<?php echo e(route('clusters.answer', $cluster->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="mb-3">
                <textarea name="cluster_answer"
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
                          required><?php echo e(old('cluster_answer', $cluster->cluster_answer)); ?></textarea>

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
                    Tip: Put the official steps, requirements, deadlines, and where to submit.
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo e(route('clusters.index', request()->only('status','q','search_mode','threshold'))); ?>" class="btn btn-bisu-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-bisu-primary">
                    Save / Update Answer
                </button>
            </div>
        </form>
    </div>
</div>

<?php
    $answeredAt = $cluster->cluster_answered_at ?? null;

    // New questions = created AFTER cluster_answered_at (if answered exists)
    $newQuestions = collect($cluster->questions)->filter(function($x) use ($answeredAt, $isAnswered){
        if (!$isAnswered || !$answeredAt) return false;
        return $x->created_at && $x->created_at->gt($answeredAt);
    });
?>


<?php if($isAnswered): ?>
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div>
                    <h5 class="fw-semibold mb-1" style="color:#003366;">New questions after your answer</h5>
                    <div class="text-muted">
                        Select which ones should receive the saved answer.
                    </div>
                </div>

                <div class="text-end small text-muted">
                    <?php echo e($newQuestions->count()); ?> new
                </div>
            </div>

            <?php if($newQuestions->count() > 0): ?>
                <form method="POST" action="<?php echo e(route('clusters.answer-selected', $cluster->id)); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mt-3 d-flex flex-column gap-2">
                        <?php $__currentLoopData = $newQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="d-flex gap-2 align-items-start p-2 rounded border bg-light">
                                <input type="checkbox" name="question_ids[]" value="<?php echo e($nq->id); ?>" class="form-check-input mt-1">
                                <div class="flex-grow-1">
                                    <div class="small text-muted mb-1">
                                        Posted <?php echo e($nq->created_at ? $nq->created_at->format('M d, Y • h:i A') : ''); ?>

                                    </div>
                                    <div style="white-space: pre-line;"><?php echo e($nq->question_text); ?></div>
                                </div>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-dark">
                            Apply Saved Answer to Selected
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="mt-3 text-muted">No new questions found.</div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>


<div class="d-flex align-items-center justify-content-between mb-2">
    <h5 class="fw-semibold mb-0" style="color:#003366;">Thread Posts</h5>
    <small class="text-muted"><?php echo e($total); ?> total</small>
</div>

<?php $__empty_1 = true; $__currentLoopData = $cluster->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
        $qAnswered = !empty($q->answer) && trim($q->answer) !== '';

        // ✅ similarity score from controller
        $sim = (float) ($q->sim_score ?? 0);

        // ✅ Mark "similar" in RED (adviser request)
        // Use threshold as the cut line
        $isSimilarMarked = $sim >= (float)$threshold;

        // ✅ answered indicator black (dot)
        $dot = $qAnswered ? 'background:#111827;' : 'background:#f59e0b;';

        $border = $isSimilarMarked ? 'border border-danger' : 'border';
        $bg = $isSimilarMarked ? 'bg-danger bg-opacity-10' : 'bg-white';
    ?>

    <div class="card <?php echo e($border); ?> shadow-sm mb-2 <?php echo e($bg); ?>" style="border-radius:14px;">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex justify-content-between align-items-start gap-3">

                <div class="d-flex gap-3">
                    <div class="rounded-circle flex-shrink-0" style="width:12px;height:12px;margin-top:7px; <?php echo e($dot); ?>"></div>

                    <div>
                        <div class="small text-muted mb-1">
                            Post #<?php echo e($loop->iteration); ?>

                            <?php if($q->created_at): ?> • <?php echo e($q->created_at->format('M d, Y • h:i A')); ?> <?php endif; ?>
                            <span class="ms-2 badge bg-light text-dark border">
                                Similarity: <?php echo e(number_format($sim, 2)); ?>

                            </span>
                            <?php if($isSimilarMarked): ?>
                                <span class="ms-1 badge bg-danger">Marked similar</span>
                            <?php endif; ?>
                        </div>

                        <div style="white-space: pre-line;"><?php echo e($q->question_text); ?></div>
                    </div>
                </div>

                <div class="text-end">
                    <span class="badge <?php echo e($qAnswered ? 'bg-dark' : 'bg-warning text-dark'); ?>">
                        <?php echo e($qAnswered ? 'Answered' : 'Unanswered'); ?>

                    </span>
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
                              placeholder="Write an answer for this specific post..."><?php echo e(old('answer', $q->answer)); ?></textarea>

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
                            Save Answer
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="text-center py-5 text-muted">
        No posts in this thread yet.
    </div>
<?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/clusters/show.blade.php ENDPATH**/ ?>