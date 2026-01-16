

<?php $__env->startSection('content'); ?>
<h1>Ask a Question</h1>

<?php if(session('success')): ?>
    <div><?php echo e(session('success')); ?></div>
<?php endif; ?>

<form action="<?php echo e(route('questions.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <div>
        <label for="question_text">Your question</label>
        <textarea name="question_text" id="question_text" rows="4" required><?php echo e(old('question_text')); ?></textarea>
        <?php $__errorArgs = ['question_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <button type="submit">Submit</button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/ask.blade.php ENDPATH**/ ?>