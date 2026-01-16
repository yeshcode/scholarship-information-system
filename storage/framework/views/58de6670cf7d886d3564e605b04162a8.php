

<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-bold mb-4">Edit Enrollment</h1>

<?php if(session('success')): ?>
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('admin.enrollments.update', $enrollment->id)); ?>">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <select name="user_id" class="border p-2 w-full mb-4" required>
        <option value="">Select User</option>
        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($user->id); ?>" <?php echo e($enrollment->user_id == $user->id ? 'selected' : ''); ?>><?php echo e($user->firstname); ?> <?php echo e($user->lastname); ?> (<?php echo e($user->user_id); ?>)</option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    
<label class="block text-gray-700 mb-2">Semester</label>
<p class="border p-2 w-full mb-4 bg-gray-100">
    <?php echo e($enrollment->semester->term ?? 'N/A'); ?>

    <?php echo e($enrollment->semester->academic_year ?? ''); ?>

</p>


<input type="hidden" name="semester_id" value="<?php echo e($enrollment->semester_id); ?>">

    <select name="section_id" class="border p-2 w-full mb-4" required>
        <option value="">Select Section</option>
        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($section->id); ?>" <?php echo e($enrollment->section_id == $section->id ? 'selected' : ''); ?>><?php echo e($section->section_name); ?> (<?php echo e($section->course->course_name ?? 'N/A'); ?> - <?php echo e($section->yearLevel->year_level_name ?? 'N/A'); ?>)</option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <select name="course_id" class="border p-2 w-full mb-4" required>
        <option value="">Select Course</option>
        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($course->id); ?>" <?php echo e($enrollment->course_id == $course->id ? 'selected' : ''); ?>><?php echo e($course->course_name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    
    <!-- Updated Status Dropdown (replaces the text input) -->
    <label for="status" class="block text-gray-700 mb-2">Status</label>
    <select name="status" id="status" class="border p-2 w-full mb-4" required>
        <option value="">Select Status</option>
        <option value="enrolled" <?php echo e($enrollment->status == 'enrolled' ? 'selected' : ''); ?>>Enrolled</option>
        <option value="graduated" <?php echo e($enrollment->status == 'graduated' ? 'selected' : ''); ?>>Graduated</option>
        <option value="not_enrolled" <?php echo e($enrollment->status == 'not_enrolled' ? 'selected' : ''); ?>>Not Enrolled</option>
    </select>
    
    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update Enrollment</button>
    <a href="<?php echo e(route('admin.dashboard', ['page' => 'enrollments'])); ?>" class="ml-4 text-gray-500">Cancel</a>
</form>

<form method="POST" action="<?php echo e(route('admin.enrollments.destroy', $enrollment->id)); ?>" class="mt-4">
    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('Delete?')">Delete Enrollment</button>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/enrollments-edit.blade.php ENDPATH**/ ?>