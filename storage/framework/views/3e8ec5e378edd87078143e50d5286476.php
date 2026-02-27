

<?php $__env->startSection('page-content'); ?>

<?php
    // Only show scholarships that are TDP or TES
    // (Better to do this in controller, but you asked UI — so we filter here safely.)
    $tdpTesScholarships = collect($scholarships ?? [])
        ->filter(function ($s) {
            $name = strtoupper(trim($s->scholarship_name ?? ''));
            return str_contains($name, 'TDP') || str_contains($name, 'TES');
        })
        ->values();

    $q = request('q', '');
?>

<style>
    :root{
        --bisu-blue:#003366;
        --bisu-blue-2:#0b4a85;
    }
    .page-title-bisu{ font-weight:800; font-size:1.6rem; color:var(--bisu-blue); margin:0; }
    .subtext{ color:#6b7280; font-size:.9rem; }

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
    }
    .table td{ vertical-align:middle; font-size:.92rem; }
    .badge-soft{
        background:#eef5ff;
        color:#0b4a85;
        border:1px solid #cfe0ff;
        font-weight:700;
    }
    .action-link{
        font-weight:700;
        text-decoration:none;
    }
    .action-link:hover{ text-decoration:underline; }
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
        
        <form method="GET" class="d-flex gap-2">
            <input type="text"
                   name="q"
                   value="<?php echo e($q); ?>"
                   class="form-control form-control-sm"
                   placeholder="Search scholarship / semester / batch...">
            <button class="btn btn-outline-secondary btn-sm">Search</button>
        </form>

        
        <button type="button" class="btn btn-bisu btn-sm" data-bs-toggle="modal" data-bs-target="#addBatchModal">
            + Add Batch
        </button>
    </div>
</div>


<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="fw-bold text-secondary">Batch List</div>
        <small class="text-muted">Showing <?php echo e($batches->count()); ?> of <?php echo e($batches->total()); ?></small>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="thead-bisu">
                    <tr>
                        <th>Scholarship</th>
                        <th>Semester</th>
                        <th>Batch</th>
                        <th class="text-end" style="width:140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $schName = $batch->scholarship->scholarship_name ?? 'N/A';
                            $semLabel = ($batch->semester->term ?? 'N/A') . ' ' . ($batch->semester->academic_year ?? '');
                        ?>
                        <tr>
                            <td>
                                <span class="badge badge-soft"><?php echo e($schName); ?></span>
                            </td>
                            <td><?php echo e($semLabel); ?></td>
                            <td class="fw-bold">Batch <?php echo e($batch->batch_number); ?></td>
                            <td class="text-end">
                                <a href="<?php echo e(route('coordinator.scholarship-batches.edit', $batch->id)); ?>"
                                   class="action-link text-primary me-3">
                                    Edit
                                </a>
                                <a href="<?php echo e(route('coordinator.scholarship-batches.confirm-delete', $batch->id)); ?>"
                                   class="action-link text-danger">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
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



<div class="modal fade" id="addBatchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header text-white" style="background: var(--bisu-blue);">
                <div>
                    <div class="fw-bold">Add Scholarship Batch</div>
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
                        <div class="row g-3">

                            
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-secondary mb-1">Scholarship (TDP / TES)</label>
                                <select name="scholarship_id" class="form-select form-select-sm" required>
                                    <option value="">Select scholarship...</option>
                                    <?php $__currentLoopData = $tdpTesScholarships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($s->id); ?>">
                                            <?php echo e($s->scholarship_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="form-text">
                                    Batches are enabled only for scholarship types with multiple releases.
                                </div>
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
                                <input type="text"
                                       name="batch_number"
                                       class="form-control form-control-sm"
                                       placeholder="e.g., 13"
                                       required>
                                <div class="form-text">
                                    You can use numeric or text batch labels (e.g., 13, 13A, Batch-13).
                                </div>
                            </div>

                        </div>
                    <?php endif; ?>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-bisu btn-sm"
                        <?php echo e($tdpTesScholarships->isEmpty() ? 'disabled' : ''); ?>>
                        Save Batch
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>



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