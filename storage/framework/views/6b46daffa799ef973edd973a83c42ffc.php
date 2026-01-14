


<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-blue-800">Edit User</h1>
                <p class="text-sm text-gray-500">Update user information and academic details.</p>
            </div>
            <a href="<?php echo e(route('admin.dashboard', ['page' => 'manage-users'])); ?>"
               class="text-sm text-blue-600 hover:text-blue-800 underline">
                ← Back to Users
            </a>
        </div>

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

        <form method="POST" action="<?php echo e(route('admin.users.update', $user->id)); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">BISU Email</label>
                    <input type="email" name="bisu_email"
                           value="<?php echo e(old('bisu_email', $user->bisu_email)); ?>"
                           class="mt-1 border rounded w-full p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Contact No</label>
                    <input type="text" name="contact_no"
                           value="<?php echo e(old('contact_no', $user->contact_no)); ?>"
                           class="mt-1 border rounded w-full p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" name="firstname"
                           value="<?php echo e(old('firstname', $user->firstname)); ?>"
                           class="mt-1 border rounded w-full p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="lastname"
                           value="<?php echo e(old('lastname', $user->lastname)); ?>"
                           class="mt-1 border rounded w-full p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Student ID (optional)</label>
                    <input type="text" name="student_id"
                           value="<?php echo e(old('student_id', $user->student_id)); ?>"
                           class="mt-1 border rounded w-full p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <input type="text" name="status"
                           value="<?php echo e(old('status', $user->status)); ?>"
                           class="mt-1 border rounded w-full p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">User Type</label>
                    <select name="user_type_id"
                            class="mt-1 border rounded w-full p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="">Select User Type</option>
                        <?php $__currentLoopData = $userTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->id); ?>" <?php echo e(old('user_type_id', $user->user_type_id) == $type->id ? 'selected' : ''); ?>>
                                <?php echo e($type->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            
            <div class="border-t border-gray-200 pt-4 mt-4">
                <h2 class="text-sm font-semibold text-gray-700 mb-3">Academic Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">College</label>
                        <select name="college_id"
                                class="mt-1 border rounded w-full p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select College</option>
                            <?php $__currentLoopData = $colleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $college): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($college->id); ?>" <?php echo e(old('college_id', $user->college_id) == $college->id ? 'selected' : ''); ?>>
                                    <?php echo e($college->college_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Year Level</label>
                        <select name="year_level_id"
                                class="mt-1 border rounded w-full p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Year Level</option>
                            <?php $__currentLoopData = $yearLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($level->id); ?>" <?php echo e(old('year_level_id', $user->year_level_id) == $level->id ? 'selected' : ''); ?>>
                                    <?php echo e($level->year_level_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Section</label>
                        <select name="section_id" id="section_id"
                                class="mt-1 border rounded w-full p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Section</option>
                            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($section->id); ?>"
                                        data-course="<?php echo e($section->course->course_name ?? 'N/A'); ?>"
                                        <?php echo e(old('section_id', $user->section_id) == $section->id ? 'selected' : ''); ?>>
                                    <?php echo e($section->section_name); ?>

                                    <?php if($section->course): ?>
                                        (<?php echo e($section->course->course_name); ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Course (based on Section)</label>
                        <div id="coursePreview"
                             class="mt-1 w-full p-2 text-sm border rounded bg-gray-50 text-gray-700">
                            <?php echo e($user->section->course->course_name ?? 'N/A'); ?>

                        </div>
                    </div>
                </div>
            </div>

            
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 mt-4">
                <a href="<?php echo e(route('admin.dashboard', ['page' => 'manage-users'])); ?>"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded shadow-sm">
                    Update User
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sectionSelect = document.getElementById('section_id');
        const coursePreview = document.getElementById('coursePreview');

        if (sectionSelect && coursePreview) {
            const updateCourse = () => {
                const sel = sectionSelect.options[sectionSelect.selectedIndex];
                const courseName = sel ? sel.getAttribute('data-course') : 'N/A';
                coursePreview.textContent = courseName || 'N/A';
            };

            sectionSelect.addEventListener('change', updateCourse);
            updateCourse();
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/users-edit.blade.php ENDPATH**/ ?>