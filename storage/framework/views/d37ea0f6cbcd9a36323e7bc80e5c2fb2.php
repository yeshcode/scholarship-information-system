

<?php $__env->startSection('page-content'); ?>

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
    .table td{ vertical-align:middle; white-space:nowrap; font-size:.9rem; }

    /* ✅ make modal body scrollable even if bootstrap fails */
    .modal-body{
        max-height: calc(100vh - 210px);
        overflow-y: auto;
    }

    .row-disabled{
        background:#f3f4f6;
        color:#6b7280;
    }
    .row-disabled td{
        color:#6b7280 !important;
    }
    .badge-na{
        background:#e5e7eb !important;
        color:#6b7280 !important;
        border:1px solid #d1d5db;
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


<div class="d-flex align-items-end justify-content-between flex-wrap gap-2 mb-3">
    <div>
        <h2 class="page-title-bisu">Upload Scholars (Bulk)</h2>
        <div class="subtext">
            Upload an <strong>Excel/CSV</strong> file. The system will match rows against your student database and check
            enrollment status in the <strong>current semester</strong>.
        </div>

        <?php if(!empty($currentSemester)): ?>
            <div class="mt-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                    Current Semester:
                    <strong><?php echo e($currentSemester->term ?? $currentSemester->semester_name); ?> <?php echo e($currentSemester->academic_year); ?></strong>
                </span>
            </div>
        <?php endif; ?>
    </div>

    <a href="<?php echo e(route('coordinator.manage-scholars')); ?>" class="btn btn-outline-secondary btn-sm">
        ← Back to Manage Scholars
    </a>
</div>


<div class="card shadow-sm mb-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="fw-bold text-secondary">Upload File</div>
        <small class="text-muted">Supported: Excel (.xlsx/.xls) • CSV</small>
    </div>

    <div class="card-body">
        <form action="<?php echo e(route('coordinator.scholars.upload.process')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-8">
                    <label class="form-label fw-semibold text-secondary mb-1">Choose file</label>
                    <input
                        type="file"
                        name="file"
                        class="form-control form-control-sm"
                        accept=".xlsx,.xls,.csv"
                        required
                    >
                    <div class="form-text">
                        The system will auto-detect headers like <code>First Name</code>, <code>FIRSTNAME</code>, <code>first_name</code>, etc.
                        It only reads: <strong>First Name, Last Name, Year Level, Enrollment Status</strong>.
                    </div>
                </div>

                <div class="col-12 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-bisu btn-sm w-100">
                        Process File
                    </button>

                    <a href="<?php echo e(route('coordinator.manage-scholars')); ?>" class="btn btn-outline-secondary btn-sm w-100">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>


<?php if(session('results')): ?>
<div class="modal fade" id="resultsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <div>
                    <div class="fw-bold">Comparison Results</div>
                    <small class="opacity-75">
                        Only <strong>Verified + Enrolled (Current) + Not Yet Scholar</strong> can be selected.
                    </small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            
            <form method="POST" action="<?php echo e(route('coordinator.scholars.upload.add-selected')); ?>">
                <?php echo csrf_field(); ?>

                <input type="hidden" name="results_json" value='<?php echo json_encode(session("results"), 15, 512) ?>'>

                <div class="modal-body">

                    
                    <div class="card border mb-3">
                        <div class="card-body">
                            <div class="row g-2 align-items-end">

                                
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-secondary mb-1">Assign Scholarship</label>
                                    <select name="scholarship_id" id="scholarship_id" class="form-select form-select-sm" required>
                                        <option value="">Select scholarship...</option>
                                        <?php $__currentLoopData = ($scholarships ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($s->id); ?>"><?php echo e($s->scholarship_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                
                                <div class="col-12 col-md-6" id="batch_wrap">
                                    <label class="form-label fw-semibold text-secondary mb-1">Batch (optional)</label>
                                    <select name="batch_id" id="batch_id" class="form-select form-select-sm">
                                        <option value="" selected>No batch</option>
                                        <?php $__currentLoopData = ($batches ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option
                                                value="<?php echo e($b->id); ?>"
                                                data-scholarship-id="<?php echo e($b->scholarship_id); ?>"
                                            >
                                                Batch <?php echo e($b->batch_number); ?>

                                                (<?php echo e($b->semester->term ?? ''); ?> <?php echo e($b->semester->academic_year ?? ''); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="form-text">
                                        If your scholarship has batches (TDP/TES), choose a batch. Otherwise leave as No batch.
                                    </div>
                                </div>

                                
                                <input type="hidden" name="status" value="active">


                                <div class="col-12 col-md-8">
                                    <div class="alert alert-info mb-0 py-2 small">
                                        Select eligible rows below, then click <strong>Process / Add Selected</strong>.
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="thead-bisu">
                                <tr>
                                    <th style="width:80px;">Select</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Year Level</th>
                                    <th>Verified</th>
                                    <th>Enrollment Status</th>
                                    <th>Current Scholarship</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__currentLoopData = session('results'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $verified = !empty($result['user']);
                                        $isScholar = !empty($result['is_scholar']);
                                        $enrollStatus = $result['enrollment_status'] ?? 'not_enrolled';
                                        $canSelect = $verified && ($enrollStatus === 'enrolled') && !$isScholar;
                                    ?>

                                    <tr class="<?php echo e($canSelect ? '' : 'row-disabled'); ?>">
                                        <td class="text-center">
                                            <?php if($canSelect): ?>
                                                <input type="checkbox" name="selected_indexes[]" value="<?php echo e($index); ?>">
                                            <?php else: ?>
                                                <span class="badge badge-na">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td><?php echo e($result['data']['last_name'] ?? ''); ?></td>
                                        <td><?php echo e($result['data']['first_name'] ?? ''); ?></td>
                                        <td><?php echo e($result['data']['year_level'] ?? ''); ?></td>

                                        <td>
                                            <?php if($verified): ?>
                                                <span class="badge bg-success-subtle text-success">Verified</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger">Not Verified</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if($enrollStatus === 'enrolled'): ?>
                                                <span class="badge bg-success-subtle text-success">ENROLLED</span>
                                            <?php elseif($enrollStatus === 'dropped'): ?>
                                                <span class="badge bg-danger-subtle text-danger">DROPPED</span>
                                            <?php elseif($enrollStatus === 'graduated'): ?>
                                                <span class="badge bg-primary-subtle text-primary">GRADUATED</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary-subtle text-secondary">NOT ENROLLED</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if($isScholar): ?>
                                                <span class="badge bg-warning-subtle text-warning">
                                                    <?php echo e($result['existing_scholarship_name'] ?? 'SCHOLAR'); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary-subtle text-secondary">NO</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>

                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-bisu btn-sm">
                        Process / Add Selected
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    <?php if(session('results')): ?>
        const el = document.getElementById('resultsModal');
        const resultsModal = new bootstrap.Modal(el, { backdrop: 'static' });
        resultsModal.show();
    <?php endif; ?>
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    function isBatchBasedScholarshipName(name){
        name = (name || '').toUpperCase().trim();
        return name.includes('TDP') || name.includes('TES');
    }

    function filterBatchesByScholarship(){
        const schSelect  = document.getElementById('scholarship_id');
        const batchWrap  = document.getElementById('batch_wrap');
        const batchSelect= document.getElementById('batch_id');

        if(!schSelect || !batchSelect || !batchWrap) return;

        const selectedOption = schSelect.options[schSelect.selectedIndex];
        const schName = selectedOption ? selectedOption.text : '';
        const schId   = schSelect.value;

        const batchBased = isBatchBasedScholarshipName(schName);

        // show/hide + required
        batchWrap.style.display = batchBased ? '' : 'none';
        batchSelect.required = batchBased;

        // reset to "No batch"
        batchSelect.value = '';

        // filter visible options
        const opts = batchSelect.querySelectorAll('option[data-scholarship-id]');
        opts.forEach(opt => {
            const optSchId = opt.getAttribute('data-scholarship-id');
            opt.hidden = batchBased ? (optSchId !== schId) : true;
        });
    }

    // run on change
    const scholarshipSelect = document.getElementById('scholarship_id');
    if (scholarshipSelect) {
        scholarshipSelect.addEventListener('change', filterBatchesByScholarship);
        filterBatchesByScholarship(); // run once on load
    }

});
</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/upload-scholars.blade.php ENDPATH**/ ?>