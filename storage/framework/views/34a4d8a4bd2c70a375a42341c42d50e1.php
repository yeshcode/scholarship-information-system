

<?php $__env->startSection('content'); ?>
<style>
    :root{
        /* ✅ Brand Blues (BISU vibe) */
        --brand:#0b2e5e;       /* deep navy */
        --brand2:#114a9a;      /* hover blue */
        --brandSoft:#eaf2ff;   /* soft blue bg */
        --brandLine:#cfe0ff;   /* blue border */

        /* Neutrals */
        --muted:#6b7280;
        --bg:#f5f8ff;          /* slightly blue-ish background */
        --line:#e6edf7;        /* softer line */
        --text:#0f172a;

        /* Status colors tuned to match the blue theme */
        --success:#157347;         /* calm green */
        --successSoft:#e9f7ef;     /* soft green bg */
        --successLine:#bfe9d1;

        --warning:#b45309;         /* warm amber (not too bright) */
        --warningSoft:#fff6e9;     /* soft amber bg */
        --warningLine:#ffd9a8;

        --danger:#b42318;
        --dangerSoft:#ffeceb;
        --dangerLine:#ffd0cd;

        --infoSoft:#eef5ff;
        --infoLine:#cfe0ff;
    }

    body{ background: var(--bg); color: var(--text); }

    .page-title-blue{
        font-weight: 850;
        font-size: 1.75rem;
        color: var(--brand);
        margin: 0;
        letter-spacing:.2px;
    }
    .subtext{ color: var(--muted); font-size: .92rem; }

    .card-soft{
        border: 1px solid var(--line);
        border-radius: 16px;
        background: #fff;
    }

    .section-title{
        font-weight: 850;
        color: var(--brand);
        margin: 0;
        letter-spacing:.1px;
    }

    /* ✅ Buttons */
    .btn-bisu-primary{
        background: linear-gradient(180deg, var(--brand2), var(--brand));
        border-color: var(--brand);
        color: #fff;
    }
    .btn-bisu-primary:hover{
        background: linear-gradient(180deg, #1a56b6, var(--brand2));
        border-color: var(--brand2);
        color:#fff;
    }
    .btn-bisu-secondary{
        background: #fff;
        border: 1px solid var(--line);
        color: var(--text);
    }
    .btn-bisu-secondary:hover{
        background: #f7fbff;
        border-color: var(--brandLine);
        color: var(--brand);
    }

    /* ✅ Badges */
    .meta-badge{
        background:#fff;
        border: 1px solid var(--line);
        color: var(--text);
        font-weight: 650;
    }

    /* ✅ Quick Summary cards */
    .stat-card{
        border: 1px solid var(--line);
        border-radius: 16px;
        background: #fff;
        padding: .95rem 1.05rem;
        height: 100%;
        box-shadow: 0 .35rem 1rem rgba(15,23,42,.06);
    }
    .stat-title{
        font-size: .8rem;
        color: var(--muted);
        margin-bottom: .2rem;
    }
    .stat-value{
        font-weight: 900;
        font-size: 1.25rem;
        color: var(--text);
        line-height: 1.1;
    }
    .stat-note{
        font-size: .82rem;
        color: var(--muted);
        margin-top: .25rem;
    }

    /* ✅ Hint box */
    .hint{
        background: var(--brandSoft);
        border: 1px dashed var(--brandLine);
        border-radius: 14px;
        padding: .8rem .95rem;
        color: #274468;
        font-size: .92rem;
    }

    /* ✅ Post cards */
    .post-card{
        border: 1px solid var(--line);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 .35rem 1rem rgba(15,23,42,.06);
    }
    .post-card.is-answered{
        border-color: var(--successLine);
        background: var(--successSoft);
    }
    .post-card.is-unanswered{
        border-color: var(--warningLine);
        background: var(--warningSoft);
    }

    /* ✅ Small “Similarity” badge to look softer */
    .badge.bg-light.text-dark.border{
        background: #ffffff !important;
        border-color: var(--line) !important;
        color: #111827 !important;
        font-weight: 650;
    }
</style>

<div class="mx-auto" style="max-width: 1100px;">

<?php
    $isAnswered = !is_null($cluster->cluster_answer) && trim($cluster->cluster_answer) !== '';
    $badgeClass = $isAnswered ? 'bg-dark' : 'bg-warning text-dark';
    $badgeText  = $isAnswered ? 'Answered' : 'Needs reply';

    $topic = $cluster->label ?: \Illuminate\Support\Str::limit($cluster->representative_question, 60);
    $total = $cluster->questions->count();
    $threshold = $threshold ?? 0.40;

    // Unanswered questions in this cluster
    $unansweredQuestions = collect($cluster->questions)->filter(function($x){
        $status = strtolower(trim((string)($x->status ?? '')));
        $answer = trim((string)($x->answer ?? ''));

        // treat as unanswered if status says so OR answer is empty
        return ($status === '' || $status === 'unanswered') && $answer === '';
    });

    $answeredAt = $cluster->cluster_answered_at ?? null;
    $newQuestions = collect($cluster->questions)->filter(function($x) use ($answeredAt, $isAnswered){
        if (!$isAnswered || !$answeredAt) return false;

        $isNew = $x->created_at && $x->created_at->gt($answeredAt);

        $status = strtolower(trim((string)($x->status ?? '')));
        $answer = trim((string)($x->answer ?? ''));

        $isUnanswered = ($status === '' || $status === 'unanswered') && $answer === '';

        return $isNew && $isUnanswered;

        
    });
?>




<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h2 class="page-title-blue mb-1">Inquiry Thread</h2>
        
    </div>

    <div class="d-flex gap-2">
        <a href="<?php echo e(route('clusters.index', request()->only('status','q','search_mode','threshold'))); ?>"
           class="btn btn-bisu-secondary btn-sm">
            ← Back
        </a>
    </div>
</div>


<div class="row g-2 g-md-3 mb-3">
    <div class="col-12 col-md-3">
        <div class="stat-card shadow-sm">
            <div class="stat-title">Total posts</div>
            <div class="stat-value"><?php echo e($total); ?></div>
            <div class="stat-note">All inquiries in this thread</div>
        </div>
    </div>

    <div class="col-12 col-md-3">
        <div class="stat-card shadow-sm" style="background: var(--warningSoft); border-color: var(--warningLine);">
            <div class="stat-title">Unanswered</div>
            <div class="stat-value"><?php echo e($unansweredQuestions->count()); ?></div>
            <div class="stat-note">Still waiting for reply</div>
        </div>
    </div>

    <div class="col-12 col-md-3">
        <div class="stat-card shadow-sm" style="background: var(--successSoft); border-color: var(--successLine);">
            <div class="stat-title">New after answer</div>
            <div class="stat-value"><?php echo e($newQuestions->count()); ?></div>
            <div class="stat-note">Posted after saved answer</div>
        </div>
    </div>

    <div class="col-12 col-md-3">
        <div class="stat-card shadow-sm" style="background: var(--infoSoft); border-color: var(--infoLine);">
            <div class="stat-title">Thread status</div>
            <div class="stat-value"><?php echo e($isAnswered ? 'Answered' : 'Needs reply'); ?></div>
            <div class="stat-note">Based on saved answer</div>
        </div>
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


<div class="card-soft shadow-sm mb-3">
    <div class="card-body p-3 p-md-4">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div class="d-flex gap-3">

                <div class="rounded-circle flex-shrink-0"
                     style="width:12px;height:12px;margin-top:7px; <?php echo e($isAnswered ? 'background:#111827;' : 'background:#f59e0b;'); ?>">
                </div>

                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <h5 class="mb-0 fw-semibold" style="color: var(--brand);"><?php echo e($topic); ?></h5>
                        <span class="badge <?php echo e($badgeClass); ?>"><?php echo e($badgeText); ?></span>

                        <?php if($isAnswered && $newQuestions->count() > 0): ?>
                            <span class="badge bg-success">New Posts</span>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                <?php echo e($newQuestions->count()); ?> waiting
                            </span>
                        <?php endif; ?>
                    </div>


                    <div class="mt-2 d-flex flex-wrap gap-2">
                        <span class="badge meta-badge"><?php echo e($total); ?> post<?php echo e($total == 1 ? '' : 's'); ?></span>
                        <span class="badge meta-badge">Anonymous</span>
                        <?php if(!empty($cluster->created_at)): ?>
                            <span class="badge meta-badge"><?php echo e($cluster->created_at->format('M d, Y')); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <div class="small text-muted">Thread ID</div>
                <div class="fw-semibold mb-2" style="color:var(--brand);">#<?php echo e($cluster->id); ?></div>

                <button class="btn btn-outline-secondary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#editLabelModal">
                    Edit Topic
                </button>
            </div>
        </div>

        
    </div>
</div>


<?php if(!$isAnswered): ?>
    <div class="card-soft shadow-sm mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div>
                    <h5 class="section-title mb-1">Answer selected questions</h5>
                    
                </div>
                <div class="text-end small text-muted">
                    <?php echo e($unansweredQuestions->count()); ?> unanswered
                </div>
            </div>

            <?php if($unansweredQuestions->count() > 0): ?>
                <form method="POST" action="<?php echo e(route('clusters.bulk-answer', $cluster->id)); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mt-3">
                        <label class="form-label small text-muted mb-1">Your answer</label>
                        <textarea name="answer"
                                  rows="4"
                                  class="form-control <?php $__errorArgs = ['answer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  placeholder="Type one answer that will apply to the selected questions..."
                                  required><?php echo e(old('answer')); ?></textarea>

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

                        
                    </div>

                    <div class="mt-3 d-flex flex-column gap-2">
                        <div class="small text-muted">Select posts:</div>

                        <?php $__currentLoopData = $unansweredQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="d-flex gap-2 align-items-start p-2 rounded border bg-light">
                                <input type="checkbox"
                                       name="question_ids[]"
                                       value="<?php echo e($uq->id); ?>"
                                       class="form-check-input mt-1">
                                <div class="flex-grow-1">
                                    <div class="small text-muted mb-1">
                                        Posted <?php echo e($uq->created_at ? \Carbon\Carbon::parse($uq->created_at)->format('M d, Y • h:i A') : ''); ?>

                                    </div>
                                    <div style="white-space: pre-line;"><?php echo e($uq->question_text); ?></div>
                                </div>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-success">
                            Answer Selected
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="mt-3 text-muted">No unanswered questions found.</div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>


<?php if($isAnswered): ?>
    <div class="card-soft shadow-sm mb-3" style="background: var(--successSoft); border-color:#198754;">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div>
                    <h5 class="section-title mb-1" style="color:#146c43;">New posts after your answer</h5>
                    <div class="subtext">
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
                            <label class="d-flex gap-2 align-items-start p-2 rounded border bg-white">
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
                        <button class="btn btn-success">
                            Apply Saved Answer
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="mt-3 text-muted">No new questions found.</div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card-soft shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <h5 class="section-title mb-0">Saved answer (optional)</h5>
                    <div class="subtext">Used when applying an answer to new posts.</div>
                </div>
                <span class="badge <?php echo e($isAnswered ? 'bg-success' : 'bg-secondary'); ?>">
                    <?php echo e($isAnswered ? 'Saved' : 'Not saved'); ?>

                </span>
            </div>

            <form action="<?php echo e(route('clusters.answer', $cluster->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <textarea name="cluster_answer"
                          rows="3"
                          class="form-control <?php $__errorArgs = ['cluster_answer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          placeholder="Save the official answer here so you can reuse it for future new posts..."
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

                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn btn-bisu-primary btn-sm">
                        Save Answer
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>


<div class="d-flex align-items-center justify-content-between mb-2">
    <h5 class="section-title mb-0">Thread Posts</h5>
    <small class="text-muted"><?php echo e($total); ?> total</small>
</div>

<?php $__empty_1 = true; $__currentLoopData = $cluster->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
        $qAnswered = !empty($q->answer) && trim($q->answer) !== '';
        $sim = (float) ($q->sim_score ?? 0);
        $isSimilarMarked = $sim >= (float)$threshold;
    ?>

    <div class="post-card shadow-sm mb-2 <?php echo e($qAnswered ? 'is-answered' : 'is-unanswered'); ?>">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex justify-content-between align-items-start gap-3">
                <div class="me-2">
                    <div class="small text-muted mb-1">
                        Post #<?php echo e($loop->iteration); ?>

                        <?php if($q->created_at): ?> • <?php echo e($q->created_at->format('M d, Y • h:i A')); ?> <?php endif; ?>

                        

                        <?php if($isSimilarMarked): ?>
                            <span class="ms-1 badge bg-danger">Marked similar</span>
                        <?php endif; ?>
                    </div>

                    <div style="white-space: pre-line;"><?php echo e($q->question_text); ?></div>
                </div>

                <div class="text-end">
                    <span class="badge <?php echo e($qAnswered ? 'bg-success' : 'bg-warning text-dark'); ?>">
                        <?php echo e($qAnswered ? 'Answered' : 'Unanswered'); ?>

                    </span>
                </div>
            </div>

            
            <div class="mt-3">
                <form method="POST" action="<?php echo e(route('clusters.questions.answer', $q->id)); ?>">
                    <?php echo csrf_field(); ?>

                    <label class="form-label small text-muted mb-1">Manual reply</label>
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
                            placeholder="Write an answer for this specific post..."
                            <?php echo e($qAnswered ? 'disabled' : ''); ?>><?php echo e(old('answer', $q->answer)); ?></textarea>

                    <?php if($qAnswered): ?>
                        <div class="small text-success mt-1">
                            This post is already answered and locked.
                        </div>
                    <?php endif; ?>

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

                        <button type="submit"
                                class="btn btn-bisu-primary btn-sm"
                                <?php echo e($qAnswered ? 'disabled' : ''); ?>>
                            <?php echo e($qAnswered ? 'Locked' : 'Save Answer'); ?>

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


<div class="modal fade" id="editLabelModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:14px;">
      <form method="POST" action="<?php echo e(route('clusters.rename', $cluster->id)); ?>">
        <?php echo csrf_field(); ?>

        <div class="modal-header">
          <h5 class="modal-title">Rename Topic</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <label class="form-label small text-muted mb-1">Topic title</label>
          <input type="text"
                 name="label"
                 class="form-control"
                 value="<?php echo e($cluster->label); ?>"
                 placeholder="Enter new topic title..."
                 required>
          <div class="form-text text-muted">Keep it short and clear (example: “TES Requirements”).</div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-bisu-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-bisu-primary">
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/clusters/show.blade.php ENDPATH**/ ?>