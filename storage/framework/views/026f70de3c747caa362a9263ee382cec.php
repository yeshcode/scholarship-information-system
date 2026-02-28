

<?php $__env->startSection('content'); ?>
<style>
@media print {
    body { background:#fff !important; }
    .no-print { display:none !important; }
}
.report-wrap{
    max-width: 210mm;
    margin: 0 auto;
    padding: 12mm;
    background: #fff;
}
@page { size: A4; margin: 12mm; }

.hr-line{ border:0; border-top:2px solid #000; margin:10px 0 14px; }

.report-title{
    text-align:center;
    font-weight:700;
    margin: 6px 0 10px;
    letter-spacing:.4px;
    text-transform: uppercase;
}

.table-report th{
    background:#f2f2f2;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing:.4px;
    vertical-align: middle;
    text-align:center;
}
.table-report td{
    font-size: 11px;
    vertical-align: middle;
}
.meta-line{ font-size:12px; margin-bottom:10px; }
</style>

<div class="container py-3 no-print">
    <a href="<?php echo e(route('coordinator.reports')); ?>" class="btn btn-sm btn-outline-secondary">Back</a>
    <button class="btn btn-sm btn-primary" style="background:#0b2e5e;border-color:#0b2e5e;" onclick="window.print()">
        Print / Save as PDF
    </button>
</div>

<div class="report-wrap">
    <?php echo $__env->make('coordinator.reports.partials.a4-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="report-title">SUMMARY OF SCHOLARSHIPS</div>

    
    <div class="text-center" style="margin-top:2px; margin-bottom:10px; line-height:1.2;">
        <div style="font-weight:600;">Candijay Campus</div>
        <div style="text-decoration: underline; text-underline-offset: 3px;">
            1st and 2nd Semester, <?php echo e($academicYear ? ('AY ' . $academicYear) : 'AY not set'); ?>

        </div>
    </div>

    <table class="table table-bordered table-report">
        <thead>
            <tr>
                <th rowspan="2" style="width:50px;">No.</th>
                <th rowspan="2">Scholarship Program</th>
                <th colspan="2" style="width:220px;">Number of Scholars</th>
            </tr>
            <tr>
                <th style="width:110px;">
                    <?php echo e($sem1?->term ?? '1st Sem'); ?>

                </th>
                <th style="width:110px;">
                    <?php echo e($sem2?->term ?? '2nd Sem'); ?>

                </th>
            </tr>
        </thead>

        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-center"><?php echo e($i + 1); ?></td>
                    <td><?php echo e($r->scholarship_name); ?></td>
                    <td class="text-end"><?php echo e((int) $r->total_sem1); ?></td>
                    <td class="text-end"><?php echo e((int) $r->total_sem2); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted">No data found for this academic year.</td>
                </tr>
            <?php endif; ?>
        </tbody>

        <tfoot>
            <tr>
                <th colspan="2" class="text-end">Grand Total</th>
                <th class="text-end"><?php echo e($grandSem1); ?></th>
                <th class="text-end"><?php echo e($grandSem2); ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="meta-line text-end" style="margin-top:6px;">
        <strong>Overall Total:</strong> <?php echo e((int)$grandSem1 + (int)$grandSem2); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/reports/summary-of-scholarships.blade.php ENDPATH**/ ?>