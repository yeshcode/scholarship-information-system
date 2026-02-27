
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
            <button type="button" class="btn btn-bisu-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addEnrollmentModal">
                Add Enrollment
            </button>
            <a href="<?php echo e(route('admin.enrollments.enroll-students')); ?>" class="btn btn-bisu-secondary shadow-sm">
                Enroll Students
            </a>
            <a href="<?php echo e(route('admin.enrollments.records')); ?>" class="btn btn-outline-secondary shadow-sm">
                Records
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

        <div class="col-md-3">
            <label class="form-label mb-1 fw-semibold text-secondary">
                Semester
            </label>

            <select name="semester_id"
                class="form-select form-select-sm"
                onchange="this.form.submit()">

            <option value="">All Semesters</option>

            <?php
                $grouped = ($semesters ?? collect())->groupBy('academic_year');
            ?>

            <?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year => $yearSemesters): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <optgroup label="AY <?php echo e($year); ?>">
                    <?php $__currentLoopData = $yearSemesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($semester->id); ?>"
                            <?php echo e((string)request('semester_id') === (string)$semester->id ? 'selected' : ''); ?>>
                            <?php echo e($semester->term); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </optgroup>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </select>

        </div>


            <div class="col-md-3">
                <label class="form-label mb-1 fw-semibold text-secondary">College</label>
                <select name="college_id"class="form-select form-select-sm" onchange="this.form.course_id.value=''; this.form.submit()">
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
                <select name="course_id"
                        class="form-select form-select-sm"
                        onchange="this.form.submit()"
                        <?php echo e(request('college_id') ? '' : 'disabled'); ?>>
                    <option value="">
                        <?php echo e(request('college_id') ? 'All Courses' : 'Select College first'); ?>

                    </option>

                    <?php $__currentLoopData = $courses ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($course->id); ?>" <?php echo e((string)request('course_id') === (string)$course->id ? 'selected' : ''); ?>>
                            <?php echo e($course->course_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            

                <div class="col-12">
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold text-secondary">Search Student</label>
                            <input type="text"
                                name="search"
                                value="<?php echo e(request('search')); ?>"
                                class="form-control form-control-sm"
                                placeholder="Search last name, first name, student ID...">
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-sm btn-bisu-primary w-100" type="submit">Search</button>
                        </div>
                    </div>
                </div>
        </div>

        <?php if(request('college_id') || request('course_id') || request('search')): ?>
            <div class="mt-3">
                <a href="<?php echo e(route('admin.dashboard', ['page' => 'enrollments', 'semester_id' => $selectedSemesterId])); ?>"
                   class="btn btn-sm btn-outline-secondary">
                    ✖ Clear Filters
                </a>
            </div>
        <?php endif; ?>
    </form>

    
       
<?php if(empty(request('semester_id'))): ?>

    
    <?php $__empty_1 = true; $__currentLoopData = ($enrollmentGroups ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php $sem = $g['semester']; ?>

        <div class="table-card shadow-sm mt-3">
            <div class="px-3 py-2 bg-light fw-bold">
                AY <?php echo e($sem->academic_year ?? ''); ?> — <?php echo e($sem->term ?? ''); ?>

            </div>

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
                            <th style="width:110px;">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $__empty_2 = true; $__currentLoopData = ($g['rows'] ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <?php
                                $status = strtolower($enrollment->status ?? '');
                                $badge = 'bg-secondary';
                                if ($status === 'enrolled') $badge = 'bg-success';
                                elseif ($status === 'dropped') $badge = 'bg-danger';
                                elseif ($status === 'graduated') $badge = 'bg-primary';
                            ?>

                            <tr>
                                <td><?php echo e($enrollment->user->student_id ?? 'N/A'); ?></td>
                                <td class="text-start"><?php echo e($enrollment->user->lastname ?? 'N/A'); ?></td>
                                <td class="text-start"><?php echo e($enrollment->user->firstname ?? 'N/A'); ?></td>
                                <td><?php echo e($enrollment->semester->term ?? 'N/A'); ?> <?php echo e($enrollment->semester->academic_year ?? ''); ?></td>
                                <td><?php echo e($enrollment->user->college->college_name ?? 'N/A'); ?></td>
                                <td><?php echo e($enrollment->user->course->course_name ?? 'N/A'); ?></td>
                                <td><?php echo e($enrollment->yearLevel->year_level_name ?? $enrollment->user->yearLevel->year_level_name ?? 'N/A'); ?></td>
                                <td>
                                    <span class="badge badge-status <?php echo e($badge); ?>">
                                        <?php echo e(strtoupper($status)); ?>

                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.enrollments.edit', $enrollment->id)); ?>" class="btn btn-sm btn-warning">
                                        Update
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <tr>
                                <td colspan="9" class="text-muted py-4 text-center">
                                    No enrollment records found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            
            <div class="p-3 d-flex justify-content-center">
                <?php if(isset($g['rows']) && method_exists($g['rows'], 'links')): ?>
                    <?php echo e($g['rows']->links('pagination::bootstrap-4')); ?>

                <?php endif; ?>
            </div>
        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="table-card shadow-sm mt-3">
            <div class="p-4 text-center text-muted">No enrollment records found.</div>
        </div>
    <?php endif; ?>

<?php else: ?>

    
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
                        <th style="width:110px;">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = ($enrollmentRows ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $status = strtolower($enrollment->status ?? '');
                            $badge = 'bg-secondary';
                            if ($status === 'enrolled') $badge = 'bg-success';
                            elseif ($status === 'dropped') $badge = 'bg-danger';
                            elseif ($status === 'graduated') $badge = 'bg-primary';
                        ?>

                        <tr>
                            <td><?php echo e($enrollment->user->student_id ?? 'N/A'); ?></td>
                            <td class="text-start"><?php echo e($enrollment->user->lastname ?? 'N/A'); ?></td>
                            <td class="text-start"><?php echo e($enrollment->user->firstname ?? 'N/A'); ?></td>
                            <td><?php echo e($enrollment->semester->term ?? 'N/A'); ?> <?php echo e($enrollment->semester->academic_year ?? ''); ?></td>
                            <td><?php echo e($enrollment->user->college->college_name ?? 'N/A'); ?></td>
                            <td><?php echo e($enrollment->user->course->course_name ?? 'N/A'); ?></td>
                            <td><?php echo e($enrollment->yearLevel->year_level_name ?? $enrollment->user->yearLevel->year_level_name ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge badge-status <?php echo e($badge); ?>">
                                    <?php echo e(strtoupper($status)); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(route('admin.enrollments.edit', $enrollment->id)); ?>" class="btn btn-sm btn-warning">
                                    Update
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-muted py-4 text-center">
                                No enrollment records found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        <?php if(isset($enrollmentRows) && method_exists($enrollmentRows, 'links')): ?>
            <?php echo e($enrollmentRows->links('pagination::bootstrap-4')); ?>

        <?php endif; ?>
    </div>

<?php endif; ?>

</div>



<div class="modal fade" id="addEnrollmentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Add Enrollment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <ul class="nav nav-pills gap-2 mb-3" id="enrollTab">
          <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tabNew" type="button">
              New / Transferee
            </button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabPromote" type="button">
              Promotion
            </button>
          </li>
        </ul>

        <div class="tab-content">
            
            <div class="tab-pane fade show active" id="tabNew">

            <form method="POST" action="<?php echo e(route('admin.enrollments.manualNew')); ?>" id="formManualNew">
                <?php echo csrf_field(); ?>

                <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary">Semester (Target)</label>
                    <select name="semester_id" id="new_semester_id" class="form-select form-select-sm" required>
                    <option value="">Select semester</option>
                    <?php $__currentLoopData = $semesters ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($semester->id); ?>"><?php echo e($semester->term); ?> <?php echo e($semester->academic_year); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary">Type</label>
                    <select id="new_mode" class="form-select form-select-sm">
                    <option value="new">New Student</option>
                    <option value="transferee">Transferee</option>
                    </select>
                    
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary">College</label>
                    <select name="college_id" id="new_college_id" class="form-select form-select-sm" required>
                    <option value="">Select college</option>
                    <?php $__currentLoopData = $colleges ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c->id); ?>"><?php echo e($c->college_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary">Course</label>
                    <select name="course_id" id="new_course_id" class="form-select form-select-sm" required>
                    <option value="">Select college first</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary">Year Level</label>
                    <select name="year_level_id" id="new_year_level_id" class="form-select form-select-sm" required>
                    <option value="">Select year level</option>
                    <?php $__currentLoopData = \App\Models\YearLevel::orderBy('year_level_name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($yl->id); ?>"><?php echo e($yl->year_level_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <input type="hidden" name="user_id" id="new_user_id">

                <div class="col-md-7">
                <label class="form-label fw-semibold text-secondary">Search Student ID</label>
                <input type="text" id="new_student_id" class="form-control form-control-sm" placeholder="e.g., 2023-00001">
                
                </div>

                <div class="col-md-5 d-flex align-items-end">
                <button type="button" class="btn btn-bisu-primary btn-sm w-100" id="btnNewSearch">
                    Find Student
                </button>
                </div>

                <div class="col-12">
                <div class="border rounded p-3 bg-white" id="new_confirm" style="display:none;">
                    <div class="fw-bold mb-2">Student Information</div>
                    <div class="small">
                    <div><span class="text-muted">Student:</span> <span id="new_name"></span></div>
                    <div><span class="text-muted">College:</span> <span id="new_college"></span></div>
                    <div><span class="text-muted">Course:</span> <span id="new_course"></span></div>
                    <div><span class="text-muted">Year Level:</span> <span id="new_year"></span></div>
                    <div><span class="text-muted">Email:</span> <span id="new_email"></span></div>
                    </div>
                </div>

                <div class="alert alert-warning py-2 small" id="new_warn" style="display:none;"></div>
                </div>

                </div>

                <div class="modal-footer bg-light border-top mt-3">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success btn-sm">Save Enrollment</button>
                </div>
            </form>
            </div>

        
        <div class="tab-pane fade" id="tabPromote">
        <form method="POST" action="<?php echo e(route('admin.enrollments.manualPromote')); ?>" id="formManualPromote">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="user_id" id="pro_user_id">

            <div class="row g-3">
            <div class="col-md-7">
                <label class="form-label fw-semibold text-secondary">Search Student ID</label>
                <input type="text" id="pro_student_id" class="form-control form-control-sm" placeholder="e.g., 2023-00001">
            </div>
            <div class="col-md-5 d-flex align-items-end">
                <button type="button" class="btn btn-bisu-primary btn-sm w-100" id="btnProSearch">
                Find Student
                </button>
            </div>

            <div class="col-12">
                <div class="border rounded p-3 bg-white" id="pro_confirm" style="display:none;">
                <div class="fw-bold mb-2">Confirmation</div>
                <div class="small">
                    <div><span class="text-muted">Student:</span> <span id="pro_name"></span></div>
                    <div><span class="text-muted">College:</span> <span id="pro_college"></span></div>
                    <div><span class="text-muted">Course:</span> <span id="pro_course"></span></div>
                    <div><span class="text-muted">Year Level:</span> <span id="pro_year"></span></div>
                    <div><span class="text-muted">Previous Enrolled Semester:</span> <span id="pro_prev_sem"></span></div>
                </div>
                </div>

                <div class="alert alert-warning py-2 small" id="pro_warn" style="display:none;"></div>
            </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold text-secondary">Target Semester</label>
                    <select name="target_semester_id" id="pro_target_semester_id" class="form-select form-select-sm" required>
                    <option value="">Select target semester</option>
                    <?php $__currentLoopData = $semesters ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($semester->id); ?>"><?php echo e($semester->term); ?> <?php echo e($semester->academic_year); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                </div>

                <div class="modal-footer bg-light border-top mt-3">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success btn-sm" id="btnProSubmit" disabled>Promote</button>
                </div>
            </form>
        </div>
    </div>

    

 </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

  // ---------- NEW: college -> courses ----------
  const collegeSel = document.getElementById('new_college_id');
  const courseSel  = document.getElementById('new_course_id');

  async function loadCoursesByCollege() {
    const collegeId = collegeSel.value;
    courseSel.innerHTML = '<option value="">Loading...</option>';

    const url = `<?php echo e(route('admin.ajax.coursesByCollege')); ?>?college_id=${encodeURIComponent(collegeId)}`;
    const res = await fetch(url);
    const data = await res.json();

    let html = `<option value="">Select course</option>`;
    data.forEach(c => html += `<option value="${c.id}">${c.course_name}</option>`);
    courseSel.innerHTML = html;
  }

  if (collegeSel) {
    collegeSel.addEventListener('change', async () => {
      await loadCoursesByCollege();
      runEligibleSearch(); // refresh list after changing college
    });
  }

  // ---------- NEW: eligible students search ----------
  const semSel   = document.getElementById('new_semester_id');
  const yearSel  = document.getElementById('new_year_level_id');
  const qInput   = document.getElementById('new_student_q');
  const modeSel  = document.getElementById('new_mode');
  const studSel  = document.getElementById('new_student_select');

  let timer = null;

  async function runEligibleSearch(){
    const semester_id = semSel.value;
    const college_id  = collegeSel.value;
    const course_id   = courseSel.value;
    const year_level_id = yearSel.value;
    const q = qInput.value.trim();
    const mode = modeSel.value;

    if (!semester_id || !college_id) {
      studSel.innerHTML = `<option value="">Select target semester + college first...</option>`;
      return;
    }

    const params = new URLSearchParams({
      semester_id, college_id, course_id, year_level_id, q, mode
    });

    const url = `<?php echo e(route('admin.ajax.eligibleStudents')); ?>?` + params.toString();
    const res = await fetch(url);
    const data = await res.json();

    if (!data.length) {
      studSel.innerHTML = `<option value="">No eligible students found.</option>`;
      return;
    }

    studSel.innerHTML = data.map(s =>
      `<option value="${s.id}">
        ${s.name} — ${s.student_id || 'N/A'} (${s.email || 'N/A'})
      </option>`
    ).join('');
  }

  [semSel, courseSel, yearSel, modeSel].forEach(el => {
    if (!el) return;
    el.addEventListener('change', runEligibleSearch);
  });

  if (qInput) {
    qInput.addEventListener('input', () => {
      clearTimeout(timer);
      timer = setTimeout(runEligibleSearch, 250);
    });
  }

  // ---------- PROMOTION lookup ----------
  const btnSearch = document.getElementById('btnProSearch');
  const studIdIn  = document.getElementById('pro_student_id');
  const warnBox   = document.getElementById('pro_warn');
  const confirmBox= document.getElementById('pro_confirm');
  const btnSubmit = document.getElementById('btnProSubmit');

  async function lookupPromotionStudent(){
    warnBox.style.display = 'none';
    confirmBox.style.display = 'none';
    btnSubmit.disabled = true;

    const student_id = studIdIn.value.trim();
    if (!student_id) return;

    const url = `<?php echo e(route('admin.ajax.promotionStudent')); ?>?student_id=${encodeURIComponent(student_id)}`;
    const res = await fetch(url);
    const data = await res.json();

    if (!data.found) {
      warnBox.textContent = 'Student not found.';
      warnBox.style.display = 'block';
      return;
    }
    if (!data.previous) {
      warnBox.textContent = 'Student found, but has no previous enrolled semester to promote from.';
      warnBox.style.display = 'block';
      return;
    }

    document.getElementById('pro_user_id').value = data.student.id;
    document.getElementById('pro_name').textContent = `${data.student.name} (${data.student.student_id || ''})`;
    document.getElementById('pro_college').textContent = data.student.college || 'N/A';
    document.getElementById('pro_course').textContent = data.student.course || 'N/A';
    document.getElementById('pro_year').textContent = data.student.year || 'N/A';
    document.getElementById('pro_prev_sem').textContent = data.previous.semester_label || 'N/A';

    confirmBox.style.display = 'block';
    btnSubmit.disabled = false;
  }

  if (btnSearch) btnSearch.addEventListener('click', lookupPromotionStudent);


    // ---------- NEW/TANSFEREE lookup by Student ID ----------
  const btnNewSearch = document.getElementById('btnNewSearch');
  const newStudIdIn  = document.getElementById('new_student_id');
  const newWarnBox   = document.getElementById('new_warn');
  const newConfirm   = document.getElementById('new_confirm');

  async function lookupNewStudent(){
    newWarnBox.style.display = 'none';
    newConfirm.style.display = 'none';
    document.getElementById('new_user_id').value = '';

    const student_id = newStudIdIn.value.trim();
    const semester_id = semSel.value; // target semester from your dropdown

    if (!semester_id) {
      newWarnBox.textContent = 'Please select a target semester first.';
      newWarnBox.style.display = 'block';
      return;
    }

    if (!student_id) {
      newWarnBox.textContent = 'Please enter a student ID.';
      newWarnBox.style.display = 'block';
      return;
    }

    const url = `<?php echo e(route('admin.ajax.newStudentLookup')); ?>?student_id=${encodeURIComponent(student_id)}&semester_id=${encodeURIComponent(semester_id)}`;
    const res = await fetch(url);
    const data = await res.json();

    if (!data.found) {
      newWarnBox.textContent = data.message || 'Student not found.';
      newWarnBox.style.display = 'block';
      return;
    }

    // fill hidden user_id
    document.getElementById('new_user_id').value = data.student.id;

    // fill preview
    document.getElementById('new_name').textContent = `${data.student.name} (${data.student.student_id || ''})`;
    document.getElementById('new_college').textContent = data.student.college || 'N/A';
    document.getElementById('new_course').textContent  = data.student.course || 'N/A';
    document.getElementById('new_year').textContent    = data.student.year || 'N/A';
    document.getElementById('new_email').textContent   = data.student.email || 'N/A';

    newConfirm.style.display = 'block';
  }

  if (btnNewSearch) btnNewSearch.addEventListener('click', lookupNewStudent);

});
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/enrollments.blade.php ENDPATH**/ ?>