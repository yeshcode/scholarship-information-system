  

<?php $__env->startSection('content'); ?>


<!-- Page-Specific Content (Yielded from individual views) -->
<?php echo $__env->yieldContent('page-content'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/layouts/super-admin.blade.php ENDPATH**/ ?>