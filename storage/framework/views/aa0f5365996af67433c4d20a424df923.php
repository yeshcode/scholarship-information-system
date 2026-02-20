

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

    /* Make modal body scroll reliably */
    #addUserModal .modal-content{
    max-height: calc(100vh - 2rem);
    }

    #addUserModal .modal-body{
    overflow-y: auto;
    max-height: calc(100vh - 190px); /* header+footer allowance */
    }

    /* Optional: make it feel wider on desktop */
    @media (min-width: 992px){
    #addUserModal .modal-dialog{
        max-width: 1100px; /* adjust if you want */
    }
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
        <button type="button"
                class="btn btn-primary btn-sm"
                style="background-color:#003366; border-color:#003366;"
                data-bs-toggle="modal"
                data-bs-target="#addUserModal">
            + Add User
        </button>


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
                        <th>Middle Name</th>
                        <th>Suffix</th>
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
                            <td><?php echo e($user->middlename ?? '—'); ?></td>
                            <td><?php echo e($user->suffix ?? '—'); ?></td>
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
                            <td colspan="11" class="text-muted py-3">
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

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-fullscreen-lg-down modal-xl modal-dialog-centered modal-dialog-scrollable">


    <div class="modal-content">

      <form method="POST" action="<?php echo e(route('admin.users.store')); ?>">
        <?php echo csrf_field(); ?>

        <div class="modal-header">
          <h5 class="modal-title fw-bold text-primary">Add User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

          
          <?php if($errors->any()): ?>
            <div class="alert alert-danger small">
              <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </ul>
            </div>
          <?php endif; ?>

          <div class="row g-3">

  
  <div class="col-12 col-lg-6">
    <div class="border rounded-3 p-3 h-100">
      <div class="fw-semibold text-primary mb-2">Account Info</div>

      <div class="row g-3">
        <div class="col-12 col-md-6">
          <label class="form-label">BISU Email</label>
          <input type="email" name="bisu_email" class="form-control form-control-sm"
                 value="<?php echo e(old('bisu_email')); ?>" required>
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label">Contact No</label>
          <input type="text" name="contact_no" class="form-control form-control-sm"
                 value="<?php echo e(old('contact_no')); ?>" required>
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label">First Name</label>
          <input type="text" name="firstname" class="form-control form-control-sm"
                 value="<?php echo e(old('firstname')); ?>" required>
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label">Middle Name</label>
          <input type="text" name="middlename" class="form-control form-control-sm"
                 value="<?php echo e(old('middlename')); ?>">
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label">Suffix (optional)</label>
          <input type="text" name="suffix" class="form-control form-control-sm"
                 value="<?php echo e(old('suffix')); ?>" placeholder="Jr, Sr, III...">
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label">Last Name</label>
          <input type="text" name="lastname" class="form-control form-control-sm"
                 value="<?php echo e(old('lastname')); ?>" required>
        </div>

        <div class="col-12 col-md-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select form-select-sm">
            <option value="active" <?php echo e(old('status','active')=='active'?'selected':''); ?>>active</option>
            <option value="inactive" <?php echo e(old('status')=='inactive'?'selected':''); ?>>inactive</option>
          </select>
        </div>

        <div class="col-12 col-md-3">
          <label class="form-label">User Type</label>
          <select name="user_type_id" id="m_user_type_id" class="form-select form-select-sm" required>
            <option value="">Select</option>
            <?php $__currentLoopData = $userTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($type->id); ?>" <?php echo e(old('user_type_id')==$type->id?'selected':''); ?>>
                <?php echo e($type->name); ?>

              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-12">
          <label class="form-label">Student ID (only for Students)</label>
          <input type="text" name="student_id" id="m_student_id"
                 class="form-control form-control-sm"
                 value="<?php echo e(old('student_id')); ?>">
          <div class="form-text">For students, this will be their default password.</div>
        </div>

        <div class="col-12" id="m_password_wrapper">
          <label class="form-label">Password (for non-students)</label>
          <input type="password" name="password" id="m_password"
                 class="form-control form-control-sm">
          <div class="form-text">If Student, password will be Student ID.</div>
        </div>
      </div>
    </div>
  </div>

  
  <div class="col-12 col-lg-6">
    <div class="border rounded-3 p-3 h-100">
      <div class="fw-semibold text-primary mb-2">Academic Info (Students)</div>

      <div class="row g-3">
        <div class="col-12 col-md-4">
          <label class="form-label">College</label>
          <select name="college_id" id="m_college_id" class="form-select form-select-sm">
            <option value="">Select</option>
            <?php $__currentLoopData = $colleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $college): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($college->id); ?>" <?php echo e(old('college_id')==$college->id?'selected':''); ?>>
                <?php echo e($college->college_name); ?>

              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label">Course</label>
          <select name="course_id" id="m_course_id" class="form-select form-select-sm">
            <option value="">Select</option>
            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($course->id); ?>" <?php echo e(old('course_id')==$course->id?'selected':''); ?>>
                <?php echo e($course->course_name); ?>

              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label">Year Level</label>
          <select name="year_level_id" id="m_year_level_id" class="form-select form-select-sm">
            <option value="">Select</option>
            <?php $__currentLoopData = $yearLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($level->id); ?>" <?php echo e(old('year_level_id')==$level->id?'selected':''); ?>>
                <?php echo e($level->year_level_name); ?>

              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-12">
          <div class="alert alert-light border small mb-0">
            Tip: For non-student users, Academic Info can be left empty.
          </div>
        </div>
      </div>
    </div>
  </div>

</div>


        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm" style="background:#003366;border-color:#003366;">
            Save User
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<?php if($errors->any()): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('addUserModal');
    if(modalEl){
      const m = new bootstrap.Modal(modalEl);
      m.show();
    }
  });
</script>
<?php endif; ?>


<?php
  $studentUserTypeId = $studentUserTypeId ?? null;
?>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    const studentUserTypeId = <?php echo json_encode($studentUserTypeId ?? null, 15, 512) ?>;


    const userType = document.getElementById('m_user_type_id');
    const passWrap = document.getElementById('m_password_wrapper');
    const passInp  = document.getElementById('m_password');

    function syncStudentMode(){
      const isStudent = userType.value == studentUserTypeId && studentUserTypeId !== null;

      // hide password for students
      if(isStudent){
        passWrap.style.display = 'none';
        passInp.required = false;
        passInp.value = '';
      } else {
        passWrap.style.display = 'block';
        passInp.required = true;
      }
    }

    userType?.addEventListener('change', syncStudentMode);
    syncStudentMode();
  });
</script>


</div>
<?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/users.blade.php ENDPATH**/ ?>