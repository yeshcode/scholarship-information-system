


<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">

        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-blue-800">Bulk Upload Students</h1>
                <p class="text-sm text-gray-500">
                    Upload a CSV file to register multiple students at once.
                </p>
            </div>
            <a href="<?php echo e(route('admin.dashboard', ['page' => 'manage-users'])); ?>"
               class="text-sm text-blue-600 hover:text-blue-800 underline">
                ← Back to Users
            </a>
        </div>

        <p class="mb-4 text-sm text-gray-600">
            CSV should include headers like:
            <span class="font-mono bg-gray-100 px-1 py-0.5 rounded">
                firstname, lastname, bisu_email, contact_no, student_id
            </span>.
            Extra columns will be ignored.
        </p>

        <?php if(session('success')): ?>
            <div class="bg-green-100 text-green-800 p-3 mb-4 rounded border border-green-200 text-sm">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-100 text-red-800 p-3 mb-4 rounded border border-red-200 text-sm">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded mb-4 text-sm">
                <ul class="list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('admin.users.bulk-upload')); ?>"
              enctype="multipart/form-data"
              class="space-y-5">
            <?php echo csrf_field(); ?>

            <div>
                <label for="csv_file" class="block text-sm font-medium text-gray-700">CSV File</label>
                <input type="file" name="csv_file" id="csv_file"
                       class="mt-1 border rounded w-full p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required>
                <p class="text-xs text-gray-500 mt-1">
                    Only <span class="font-mono">.csv</span> and <span class="font-mono">.txt</span> files are allowed.
                </p>
            </div>

            <div class="border-t border-gray-200 pt-4 mt-2">
                <h2 class="text-sm font-semibold text-gray-700 mb-3">Default Academic Assignment</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="college_id" class="block text-sm font-medium text-gray-700">College</label>
                        <select name="college_id" id="college_id"
                                class="mt-1 border rounded w-full p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="">Select College</option>
                            <?php $__currentLoopData = $colleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $college): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($college->id); ?>" <?php echo e(old('college_id') == $college->id ? 'selected' : ''); ?>>
                                    <?php echo e($college->college_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label for="year_level_id" class="block text-sm font-medium text-gray-700">Year Level</label>
                        <select name="year_level_id" id="year_level_id"
                                class="mt-1 border rounded w-full p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="">Select Year Level</option>
                            <?php $__currentLoopData = $yearLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($level->id); ?>" <?php echo e(old('year_level_id') == $level->id ? 'selected' : ''); ?>>
                                    <?php echo e($level->year_level_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label for="course_id" class="block text-sm font-medium text-gray-700">Course</label>
                        <select name="course_id" id="course_id"
                                class="mt-1 border rounded w-full p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="">Select Course</option>
                            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($course->id); ?>"
                                        <?php echo e(old('course_id') == $course->id ? 'selected' : ''); ?>>
                                    <?php echo e($course->course_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 mt-4">
                <a href="<?php echo e(route('admin.dashboard', ['page' => 'manage-users'])); ?>"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold rounded shadow-sm">
                    Upload Students
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // No JavaScript needed since course is selected directly
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/users-bulk-upload.blade.php ENDPATH**/ ?>