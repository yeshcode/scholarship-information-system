

<style>
    /* Compact table (more rows visible) */
    .table-compact th,
    .table-compact td {
        padding: 0.35rem 0.45rem !important;
        font-size: 0.82rem;
        vertical-align: middle;
        white-space: nowrap;
    }

    .table-compact thead th {
        font-size: 0.75rem;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    /* Make action buttons smaller */
    .btn-compact {
        padding: 0.2rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1.2;
    }

    /* Blue header like your design */
    .thead-bisu {
        background-color: #003366;
        color: #fff;
    }
</style>

<div class="p-3">

    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title-blue mb-0">Manage System Users</h1>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success py-2 mb-3">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger py-2 mb-3">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    
    <div class="d-flex justify-content-end gap-2 mb-3">
        <a href="<?php echo e(route('admin.users.create')); ?>"
           class="btn btn-primary btn-sm"
           style="background-color:#003366; border-color:#003366;">
            + Add User
        </a>

        <a href="<?php echo e(route('admin.users.bulk-upload-form')); ?>"
           class="btn btn-primary btn-sm"
           style="background-color:#003366; border-color:#003366;">
            📤 Bulk Upload Students
        </a>
    </div>

    
    <form method="GET" action="<?php echo e(route('admin.dashboard')); ?>" class="mb-3">
        <input type="hidden" name="page" value="manage-users">

        <div class="row g-2">
            
            <div class="col-12 col-md-4">
                <label class="form-label mb-1">College</label>
                <select name="college_id" class="form-select form-select-sm"
                    onchange="
                        // clear selected course when college changes
                        const courseSelect = this.form.querySelector('select[name=course_id]');
                        if (courseSelect) courseSelect.selectedIndex = 0;
                        this.form.submit();
                    ">
                    <option value="">All Colleges</option>
                    <?php $__currentLoopData = $colleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $college): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($college->id); ?>" <?php echo e(request('college_id') == $college->id ? 'selected' : ''); ?>>
                            <?php echo e($college->college_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div class="col-12 col-md-4">
                <label class="form-label mb-1">Course</label>

                <select name="course_id"
                        class="form-select form-select-sm"
                        onchange="this.form.submit()"
                        <?php if(!request('college_id')): ?> disabled <?php endif; ?>>

                    <option value="">
                        <?php echo e(request('college_id') ? 'All Courses' : 'Select a college first'); ?>

                    </option>

                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($course->id); ?>" <?php echo e(request('course_id') == $course->id ? 'selected' : ''); ?>>
                            <?php echo e($course->course_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <?php if(!request('college_id')): ?>
                    <small class="text-muted">Choose a college to load courses.</small>
                <?php endif; ?>
            </div>

            
            <div class="col-12 col-md-4">
                <label class="form-label mb-1">Year Level</label>
                <select name="year_level_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Year Levels</option>
                    <?php $__currentLoopData = $yearLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($level->id); ?>" <?php echo e(request('year_level_id') == $level->id ? 'selected' : ''); ?>>
                            <?php echo e($level->year_level_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        
        <?php if(request('college_id') || request('course_id') || request('year_level_id')): ?>
            <div class="mt-2">
                <a href="<?php echo e(route('admin.dashboard', ['page' => 'manage-users'])); ?>"
                   class="btn btn-secondary btn-sm">
                    ✖ Clear Filters
                </a>
            </div>
        <?php endif; ?>
    </form>

    
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm table-compact text-center mb-0">
                <thead class="thead-bisu">
                    <tr>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Student ID</th>
                        <th>Email</th>
                        <th>College</th>
                        <th>Course</th>
                        <th>Year Level</th>
                        <th>Status</th>
                        <th style="min-width:140px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($user->lastname); ?></td>
                            <td><?php echo e($user->firstname); ?></td>
                            <td><?php echo e($user->student_id ?? 'N/A'); ?></td>
                            <td><?php echo e($user->bisu_email); ?></td>
                            <td><?php echo e($user->college->college_name ?? 'N/A'); ?></td>
                            <td><?php echo e($user->course->course_name ?? 'N/A'); ?></td>
                            <td><?php echo e($user->yearLevel->year_level_name ?? 'N/A'); ?></td>
                            <td><?php echo e($user->status); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.users.edit', $user->id)); ?>"
                                   class="btn btn-primary btn-compact text-white"
                                   style="background-color:#003366; border-color:#003366;">
                                    Edit
                                </a>

                                <a href="<?php echo e(route('admin.users.delete', $user->id)); ?>"
                                   class="btn btn-danger btn-compact text-white">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-muted py-3">
                                No users found.
                                <a href="<?php echo e(route('admin.users.create')); ?>" class="text-primary text-decoration-underline">
                                    Add one now
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="mt-3 d-flex justify-content-center">
        <?php echo e($users->appends(request()->except('users_page'))->links()); ?>

    </div>

</div>
<?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/users.blade.php ENDPATH**/ ?>