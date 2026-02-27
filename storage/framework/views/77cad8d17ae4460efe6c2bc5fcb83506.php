

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
    .btn-bisu:hover{ background:var(--bisu-blue-2) !important; border-color:var(--bisu-blue-2) !important; }

    .card-bisu{
        border:1px solid #e5e7eb;
        border-radius:14px;
        overflow:hidden;
    }

    .thead-bisu th{
        background:var(--bisu-blue) !important;
        color:#fff !important;
        font-size:.78rem;
        letter-spacing:.03em;
        text-transform:uppercase;
        white-space:nowrap;
    }

    .table td{ vertical-align:middle; font-size:.9rem; }
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
        <h2 class="page-title-bisu">Manage Stipend</h2>
        <div class="subtext">Create and manage release schedules for TDP/TES batches.</div>
    </div>

    <a href="<?php echo e(route('coordinator.stipend-releases.create')); ?>" class="btn btn-bisu btn-sm">
        Add Stipend
    </a>
</div>

<div class="card card-bisu shadow-sm mb-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="fw-bold text-secondary">Filters</div>
        <small class="text-muted">Semester filter is based on release record</small>
    </div>

    <div class="card-body">
        <form method="GET" action="<?php echo e(route('coordinator.manage-stipend-releases')); ?>">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label-bisu">Release Semester</label>
                    <select name="semester_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All semesters</option>
                        <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sem->id); ?>" <?php echo e((string)$semesterId === (string)$sem->id ? 'selected' : ''); ?>>
                                <?php echo e($sem->term ?? $sem->semester_name); ?> <?php echo e($sem->academic_year); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <button class="btn btn-outline-secondary btn-sm" type="submit">Apply</button>
                    <a href="<?php echo e(route('coordinator.manage-stipend-releases')); ?>" class="btn btn-outline-secondary btn-sm">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="card card-bisu shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="fw-bold text-secondary">Release Schedule List</div>
        <small class="text-muted">Newest first</small>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead class="thead-bisu">
                <tr>
                    <th>Scholarship</th>
                    <th>Batch</th>
                    <th>Release Semester</th>
                    <th>Title</th>
                    <th class="text-end">Amount</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $releases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $release): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $batch = $release->scholarshipBatch;
                        $schName = $batch?->scholarship?->scholarship_name ?? 'N/A';
                        $batchLabel = $batch ? ('Batch ' . $batch->batch_number) : 'N/A';

                        $releaseSemLabel = $release->semester
                            ? (($release->semester->term ?? $release->semester->semester_name) . ' ' . $release->semester->academic_year)
                            : 'N/A';

                        $status = $release->status ?? '';
                        $statusLabel = match($status) {
                            'for_billing' => 'For Billing',
                            'for_check' => 'For Check',
                            'for_release' => 'For Release',
                            'received' => 'Received',
                            default => strtoupper($status ?: 'N/A')
                        };

                        $badge = match($status) {
                            'for_billing' => 'bg-warning-subtle text-warning',
                            'for_check' => 'bg-primary-subtle text-primary',
                            'for_release' => 'bg-success-subtle text-success',
                            'received' => 'bg-secondary-subtle text-secondary',
                            default => 'bg-light text-dark'
                        };
                    ?>

                    <tr>
                        <td><?php echo e($schName); ?></td>
                        <td><?php echo e($batchLabel); ?></td>
                        <td><?php echo e($releaseSemLabel); ?></td>
                        
                        <td class="fw-semibold"><?php echo e($release->title); ?></td>
                        <td class="text-end">₱ <?php echo e(number_format((float)$release->amount, 2)); ?></td>
                        <td><span class="badge <?php echo e($badge); ?>"><?php echo e($statusLabel); ?></span></td>
                        <td class="text-end">
                            <a href="<?php echo e(route('coordinator.stipend-releases.form', $release->id)); ?>" class="btn btn-sm btn-outline-secondary">
                                Form
                            </a>
                            <a href="<?php echo e(route('coordinator.stipend-releases.edit', $release->id)); ?>" class="btn btn-sm btn-outline-primary">
                                Edit
                            </a>
                            <a href="<?php echo e(route('coordinator.stipend-releases.confirm-delete', $release->id)); ?>" class="btn btn-sm btn-outline-danger">
                                Delete
                            </a>
                        </td>
                    </tr>   
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No release schedules found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-body">
        <?php echo e($releases->links()); ?>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/manage-stipend-releases.blade.php ENDPATH**/ ?>