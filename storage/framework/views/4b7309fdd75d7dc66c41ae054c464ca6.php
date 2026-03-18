<?php $fullWidth = true; ?>


<?php $__env->startSection('page-content'); ?>
<style>
    :root{ --bisu:#003366; --line:#e5e7eb; }
    .card-bisu{ border:1px solid var(--line); border-radius:14px; overflow:hidden; }
    .thead-bisu th{
        background:var(--bisu);
        color:#fff;
        font-size:.76rem;
        text-transform:uppercase;
        letter-spacing:.03em;
        white-space:nowrap;
        text-align:center;
        vertical-align:middle;
    }
    .subhead th{
        background:#eaf2fb !important;
        color:#111 !important;
        font-size:.74rem;
        text-transform:none !important;
    }
    .table td{ font-size:.88rem; vertical-align:middle; }
</style>

<div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
    <div>
        <h4 class="fw-bold mb-1">Payroll / Liquidation Preview</h4>
        <div class="text-muted small">
            <?php echo e($scholarship?->scholarship_name ?? 'N/A'); ?>

            • Batch <?php echo e($batch?->batch_number ?? 'N/A'); ?>

            • Academic Year <?php echo e($academicYear ?? 'N/A'); ?>

        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <a href="<?php echo e(route('coordinator.manage-stipend-releases')); ?>" class="btn btn-outline-secondary btn-sm">Back</a>
        <a href="<?php echo e(route('coordinator.stipend-releases.form.print', $release->id)); ?>" target="_blank" class="btn btn-outline-primary btn-sm">Print</a>
        <a href="<?php echo e(route('coordinator.stipend-releases.form.excel', $release->id)); ?>" class="btn btn-primary btn-sm">Download Excel</a>
    </div>
</div>

<div class="card card-bisu shadow-sm">
    <div class="card-header bg-white fw-semibold text-secondary">
        Payroll Preview
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead class="thead-bisu">
                <tr>
                    <th rowspan="2">Seq. No.</th>
                    <th colspan="7">Student's Profile</th>
                    <th colspan="3">First Semester</th>
                    <th colspan="3">Second Semester</th>
                </tr>
                <tr class="subhead">
                    <th><?php echo e($isTes ? 'TES Award No.' : 'TDP Award No.'); ?></th>
                    <th>Student ID No.</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>M.I.</th>
                    <th>Degree Program</th>
                    <th>Year Level</th>

                    <th>Total Amount Received</th>
                    <th>Date Received</th>
                    <th>Student's Signature</th>

                    <th>Total Amount Received</th>
                    <th>Date Received</th>
                    <th>Student's Signature</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-center"><?php echo e($row->seq_no); ?></td>
                        <td><?php echo e($row->award_no); ?></td>
                        <td><?php echo e($row->student_id); ?></td>
                        <td><?php echo e($row->lastname); ?></td>
                        <td><?php echo e($row->firstname); ?></td>
                        <td><?php echo e($row->middlename); ?></td>
                        <td><?php echo e($row->course); ?></td>
                        <td class="text-center"><?php echo e($row->year_level); ?></td>

                        <td class="text-end">
                            <?php echo e($row->first_amount !== null ? number_format((float)$row->first_amount, 2) : ''); ?>

                        </td>
                        <td class="text-center">
                            <?php echo e($row->first_date_received ? \Carbon\Carbon::parse($row->first_date_received)->format('m/d/y') : ''); ?>

                        </td>
                        <td></td>

                        <td class="text-end">
                            <?php echo e($row->second_amount !== null ? number_format((float)$row->second_amount, 2) : ''); ?>

                        </td>
                        <td class="text-center">
                            <?php echo e($row->second_date_received ? \Carbon\Carbon::parse($row->second_date_received)->format('m/d/y') : ''); ?>

                        </td>
                        <td></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="14" class="text-center text-muted py-4">No scholars found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/stipend-release-form.blade.php ENDPATH**/ ?>