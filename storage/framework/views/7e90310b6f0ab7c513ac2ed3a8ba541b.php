<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: A4;
            margin: 12mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        .report-wrap{
            width:100%;
        }

        .title {
            text-align:center;
            font-weight:bold;
            margin-bottom:10px;
            font-size:14px;
            letter-spacing:.3px;
        }

        table {
            width:100%;
            border-collapse: collapse;
        }

        th, td {
            border:1px solid #000;
            padding:5px;
        }

        th {
            background:#f2f2f2;
            text-align:center;
        }

        td {
            vertical-align: middle;
        }

        .text-center{ text-align:center; }
    </style>
</head>

<body>
<div class="report-wrap">

    
    <?php echo $__env->make('coordinator.reports.pdf.partials.a4-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="title">
        LIST OF SCHOLARS AND GRANTEES<br>
        <?php echo e($semester ? ($semester->term.' • '.$semester->academic_year) : 'N/A'); ?>

    </div>

    
    <table>
        <thead>
            <tr>
                <th style="width:5%">#</th>
                <th style="width:30%">Name</th>
                <th style="width:25%">Course</th>
                <th style="width:15%">Year Level</th>
                <th style="width:25%">Scholarship</th>
            </tr>
        </thead>

        <tbody>
            <?php $__currentLoopData = $scholars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $u = $s->user;
                    $en = $u?->enrollments?->first();
                    $yl = $en?->yearLevel?->year_level_name ?? $u?->yearLevel?->year_level_name ?? 'N/A';
                ?>

                <tr>
                    <td class="text-center"><?php echo e($i + 1); ?></td>
                    <td><?php echo e($u?->lastname); ?>, <?php echo e($u?->firstname); ?></td>
                    <td><?php echo e($u?->course?->course_name ?? 'N/A'); ?></td>
                    <td class="text-center"><?php echo e($yl); ?></td>
                    <td><?php echo e($s->scholarship?->scholarship_name ?? 'N/A'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

</div>
</body>
</html><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/reports/pdf/list-of-scholars.blade.php ENDPATH**/ ?>