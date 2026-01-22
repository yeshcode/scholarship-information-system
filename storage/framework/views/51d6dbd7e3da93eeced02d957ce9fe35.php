

<?php $__env->startSection('content'); ?>
<h1>Question Clusters</h1>

<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>Cluster ID</th>
            <th>Label / Topic</th>
            <th>Example Question</th>
            <th>Total Questions</th>
            <th>Answered?</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $clusters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cluster): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($cluster->id); ?></td>
                <td><?php echo e($cluster->label ?? '—'); ?></td>
                <td><?php echo e($cluster->representative_question); ?></td>
                <td><?php echo e($cluster->questions_count); ?></td>
                <td><?php echo e($cluster->cluster_answer ? 'Yes' : 'No'); ?></td>
                <td>
                    <a href="<?php echo e(route('clusters.show', $cluster->id)); ?>">View</a>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="6">No question clusters yet.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/clusters/index.blade.php ENDPATH**/ ?>