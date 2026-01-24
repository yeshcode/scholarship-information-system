


<?php $__env->startSection('content'); ?>
<style>
    .page-title-blue {
        font-weight: 700;
        font-size: 1.6rem;
        color: #003366;
        margin: 0;
    }

    .subtext {
        color: #6b7280;
        font-size: .9rem;
        margin-top: .25rem;
    }

    /* Compact table */
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

    .thead-bisu {
        background-color: #003366;
        color: #fff;
    }

    .btn-bisu {
        background-color: #003366;
        border-color: #003366;
        color: #fff;
    }
    .btn-bisu:hover { opacity: .92; }

    /* Modal */
    /* Modal backdrop */
.modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.55);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 12px; /* space around modal */
}

/* Modal container - fits screen */
.modal-card {
    background: #fff;
    width: 100%;
    max-width: 1100px;      /* wide enough */
    height: calc(100vh - 24px); /* FIT SCREEN */
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column; /* header/body/footer stacked */
    box-shadow: 0 10px 35px rgba(0,0,0,.25);
}

/* Header stays visible */
.modal-header {
    background: #003366;
    color: #fff;
    padding: .9rem 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex: 0 0 auto;
}

/* Body scrolls if content is long */
.modal-body {
    padding: 1rem;
    overflow-y: auto;
    flex: 1 1 auto;
}

/* Footer always visible */
.modal-footer {
    padding: .9rem 1rem;
    background: #f9fafb;
    display: flex;
    gap: .5rem;
    justify-content: flex-end;
    border-top: 1px solid #e5e7eb;
    flex: 0 0 auto;
}

    .pill {
        display: inline-block;
        padding: .2rem .5rem;
        border-radius: 999px;
        font-size: .78rem;
        background: #eef2ff;
        color: #1f2937;
        border: 1px solid #e5e7eb;
    }
</style>

<div class="p-3 mx-auto" style="max-width: 1200px;">

    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h1 class="page-title-blue">Bulk Semester Update (Students)</h1>
            <div class="subtext">
                Select students, choose a target semester, then confirm the update.
            </div>
        </div>

        <div class="text-md-end">
            <div class="pill">
                Current Semester:
                <strong>
                    <?php echo e($currentSemester?->term); ?> <?php echo e($currentSemester?->academic_year); ?>

                </strong>
            </div>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success py-2 mb-3"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger py-2 mb-3"><?php echo e(session('error')); ?></div>
    <?php endif; ?>



   
<form method="GET" action="<?php echo e(route('admin.enrollments.enroll-students')); ?>" class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="row g-2 align-items-end">

            
            <div class="col-12 col-md-3">
                <label class="form-label mb-1">Mode</label>
                <select name="mode" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="promote" <?php echo e(request('mode','promote') === 'promote' ? 'selected' : ''); ?>>
                        Promote / Next Semester (returning)
                    </option>
                    <option value="new" <?php echo e(request('mode') === 'new' ? 'selected' : ''); ?>>
                        New Enrollment (fresh users)
                    </option>
                </select>
            </div>

            
            <div class="col-12 col-md-3">
                <label class="form-label mb-1">Source Semester (from)</label>
                <select name="source_semester_id"
                        class="form-select form-select-sm"
                        onchange="this.form.submit()"
                        <?php echo e(request('mode','promote') === 'new' ? 'disabled' : ''); ?>>
                    <option value="">Current Semester</option>
                    <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>" <?php echo e((string)request('source_semester_id') === (string)$s->id ? 'selected' : ''); ?>>
                            <?php echo e($s->term); ?> <?php echo e($s->academic_year); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <div class="subtext">Used only for Promote mode.</div>
            </div>

            
            <div class="col-12 col-md-4">
                <label class="form-label mb-1">Search Student</label>
                <input type="text"
                       name="search"
                       value="<?php echo e(request('search')); ?>"
                       class="form-control form-control-sm"
                       placeholder="Search name, email...">
            </div>

            
            <div class="col-12 col-md-2">
                <label class="form-label mb-1">Target Semester</label>
                <select name="semester_id" class="form-select form-select-sm" required>
                    <option value="">Select target</option>
                    <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>" <?php echo e((string)request('semester_id') === (string)$s->id ? 'selected' : ''); ?>>
                            <?php echo e($s->term); ?> <?php echo e($s->academic_year); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div class="col-12 d-flex gap-2 mt-2">
                <button class="btn btn-bisu btn-sm" type="submit">Apply</button>
                <a class="btn btn-secondary btn-sm" href="<?php echo e(route('admin.enrollments.enroll-students')); ?>">Clear</a>
            </div>
        </div>

        <div class="mt-3 small text-muted">
            <strong>Auto rules:</strong>
            If target is <em>1st Semester of a new academic year</em>, year level will be promoted.
            4th year students will be marked as <strong>Graduated</strong>.
        </div>
    </div>
</form>

    
    <form id="selection-form">
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm table-compact text-center mb-0">
                    <thead class="thead-bisu">
                        <tr>
                            <th style="width:38px;">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>College</th>
                            <th>Course</th>
                            <th>Year Level</th>
                            <th style="width:110px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <input type="checkbox"
                                           class="user-checkbox"
                                           value="<?php echo e($student->id); ?>">
                                </td>
                                <td><?php echo e($student->student_id ?? 'N/A'); ?></td>
                                <td class="text-start">
                                    <?php echo e($student->lastname); ?>, <?php echo e($student->firstname); ?>

                                </td>
                                <td class="text-start"><?php echo e($student->bisu_email); ?></td>
                                <td><?php echo e($student->college->college_name ?? 'N/A'); ?></td>
                                <td><?php echo e($student->course->course_name ?? 'N/A'); ?></td>
                                <td><?php echo e($student->yearLevel->year_level_name ?? 'N/A'); ?></td>
                                <td>
                                    <span class="badge bg-secondary"><?php echo e($student->status ?? 'active'); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-muted py-3">
                                    No students found.
                                    <div class="small">
                                        (If you selected a target semester, students already enrolled in that semester may be excluded.)
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="small text-muted">
                Showing <strong><?php echo e($students->count()); ?></strong> of <strong><?php echo e($students->total()); ?></strong> students
            </div>
            <div>
                <?php echo e($students->appends(request()->query())->links()); ?>

            </div>
        </div>

        
        <div class="mt-3 d-flex gap-2">
            <button type="button" id="proceed-btn" class="btn btn-bisu btn-sm">
                Proceed to Confirm Selected
            </button>

            <a href="<?php echo e(route('admin.dashboard', ['page' => 'enrollments'])); ?>" class="btn btn-secondary btn-sm">
                Back to Enrollments
            </a>
        </div>
    </form>

</div>



<div id="confirmation-modal" class="modal-backdrop">
    <div class="modal-card">
        <div class="modal-header">
            <div>
                <strong>Confirm Update</strong>
                <div class="small" style="opacity:.9;">
                    Target:
                    <span id="target-label">
                        <?php echo e($targetSemester?->term); ?> <?php echo e($targetSemester?->academic_year); ?>

                    </span>
                </div>
            </div>

            <button type="button" class="btn btn-sm btn-light" id="cancel-x">✕</button>
        </div>

        <div class="modal-body">
            <div class="alert alert-info py-2 small mb-3">
                This will update selected students to the <strong>target semester</strong>.
                Year level promotion and graduation will be handled automatically based on your rules.
            </div>

            
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-compact mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:120px;">Student ID</th>
                            <th>Name</th>
                            <th style="width:240px;">College</th>
                            <th style="width:240px;">Course</th>
                            <th style="width:140px;">Year Level</th>
                        </tr>
                    </thead>
                    <tbody id="selected-preview-body">
                        
                    </tbody>
                </table>
            </div>

            
            <form method="POST"
                  action="<?php echo e(route('admin.enrollments.store-enroll-students')); ?>"
                  id="confirm-form"
                  class="mt-3">
                <?php echo csrf_field(); ?>

                
                <input type="hidden" name="mode" value="<?php echo e(request('mode','promote')); ?>">
                <input type="hidden" name="source_semester_id" value="<?php echo e(request('source_semester_id')); ?>">
                <input type="hidden" name="semester_id" value="<?php echo e(request('semester_id')); ?>">

                
                <div id="selected-hidden-inputs"></div>
            </form>

            <div class="small text-muted mt-2">
                If “Confirm Update” is disabled, make sure you selected a Target Semester in the filter.
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" id="cancel-btn">Cancel</button>

            
            <button type="submit"
                    form="confirm-form"
                    class="btn btn-success btn-sm"
                    id="confirm-btn">
                Confirm Update
            </button>
        </div>
    </div>
</div>


<script>
    const selectAll = document.getElementById('select-all');
    selectAll?.addEventListener('change', function () {
        document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = this.checked);
    });

    const modal = document.getElementById('confirmation-modal');
    const proceedBtn = document.getElementById('proceed-btn');
    const confirmBtn = document.getElementById('confirm-btn');

    function openModal() { modal.style.display = 'flex'; }
    function closeModal() { modal.style.display = 'none'; }

    document.getElementById('cancel-btn')?.addEventListener('click', closeModal);
    document.getElementById('cancel-x')?.addEventListener('click', closeModal);

    // close modal on backdrop click (optional)
    modal?.addEventListener('click', function(e){
        if(e.target === modal) closeModal();
    });

    proceedBtn?.addEventListener('click', function () {
        const targetSemesterId = "<?php echo e(request('semester_id')); ?>";

        if (!targetSemesterId) {
            alert('Please select a Target Semester first.');
            return;
        }

        const selected = document.querySelectorAll('.user-checkbox:checked');
        if (selected.length === 0) {
            alert('Please select at least one student.');
            return;
        }

        const previewBody = document.getElementById('selected-preview-body');
        const hiddenWrap = document.getElementById('selected-hidden-inputs');

        previewBody.innerHTML = '';
        hiddenWrap.innerHTML = '';

        selected.forEach(cb => {
            const row = cb.closest('tr');
            const studentId = row.children[1].textContent.trim();
            const name = row.children[2].textContent.trim();
            const college = row.children[4].textContent.trim();
            const course = row.children[5].textContent.trim();
            const yearLevel = row.children[6].textContent.trim();

            previewBody.innerHTML += `
                <tr>
                    <td>${studentId}</td>
                    <td>${name}</td>
                    <td>${college}</td>
                    <td>${course}</td>
                    <td>${yearLevel}</td>
                </tr>
            `;

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_users[]';
            input.value = cb.value;
            hiddenWrap.appendChild(input);
        });

        // enable confirm
        if (confirmBtn) confirmBtn.disabled = false;

        openModal();
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/enroll-students.blade.php ENDPATH**/ ?>