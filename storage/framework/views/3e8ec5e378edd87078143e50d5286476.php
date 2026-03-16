

<?php $__env->startSection('page-content'); ?>

<?php
    $tdpTesScholarships = collect($scholarships ?? [])
        ->filter(function ($s) {
            $name = strtoupper(trim($s->scholarship_name ?? ''));
            return str_contains($name, 'TDP') || str_contains($name, 'TES');
        })
        ->values();
?>

<style>
    :root{
        --bisu-blue:#003366;
        --bisu-blue-2:#0b4a85;
        --bisu-bg:#f4f7fb;
        --bisu-line:#e5e7eb;
        --bisu-muted:#6b7280;
    }

    .page-title-bisu{ font-weight:800; font-size:1.6rem; color:var(--bisu-blue); margin:0; }
    .subtext{ color:var(--bisu-muted); font-size:.92rem; }

    .card-bisu{ border:1px solid var(--bisu-line); border-radius: 12px; overflow:hidden; }

    .btn-bisu{
        background:var(--bisu-blue) !important;
        border-color:var(--bisu-blue) !important;
        color:#fff !important;
        font-weight:700;
    }
    .btn-bisu:hover{
        background:var(--bisu-blue-2) !important;
        border-color:var(--bisu-blue-2) !important;
        color:#fff !important;
    }

    .thead-bisu th{
        background:var(--bisu-blue) !important;
        color:#fff !important;
        font-size:.78rem;
        letter-spacing:.03em;
        text-transform:uppercase;
        white-space:nowrap;
        vertical-align: middle;
    }

    .table td{ vertical-align:middle; font-size:.92rem; }
    .table td, .table th{ border-color: var(--bisu-line) !important; }

    .badge-soft{
        background:#eef5ff;
        color:#0b4a85;
        border:1px solid #cfe0ff;
        font-weight:700;
        border-radius: 999px;
        padding: .35rem .6rem;
    }

    .action-btn{
        border-radius: 10px;
        padding: .25rem .6rem;
        font-weight: 700;
        font-size: .86rem;
        border:1px solid transparent;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:.35rem;
    }
    .action-edit{
        color:#0b4a85;
        background:#eef5ff;
        border-color:#cfe0ff;
    }
    .action-edit:hover{ background:#e3efff; color:#0b4a85; text-decoration:none; }
    .action-del{
        color:#b42318;
        background:#fff1f2;
        border-color:#fecdd3;
    }
    .action-del:hover{ background:#ffe4e6; color:#b42318; text-decoration:none; }

    .small-muted{ color:var(--bisu-muted); font-size:.85rem; }

    /* Modal polish */
    .modal-bisu .modal-header{ background: var(--bisu-blue); color:#fff; }
    .modal-bisu .modal-title{ font-weight:800; letter-spacing:.2px; }
    .modal-bisu .modal-content{ border-radius: 14px; border: 0; overflow:hidden; }
    .modal-bisu .modal-footer{ border-top:1px solid var(--bisu-line); }
    .help-note{
        background: #f8fafc;
        border: 1px solid var(--bisu-line);
        border-radius: 10px;
        padding: .6rem .75rem;
        color: var(--bisu-muted);
        font-size:.88rem;
    }
    .danger-soft{
        background:#fff1f2;
        border:1px solid #fecdd3;
        color:#b42318;
        border-radius: 10px;
        padding: .75rem .9rem;
        font-weight:700;
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


<div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">
    <div>
        <h2 class="page-title-bisu">Scholarship Batches</h2>
        <div class="subtext">
            Manage batch lists for <strong>TDP</strong> and <strong>TES</strong>. These batches are used for scholar assignment and stipend schedules.
        </div>
    </div>

    <div class="d-flex gap-2">
        
        <button type="button" class="btn btn-bisu btn-sm" data-bs-toggle="modal" data-bs-target="#addBatchModal">
            Add Batch
        </button>
    </div>
</div>


<div class="card card-bisu shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="fw-bold text-secondary">Batch List</div>
        <small class="text-muted">Showing <?php echo e($batches->count()); ?> of <?php echo e($batches->total()); ?></small>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-bisu">
                    <tr>
                        <th>Scholarship</th>
                        <th>Semester</th>
                        <th>Batch</th>
                        <th>Date Added</th>
                        <th class="text-end" style="width:190px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $schName = $batch->scholarship->scholarship_name ?? 'N/A';
                            $semTerm = $batch->semester->term ?? 'N/A';
                            $semAy = $batch->semester->academic_year ?? '';
                            $semLabel = $semTerm . ' ' . $semAy;
                            $dateAdded = $batch->created_at ? $batch->created_at->format('M d, Y') : '—';
                            $dateUpdated = $batch->updated_at ? $batch->updated_at->format('M d, Y') : '—';
                        ?>
                        <tr>
                            <td><span class="badge-soft"><?php echo e($schName); ?></span></td>
                            <td><?php echo e($semLabel); ?></td>
                            <td class="fw-bold">Batch <?php echo e($batch->batch_number); ?></td>

                            <td>
                                <div class="fw-semibold"><?php echo e($dateAdded); ?></div>
                                <div class="small-muted">Updated: <?php echo e($dateUpdated); ?></div>
                            </td>

                            <td class="text-end">
                                
                                <button type="button"
                                        class="action-btn action-edit me-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editBatchModal"
                                        data-id="<?php echo e($batch->id); ?>"
                                        data-scholarship="<?php echo e($batch->scholarship_id); ?>"
                                        data-semester="<?php echo e($batch->semester_id); ?>"
                                        data-batchnumber="<?php echo e($batch->batch_number); ?>">
                                    Edit
                                </button>

                                
                                <button type="button"
                                        class="action-btn action-del"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteBatchModal"
                                        data-id="<?php echo e($batch->id); ?>"
                                        data-schname="<?php echo e(e($schName)); ?>"
                                        data-sem="<?php echo e(e($semLabel)); ?>"
                                        data-bn="<?php echo e(e($batch->batch_number)); ?>">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No batches found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer bg-white">
        <?php echo e($batches->withQueryString()->links()); ?>

    </div>
</div>


<div class="modal fade modal-bisu" id="addBatchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <div class="modal-title">Add Scholarship Batch</div>
                    <small class="opacity-75">Only <strong>TDP</strong> and <strong>TES</strong> scholarships can have batches.</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="<?php echo e(route('coordinator.scholarship-batches.store')); ?>">
                <?php echo csrf_field(); ?>

                <div class="modal-body">
                    <?php if($tdpTesScholarships->isEmpty()): ?>
                        <div class="alert alert-warning mb-0">
                            No TDP/TES scholarship found in your database.
                            Please create the scholarship first (TDP or TES), then come back here.
                        </div>
                    <?php else: ?>
                        <div class="help-note mb-3">
                            Tip: Use batch labels like <strong>13</strong>, <strong>13A</strong>, or <strong>Batch-13</strong>.
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-secondary mb-1">Scholarship (TDP / TES)</label>
                                <select name="scholarship_id" class="form-select form-select-sm" required>
                                    <option value="">Select scholarship...</option>
                                    <?php $__currentLoopData = $tdpTesScholarships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($s->id); ?>"><?php echo e($s->scholarship_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-secondary mb-1">Semester</label>
                                <select name="semester_id" class="form-select form-select-sm" required>
                                    <option value="">Select semester...</option>
                                    <?php $__currentLoopData = ($semesters ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($sem->id); ?>">
                                            <?php echo e($sem->term ?? $sem->semester_name); ?> <?php echo e($sem->academic_year); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-secondary mb-1">Batch Number</label>
                                <input type="text" name="batch_number" class="form-control form-control-sm" placeholder="e.g., 13" required>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-bisu btn-sm" <?php echo e($tdpTesScholarships->isEmpty() ? 'disabled' : ''); ?>>
                        Save Batch
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


<div class="modal fade modal-bisu" id="editBatchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <div class="modal-title">Edit Scholarship Batch</div>
                    <small class="opacity-75">Update scholarship, semester, and batch label.</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="editBatchForm" method="POST" action="">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary mb-1">Scholarship</label>
                            <select id="edit_scholarship_id" name="scholarship_id" required class="form-select form-select-sm">
                                <?php $__currentLoopData = ($scholarships ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($s->id); ?>"><?php echo e($s->scholarship_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary mb-1">Semester</label>
                            <select id="edit_semester_id" name="semester_id" required class="form-select form-select-sm">
                                <?php $__currentLoopData = ($semesters ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sem->id); ?>">
                                        <?php echo e($sem->term ?? $sem->semester_name); ?> <?php echo e($sem->academic_year); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary mb-1">Batch Number</label>
                            <input id="edit_batch_number" type="text" name="batch_number" class="form-control form-control-sm" required>
                            <div class="form-text">Example: 13, 13A, Batch-13</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-bisu btn-sm">Save Changes</button>
                </div>
            </form>

        </div>
    </div>
</div>


<div class="modal fade modal-bisu" id="deleteBatchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <div class="modal-title">Confirm Delete</div>
                    <small class="opacity-75">This action cannot be undone.</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="danger-soft mb-3">
                    Are you sure you want to delete this batch?
                </div>

                <div class="help-note">
                    <div class="mb-1"><strong>Scholarship:</strong> <span id="del_scholarship"></span></div>
                    <div class="mb-1"><strong>Semester:</strong> <span id="del_semester"></span></div>
                    <div><strong>Batch:</strong> Batch <span id="del_batch"></span></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>

                <form id="deleteBatchForm" method="POST" action="">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger btn-sm">Yes, Delete</button>
                </form>
            </div>

        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function(){

    // --------- EDIT MODAL ----------
    const editModal = document.getElementById('editBatchModal');
    if(editModal){
        editModal.addEventListener('show.bs.modal', function(event){
            const btn = event.relatedTarget;

            const id = btn.getAttribute('data-id');
            const scholarshipId = btn.getAttribute('data-scholarship');
            const semesterId = btn.getAttribute('data-semester');
            const batchNumber = btn.getAttribute('data-batchnumber');

            // Set form action (PUT) to your update route
            const updateUrl = `<?php echo e(url('/coordinator/scholarship-batches')); ?>/${id}`;
            document.getElementById('editBatchForm').action = updateUrl;

            // Fill fields
            document.getElementById('edit_scholarship_id').value = scholarshipId;
            document.getElementById('edit_semester_id').value = semesterId;
            document.getElementById('edit_batch_number').value = batchNumber;
        });
    }

    // --------- DELETE MODAL ----------
    const delModal = document.getElementById('deleteBatchModal');
    if(delModal){
        delModal.addEventListener('show.bs.modal', function(event){
            const btn = event.relatedTarget;

            const id = btn.getAttribute('data-id');
            const schName = btn.getAttribute('data-schname');
            const sem = btn.getAttribute('data-sem');
            const bn = btn.getAttribute('data-bn');

            document.getElementById('del_scholarship').textContent = schName;
            document.getElementById('del_semester').textContent = sem;
            document.getElementById('del_batch').textContent = bn;

            // Set form action (DELETE) to your destroy route
            const deleteUrl = `<?php echo e(url('/coordinator/scholarship-batches')); ?>/${id}`;
            document.getElementById('deleteBatchForm').action = deleteUrl;
        });
    }

});
</script>


<?php if($errors->any()): ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const modalEl = document.getElementById('addBatchModal');
    if(modalEl){
        const m = new bootstrap.Modal(modalEl);
        m.show();
    }
});
</script>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/scholarship-batches.blade.php ENDPATH**/ ?>