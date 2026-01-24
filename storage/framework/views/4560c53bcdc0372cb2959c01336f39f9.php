
<?php $fullWidth = true; ?>


<?php $__env->startSection('content'); ?>

<style>
    .page-title-blue {
        font-weight: 700;
        font-size: 1.7rem;
        color: #003366;
        margin: 0;
    }
    .subtext { color: #6b7280; font-size: .9rem; }

    .table-card {
        background: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }

    /* ✅ compact rows */
    .modern-table th,
    .modern-table td {
        border: 1px solid #e5e7eb;
        padding: 6px 8px !important;
        font-size: 0.82rem;
        vertical-align: middle;
        text-align: center;
        white-space: nowrap;
    }
    .modern-table thead {
        background-color: #003366;
        color: #ffffff;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: .03em;
    }
    .modern-table tbody tr:nth-child(even) { background-color: #f9fafb; }
    .modern-table tbody tr:hover { background-color: #e8f1ff; transition: 0.15s ease-in-out; }

    .btn-bisu-primary {
        background-color: #003366;
        color: #ffffff;
        border: 1px solid #003366;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
    }
    .btn-bisu-primary:hover { background-color: #002244; border-color: #002244; color: #ffffff; }

    .btn-bisu-secondary {
        background-color: #6f42c1;
        color: #ffffff;
        border: 1px solid #6f42c1;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
    }
    .btn-bisu-secondary:hover { background-color: #59339b; border-color: #59339b; color: #ffffff; }

    .badge-status { font-size: 0.75rem; padding: 4px 8px; border-radius: 999px; }
</style>

<div class="container-fluid py-3">

    
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
        <div>
            <h2 class="page-title-blue">Manage Enrollments</h2>
            <div class="subtext">Shows students and their status for the selected semester.</div>
        </div>

        <div class="d-flex gap-2">
            <a href="<?php echo e(route('admin.enrollments.create')); ?>" class="btn btn-bisu-primary shadow-sm">
                + Add Enrollment
            </a>
            <a href="<?php echo e(route('admin.enrollments.enroll-students')); ?>" class="btn btn-bisu-secondary shadow-sm">
                📚 Enroll Students
            </a>
            <a href="<?php echo e(route('admin.enrollments.records')); ?>" class="btn btn-outline-secondary shadow-sm">
                📂 Records
            </a>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo e(session('error')); ?>

            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <form method="GET" action="<?php echo e(route('admin.dashboard')); ?>" class="mb-3">
        <input type="hidden" name="page" value="enrollments">

        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label mb-1 fw-semibold text-secondary">Semester</label>
                <select name="semester_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <?php $__currentLoopData = $semesters ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($semester->id); ?>"
                            <?php echo e((string)request('semester_id', $selectedSemesterId ?? '') === (string)$semester->id ? 'selected' : ''); ?>>
                            <?php echo e($semester->term); ?> <?php echo e($semester->academic_year); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label mb-1 fw-semibold text-secondary">College</label>
                <select name="college_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Colleges</option>
                    <?php $__currentLoopData = $colleges ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $college): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($college->id); ?>" <?php echo e(request('college_id') == $college->id ? 'selected' : ''); ?>>
                            <?php echo e($college->college_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label mb-1 fw-semibold text-secondary">Course</label>
                <select name="course_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Courses</option>
                    <?php $__currentLoopData = $courses ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($course->id); ?>" <?php echo e(request('course_id') == $course->id ? 'selected' : ''); ?>>
                            <?php echo e($course->course_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label mb-1 fw-semibold text-secondary">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <?php $__currentLoopData = $statuses ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($st); ?>" <?php echo e(request('status') === $st ? 'selected' : ''); ?>>
                            <?php echo e(strtoupper(str_replace('_',' ', $st))); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <?php if(request('college_id') || request('course_id') || request('status')): ?>
            <div class="mt-3">
                <a href="<?php echo e(route('admin.dashboard', ['page' => 'enrollments', 'semester_id' => $selectedSemesterId])); ?>"
                   class="btn btn-sm btn-outline-secondary">
                    ✖ Clear Filters
                </a>
            </div>
        <?php endif; ?>
    </form>

    
    <div class="table-card shadow-sm mt-3">
        <div class="table-responsive">
            <table class="table modern-table mb-0">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Semester</th>
                        <th>College</th>
                        <th>Course</th>
                        <th>Year Level</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $studentsForEnrollmentList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            // Derived status:
                            $status = $row->enrollment_status ?? 'not_enrolled';

                            $badge = 'bg-secondary';
                            if ($status === 'enrolled') $badge = 'bg-success';
                            elseif ($status === 'dropped') $badge = 'bg-danger';
                            elseif ($status === 'graduated') $badge = 'bg-primary';
                            elseif ($status === 'not_enrolled') $badge = 'bg-secondary';
                        ?>

                        <tr>
                            <td><?php echo e($row->student_id ?? 'N/A'); ?></td>
                            <td class="text-start"><?php echo e($row->lastname ?? 'N/A'); ?></td>
                            <td class="text-start"><?php echo e($row->firstname ?? 'N/A'); ?></td>
                            <td>
                                <?php echo e($row->sem_term ?? 'N/A'); ?>

                                <?php echo e($row->sem_academic_year ?? ''); ?>

                            </td>
                            <td><?php echo e($row->college->college_name ?? 'N/A'); ?></td>
                            <td><?php echo e($row->course->course_name ?? 'N/A'); ?></td>
                            <td><?php echo e($row->yearLevel->year_level_name ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge badge-status <?php echo e($badge); ?>">
                                    <?php echo e(strtoupper(str_replace('_',' ', $status))); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-muted py-4 text-center">
                                No students found for this filter.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <?php if(isset($studentsForEnrollmentList)): ?>
        <div class="mt-4 d-flex justify-content-center">
            <?php echo e($studentsForEnrollmentList->appends(request()->except('enrollments_page'))->links('pagination::bootstrap-4')); ?>

        </div>
    <?php endif; ?>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/enrollments.blade.php ENDPATH**/ ?>