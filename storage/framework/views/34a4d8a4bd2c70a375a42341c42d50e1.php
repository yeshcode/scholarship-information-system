

<?php $__env->startSection('content'); ?>
<h1>Cluster #<?php echo e($cluster->id); ?></h1>

<?php if(session('success')): ?>
    <div><?php echo e(session('success')); ?></div>
<?php endif; ?>

<p><strong>Label/Topic:</strong> <?php echo e($cluster->label ?? '—'); ?></p>
<p><strong>Representative Question:</strong> <?php echo e($cluster->representative_question); ?></p>

<h2>Answer for this Cluster</h2>

<form action="<?php echo e(route('clusters.answer', $cluster->id)); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <textarea name="cluster_answer" rows="4" required><?php echo e(old('cluster_answer', $cluster->cluster_answer)); ?></textarea>
    <?php $__errorArgs = ['cluster_answer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <button type="submit">Save Answer for All</button>
</form>

<h2>Questions in this Cluster</h2>
<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>Student</th>
            <th>Question</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $cluster->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($q->user->name ?? 'Student #'.$q->user_id); ?></td>
                <td><?php echo e($q->question_text); ?></td>
                <td><?php echo e($q->created_at->format('Y-m-d H:i')); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="3">No questions in this cluster.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/clusters/show.blade.php ENDPATH**/ ?>