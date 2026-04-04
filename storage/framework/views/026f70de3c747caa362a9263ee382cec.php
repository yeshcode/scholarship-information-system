

<?php $__env->startSection('page-content'); ?>
<style>
    :root{
        --bisu-blue:#0b2e5e;
        --line:#d1d5db;
        --paper-shadow:0 10px 25px rgba(0,0,0,.08);
    }

    body{
        background:#f3f4f6;
    }

    .no-print{
        margin-bottom: 18px;
    }

    .report-actions{
        max-width: 210mm;
        margin: 0 auto 16px auto;
        display:flex;
        gap:10px;
        align-items:center;
    }

    .btn-bisu{
        background:var(--bisu-blue);
        border-color:var(--bisu-blue);
        color:#fff;
        font-weight:600;
    }

    .btn-bisu:hover{
        background:#174a8b;
        border-color:#174a8b;
        color:#fff;
    }

    .report-wrap{
        width: 210mm;
        min-height: 297mm;
        margin: 0 auto 24px auto;
        padding: 12mm;
        background: #fff;
        box-shadow: var(--paper-shadow);
    }

    @page{
        size: A4;
        margin: 12mm;
    }

    .hr-line{
        border:0;
        border-top:2px solid #000;
        margin:10px 0 14px;
    }

    .report-title{
        text-align:center;
        font-weight:700;
        margin: 6px 0 10px;
        letter-spacing:.4px;
        text-transform: uppercase;
        font-size: 16px;
    }

    .report-subtitle{
        text-align:center;
        margin-top:2px;
        margin-bottom:10px;
        line-height:1.2;
        font-size:13px;
    }

    .report-subtitle .campus{
        font-weight:600;
    }

    .report-subtitle .ay{
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    .table-report{
        width:100%;
        border-collapse:collapse;
    }

    .table-report th,
    .table-report td{
        border:1px solid #000;
        padding:6px 8px;
    }

    .table-report th{
        background:#f2f2f2 !important;
        font-size:11px;
        text-transform:uppercase;
        letter-spacing:.4px;
        vertical-align:middle;
        text-align:center;
    }

    .table-report td{
        font-size:11px;
        vertical-align:middle;
    }

    .meta-line{
        font-size:12px;
        margin-top:8px;
        text-align:right;
    }

    @media print{
        body{
            background:#fff !important;
        }

        .no-print,
        .sidebar,
        .navbar,
        .main-header,
        .app-header,
        .menu,
        .topbar,
        .footer,
        aside,
        nav{
            display:none !important;
        }

        .content-wrapper,
        .main-content,
        .container,
        .container-fluid,
        .page-content{
            margin:0 !important;
            padding:0 !important;
            width:100% !important;
            max-width:100% !important;
        }

        .report-wrap{
            width:100% !important;
            min-height:auto !important;
            margin:0 !important;
            padding:0 !important;
            box-shadow:none !important;
        }
    }
</style>

<div class="no-print">
    <div class="report-actions">
        <a href="<?php echo e(route('coordinator.reports')); ?>" class="btn btn-sm btn-outline-secondary">Back</a>
        <button class="btn btn-sm btn-bisu" onclick="window.print()">Print</button>
    </div>
</div>

<div class="report-wrap">
    <?php echo $__env->make('coordinator.reports.partials.a4-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="report-title">SUMMARY OF SCHOLARSHIPS</div>

    <div class="report-subtitle">
        <div class="campus">Candijay Campus</div>
        <div class="ay">
            1st and 2nd Semester, <?php echo e($academicYear ? ('AY ' . $academicYear) : 'AY not set'); ?>

        </div>
    </div>

    <table class="table-report">
        <thead>
            <tr>
                <th rowspan="2" style="width:50px;">No.</th>
                <th rowspan="2">Scholarship Program</th>
                <th colspan="2" style="width:220px;">Number of Scholars</th>
            </tr>
            <tr>
                <th style="width:110px;"><?php echo e($sem1?->term ?? '1st Sem'); ?></th>
                <th style="width:110px;"><?php echo e($sem2?->term ?? '2nd Sem'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="text-align:center;"><?php echo e($i + 1); ?></td>
                    <td><?php echo e($r->scholarship_name); ?></td>
                    <td style="text-align:right;"><?php echo e((int) $r->total_sem1); ?></td>
                    <td style="text-align:right;"><?php echo e((int) $r->total_sem2); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" style="text-align:center;">No data found for this academic year.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" style="text-align:right;">Grand Total</th>
                <th style="text-align:right;"><?php echo e($grandSem1); ?></th>
                <th style="text-align:right;"><?php echo e($grandSem2); ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="meta-line">
        <strong>Overall Total:</strong> <?php echo e((int)$grandSem1 + (int)$grandSem2); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/reports/summary-of-scholarships.blade.php ENDPATH**/ ?>