

<?php $__env->startSection('page-content'); ?>
    <?php if(session('success')): ?>
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold">Enrollment Records</h2>
    </div>

    
    <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
        <h3 class="font-semibold mb-3">Add Enrollment Record</h3>

        <form action="<?php echo e(route('coordinator.enrollment-records.add')); ?>" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <?php echo csrf_field(); ?>

            <div>
                <label class="text-sm font-medium">Student</label>
                <select name="user_id" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Select student</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u->id); ?>">
                            <?php echo e($u->lastname); ?>, <?php echo e($u->firstname); ?> (<?php echo e($u->student_id ?? 'No ID'); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="text-sm font-medium">Semester</label>
                <select name="semester_id" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Select semester</option>
                    <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>">
                            <?php echo e($s->semester_name ?? ('Semester #' . $s->id)); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['semester_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="text-sm font-medium">Course</label>
                <select name="course_id" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Select course</option>
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($course->id); ?>">
                            <?php echo e($course->course_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="text-sm font-medium">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="enrolled">Enrolled</option>
                    <option value="dropped">Dropped</option>
                    <option value="inactive">Inactive</option>
                </select>
                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                    Add Record
                </button>
            </div>
        </form>
    </div>

    
    <div class="bg-white border border-gray-200 rounded-lg overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Student</th>
                    <th class="px-4 py-3 text-left">Student ID</th>
                    <th class="px-4 py-3 text-left">Course</th>
                    <th class="px-4 py-3 text-left">Semester</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $enrolledUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $en): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-4 py-3">
                            <?php echo e($en->user->lastname ?? 'N/A'); ?>, <?php echo e($en->user->firstname ?? 'N/A'); ?>

                        </td>
                        <td class="px-4 py-3"><?php echo e($en->user->student_id ?? 'N/A'); ?></td>
                        <td class="px-4 py-3"><?php echo e($en->course->course_name ?? 'N/A'); ?></td>
                        <td class="px-4 py-3"><?php echo e($en->semester->semester_name ?? 'N/A'); ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded bg-gray-100 text-gray-700">
                                <?php echo e($en->status ?? 'N/A'); ?>

                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                            No enrollment records found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <?php echo e($enrolledUsers->links()); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/enrollment-records.blade.php ENDPATH**/ ?>