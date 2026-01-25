

<?php $__env->startSection('page-content'); ?>

<style>
    .page-title-blue {
        font-weight: 700;
        font-size: 1.6rem;
        color: #003366;
        margin: 0;
    }
    .subtext { color: #6b7280; font-size: .9rem; }

    .btn-bisu-primary {
        background-color: #003366;
        border-color: #003366;
        color: #fff;
        font-weight: 600;
    }
    .btn-bisu-primary:hover { opacity: .92; color: #fff; }

    .thead-bisu {
        background: #003366;
        color: #fff;
        font-size: .78rem;
        letter-spacing: .03em;
        text-transform: uppercase;
    }
</style>


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


<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-2 mb-3">

    
    <div>
        <h2 class="page-title-blue">Add Scholar (Manual)</h2>
        <div class="subtext">
            Search a student first. Only students <strong>enrolled in the current semester</strong> can be added.
        </div>
    </div>

    
    <div class="d-flex flex-column align-items-md-end gap-2">

        <a href="<?php echo e(route('coordinator.manage-scholars')); ?>"
           class="btn btn-outline-secondary btn-sm">
            ← Back to Manage Scholars
        </a>

        <div>
            <?php if($currentSemester): ?>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                    Current Semester:
                    <strong><?php echo e($currentSemester->term ?? $currentSemester->semester_name); ?> <?php echo e($currentSemester->academic_year); ?></strong>
                </span>
            <?php else: ?>
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                    No current semester set
                </span>
            <?php endif; ?>
        </div>

    </div>
</div>



<div class="card shadow-sm mb-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong class="text-secondary">Search Student</strong>
        <small class="text-muted">Type name or student ID</small>
    </div>

    <div class="card-body">
        <form method="GET" action="<?php echo e(route('coordinator.scholars.create')); ?>">
            <div class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label class="form-label fw-semibold text-secondary mb-1">Search</label>
                    <input type="text" name="q" value="<?php echo e($q ?? ''); ?>"
                           class="form-control form-control-sm"
                           placeholder="Lastname, Firstname, or Student ID...">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-bisu-primary btn-sm w-100" type="submit">Search</button>
                    <a class="btn btn-outline-secondary btn-sm w-100" href="<?php echo e(route('coordinator.scholars.create')); ?>">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong class="text-secondary">Search Results</strong>
        <small class="text-muted">Click "Add Scholar" only if eligible</small>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead class="thead-bisu">
                <tr>
                    <th class="text-start">Student</th>
                    <th class="text-start">Student ID</th>
                    <th class="text-start">Course</th>
                    <th class="text-start">Year</th>
                    <th class="text-start">Enrolled (Current)</th>
                    <th class="text-start">Already Scholar?</th>
                    <th style="width:170px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(($candidates ?? collect())->count() === 0): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <?php if(($q ?? '') === ''): ?>
                                Search a student to show results.
                            <?php else: ?>
                                No matching students found.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $__currentLoopData = $candidates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $disabled = (!$c->is_enrolled_current) || ($c->is_scholar) || (!$currentSemester);
                        ?>
                        <tr>
                            <td class="text-start">
                                <?php echo e($c->user->lastname); ?>, <?php echo e($c->user->firstname); ?>

                            </td>
                            <td class="text-start"><?php echo e($c->user->student_id ?? 'N/A'); ?></td>
                            <td class="text-start"><?php echo e($c->user->course->course_name ?? 'N/A'); ?></td>
                            <td class="text-start"><?php echo e($c->user->yearLevel->year_level_name ?? 'N/A'); ?></td>
                            <td class="text-start">
                                <?php if($c->is_enrolled_current): ?>
                                    <span class="badge bg-success-subtle text-success">ENROLLED</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger">NOT ENROLLED</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-start">
                                <?php if($c->is_scholar): ?>
                                    <span class="badge bg-warning-subtle text-warning">YES</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary">NO</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button"
                                        class="btn btn-sm btn-bisu-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addScholarModal"
                                        data-student-id="<?php echo e($c->user->id); ?>"
                                        data-student-name="<?php echo e($c->user->firstname); ?> <?php echo e($c->user->lastname); ?>"
                                        <?php echo e($disabled ? 'disabled' : ''); ?>>
                                    Add Scholar
                                </button>

                                <?php if(!$currentSemester): ?>
                                    <div class="small text-muted mt-1">No current semester</div>
                                <?php elseif($c->is_scholar): ?>
                                    <div class="small text-muted mt-1">Already a scholar</div>
                                <?php elseif(!$c->is_enrolled_current): ?>
                                    <div class="small text-muted mt-1">Not enrolled current sem</div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong class="text-secondary">Scholar Records</strong>
        <small class="text-muted">Existing scholars</small>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-start">Student</th>
                    <th class="text-start">Student ID</th>
                    <th class="text-start">Scholarship</th>
                    <th class="text-start">Batch</th>
                    <th class="text-start">Semester</th>
                    <th class="text-start">Status</th>
                    <th class="text-start">Date Added</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $scholars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-start"><?php echo e($s->user->lastname ?? 'N/A'); ?>, <?php echo e($s->user->firstname ?? 'N/A'); ?></td>
                        <td class="text-start"><?php echo e($s->user->student_id ?? 'N/A'); ?></td>
                        <td class="text-start"><?php echo e($s->scholarship->scholarship_name ?? 'N/A'); ?></td>
                        <td class="text-start"><?php echo e($s->scholarshipBatch->batch_number ?? 'N/A'); ?></td>
                        <td class="text-start">
                            <?php echo e($s->scholarshipBatch->semester->term ?? ''); ?>

                            <?php echo e($s->scholarshipBatch->semester->academic_year ?? ''); ?>

                        </td>
                        <td class="text-start">
                            <span class="badge bg-secondary-subtle text-secondary">
                                <?php echo e(strtoupper($s->status ?? 'N/A')); ?>

                            </span>
                        </td>
                        <td class="text-start"><?php echo e($s->date_added ?? ''); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No scholars found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card-body pt-3">
        <?php echo e($scholars->links()); ?>

    </div>
</div>




<div class="modal fade" id="addScholarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="<?php echo e(route('coordinator.scholars.store')); ?>" class="modal-content">
            <?php echo csrf_field(); ?>

            <div class="modal-header bg-dark text-white">
                <div>
                    <div class="fw-bold">Add Scholar</div>
                    <small class="opacity-75">Confirm details then submit</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="student_id" id="modal_student_id">

                <div class="mb-2">
                    <label class="form-label fw-semibold text-secondary mb-1">Student</label>
                    <input type="text" id="modal_student_name" class="form-control form-control-sm" readonly>
                </div>

                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary mb-1">Batch</label>
                        <select name="batch_id" class="form-select form-select-sm" required>
                            <option value="">Select batch</option>
                            <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($b->id); ?>">
                                    <?php echo e($b->scholarship->scholarship_name ?? 'Scholarship'); ?> - Batch <?php echo e($b->batch_number); ?>

                                    (<?php echo e($b->semester->term ?? ''); ?> <?php echo e($b->semester->academic_year ?? ''); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary mb-1">Date Added</label>
                        <input type="date" name="date_added" class="form-control form-control-sm" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary mb-1">Status</label>
                        <select name="status" class="form-select form-select-sm" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="graduated">Graduated</option>
                        </select>
                    </div>
                </div>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger mt-3 mb-0">
                        <div class="fw-semibold">Please fix the errors:</div>
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-bisu-primary btn-sm">Save Scholar</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('addScholarModal');
    if (!modal) return;

    modal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        const studentId = btn.getAttribute('data-student-id');
        const studentName = btn.getAttribute('data-student-name');

        document.getElementById('modal_student_id').value = studentId;
        document.getElementById('modal_student_name').value = studentName;
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/create-scholar.blade.php ENDPATH**/ ?>