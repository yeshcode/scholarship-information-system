

<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-bold mb-4">Edit Course</h1>

<?php if(session('success')): ?>
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('admin.courses.update', $course->id)); ?>">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <input type="text" name="course_name" value="<?php echo e($course->course_name); ?>" class="border p-2 w-full mb-4" required>
    <textarea name="course_description" placeholder="Course Description (optional)" class="border p-2 w-full mb-4" rows="3"><?php echo e($course->course_description); ?></textarea>  <!-- Added field -->
    <select name="college_id" class="border p-2 w-full mb-4" required>
        <option value="">Select College</option>
        <?php $__currentLoopData = $colleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $college): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($college->id); ?>" <?php echo e($college->id == $course->college_id ? 'selected' : ''); ?>><?php echo e($college->college_name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <button type="submit" class="bg-yellow-500 text-black px-4 py-2 rounded">Update Course</button>
    <a href="<?php echo e(route('admin.dashboard', ['page' => 'courses'])); ?>" class="ml-4 text-gray-500">Cancel</a>
</form>

<form method="POST" action="<?php echo e(route('admin.courses.destroy', $course->id)); ?>" class="mt-4">
    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
    <button type="submit" class="bg-red-500 text-black px-4 py-2 rounded" onclick="return confirm('Delete?')">Delete Course</button>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/courses-edit.blade.php ENDPATH**/ ?>