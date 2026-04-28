

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

    <div class="report-title">SCHOLARSHIP SCHOLARS REPORT</div>

    <div class="report-subtitle">
        <div class="campus">Candijay Campus</div>
        <div class="ay">
            <?php echo e($semester ? ($semester->term . ' • AY ' . $semester->academic_year) : 'Academic Year not set'); ?>

        </div>
    </div>

    <form method="GET" action="<?php echo e(route('coordinator.reports.scholarship-summary')); ?>">
        <input type="hidden" name="semester_id" value="<?php echo e($semesterId); ?>">

        <div class="mb-3" style="display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end;">
            <div style="flex:1; min-width:230px;">
                <label class="form-label" style="font-weight:600;">Choose Scholarship</label>
                <select name="scholarship_id" class="form-select form-select-sm">
                    <option value="">All Scholarships</option>
                    <?php $__currentLoopData = $scholarships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sch->id); ?>" <?php if($selectedScholarship && $selectedScholarship->id == $sch->id): echo 'selected'; endif; ?>><?php echo e($sch->scholarship_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-bisu btn-sm">Show Report</button>
            </div>
            <div style="margin-left:auto;">
                <a href="<?php echo e(route('coordinator.reports.scholarship-summary.pdf', ['semester_id' => $semesterId, 'scholarship_id' => $selectedScholarship?->id])); ?>" class="btn btn-outline-secondary btn-sm">
                    Download PDF
                </a>
            </div>
        </div>
    </form>

    <?php if($selectedScholarship): ?>
        <div class="meta-line" style="margin-bottom:12px;">
            <strong>Scholarship:</strong> <?php echo e($selectedScholarship->scholarship_name); ?>

            &nbsp;&bull;&nbsp;
            <strong>Total Scholars:</strong> <?php echo e($selectedScholarship->scholars->count()); ?>

        </div>

        <?php if($selectedScholarship->scholars->isEmpty()): ?>
            <div style="padding:16px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:10px;">No scholars found for this scholarship.</div>
        <?php else: ?>
            <table class="table-report">
                <thead>
                    <tr>
                        <th style="width:40px;">No.</th>
                        <th>Name</th>
                        <th style="width:100px;">Sex</th>
                        <th style="width:160px;">Course</th>
                        <th style="width:90px;">Year</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $selectedScholarship->scholars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $scholar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $user = $scholar->user;
                            $yearLevel = $user?->yearLevel?->year_level_name ?? ($scholar->scholarshipBatch?->semester?->term ? 'N/A' : 'N/A');
                        ?>
                        <tr>
                            <td style="text-align:center;"><?php echo e($i + 1); ?></td>
                            <td><?php echo e($user?->lastname ?? '-'); ?>, <?php echo e($user?->firstname ?? '-'); ?></td>
                            <td style="text-align:center;"><?php echo e($user?->sex ?? '-'); ?></td>
                            <td><?php echo e($user?->course?->course_name ?? '-'); ?></td>
                            <td style="text-align:center;"><?php echo e($yearLevel); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php else: ?>
        <?php $__currentLoopData = $scholarships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="margin-top:18px; margin-bottom:8px; font-weight:700;">
                <?php echo e($sch->scholarship_name); ?> (<?php echo e($sch->scholars->count()); ?> scholars)
            </div>

            <?php if($sch->scholars->isEmpty()): ?>
                <div style="padding:12px 14px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:10px; margin-bottom:8px; color:#6b7280;">No scholars for this scholarship.</div>
            <?php else: ?>
                <table class="table-report" style="margin-bottom:10px;">
                    <thead>
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th>Name</th>
                            <th style="width:100px;">Sex</th>
                            <th style="width:160px;">Course</th>
                            <th style="width:90px;">Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $sch->scholars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $scholar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $user = $scholar->user;
                                $yearLevel = $user?->yearLevel?->year_level_name ?? 'N/A';
                            ?>
                            <tr>
                                <td style="text-align:center;"><?php echo e($i + 1); ?></td>
                                <td><?php echo e($user?->lastname ?? '-'); ?>, <?php echo e($user?->firstname ?? '-'); ?></td>
                                <td style="text-align:center;"><?php echo e($user?->sex ?? '-'); ?></td>
                                <td><?php echo e($user?->course?->course_name ?? '-'); ?></td>
                                <td style="text-align:center;"><?php echo e($yearLevel); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/reports/scholarship-summary.blade.php ENDPATH**/ ?>