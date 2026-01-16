

<?php $__env->startSection('content'); ?>
<h1>My Questions</h1>

<?php if(session('success')): ?>
    <div><?php echo e(session('success')); ?></div>
<?php endif; ?>

<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>Date</th>
            <th>Question</th>
            <th>Status</th>
            <th>Answer</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($q->created_at->format('Y-m-d H:i')); ?></td>
                <td><?php echo e($q->question_text); ?></td>
                <td><?php echo e(ucfirst($q->status)); ?></td>
                <td><?php echo e($q->answer ?? 'No answer yet. Please wait for the office staff.'); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="4">You have not asked any questions yet.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/my-questions.blade.php ENDPATH**/ ?>