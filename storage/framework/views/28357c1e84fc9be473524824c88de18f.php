

<?php $__env->startSection('content'); ?>
<h2>Stipend History</h2>
<?php if($stipends->isEmpty()): ?>
    <p>No stipend history available.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>Release Title</th>
                <th>Amount Received</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $stipends; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stipend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($stipend->stipendRelease->title ?? 'N/A'); ?></td>
                <td><?php echo e($stipend->amount_received); ?></td>
                <td><?php echo e($stipend->status); ?></td>
                <td><?php echo e($stipend->created_at->format('Y-m-d')); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php echo e($stipends->links()); ?>

<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/stipend-history.blade.php ENDPATH**/ ?>