


<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-sm mx-auto bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-xl font-bold mb-4 text-red-600">Confirm Deletion</h1>
        <p class="text-gray-700 mb-4 text-sm">
            Are you sure you want to delete this user? This action cannot be undone.
        </p>

        <?php
            $courseName = $user->section->course->course_name ?? 'N/A';
            $yearName   = $user->yearLevel->year_level_name ?? 'N/A';
            $courseYear = ($courseName !== 'N/A' || $yearName !== 'N/A')
                            ? trim($courseName . ' - ' . $yearName, ' -')
                            : 'N/A';
        ?>

        <div class="bg-gray-50 p-4 rounded-lg mb-4 border">
            <h2 class="font-semibold text-gray-800">
                <?php echo e($user->lastname); ?>, <?php echo e($user->firstname); ?>

            </h2>
            <p class="text-sm text-gray-600 mt-1">
                Email: <span class="font-medium"><?php echo e($user->bisu_email); ?></span>
            </p>
            <p class="text-sm text-gray-600">
                Student ID: <span class="font-medium"><?php echo e($user->student_id ?? 'N/A'); ?></span>
            </p>
            <p class="text-sm text-gray-600">
                Course &amp; Year: <span class="font-medium"><?php echo e($courseYear); ?></span>
            </p>
            <p class="text-sm text-gray-600">
                College: <span class="font-medium"><?php echo e($user->college->college_name ?? 'N/A'); ?></span>
            </p>
            <p class="text-sm text-gray-600">
                Status: <span class="font-medium capitalize"><?php echo e($user->status); ?></span>
            </p>
        </div>

        <form method="POST" action="<?php echo e(route('admin.users.destroy', $user->id)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <div class="flex space-x-3">
                <button type="submit"
                        class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-200 text-sm">
                    🗑️ <span class="ml-1">Yes, Delete</span>
                </button>
                <a href="<?php echo e(route('admin.dashboard', ['page' => 'manage-users'])); ?>"
                   class="inline-flex items-center text-gray-600 hover:text-gray-800 font-medium py-2 px-4 transition duration-200 text-sm">
                    ❌ <span class="ml-1">Cancel</span>
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/users-delete.blade.php ENDPATH**/ ?>