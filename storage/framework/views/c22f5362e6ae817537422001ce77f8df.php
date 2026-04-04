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
    .form-control{
        border-radius:14px;
        border:1px solid var(--line);
        padding:.7rem .9rem;
    }
    .form-control:focus{
        border-color: rgba(11,46,94,.35);
        box-shadow: 0 0 0 .2rem rgba(11,46,94,.12);
    }

    .hint{
        font-size:.84rem;
        color:var(--muted);
    }

    .danger-box{
        border:1px solid rgba(179,0,0,.25);
        background:#fdecec;
        border-radius:14px;
        padding:.9rem 1rem;
    }

    /* ===== Responsive Helpers ===== */
.actions-wrap{
    display:flex;
    justify-content:center;
    gap:.4rem;
    flex-wrap:wrap; /* ✅ prevents cutoff */
}

/* Let buttons shrink gracefully */
.actions-wrap .btn{
    white-space: nowrap;
}

/* Reduce padding a bit on small screens */
@media (max-width: 576px){
    .page-title{ font-size: 1.45rem; }
    .page-sub{ font-size: .86rem; }

    .table-card{
        border-radius: 14px;
    }

    .modern-table th,
    .modern-table td{
        padding: 10px 10px;
        font-size: .88rem;
    }

    /* Modals: more usable on phones */
    .modal-dialog{
        margin: .75rem;
    }
    .modal-content{
        border-radius: 16px;
    }
}

/* ===== Mobile Table -> Card Layout ===== */
/* On small screens, show each row as a card instead of wide table */
@media (max-width: 768px){
    .modern-table thead{
        display:none; /* hide header on mobile */
    }
    .modern-table,
    .modern-table tbody,
    .modern-table tr,
    .modern-table td{
        display:block;
        width:100%;
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

    /* Add label before each value */
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

    @media (min-width: 768px){
        .w-md-auto{ width: auto !important; }
    }
}
</style>

<div class="container py-4">

    
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 mb-3">
        <div>
            <h2 class="page-title">Manage User Types</h2>
            <div class="page-sub">Add, edit, and remove user roles used in the system.</div>
        </div>

        <div class="d-grid d-md-block">
            <button type="button"
                    class="btn btn-bisu btn-bisu-primary shadow-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#createUserTypeModal">
                Add User Type
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
                        <th style="width: 25%;">User Type Name</th>
                        <th>Description</th>
                        <th class="text-center" style="width: 220px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $userTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td data-label="User Type Name" class="fw-semibold text-dark">
                            <?php echo e($userType->name); ?>

                        </td>

                        <td data-label="Description" class="text-secondary">
                            <?php echo e($userType->description ?? 'No description'); ?>

                        </td>

                        <td data-label="Actions" class="text-center">
                            <div class="actions-wrap">
                                <button type="button"
                                        class="btn btn-sm btn-bisu btn-bisu-outline"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editUserTypeModal"
                                        data-id="<?php echo e($userType->id); ?>"
                                        data-name="<?php echo e($userType->name); ?>"
                                        data-description="<?php echo e($userType->description); ?>"
                                        data-dashboard_url="<?php echo e($userType->dashboard_url); ?>">
                                    Edit
                                </button>

                                <button type="button"
                                        class="btn btn-sm btn-bisu btn-bisu-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteUserTypeModal"
                                        data-id="<?php echo e($userType->id); ?>"
                                        data-name="<?php echo e($userType->name); ?>"
                                        data-description="<?php echo e($userType->description); ?>"
                                        data-dashboard_url="<?php echo e($userType->dashboard_url); ?>">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="3" class="text-center py-4 text-muted">
                            No user types found. Click <strong>Add User Type</strong> to create one.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="createUserTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <div>
            <h5 class="modal-title mb-0">Add User Type</h5>
            <div class="hint">Define a new role that can be used in the system.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" action="<?php echo e(route('admin.user-types.store')); ?>">
        <?php echo csrf_field(); ?>

        <div class="modal-body">

          <div class="mb-3">
            <label class="form-label" for="create_name">Name</label>
            <input type="text" name="name" id="create_name" class="form-control"
                   placeholder="e.g. Scholarship Coordinator" required>
          </div>

          <div class="mb-3">
            <label class="form-label" for="create_description">Description</label>
            <textarea name="description" id="create_description" class="form-control" rows="3"
                      placeholder="Short description (optional)"></textarea>
          </div>

          <div class="mb-2">
            <label class="form-label" for="create_dashboard_url">Dashboard URL</label>
            <input type="text" name="dashboard_url" id="create_dashboard_url" class="form-control"
                   placeholder="/coordinator/dashboard">
            <div class="hint mt-1">Optional. Example: <code>/coordinator/dashboard</code></div>
          </div>

        </div>

        <div class="modal-footer border-top-0">
          <button type="button" class="btn btn-bisu btn-bisu-outline" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-bisu btn-bisu-primary">Add User Type</button>
        </div>

      </form>

    </div>
  </div>
</div>


<div class="modal fade" id="editUserTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <div>
            <h5 class="modal-title mb-0">Edit User Type</h5>
            <div class="hint">Update the details of this user type.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      
      <form method="POST" id="editUserTypeForm" action="">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="modal-body">

          <div class="mb-3">
            <label class="form-label" for="edit_name">Name</label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label" for="edit_description">Description</label>
            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
          </div>

          <div class="mb-2">
            <label class="form-label" for="edit_dashboard_url">Dashboard URL</label>
            <input type="text" name="dashboard_url" id="edit_dashboard_url" class="form-control"
                   placeholder="/coordinator/dashboard">
            <div class="hint mt-1">Example: <code>/coordinator/dashboard</code></div>
          </div>

        </div>

        <div class="modal-footer border-top-0">
          <button type="button" class="btn btn-bisu btn-bisu-outline" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-bisu btn-bisu-primary">Save Changes</button>
        </div>

      </form>

    </div>
  </div>
</div>


<div class="modal fade" id="deleteUserTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <div>
            <h5 class="modal-title mb-0" style="color:#b30000;">Confirm Deletion</h5>
            <div class="hint">This action cannot be undone.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" id="deleteUserTypeForm" action="">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>

        <div class="modal-body">
            <div class="danger-box">
                <div class="fw-bold mb-1" id="delete_name">User Type</div>
                <div class="text-muted small mb-2" id="delete_description">No description</div>
                <div class="text-muted small">
                    <strong>Dashboard URL:</strong> <span id="delete_dashboard_url">N/A</span>
                </div>
            </div>

            <div class="mt-3 text-muted small">
                Deleting this user type may affect users assigned to this role.
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
    const editModal = document.getElementById('editUserTypeModal');
    const editForm  = document.getElementById('editUserTypeForm');

    editModal?.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        if(!btn) return;

        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name') || '';
        const description = btn.getAttribute('data-description') || '';
        const dashboardUrl = btn.getAttribute('data-dashboard_url') || '';

        // Set form action (same as your edit page route but update route)
        editForm.action = `<?php echo e(url('/admin/user-types')); ?>/${id}`;

        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_dashboard_url').value = dashboardUrl;
    });

    // DELETE MODAL FILL
    const deleteModal = document.getElementById('deleteUserTypeModal');
    const deleteForm  = document.getElementById('deleteUserTypeForm');

    deleteModal?.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        if(!btn) return;

        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name') || 'User Type';
        const description = btn.getAttribute('data-description') || 'No description provided.';
        const dashboardUrl = btn.getAttribute('data-dashboard_url') || 'N/A';

        deleteForm.action = `<?php echo e(url('/admin/user-types')); ?>/${id}`;

        document.getElementById('delete_name').textContent = name;
        document.getElementById('delete_description').textContent = description;
        document.getElementById('delete_dashboard_url').textContent = dashboardUrl;
    });

});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/user-type.blade.php ENDPATH**/ ?>