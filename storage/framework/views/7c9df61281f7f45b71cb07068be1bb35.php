

<?php $__env->startSection('page-content'); ?>
<style>
    :root{
        --bisu-blue:#0b2e5e;
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
        width:210mm;
        min-height:297mm;
        margin:0 auto 24px auto;
        padding:12mm;
        background:#fff;
        box-shadow:var(--paper-shadow);
    }

    @page{
        size:A4;
        margin:12mm;
    }

    .report-title{
        text-align:center;
        font-weight:700;
        margin:6px 0 10px;
        letter-spacing:.4px;
        text-transform:uppercase;
        font-size:16px;
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

    .report-subtitle .sem{
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    .meta-line{
        font-size:12px;
        margin-bottom:10px;
    }

    .table-report{
        width:100%;
        border-collapse:collapse;
    }

    .table-report th,
    .table-report td{
        border:1px solid #000;
        padding:6px 6px;
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

    .footer-block{
        margin-top: 26px;
        font-size: 12px;
    }

    .footer-row{
        display:flex;
        justify-content: space-between;
        gap: 16px;
        margin-top: 18px;
    }

    .footer-sign{
        width: 55%;
        text-align: center;
    }

    .footer-date{
        width: 30%;
        text-align: center;
    }

    .footer-line{
        border-top: 1px solid #000;
        margin-top: 26px;
    }

    .doc-code{
        margin-top: 8px;
        font-size: 11px;
        text-align: right;
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

    <div class="report-title">LIST OF SCHOLARS AND GRANTEES</div>

    <?php
        $semLabel = $semester
            ? ($semester->term . ', AY ' . $semester->academic_year)
            : 'Semester not set';
    ?>

    <div class="report-subtitle">
        <div class="campus">Candijay Campus</div>
        <div class="sem"><?php echo e($semLabel); ?></div>
    </div>

    <div class="meta-line">
        <strong>Total:</strong> <?php echo e($scholars->count()); ?>

    </div>

    <table class="table-report">
        <thead>
            <tr>
                <th rowspan="2" style="width:38px;">No.</th>
                <th rowspan="2" style="width:160px;">Scholarship Program</th>
                <th colspan="3">Name</th>
                <th rowspan="2" style="width:55px;">Sex</th>
                <th rowspan="2" style="width:140px;">Course</th>
                <th rowspan="2" style="width:85px;">Year Level</th>
            </tr>
            <tr>
                <th style="width:120px;">Last</th>
                <th style="width:120px;">First</th>
                <th style="width:45px;">MI</th>
            </tr>
        </thead>

        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $scholars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $u = $s->user;

                    $en = $u?->enrollments?->firstWhere('semester_id', $semesterId)
                        ?? $u?->enrollments?->first();

                    $yearLevelLabel =
                        $en?->yearLevel?->year_level_name
                        ?? $u?->yearLevel?->year_level_name
                        ?? '-';

                    $sex = $u?->sex ?? '-';

                    $miRaw = $u?->middlename ?? '';
                    $mi = $miRaw ? strtoupper(mb_substr($miRaw, 0, 1)) : '';
                ?>

                <tr>
                    <td style="text-align:center;"><?php echo e($i + 1); ?></td>
                    <td><?php echo e($s->scholarship->scholarship_name ?? '-'); ?></td>
                    <td><?php echo e($u?->lastname ?? '-'); ?></td>
                    <td><?php echo e($u?->firstname ?? '-'); ?></td>
                    <td style="text-align:center;"><?php echo e($mi); ?></td>
                    <td style="text-align:center;"><?php echo e($sex); ?></td>
                    <td><?php echo e($u?->course?->course_name ?? '-'); ?></td>
                    <td style="text-align:center;"><?php echo e($yearLevelLabel); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">No scholars found for this semester.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer-block">
        <div class="footer-row">
            <div class="footer-sign">
                <div class="footer-line"></div>
                <div>Admission &amp; Scholarship Director</div>
            </div>

            <div class="footer-date">
                <div class="footer-line"></div>
                <div>Date</div>
            </div>
        </div>

        <div class="doc-code">
            F-SAS-ADS-007 &nbsp; | &nbsp; Rev. 2 &nbsp; | &nbsp; 07/01/24 &nbsp; | &nbsp; Page 1 of 1
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/reports/list-of-scholars.blade.php ENDPATH**/ ?>