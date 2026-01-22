

<?php $__env->startSection('content'); ?>
<h2>Announcements</h2>
<?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="card mb-3">
    <div class="card-body">
        <h5><?php echo e($announcement->title); ?></h5>
        <p><?php echo e($announcement->description); ?></p>
        <small>Posted on: <?php echo e($announcement->posted_at ? $announcement->posted_at->format('Y-m-d H:i') : 'N/A'); ?></small>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php echo e($announcements->links()); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/announcements.blade.php ENDPATH**/ ?>