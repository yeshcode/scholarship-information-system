

<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-bold mb-4">Edit Semester</h1>

<?php if(session('success')): ?>
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('admin.semesters.update', $semester->id)); ?>">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <input type="text" name="term" value="<?php echo e($semester->term); ?>" class="border p-2 w-full mb-4" required>
    <input type="text" name="academic_year" value="<?php echo e($semester->academic_year); ?>" class="border p-2 w-full mb-4" required>
    <input type="date" name="start_date" value="<?php echo e($semester->start_date); ?>" class="border p-2 w-full mb-4" required>
    <input type="date" name="end_date" value="<?php echo e($semester->end_date); ?>" class="border p-2 w-full mb-4" required>
    <label class="block mb-4">
        <input type="checkbox"
            name="is_current"
            value="1"
            <?php echo e($semester->is_current ? 'checked' : ''); ?>>
        <span class="ml-2 font-semibold">
            Set as Current Semester
        </span>
    </label>

    <p class="text-sm text-gray-500 mb-4">
        Note: Setting this semester as current will automatically deactivate the
        previous current semester and require students to be re-enrolled.
    </p>
    <button type="submit" class="bg-yellow-500 text-black px-4 py-2 rounded">Update Semester</button>
    <a href="<?php echo e(route('admin.dashboard', ['page' => 'semesters'])); ?>" class="ml-4 text-gray-500">Cancel</a>
</form>

<form method="POST" action="<?php echo e(route('admin.semesters.destroy', $semester->id)); ?>" class="mt-4">
    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
    <button type="submit" class="bg-red-500 text-black px-4 py-2 rounded" onclick="return confirm('Delete?')">Delete Semester</button>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/semesters-edit.blade.php ENDPATH**/ ?>