


<?php $__env->startSection('content'); ?>

<style>
    .page-title-blue {
        font-weight: 700;
        font-size: 1.9rem;
        color: #003366;
    }
    .table-card {
        background: #ffffff;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }
    .modern-table thead {
        background-color: #003366;
        color: #ffffff;
    }
    .modern-table th,
    .modern-table td {
        border: 1px solid #e5e7eb;
        padding: 10px 12px;
        font-size: 0.9rem;
        vertical-align: middle;
        text-align: center;
    }
    .modern-table tbody tr:nth-child(even) {
        background-color: #f9fafb;
    }
    .modern-table tbody tr:hover {
        background-color: #e8f1ff;
        transition: 0.15s ease-in-out;
    }
    .badge-status {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 999px;
    }
</style>

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="page-title-blue mb-1">
                Enrollment Records – AY <?php echo e($academicYear); ?>

            </h2>
            <p class="text-muted mb-0">
                Showing all students enrolled in any semester of Academic Year <?php echo e($academicYear); ?>.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="<?php echo e(route('admin.enrollments.records')); ?>" class="btn btn-bisu-outline-primary">
                ← Back to Academic Years
            </a>
            <a href="<?php echo e(route('admin.enrollments')); ?>" class="btn btn-outline-secondary">
                Back to Manage Enrollments
            </a>
        </div>
    </div>

    <div class="table-card shadow-sm mt-3">
        <div class="table-responsive">
            <table class="table modern-table mb-0">
                <thead>
                    <tr>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Semester</th>
                        <th>Section</th>
                        <th>Course</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($enrollment->user->lastname ?? 'N/A'); ?></td>
                            <td><?php echo e($enrollment->user->firstname ?? 'N/A'); ?></td>
                            <td><?php echo e($enrollment->user->middlename ?? 'N/A'); ?></td>
                            <td>
                                <?php echo e($enrollment->semester->term ?? 'N/A'); ?>

                                <?php echo e($enrollment->semester->academic_year ?? ''); ?>

                            </td>
                            <td>
                                <?php echo e($enrollment->section->section_name ?? 'N/A'); ?>

                                (<?php echo e($enrollment->section->course->course_name ?? ''); ?>)
                            </td>
                            <td><?php echo e($enrollment->course->course_name ?? 'N/A'); ?></td>
                            <td>
                                <?php
                                    $status = strtolower($enrollment->status ?? '');
                                    $badgeClass = 'bg-secondary';

                                    if ($status === 'enrolled')      $badgeClass = 'bg-success';
                                    elseif ($status === 'graduated') $badgeClass = 'bg-primary';
                                    elseif ($status === 'not_enrolled') $badgeClass = 'bg-danger';
                                ?>
                                <span class="badge badge-status <?php echo e($badgeClass); ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $enrollment->status))); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-muted py-4">
                                No enrollment records found for AY <?php echo e($academicYear); ?>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="mt-4 d-flex justify-content-center">
        <?php echo e($enrollments->links('pagination::bootstrap-4')); ?>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/enrollment-records-year.blade.php ENDPATH**/ ?>