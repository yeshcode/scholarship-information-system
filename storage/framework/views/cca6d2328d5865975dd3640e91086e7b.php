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
            margin-bottom:8px;
            font-size:14px;
            letter-spacing:.3px;
            text-transform: uppercase;
        }

        .subtitle{
            text-align:center;
            font-size:12px;
            margin-bottom:12px;
        }

        table {
            width:100%;
            border-collapse: collapse;
        }

        th, td {
            border:1px solid #000;
            padding:6px;
        }

        th {
            background:#f2f2f2;
            text-align:center;
        }

        td {
            vertical-align: middle;
        }

        .text-left{ text-align:left; }
        .text-center{ text-align:center; }
        .text-right{ text-align:right; }

        tfoot th{
            font-weight:bold;
        }

        .meta-line{
            margin-top:8px;
            font-size:11px;
            text-align:right;
        }
    </style>
</head>

<body>
<div class="report-wrap">

    
    <?php echo $__env->make('coordinator.reports.pdf.partials.a4-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="title">
        SUMMARY OF SCHOLARSHIPS
    </div>

    <div class="subtitle">
        Candijay Campus • AY <?php echo e($academicYear ?? 'N/A'); ?>

    </div>

    
    <table>
        <thead>
            <tr>
                <th style="width:55%;">Scholarship</th>
                <th style="width:22%;">1st Sem</th>
                <th style="width:23%;">2nd Sem</th>
            </tr>
        </thead>

        <tbody>
            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="text-left"><?php echo e($r->scholarship_name); ?></td>
                    <td class="text-center"><?php echo e($r->total_sem1); ?></td>
                    <td class="text-center"><?php echo e($r->total_sem2); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>

        <tfoot>
            <tr>
                <th class="text-right">TOTAL</th>
                <th class="text-center"><?php echo e($grandSem1); ?></th>
                <th class="text-center"><?php echo e($grandSem2); ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="meta-line">
        <strong>Overall Total:</strong> <?php echo e((int)$grandSem1 + (int)$grandSem2); ?>

    </div>

</div>
</body>
</html><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/reports/pdf/summary-of-scholarships.blade.php ENDPATH**/ ?>