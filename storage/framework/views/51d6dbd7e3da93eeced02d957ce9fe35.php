

<?php $__env->startSection('content'); ?>
<div class="mx-auto" style="max-width: 1100px;">

    <?php
        $status = request('status'); // answered | unanswered | null
        $q = request('q');

        $isActive = fn($value) => $status === $value ? 'active' : '';
    ?>

    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h2 class="page-title-blue mb-0">Student Inquiries</h2>
            <small class="text-muted">Grouped similar questions for faster replying.</small>
        </div>

        
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('clusters.index', ['q' => $q])); ?>"
               class="btn btn-bisu-secondary btn-sm <?php echo e(!$status ? 'active' : ''); ?>">
                All
            </a>

            <a href="<?php echo e(route('clusters.index', ['status' => 'unanswered', 'q' => $q])); ?>"
               class="btn btn-bisu-secondary btn-sm <?php echo e($isActive('unanswered')); ?>">
                Unanswered
            </a>

            <a href="<?php echo e(route('clusters.index', ['status' => 'answered', 'q' => $q])); ?>"
               class="btn btn-bisu-secondary btn-sm <?php echo e($isActive('answered')); ?>">
                Answered
            </a>
        </div>
    </div>

    
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-3">
            <form method="GET" action="<?php echo e(route('clusters.index')); ?>">
                
                <input type="hidden" name="status" value="<?php echo e($status); ?>">

                <div class="d-flex flex-column flex-md-row gap-2 align-items-md-center">
                    <div class="flex-grow-1">
                        <input type="text"
                               name="q"
                               value="<?php echo e($q); ?>"
                               class="form-control"
                               placeholder="Search a topic or keyword (e.g., stipend, requirements)">
                    </div>

                    <button class="btn btn-bisu-primary" type="submit">
                        Search
                    </button>

                    <?php if($q): ?>
                        <a class="btn btn-outline-secondary"
                           href="<?php echo e(route('clusters.index', ['status' => $status])); ?>">
                            Clear
                        </a>
                    <?php endif; ?>
                </div>

                <small class="text-muted d-block mt-2">
                    Tip: Use short labels like “TDP Requirements”, “Stipend Release”, “Eligibility”, etc.
                </small>
            </form>
        </div>
    </div>

    
    <?php $__empty_1 = true; $__currentLoopData = $clusters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cluster): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            // treat NULL + empty string as unanswered
            $isAnswered = !is_null($cluster->cluster_answer) && trim($cluster->cluster_answer) !== '';
            $badgeClass = $isAnswered ? 'bg-success' : 'bg-warning text-dark';
            $badgeText  = $isAnswered ? 'Answered' : 'Needs reply';

            $topic = $cluster->label ?: \Illuminate\Support\Str::limit($cluster->representative_question, 45);
        ?>

        <div class="card border-0 shadow-sm mb-2">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex align-items-start justify-content-between gap-3">

                    
                    <div class="d-flex gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:42px;height:42px;background:#003366;color:#fff;font-weight:700;">
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
                                <span class="fw-semibold">Example:</span>
                                <span style="white-space: pre-line;">
                                    <?php echo e(\Illuminate\Support\Str::limit($cluster->representative_question, 140)); ?>

                                </span>
                            </div>

                            <div class="mt-2 d-flex flex-wrap gap-2">
                                <span class="badge bg-light text-dark border">
                                    👥 <?php echo e($cluster->questions_count); ?> student<?php echo e($cluster->questions_count == 1 ? '' : 's'); ?>

                                </span>

                                <?php if(!empty($cluster->created_at)): ?>
                                    <span class="badge bg-light text-dark border">
                                        🕒 <?php echo e($cluster->created_at->format('M d, Y')); ?>

                                    </span>
                                <?php endif; ?>

                                <span class="badge bg-light text-dark border">
                                    🔒 Names hidden
                                </span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="text-end">
                        <a href="<?php echo e(route('clusters.show', $cluster->id)); ?>"
                           class="btn btn-bisu-primary btn-sm">
                            Open Thread
                        </a>

                        <?php if($isAnswered): ?>
                            <div class="small text-muted mt-2">Reply already posted</div>
                        <?php else: ?>
                            <div class="small text-muted mt-2">Write one reply for all</div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-5">
            <div class="mb-2" style="font-size:2rem;">📭</div>
            <h5 class="fw-semibold mb-1" style="color:#003366;">No inquiries yet</h5>
            <p class="text-muted mb-0">Student questions will appear here once submitted.</p>
        </div>
    <?php endif; ?>

    
    <?php if(method_exists($clusters, 'links')): ?>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($clusters->links()); ?>

        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/clusters/index.blade.php ENDPATH**/ ?>