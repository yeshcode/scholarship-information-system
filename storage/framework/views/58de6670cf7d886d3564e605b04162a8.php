

<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width: 900px;">

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
        <div>
            <h2 class="fw-bold" style="color:#003366;">Update Enrollment Status</h2>
            <div class="text-muted small">Student details are locked. You can only update the enrollment status.</div>
        </div>
        <a href="<?php echo e(route('admin.dashboard', ['page' => 'enrollments'])); ?>" class="btn btn-secondary btn-sm">
            Back
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success py-2"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="<?php echo e(route('admin.enrollments.update', $enrollment->id)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Student</label>
                    <input type="text"
                           class="form-control"
                           value="<?php echo e($enrollment->user->lastname ?? ''); ?>, <?php echo e($enrollment->user->firstname ?? ''); ?> (<?php echo e($enrollment->user->student_id ?? 'N/A'); ?>)"
                           readonly>
                    <input type="hidden" name="user_id" value="<?php echo e($enrollment->user_id); ?>">
                </div>

                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Semester</label>
                    <input type="text"
                           class="form-control"
                           value="<?php echo e($enrollment->semester->term ?? 'N/A'); ?> <?php echo e($enrollment->semester->academic_year ?? ''); ?>"
                           readonly>
                    <input type="hidden" name="semester_id" value="<?php echo e($enrollment->semester_id); ?>">
                </div>

                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">College</label>
                    <input type="text"
                           class="form-control"
                           value="<?php echo e($enrollment->user->college->college_name ?? 'N/A'); ?>"
                           readonly>
                </div>

                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Course</label>
                    <input type="text"
                           class="form-control"
                           value="<?php echo e($enrollment->user->course->course_name ?? 'N/A'); ?>"
                           readonly>
                    
                    <input type="hidden" name="course_id" value="<?php echo e($enrollment->course_id); ?>">
                </div>

                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Year Level</label>
                    <input type="text"
                           class="form-control"
                           value="<?php echo e($enrollment->user->yearLevel->year_level_name ?? 'N/A'); ?>"
                           readonly>
                </div>

                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="enrolled" <?php echo e($enrollment->status === 'enrolled' ? 'selected' : ''); ?>>Enrolled</option>
                        <option value="dropped" <?php echo e($enrollment->status === 'dropped' ? 'selected' : ''); ?>>Dropped</option>
                    </select>
                </div>

                <div class="d-flex gap-2 justify-content-end">
                    <a href="<?php echo e(route('admin.dashboard', ['page' => 'enrollments'])); ?>" class="btn btn-light">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-warning">
                        Update Status
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/enrollments-edit.blade.php ENDPATH**/ ?>