

<?php $__env->startSection('content'); ?>
<?php
    use Carbon\Carbon;

    // ✅ IMPORTANT: Group the paginator's collection, not the paginator object itself
    $items = method_exists($announcements, 'getCollection')
        ? $announcements->getCollection()
        : collect($announcements);

    $grouped = $items->groupBy(function ($a) {
        // handle missing dates safely
        if (empty($a->posted_at)) return 'Date Unknown';

        $dt = $a->posted_at instanceof Carbon ? $a->posted_at : Carbon::parse($a->posted_at);

        if ($dt->isToday()) return 'Today';
        if ($dt->isYesterday()) return 'Yesterday';

        return $dt->format('M d, Y');
    });
?>

<div class="mx-auto" style="max-width: 720px;">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h2 class="page-title-blue mb-0">Announcements</h2>
            <small class="text-muted">Your updates feed.</small>
        </div>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="d-flex align-items-center my-3">
            <div class="flex-grow-1 border-top"></div>
            <span class="px-3 text-muted fw-semibold" style="font-size: .9rem;">
                <?php echo e($label); ?>

            </span>
            <div class="flex-grow-1 border-top"></div>
        </div>

        <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $dt = !empty($announcement->posted_at)
                    ? ($announcement->posted_at instanceof Carbon ? $announcement->posted_at : Carbon::parse($announcement->posted_at))
                    : null;

                $timeLabel = $dt
                    ? ($dt->isToday() || $dt->isYesterday()
                        ? $dt->format('h:i A')
                        : $dt->format('M d') . ' at ' . $dt->format('h:i A'))
                    : 'N/A';
            ?>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-3 p-md-4">

                    <div class="d-flex align-items-start gap-3 mb-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:44px;height:44px;background:#003366;color:#fff;font-weight:700;">
                            SO
                        </div>

                        <div class="w-100">
                            <div class="d-flex justify-content-between gap-2">
                                <div>
                                    <div class="fw-semibold" style="color:#003366; line-height:1.2;">
                                        Scholarship Office
                                    </div>
                                    <div class="text-muted" style="font-size:.85rem;">
                                        <?php echo e($timeLabel); ?>

                                    </div>
                                </div>

                                <?php if($dt && $dt->gt(now()->subDays(3))): ?>
                                    <span class="badge bg-success align-self-start">New</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-semibold mb-2" style="color:#1c1e21;">
                        <?php echo e($announcement->title); ?>

                    </h5>

                    <div class="text-muted" style="white-space: pre-line; font-size: .98rem;">
                        <?php echo e($announcement->description); ?>

                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-5">
            <div class="mb-2" style="font-size: 2rem;">📢</div>
            <h5 class="fw-semibold mb-1" style="color:#003366;">No announcements yet</h5>
            <p class="text-muted mb-0">Please check again later.</p>
        </div>
    <?php endif; ?>

    
    <?php if(method_exists($announcements, 'links')): ?>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($announcements->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/announcements.blade.php ENDPATH**/ ?>