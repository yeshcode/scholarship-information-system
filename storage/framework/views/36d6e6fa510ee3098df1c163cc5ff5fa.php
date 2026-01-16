

<?php $__env->startSection('page-content'); ?>
<h1>Scholarship Coordinator Dashboard</h1>
<p>Welcome! Manage your scholarship system here.</p>
<a href="<?php echo e(route('coordinator.manage-scholars')); ?>">Manage Scholars</a> |
<a href="<?php echo e(route('coordinator.enrolled-users')); ?>">View All Enrolled Users</a> |
<a href="<?php echo e(route('coordinator.manage-scholarships')); ?>">Manage Scholarships</a> |  <!-- Added this -->
<a href="<?php echo e(route('coordinator.scholarship-batches')); ?>">Manage Scholarship Batches</a> |
<a href="<?php echo e(route('coordinator.manage-stipends')); ?>">Manage Stipend</a> |
<a href="<?php echo e(route('coordinator.manage-stipend-releases')); ?>">Manage Stipend Release</a> |
<a href="<?php echo e(route('coordinator.manage-announcements')); ?>">Manage Announcements</a>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/dashboard.blade.php ENDPATH**/ ?>