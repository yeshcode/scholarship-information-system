

<?php $__env->startSection('content'); ?>

<h1 class="text-2xl font-bold mb-4">Add Enrollment</h1>
<?php if(session('success')): ?>
<div class="bg-green-100 text-green-800 p-4 mb-4 rounded"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('admin.enrollments.store')); ?>">
    <?php echo csrf_field(); ?>
    <select name="user_id" class="border p-2 w-full mb-4" required>
        <option value="">Select User</option>
        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($user->id); ?>"><?php echo e($user->firstname); ?> <?php echo e($user->lastname); ?> (<?php echo e($user->user_id); ?>)</option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <select name="semester_id" class="border p-2 w-full mb-4" required>
        <option value="">Select Semester</option>
        <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($semester->id); ?>"><?php echo e($semester->term); ?> <?php echo e($semester->academic_year); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <select name="course_id" class="border p-2 w-full mb-4" required>
        <option value="">Select Course</option>
        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($course->id); ?>"><?php echo e($course->course_name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    
    <!-- Updated Status Dropdown (replaces the text input) -->
    <label for="status" class="block text-gray-700 mb-2">Status</label>
    <select name="status" id="status" class="border p-2 w-full mb-4" required>
        <option value="">Select Status</option>
        <option value="enrolled">Enrolled</option>
        <option value="graduated">Graduated</option>
        <option value="not_enrolled">Not Enrolled</option>
    </select>
    
    <button type="submit" class="bg-green-500 text-black px-4 py-2 rounded">Add Enrollment</button>
    <a href="<?php echo e(route('admin.dashboard', ['page' => 'enrollments'])); ?>" class="ml-4 text-gray-500">Cancel</a>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/enrollments-create.blade.php ENDPATH**/ ?>