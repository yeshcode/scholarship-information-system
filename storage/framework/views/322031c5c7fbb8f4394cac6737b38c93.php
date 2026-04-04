<?php
    $bisuSeal = public_path('images/reports/bisu-seal.png');
    $bagong   = public_path('images/reports/bagong-pilipinas.png');
    $tuv      = public_path('images/reports/tuv.png');
?>

<table style="width:100%; border-collapse:collapse; margin-bottom:6px;">
    <tr>
        <td style="width:90px; text-align:center; vertical-align:middle; border:none;">
            <?php if(file_exists($bisuSeal)): ?>
                <img src="<?php echo e($bisuSeal); ?>" alt="BISU Seal" style="width:80px; height:auto;">
            <?php endif; ?>
        </td>

        <td style="text-align:center; vertical-align:middle; border:none; line-height:1.2; padding:0 8px;">
            <div style="font-size:12px;">Republic of the Philippines</div>
            <div style="font-size:15px; font-weight:bold; letter-spacing:.3px;">BOHOL ISLAND STATE UNIVERSITY</div>
            <div style="font-size:12px;">Cogtong, Candijay, Bohol, 6312, Philippines</div>
            <div style="font-size:12px; font-weight:bold; margin-top:2px;">Office of the Admission and Scholarship</div>
            <div style="font-size:11px; margin-top:4px;">Balance | Integrity | Stewardship | Uprightness</div>
        </td>

        <td style="width:90px; text-align:center; vertical-align:middle; border:none;">
            <?php if(file_exists($bagong)): ?>
                <img src="<?php echo e($bagong); ?>" alt="Bagong Pilipinas" style="width:75px; height:auto; display:block; margin:0 auto 6px;">
            <?php endif; ?>

            <?php if(file_exists($tuv)): ?>
                <img src="<?php echo e($tuv); ?>" alt="TUV" style="width:55px; height:auto; display:block; margin:0 auto;">
            <?php endif; ?>
        </td>
    </tr>
</table>

<hr style="border:0; border-top:2px solid #000; margin:6px 0 12px;"><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/reports/pdf/partials/a4-header.blade.php ENDPATH**/ ?>