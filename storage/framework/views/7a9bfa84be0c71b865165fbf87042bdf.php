

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

    .table-compact th, .table-compact td {
        padding: .45rem .6rem !important;
        font-size: .86rem;
        vertical-align: middle;
        white-space: nowrap;
    }
    .thead-bisu {
        background: #003366;
        color: #fff;
        font-size: .78rem;
        letter-spacing: .03em;
        text-transform: uppercase;
    }

    .badge-pill {
        padding: .35rem .55rem;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 600;
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
        <h2 class="page-title-blue">Enrollment Records</h2>
        <div class="subtext">
            View enrollment records. Changes from System Admin (e.g., dropped) reflect here automatically.
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <span class="badge bg-light text-dark border">
            Total: <strong><?php echo e($records->total()); ?></strong>
        </span>

        <?php if($currentSemester): ?>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                Current: <strong><?php echo e($currentSemester->term); ?> <?php echo e($currentSemester->academic_year); ?></strong>
            </span>
        <?php endif; ?>
    </div>
</div>


<div class="card shadow-sm mb-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong class="text-secondary">Filters</strong>
        <small class="text-muted">Use filters to narrow records</small>
    </div>

    <div class="card-body">
        <form method="GET" action="<?php echo e(route('coordinator.enrollment-records')); ?>">
            <div class="row g-3">

                
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold text-secondary mb-1">Semester / Academic Year</label>
                    <select name="semester_id" class="form-select form-select-sm">
                        <option value="">All Semesters</option>
                        <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s->id); ?>"
                                <?php echo e((string)request('semester_id') === (string)$s->id ? 'selected' : ''); ?>>
                                <?php echo e($s->term ?? $s->semester_name ?? 'Semester'); ?> <?php echo e($s->academic_year ?? ''); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold text-secondary mb-1">College</label>
                    <select name="college_id" id="college_id" class="form-select form-select-sm">
                        <option value="">All Colleges</option>
                        <?php $__currentLoopData = $colleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($c->id); ?>"
                                <?php echo e((string)request('college_id') === (string)$c->id ? 'selected' : ''); ?>>
                                <?php echo e($c->college_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold text-secondary mb-1">Course</label>
                    <select name="course_id" id="course_id" class="form-select form-select-sm">
                        <option value="">All Courses</option>
                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($course->id); ?>"
                                data-college-id="<?php echo e($course->college_id); ?>"
                                <?php echo e((string)request('course_id') === (string)$course->id ? 'selected' : ''); ?>>
                                <?php echo e($course->course_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="form-text">Auto-filters by selected college.</div>
                </div>

                
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold text-secondary mb-1">Year Level</label>
                    <select name="year_level_id" class="form-select form-select-sm">
                        <option value="">All Year Levels</option>
                        <?php $__currentLoopData = $yearLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($yl->id); ?>"
                                <?php echo e((string)request('year_level_id') === (string)$yl->id ? 'selected' : ''); ?>>
                                <?php echo e($yl->year_level_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold text-secondary mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="enrolled"  <?php echo e(request('status') === 'enrolled' ? 'selected' : ''); ?>>ENROLLED</option>
                        <option value="dropped"   <?php echo e(request('status') === 'dropped' ? 'selected' : ''); ?>>DROPPED</option>
                        <option value="graduated" <?php echo e(request('status') === 'graduated' ? 'selected' : ''); ?>>GRADUATED</option>
                        <option value="not_enrolled" <?php echo e(request('status') === 'not_enrolled' ? 'selected' : ''); ?>>NOT ENROLLED</option>
                    </select>
                </div>

                
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold text-secondary mb-1">Search Student</label>
                    <input type="text"
                           name="q"
                           value="<?php echo e(request('q')); ?>"
                           class="form-control form-control-sm"
                           placeholder="Search last name, first name, or student ID...">
                </div>

                
                <div class="col-12 col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-bisu-primary btn-sm w-100">
                        Apply
                    </button>
                    <a href="<?php echo e(route('coordinator.enrollment-records')); ?>" class="btn btn-outline-secondary btn-sm w-100">
                        Clear
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>


<div class="d-flex justify-content-end mb-3">
    <button type="button"
            class="btn btn-bisu-primary btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#addModal">
        + Enroll / Promote Student
    </button>
</div>


<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div>
            <strong class="text-secondary">Records</strong>
            <div class="small text-muted">
                Showing <strong><?php echo e($records->count()); ?></strong> of <strong><?php echo e($records->total()); ?></strong>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0 table-compact">
            <thead class="thead-bisu">
                <tr>
                    <th class="text-start">Student ID</th>
                    <th class="text-start">Last Name</th>
                    <th class="text-start">First Name</th>
                    <th class="text-start">College</th>
                    <th class="text-start">Course</th>
                    <th class="text-start">Year Level</th>
                    <th class="text-start">Status</th>
                </tr>
            </thead>

            <tbody>
                <?php if(($recordsMode ?? 'enrollments') === 'users'): ?>
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-start"><?php echo e($u->student_id ?? 'N/A'); ?></td>
                            <td class="text-start"><?php echo e($u->lastname ?? 'N/A'); ?></td>
                            <td class="text-start"><?php echo e($u->firstname ?? 'N/A'); ?></td>
                            <td class="text-start"><?php echo e($u->college->college_name ?? 'N/A'); ?></td>
                            <td class="text-start"><?php echo e($u->course->course_name ?? 'N/A'); ?></td>
                            <td class="text-start"><?php echo e($u->yearLevel->year_level_name ?? 'N/A'); ?></td>
                            <td class="text-start">
                                <span class="badge badge-pill bg-secondary-subtle text-secondary">
                                    NOT ENROLLED
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No students found.</td>
                        </tr>
                    <?php endif; ?>
                <?php else: ?>
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $status = $row->status ?? 'N/A';
                            $badge = 'bg-secondary-subtle text-secondary';
                            if ($status === 'enrolled') $badge = 'bg-success-subtle text-success';
                            elseif ($status === 'dropped') $badge = 'bg-danger-subtle text-danger';
                            elseif ($status === 'graduated') $badge = 'bg-primary-subtle text-primary';
                        ?>

                        <tr>
                            <td class="text-start"><?php echo e($row->user->student_id ?? 'N/A'); ?></td>
                            <td class="text-start"><?php echo e($row->user->lastname ?? 'N/A'); ?></td>
                            <td class="text-start"><?php echo e($row->user->firstname ?? 'N/A'); ?></td>
                            <td class="text-start"><?php echo e($row->user->college->college_name ?? 'N/A'); ?></td>
                            <td class="text-start"><?php echo e($row->user->course->course_name ?? 'N/A'); ?></td>
                            <td class="text-start"><?php echo e($row->user->yearLevel->year_level_name ?? 'N/A'); ?></td>
                            <td class="text-start">
                                <span class="badge badge-pill <?php echo e($badge); ?>">
                                    <?php echo e(strtoupper(str_replace('_',' ', $status))); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No enrollment records found.</td>
                        </tr>
                    <?php endif; ?>
                <?php endif; ?>
                </tbody>

        </table>
    </div>
</div>


<div class="mt-3">
    <?php echo e($records->appends(request()->query())->links()); ?>

</div>




<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <div>
                    <div class="fw-bold">Enroll / Promote Student</div>
                    <small class="opacity-75">
                        Search a student, see latest enrollment, then enroll to a target semester.
                    </small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                
                <form method="GET" action="<?php echo e(route('coordinator.enrollment-records')); ?>" class="mb-3">
                    <input type="hidden" name="show_add" value="1">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold text-secondary mb-1">Search student</label>
                            <input type="text"
                                   name="modal_q"
                                   value="<?php echo e(request('modal_q')); ?>"
                                   class="form-control form-control-sm"
                                   placeholder="Type last name, first name, or student ID...">
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button class="btn btn-bisu-primary btn-sm w-100" type="submit">Search</button>
                            <a class="btn btn-outline-secondary btn-sm w-100" href="<?php echo e(route('coordinator.enrollment-records')); ?>">Clear</a>
                        </div>
                    </div>
                </form>

                
                <form method="POST" action="<?php echo e(route('coordinator.enrollment-records.enroll-one')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary mb-1">Target semester</label>
                            <select name="semester_id" class="form-select form-select-sm" required>
                                <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($s->id); ?>"
                                        <?php echo e((string)($currentSemester?->id) === (string)$s->id ? 'selected' : ''); ?>>
                                        <?php echo e($s->term ?? $s->semester_name ?? 'Semester'); ?> <?php echo e($s->academic_year ?? ''); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="form-text">Students already enrolled in current semester are disabled.</div>
                        </div>
                    </div>

                    <div class="table-responsive border rounded">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:80px;">Select</th>
                                    <th>Student</th>
                                    <th style="width:160px;">Student ID</th>
                                    <th style="width:260px;">Latest Enrollment</th>
                                    <th style="width:180px;">Eligibility</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $modalCandidates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $latestSem = $c->latest_enrollment?->semester;
                                        $latestLabel = $latestSem
                                            ? (($latestSem->term ?? $latestSem->semester_name ?? 'Semester') . ' ' . ($latestSem->academic_year ?? ''))
                                            : 'No previous record';
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="radio"
                                                   name="user_id"
                                                   value="<?php echo e($c->user->id); ?>"
                                                   <?php echo e($c->already_in_current ? 'disabled' : ''); ?>>
                                        </td>
                                        <td><?php echo e($c->user->lastname); ?>, <?php echo e($c->user->firstname); ?></td>
                                        <td><?php echo e($c->user->student_id ?? 'N/A'); ?></td>
                                        <td class="text-muted"><?php echo e($latestLabel); ?></td>
                                        <td>
                                            <?php if($c->already_in_current): ?>
                                                <span class="badge bg-danger-subtle text-danger">Already enrolled</span>
                                            <?php else: ?>
                                                <span class="badge bg-success-subtle text-success">Eligible</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            Use search above to find a student.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger small mt-2"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['semester_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger small mt-2"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <div class="mt-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm">Enroll Selected Student</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    // Dependent Course dropdown (Bootstrap version, same logic)
    document.addEventListener('DOMContentLoaded', function () {
        const collegeSelect = document.getElementById('college_id');
        const courseSelect = document.getElementById('course_id');
        if (!collegeSelect || !courseSelect) return;

        const allCourseOptions = Array.from(courseSelect.options);

        function filterCourses() {
            const selectedCollege = collegeSelect.value;
            const currentCourse = <?php echo json_encode(request('course_id'), 15, 512) ?>;

            courseSelect.innerHTML = '';
            allCourseOptions.forEach(opt => {
                if (!opt.value) {
                    courseSelect.appendChild(opt.cloneNode(true));
                    return;
                }
                const optCollegeId = opt.getAttribute('data-college-id');
                if (!selectedCollege || optCollegeId === selectedCollege) {
                    courseSelect.appendChild(opt.cloneNode(true));
                }
            });

            if (currentCourse && Array.from(courseSelect.options).some(o => o.value === currentCourse)) {
                courseSelect.value = currentCourse;
            } else {
                courseSelect.value = '';
            }
        }

        collegeSelect.addEventListener('change', function () {
            filterCourses();
            courseSelect.value = '';
        });

        filterCourses();

        // Auto-open modal after search
        <?php if(request('show_add') === '1'): ?>
            const modal = new bootstrap.Modal(document.getElementById('addModal'));
            modal.show();
        <?php endif; ?>
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/enrollment-records.blade.php ENDPATH**/ ?>