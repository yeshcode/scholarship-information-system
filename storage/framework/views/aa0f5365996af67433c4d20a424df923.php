

<div class="p-4">

    
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title-blue">
        Manage System Users
    </h1>
</div>


    
    <?php if(session('success')): ?>
        <div class="bg-green-100 text-green-800 p-4 mb-4 rounded-lg shadow-sm border border-green-200">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-100 text-red-800 p-4 mb-4 rounded-lg shadow-sm border border-red-200">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    
    <div class="flex justify-end mb-6 space-x-4">
        <a href="<?php echo e(route('admin.users.create')); ?>" 
            class="inline-flex items-center btn-bisu-primary text-white hover:bg-blue-700 
                   font-bold py-2 px-4 rounded-lg shadow-md transition duration-200">
            + Add User
        </a>

        <a href="<?php echo e(route('admin.users.bulk-upload-form')); ?>" 
            class="inline-flex items-center btn-bisu-primary text-white hover:bg-blue-600 
                   font-bold py-2 px-4 rounded-lg shadow-md transition duration-200">
            📤 Bulk Upload Students
        </a>
    </div>

    
    <form method="GET" action="<?php echo e(route('admin.dashboard')); ?>" class="mb-6">
        <input type="hidden" name="page" value="manage-users">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    College
                </label>
                <select
                    name="college_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm 
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                    onchange="this.form.submit()"
                >
                    <option value="">All Colleges</option>
                    <?php $__currentLoopData = $colleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $college): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($college->id); ?>"
                            <?php echo e(request('college_id') == $college->id ? 'selected' : ''); ?>>
                            <?php echo e($college->college_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Course
                </label>
                <select
                    name="course_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm 
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                    onchange="this.form.submit()"
                >
                    <option value="">All Courses</option>
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($course->id); ?>"
                            <?php echo e(request('course_id') == $course->id ? 'selected' : ''); ?>>
                            <?php echo e($course->course_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Year Level
                </label>
                <select
                    name="year_level_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm 
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                    onchange="this.form.submit()"
                >
                    <option value="">All Year Levels</option>
                    <?php $__currentLoopData = $yearLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($level->id); ?>"
                            <?php echo e(request('year_level_id') == $level->id ? 'selected' : ''); ?>>
                            <?php echo e($level->year_level_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

        </div>

        
        <?php if(request('college_id') || request('course_id') || request('year_level_id')): ?>
            <div class="mt-4">
                <a href="<?php echo e(route('admin.dashboard', ['page' => 'manage-users'])); ?>"
                   class="inline-block bg-gray-200 text-gray-700 px-4 py-2 rounded-lg shadow-sm 
                          hover:bg-gray-300 transition">
                    ✖ Clear Filters
                </a>
            </div>
        <?php endif; ?>

    </form>

  
    
<div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
    <table class="table-auto w-full text-center border-collapse">
        <thead>
            <tr class="bg-[#003366] text-white text-sm uppercase tracking-wide">
                <th class="px-4 py-3 border border-gray-300">Last Name</th>
                <th class="px-4 py-3 border border-gray-300">First Name</th>
                <th class="px-4 py-3 border border-gray-300">Email</th>
                <th class="px-4 py-3 border border-gray-300">College</th>
                <th class="px-4 py-3 border border-gray-300">Course</th>
                <th class="px-4 py-3 border border-gray-300">Year Level</th>
                <th class="px-4 py-3 border border-gray-300">Status</th>
                <th class="px-4 py-3 border border-gray-300">Actions</th>
            </tr>
        </thead>

        <tbody class="text-gray-700 text-sm">
            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-100 transition even:bg-gray-50">
                    <td class="px-4 py-3 border border-gray-200"><?php echo e($user->lastname); ?></td>
                    <td class="px-4 py-3 border border-gray-200"><?php echo e($user->firstname); ?></td>
                    <td class="px-4 py-3 border border-gray-200"><?php echo e($user->bisu_email); ?></td>
                    <td class="px-4 py-3 border border-gray-200"><?php echo e($user->college->college_name ?? 'N/A'); ?></td>
                    <td class="px-4 py-3 border border-gray-200"><?php echo e($user->course->course_name ?? 'N/A'); ?></td>
                    <td class="px-4 py-3 border border-gray-200"><?php echo e($user->yearLevel->year_level_name ?? 'N/A'); ?></td>
                    <td class="px-4 py-3 border border-gray-200"><?php echo e($user->status); ?></td>

                    <td class="px-4 py-3 border border-gray-200 space-x-2">
                        <a href="<?php echo e(route('admin.users.edit', $user->id)); ?>" 
                           class="btn btn-sm btn-primary text-white px-3 py-1 rounded shadow-sm"
                           style="background-color:#003366;">
                            Edit
                        </a>

                        <a href="<?php echo e(route('admin.users.delete', $user->id)); ?>" 
                           class="btn btn-sm btn-danger text-white px-3 py-1 rounded shadow-sm">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="px-4 py-5 text-gray-500 text-center">
                        No users found.
                        <a href="<?php echo e(route('admin.users.create')); ?>" 
                           class="text-blue-600 underline hover:text-blue-800">
                            Add one now
                        </a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


   

      
    <div class="mt-4 flex justify-center">
        <?php echo e($users->appends(request()->except('users_page'))->links()); ?>

    </div>

</div>
<?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/users.blade.php ENDPATH**/ ?>