

<?php $__env->startSection('content'); ?>
<h2>Notifications</h2>
<?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="card mb-3">
    <div class="card-body">
        <h5><?php echo e($notification->title); ?></h5>
        <p><?php echo e($notification->message); ?></p>
        <small>Sent on: <?php echo e($notification->sent_at ? $notification->sent_at->format('Y-m-d H:i') : 'N/A'); ?></small>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php echo e($notifications->links()); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/notifications.blade.php ENDPATH**/ ?>