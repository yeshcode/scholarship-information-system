

<?php $__env->startSection('content'); ?>
<div class="mx-auto" style="max-width: 720px;">
    <div class="mb-3">
        <h2 class="page-title-blue mb-0">Notifications</h2>
        <small class="text-muted">Recent updates and alerts</small>
    </div>

    <hr class="mt-2 mb-3">

    <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            // ✅ highlight ONLY if unread
            $isUnread = (isset($notification->is_read) ? !$notification->is_read : false);

            // ✅ clickable card should go to an "open" route that marks it as read
            $openUrl = route('student.notifications.open', $notification->id);
        ?>

        
        <a href="<?php echo e($openUrl); ?>" class="text-decoration-none text-dark d-block">
            <div class="card border-0 shadow-sm mb-2 <?php echo e($isUnread ? 'border-start border-4 border-primary bg-light' : ''); ?>">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-start gap-3">

                        
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:40px;height:40px;background:#003366;color:#fff;font-weight:700;">
                            🔔
                        </div>

                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <h6 class="fw-semibold mb-1" style="color:#003366;">
                                    <?php echo e($notification->title); ?>

                                </h6>

                                
                                <?php if($isUnread): ?>
                                    <span class="badge bg-primary">New</span>
                                <?php endif; ?>
                            </div>

                            <p class="text-muted mb-1" style="white-space: pre-line;">
                                <?php echo e(\Illuminate\Support\Str::limit($notification->message, 140)); ?>

                            </p>

                            <small class="text-muted">
                                <?php echo e($notification->sent_at
                                    ? $notification->sent_at->format('M d, Y • h:i A')
                                    : 'N/A'); ?>

                            </small>
                        </div>

                    </div>
                </div>
            </div>
        </a>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-5">
            <div class="mb-2" style="font-size: 2rem;">🔔</div>
            <h5 class="fw-semibold mb-1" style="color:#003366;">No notifications</h5>
            <p class="text-muted mb-0">You’re all caught up.</p>
        </div>
    <?php endif; ?>

    <?php if(method_exists($notifications, 'links')): ?>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($notifications->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/notifications.blade.php ENDPATH**/ ?>