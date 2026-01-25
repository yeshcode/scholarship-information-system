

<?php $__env->startSection('page-content'); ?>

<style>
:root{
    --bisu-blue:#003366;
    --bisu-blue-2:#0b4a85;
}
.page-title-bisu{ font-weight:800; font-size:1.6rem; color:var(--bisu-blue); margin:0; }
.subtext{ color:#6b7280; font-size:.9rem; }

.btn-bisu{
    background:var(--bisu-blue)!important;
    border-color:var(--bisu-blue)!important;
    color:#fff!important;
    font-weight:700;
}
.btn-bisu:hover{ background:var(--bisu-blue-2)!important; border-color:var(--bisu-blue-2)!important; }

.card-bisu{ border:1px solid #e5e7eb; border-radius:14px; overflow:hidden; }
.card-bisu .card-header{ background:#fff; border-bottom:1px solid #eef2f7; }

.thead-bisu th{
    background:var(--bisu-blue)!important;
    color:#fff!important;
    font-size:.78rem;
    letter-spacing:.03em;
    text-transform:uppercase;
    white-space:nowrap;
}
.table td{ vertical-align:middle; white-space:nowrap; font-size:.9rem; }
.filter-label{ font-weight:700; color:#475569; margin-bottom:.35rem; font-size:.85rem; }
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
        <h2 class="page-title-bisu">Manage Stipends</h2>
        <div class="subtext">
            Filter Scholarship → Batch → Release. Bulk-assign stipend schedules to eligible scholars.
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

    <div class="d-flex gap-2">
        <button class="btn btn-bisu btn-sm" data-bs-toggle="modal" data-bs-target="#bulkStipendModal">
            + Bulk Assign Stipend
        </button>
    </div>
</div>


<div class="card card-bisu shadow-sm mb-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="fw-bold text-secondary">Filters</div>
        <small class="text-muted">Scholarship • Batch • Release • Search</small>
    </div>

    <div class="card-body">
        <form id="filterForm" method="GET" action="<?php echo e(route('coordinator.manage-stipends')); ?>">
            <div class="row g-3">

                <div class="col-12 col-md-3">
                    <label class="filter-label">Scholarship</label>
                    <select name="scholarship_id" id="scholarship_id" class="form-select form-select-sm">
                        <option value="">Select scholarship…</option>
                        <?php $__currentLoopData = $scholarships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s->id); ?>" <?php echo e((string)request('scholarship_id')===(string)$s->id?'selected':''); ?>>
                                <?php echo e($s->scholarship_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label class="filter-label">Batch</label>
                    <select name="batch_id" id="batch_id" class="form-select form-select-sm">
                        <option value="">Select batch…</option>
                        <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b->id); ?>" <?php echo e((string)request('batch_id')===(string)$b->id?'selected':''); ?>>
                                Batch <?php echo e($b->batch_number); ?>

                                (<?php echo e($b->semester->term ?? ''); ?> <?php echo e($b->semester->academic_year ?? ''); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label class="filter-label">Release Schedule</label>
                    <select name="stipend_release_id" id="stipend_release_id" class="form-select form-select-sm">
                        <option value="">All releases</option>
                        <?php $__currentLoopData = $releases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($r->id); ?>" <?php echo e((string)request('stipend_release_id')===(string)$r->id?'selected':''); ?>>
                                <?php echo e($r->title); ?>

                                (<?php echo e(strtoupper(str_replace('_',' ', $r->status ?? ''))); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label class="filter-label">Search scholar</label>
                    <input type="text" name="q" id="q" class="form-control form-control-sm"
                           value="<?php echo e(request('q')); ?>" placeholder="Lastname / Firstname / Student ID">
                </div>

            </div>
        </form>
    </div>
</div>


<div class="card card-bisu shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="fw-bold text-secondary">Stipend Records</div>
        <small class="text-muted">Showing created stipend rows</small>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead class="thead-bisu">
                <tr>
                    <th>Scholar</th>
                    <th>Scholarship</th>
                    <th>Batch</th>
                    <th>Release Title</th>
                    <th>Release Status</th>
                    <th>Release At</th>
                    <th>Received At</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $stipends; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stipend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $rel = $stipend->stipendRelease;
                        $relStatusLabel = strtoupper(str_replace('_',' ', $rel->status ?? ''));
                    ?>
                    <tr>
                        <td><?php echo e($stipend->scholar->user->firstname ?? 'N/A'); ?> <?php echo e($stipend->scholar->user->lastname ?? ''); ?></td>
                        <td><?php echo e($stipend->scholar->scholarship->scholarship_name ?? 'N/A'); ?></td>
                        <td>Batch <?php echo e($stipend->scholar->scholarshipBatch->batch_number ?? 'N/A'); ?></td>
                        <td><?php echo e($rel->title ?? 'N/A'); ?></td>
                        <td><span class="badge bg-info-subtle text-info"><?php echo e($relStatusLabel ?: 'N/A'); ?></span></td>

                        <td>
                            <?php if($stipend->release_at): ?>
                                <?php echo e(\Carbon\Carbon::parse($stipend->release_at)->format('M d, Y h:i A')); ?>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if($stipend->received_at): ?>
                                <?php echo e(\Carbon\Carbon::parse($stipend->received_at)->format('M d, Y h:i A')); ?>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>

                        <td><?php echo e($stipend->amount_received); ?></td>
                        <td><?php echo e(strtoupper(str_replace('_',' ', $stipend->status))); ?></td>

                        <td class="text-end">
                            <a href="<?php echo e(route('coordinator.stipends.edit', $stipend->id)); ?>" class="text-primary me-2">Edit</a>
                            <a href="<?php echo e(route('coordinator.stipends.confirm-delete', $stipend->id)); ?>" class="text-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">No stipend records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card-body">
        <?php echo e($stipends->links()); ?>

    </div>
</div>


<div class="modal fade" id="bulkStipendModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <div>
                    <div class="fw-bold">Bulk Assign Stipend</div>
                    <small class="opacity-75">Pick scholarship + batch, choose a release schedule, set release datetime, amount, then choose scholars.</small>
                </div>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="<?php echo e(route('coordinator.stipends.bulk-assign')); ?>">
                <?php echo csrf_field(); ?>

                <div class="modal-body">

                    <div class="row g-3 mb-3">

                        <div class="col-12 col-md-3">
                            <label class="filter-label">Scholarship</label>
                            <select name="scholarship_id" class="form-select form-select-sm" required>
                                <option value="">Select scholarship…</option>
                                <?php $__currentLoopData = $scholarships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($s->id); ?>"><?php echo e($s->scholarship_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="filter-label">Batch</label>
                            <select name="batch_id" class="form-select form-select-sm" required>
                                <option value="">Select batch…</option>
                                <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($b->id); ?>">
                                        Batch <?php echo e($b->batch_number); ?> (<?php echo e($b->semester->term ?? ''); ?> <?php echo e($b->semester->academic_year ?? ''); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="filter-label">Stipend Release Schedule</label>
                            <select name="stipend_release_id" class="form-select form-select-sm" required>
                                <option value="">Select release…</option>
                                <?php $__currentLoopData = $releases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($r->id); ?>" data-status="<?php echo e($r->status); ?>">
                                        <?php echo e($r->title); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="form-text" id="releaseNote">Select a release to see its status.</div>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="filter-label">Release Date & Time</label>
                            <input type="datetime-local" name="release_at" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="filter-label">Amount</label>
                            <input type="number" step="0.01" name="amount_received" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="filter-label">Status</label>
                            <select name="status" class="form-select form-select-sm" required>
                                <option value="for_billing">For Billing</option>
                                <option value="for_check">For Check</option>
                                <option value="for_release">For Release</option>
                                <option value="received">Received</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-info small mb-0">
                                Eligible scholars shown below are those who are <strong>ENROLLED or GRADUATED</strong> in the <strong>current semester</strong>.
                            </div>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="thead-bisu">
                                <tr>
                                    <th style="width:70px;">Select</th>
                                    <th>Student ID</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Scholarship</th>
                                    <th>Batch</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $eligibleScholars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="scholar_ids[]" value="<?php echo e($sc->id); ?>">
                                        </td>
                                        <td><?php echo e($sc->user->student_id ?? 'N/A'); ?></td>
                                        <td><?php echo e($sc->user->lastname ?? 'N/A'); ?></td>
                                        <td><?php echo e($sc->user->firstname ?? 'N/A'); ?></td>
                                        <td><?php echo e($sc->scholarship->scholarship_name ?? 'N/A'); ?></td>
                                        <td>Batch <?php echo e($sc->scholarshipBatch->batch_number ?? 'N/A'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">
                                            No eligible scholars found. Select filters above first.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal" type="button">Close</button>
                    <button class="btn btn-bisu btn-sm" type="submit">Save Bulk Stipends</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filterForm');

    const scholarship = document.getElementById('scholarship_id');
    const batch = document.getElementById('batch_id');
    const release = document.getElementById('stipend_release_id');
    const q = document.getElementById('q');

    scholarship?.addEventListener('change', () => form.submit());
    batch?.addEventListener('change', () => form.submit());
    release?.addEventListener('change', () => form.submit());

    let t=null;
    q?.addEventListener('input', () => {
        clearTimeout(t);
        t=setTimeout(()=>form.submit(), 400);
    });

    // Modal release note
    const modal = document.getElementById('bulkStipendModal');
    modal?.addEventListener('shown.bs.modal', function(){
        const releaseSelect = modal.querySelector('select[name="stipend_release_id"]');
        const note = document.getElementById('releaseNote');

        function syncNote(){
            const opt = releaseSelect.options[releaseSelect.selectedIndex];
            const status = (opt?.getAttribute('data-status') || '').toUpperCase().replaceAll('_',' ');
            note.textContent = status ? `This stipend release schedule is ${status} pa.` : 'Select a release to see its status.';
        }
        releaseSelect.addEventListener('change', syncNote);
        syncNote();
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/manage-stipends.blade.php ENDPATH**/ ?>