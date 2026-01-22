

<?php $__env->startSection('content'); ?>
<h2>Scholarships</h2>
<?php $__currentLoopData = $scholarships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scholarship): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="card mb-3">
    <div class="card-body">
        <h5><?php echo e($scholarship->scholarship_name); ?></h5>
        <p><?php echo e($scholarship->description); ?></p>
        <p><strong>Requirements:</strong> <?php echo e($scholarship->requirements); ?></p>
        <p><strong>Benefactor:</strong> <?php echo e($scholarship->benefactor); ?></p>
        <p><strong>Status:</strong> <?php echo e($scholarship->status); ?></p>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php echo e($scholarships->links()); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/scholarships.blade.php ENDPATH**/ ?>