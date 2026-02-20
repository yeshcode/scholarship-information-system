
<?php $fullWidth = true; ?>


<?php $__env->startSection('content'); ?>

<style>
    :root{
        --brand:#0b2e5e;
        --brand2:#123f85;
        --muted:#6b7280;
        --bg:#f4f7fb;
        --line:#e5e7eb;
        --danger:#b30000;
    }

    body{ background: var(--bg); }

    .page-title{
        font-weight:900;
        font-size:1.85rem;
        color:var(--brand);
        letter-spacing:.2px;
        margin:0;
    }
    .page-sub{ color:var(--muted); font-size:.92rem; }

    .table-card{
        background:#fff;
        border:1px solid var(--line);
        border-radius:16px;
        overflow:hidden;
        box-shadow: 0 14px 34px rgba(15,23,42,.07);
    }

    .modern-table thead{
        background: var(--brand);
        color:#fff;
    }
    .modern-table th, .modern-table td{
        border: 1px solid var(--line);
        padding: 12px 14px;
        font-size: .92rem;
        vertical-align: middle;
    }
    .modern-table tbody tr:nth-child(even){ background:#f9fafb; }
    .modern-table tbody tr:hover{ background:#eef6ff; transition:.12s ease; }

    .btn-bisu{
        font-weight:800;
        border-radius:12px;
        padding:.45rem .85rem;
        font-size:.85rem;
        white-space: nowrap;
    }
    .btn-bisu-primary{
        background: var(--brand);
        border:1px solid rgba(11,46,94,.35);
        color:#fff;
    }
    .btn-bisu-primary:hover{ background: var(--brand2); color:#fff; }

    .btn-bisu-outline{
        background:#fff;
        border:1px solid rgba(11,46,94,.35);
        color:var(--brand);
    }
    .btn-bisu-outline:hover{ background: var(--brand); color:#fff; }

    .btn-bisu-danger{
        background:#fff;
        border:1px solid rgba(179,0,0,.35);
        color: var(--danger);
    }
    .btn-bisu-danger:hover{ background: var(--danger); color:#fff; }

    .actions-wrap{
        display:flex;
        justify-content:center;
        gap:.4rem;
        flex-wrap:wrap;
    }

    /* badges */
    .badge-soft{
        background:#f1f5f9;
        border:1px solid var(--line);
        color:#0f172a;
        font-weight:800;
        border-radius:999px;
        padding:.35rem .65rem;
        font-size:.78rem;
    }
    .badge-current{
        background:#eaf7ef;
        border:1px solid rgba(25,135,84,.25);
        color:#198754;
        font-weight:900;
    }

    /* Modal polish */
    .modal-content{
        border:1px solid var(--line);
        border-radius:18px;
        box-shadow: 0 20px 60px rgba(0,0,0,.18);
    }
    .modal-header{
        border-bottom:1px solid var(--line);
        background: linear-gradient(180deg,#ffffff 0%,#f8fbff 100%);
    }
    .modal-title{
        font-weight:900;
        color:var(--brand);
        letter-spacing:.2px;
    }
    .form-label{
        font-weight:800;
        font-size:.9rem;
        color:#0f172a;
    }
    .form-control, .form-select{
        border-radius:14px;
        border:1px solid var(--line);
        padding:.7rem .9rem;
    }
    .form-control:focus, .form-select:focus{
        border-color: rgba(11,46,94,.35);
        box-shadow: 0 0 0 .2rem rgba(11,46,94,.12);
    }
    .hint{ font-size:.84rem; color:var(--muted); }
    .danger-box{
        border:1px solid rgba(179,0,0,.25);
        background:#fdecec;
        border-radius:14px;
        padding:.9rem 1rem;
    }

    /* Responsive */
    @media (max-width: 576px){
        .page-title{ font-size: 1.45rem; }
        .page-sub{ font-size: .86rem; }
        .modern-table th, .modern-table td{
            padding: 10px 10px;
            font-size: .88rem;
        }
        .modal-dialog{ margin: .75rem; }
        .modal-content{ border-radius: 16px; }
    }

    /* Mobile table -> cards */
    @media (max-width: 768px){
        .modern-table thead{ display:none; }
        .modern-table, .modern-table tbody, .modern-table tr, .modern-table td{
            display:block; width:100%;
        }
        .modern-table tr{
            background:#fff;
            margin: .65rem .65rem;
            border:1px solid var(--line);
            border-radius: 14px;
            overflow:hidden;
            box-shadow: 0 10px 22px rgba(15,23,42,.06);
        }
        .modern-table td{
            border:0;
            border-bottom:1px solid var(--line);
            padding: .7rem .9rem;
            text-align:left !important;
        }
        .modern-table td:last-child{
            border-bottom:0;
            background:#f9fafb;
        }
        .modern-table td[data-label]::before{
            content: attr(data-label);
            display:block;
            font-size:.78rem;
            font-weight:800;
            color: var(--muted);
            margin-bottom:.2rem;
            letter-spacing:.2px;
            text-transform: uppercase;
        }
        .actions-wrap{ justify-content:flex-start; }
    }
</style>

<div class="container py-4">

    
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 mb-3">
        <div>
            <h2 class="page-title">Manage Semesters</h2>
            <div class="page-sub">Add, edit, and manage semester records (term, academic year, and dates).</div>
        </div>

        
        <div class="d-flex justify-content-end w-100 w-md-auto">
            <button type="button"
                    class="btn btn-bisu btn-bisu-primary shadow-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#createSemesterModal">
                + Add Semester
            </button>
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

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <div class="table-card">
        <div class="table-responsive" style="max-height: calc(100vh - 260px);">
            <table class="table modern-table mb-0">
                <thead class="sticky-top">
                    <tr>
                        <th style="width:12%;">Term</th>
                        <th style="width:16%;">Academic Year</th>
                        <th style="width:16%;">Start Date</th>
                        <th style="width:16%;">End Date</th>
                        <th style="width:12%;">Current</th>
                        <th class="text-center" style="width: 240px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $semesters ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td data-label="Term" class="fw-semibold text-dark">
                            <?php echo e($semester->term); ?>

                        </td>

                        <td data-label="Academic Year" class="text-secondary">
                            <?php echo e($semester->academic_year); ?>

                        </td>

                        <td data-label="Start Date" class="text-secondary">
                            <?php echo e($semester->start_date); ?>

                        </td>

                        <td data-label="End Date" class="text-secondary">
                            <?php echo e($semester->end_date); ?>

                        </td>

                        <td data-label="Current">
                            <?php if($semester->is_current): ?>
                                <span class="badge-soft badge-current">Yes</span>
                            <?php else: ?>
                                <span class="badge-soft">No</span>
                            <?php endif; ?>
                        </td>

                        <td data-label="Actions" class="text-center">
                            <div class="actions-wrap">

                                <button type="button"
                                        class="btn btn-sm btn-bisu btn-bisu-outline"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editSemesterModal"
                                        data-id="<?php echo e($semester->id); ?>"
                                        data-term="<?php echo e($semester->term); ?>"
                                        data-academic_year="<?php echo e($semester->academic_year); ?>"
                                        data-start_date="<?php echo e($semester->start_date); ?>"
                                        data-end_date="<?php echo e($semester->end_date); ?>"
                                        data-is_current="<?php echo e($semester->is_current ? 1 : 0); ?>">
                                    ✏️ Edit
                                </button>

                                <button type="button"
                                        class="btn btn-sm btn-bisu btn-bisu-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteSemesterModal"
                                        data-id="<?php echo e($semester->id); ?>"
                                        data-term="<?php echo e($semester->term); ?>"
                                        data-academic_year="<?php echo e($semester->academic_year); ?>"
                                        data-start_date="<?php echo e($semester->start_date); ?>"
                                        data-end_date="<?php echo e($semester->end_date); ?>"
                                        data-is_current="<?php echo e($semester->is_current ? 1 : 0); ?>">
                                    🗑️ Delete
                                </button>

                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            No semesters found. Click <strong>+ Add Semester</strong> to create one.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="createSemesterModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <div>
            <h5 class="modal-title mb-0">Add Semester</h5>
            <div class="hint">Fill the term, academic year, and date range.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" action="<?php echo e(route('admin.semesters.store')); ?>">
        <?php echo csrf_field(); ?>

        <div class="modal-body">

          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label" for="create_term">Term</label>
              <input type="text" name="term" id="create_term" class="form-control"
                     placeholder="e.g. 1st Semester" required>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="create_academic_year">Academic Year</label>
              <input type="text" name="academic_year" id="create_academic_year" class="form-control"
                     placeholder="e.g. 2025-2026" required>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="create_start_date">Start Date</label>
              <input type="date" name="start_date" id="create_start_date" class="form-control" required>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="create_end_date">End Date</label>
              <input type="date" name="end_date" id="create_end_date" class="form-control" required>
            </div>

            <div class="col-12">
              <div class="form-check mt-1">
                <input class="form-check-input" type="checkbox" name="is_current" value="1" id="create_is_current">
                <label class="form-check-label fw-semibold" for="create_is_current">
                    Set as Current Semester
                </label>
              </div>
              <div class="hint mt-1">
                Note: Setting this semester as current may deactivate the previous current semester.
              </div>
            </div>
          </div>

        </div>

        <div class="modal-footer border-top-0">
          <button type="button" class="btn btn-bisu btn-bisu-outline" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-bisu btn-bisu-primary">✅ Add Semester</button>
        </div>

      </form>

    </div>
  </div>
</div>


<div class="modal fade" id="editSemesterModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <div>
            <h5 class="modal-title mb-0">Edit Semester</h5>
            <div class="hint">Update semester details.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" id="editSemesterForm" action="">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="modal-body">

          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label" for="edit_term">Term</label>
              <input type="text" name="term" id="edit_term" class="form-control" required>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="edit_academic_year">Academic Year</label>
              <input type="text" name="academic_year" id="edit_academic_year" class="form-control" required>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="edit_start_date">Start Date</label>
              <input type="date" name="start_date" id="edit_start_date" class="form-control" required>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="edit_end_date">End Date</label>
              <input type="date" name="end_date" id="edit_end_date" class="form-control" required>
            </div>

            <div class="col-12">
              <div class="form-check mt-1">
                <input class="form-check-input" type="checkbox" name="is_current" value="1" id="edit_is_current">
                <label class="form-check-label fw-semibold" for="edit_is_current">
                    Set as Current Semester
                </label>
              </div>
              <div class="hint mt-1">
                Note: Setting this semester as current may deactivate the previous current semester.
              </div>
            </div>
          </div>

        </div>

        <div class="modal-footer border-top-0">
          <button type="button" class="btn btn-bisu btn-bisu-outline" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-bisu btn-bisu-primary">💾 Save Changes</button>
        </div>

      </form>

    </div>
  </div>
</div>


<div class="modal fade" id="deleteSemesterModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <div>
            <h5 class="modal-title mb-0" style="color:#b30000;">Confirm Deletion</h5>
            <div class="hint">This action cannot be undone.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" id="deleteSemesterForm" action="">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>

        <div class="modal-body">
            <div class="danger-box">
                <div class="fw-bold mb-1" id="delete_semester_title">Semester</div>
                <div class="text-muted small" id="delete_semester_dates">Dates</div>
                <div class="text-muted small mt-1" id="delete_semester_current">Current: No</div>
            </div>

            <div class="mt-3 text-muted small">
                Deleting a semester may affect enrollment records linked to it.
            </div>
        </div>

        <div class="modal-footer border-top-0">
          <button type="button" class="btn btn-bisu btn-bisu-outline" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger fw-bold" style="border-radius:14px; padding:.6rem 1rem;">
            Yes, Delete
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){

    // EDIT MODAL FILL
    const editModal = document.getElementById('editSemesterModal');
    const editForm  = document.getElementById('editSemesterForm');

    editModal?.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        if(!btn) return;

        const id = btn.getAttribute('data-id');
        const term = btn.getAttribute('data-term') || '';
        const academicYear = btn.getAttribute('data-academic_year') || '';
        const startDate = btn.getAttribute('data-start_date') || '';
        const endDate = btn.getAttribute('data-end_date') || '';
        const isCurrent = btn.getAttribute('data-is_current') === '1';

        editForm.action = `<?php echo e(url('/admin/semesters')); ?>/${id}`;

        document.getElementById('edit_term').value = term;
        document.getElementById('edit_academic_year').value = academicYear;
        document.getElementById('edit_start_date').value = startDate;
        document.getElementById('edit_end_date').value = endDate;
        document.getElementById('edit_is_current').checked = isCurrent;
    });

    // DELETE MODAL FILL
    const deleteModal = document.getElementById('deleteSemesterModal');
    const deleteForm  = document.getElementById('deleteSemesterForm');

    deleteModal?.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        if(!btn) return;

        const id = btn.getAttribute('data-id');
        const term = btn.getAttribute('data-term') || '';
        const academicYear = btn.getAttribute('data-academic_year') || '';
        const startDate = btn.getAttribute('data-start_date') || '';
        const endDate = btn.getAttribute('data-end_date') || '';
        const isCurrent = btn.getAttribute('data-is_current') === '1';

        deleteForm.action = `<?php echo e(url('/admin/semesters')); ?>/${id}`;

        document.getElementById('delete_semester_title').textContent = `${term} - ${academicYear}`;
        document.getElementById('delete_semester_dates').textContent = `Start: ${startDate} | End: ${endDate}`;
        document.getElementById('delete_semester_current').textContent = `Current: ${isCurrent ? 'Yes' : 'No'}`;
    });

});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/semesters.blade.php ENDPATH**/ ?>