<?php $fullWidth = true; ?>


<?php $__env->startSection('page-content'); ?>
<style>
    :root{ --bisu:#003366; --line:#e5e7eb; --muted:#6b7280; }
    .card-bisu{ border:1px solid var(--line); border-radius:14px; overflow:hidden; }
    .thead-bisu th{ background:var(--bisu); color:#fff; font-size:.78rem; text-transform:uppercase; letter-spacing:.03em; white-space:nowrap; }
</style>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show">
    <?php echo e(session('success')); ?> <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <?php echo e(session('error')); ?> <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php
    $batch = $release->scholarshipBatch;
    $schName = $batch?->scholarship?->scholarship_name ?? 'N/A';
    $batchLabel = $batch ? ('Batch ' . $batch->batch_number) : 'N/A';
    $semLabel = $release->semester
        ? (($release->semester->term ?? $release->semester->semester_name) . ' ' . $release->semester->academic_year)
        : 'N/A';
?>

<div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
    <div>
        <h4 class="fw-bold mb-1">Stipend Release / Liquidation Form</h4>
        <div class="text-muted small">
            <?php echo e($schName); ?> • <?php echo e($batchLabel); ?> • Release Semester: <?php echo e($semLabel); ?> • Title:
            <span class="fw-semibold"><?php echo e($release->title); ?></span>
            • Amount: <span class="fw-semibold">₱ <?php echo e(number_format((float)$release->amount, 2)); ?></span>
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
        Scholars List (Preview)
        <span class="text-muted small">• liquidation columns</span>
    </div>

    <div class="table-responsive">
         <?php
            $columns = collect([
            (object)['key'=>'student_id','label'=>'Student ID'],
            (object)['key'=>'lastname','label'=>'Last Name'],
            (object)['key'=>'firstname','label'=>'First Name'],
            (object)['key'=>'middlename','label'=>'Middle Name'],
            (object)['key'=>'year_level','label'=>'Year Level'],
            (object)['key'=>'course','label'=>'Course'],
            (object)['key'=>'amount','label'=>'Amount'],
            (object)['key'=>'date_received','label'=>'Date Received'],
            (object)['key'=>'printed_name','label'=>'Printed Name'],
            (object)['key'=>'remarks','label'=>'Remarks'],
            (object)['key'=>'signature','label'=>'Signature'],
            ]);
        ?>

        <table class="table table-bordered table-hover mb-0">
            <thead class="thead-bisu">
                <tr>
                    <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th><?php echo e($c->label); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $scholars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $u = $s->user;

                        // ✅ year level per semester (release semester)
                        $en = $s->enrollments->first();
                        $semYearLevel = $en?->yearLevel?->year_level ?? null;
                        $fallbackYearLevel = $u?->yearLevel?->year_level ?? $u?->year_level ?? '';

                        $yearLevel = $semYearLevel ?? $fallbackYearLevel;
                    ?>
                    <tr>
                        <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $val = match($c->key) {
                                    // ✅ added columns
                                    'student_id' => $u?->student_id ?? '',
                                    'amount'     => '₱ ' . number_format((float)$release->amount, 2),
                                    'remarks'    => '',

                                    // typical liquidation fields
                                    'printed_name'  => '',
                                    'date_received' => '',

                                    // existing
                                    'firstname'  => $u?->firstname ?? '',
                                    'middlename' => $u?->middlename ?? '',
                                    'lastname'   => $u?->lastname ?? '',
                                    'year_level' => $yearLevel,
                                    'course'     => $u?->course?->course_name ?? '',
                                    'college'    => $u?->college?->college_name ?? '',
                                    'signature'  => '',
                                    default      => '',
                                };
                            ?>
                            <td><?php echo e($val); ?></td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="<?php echo e($columns->count()); ?>" class="text-center text-muted py-4">
                            No scholars found in this batch.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/stipend-release-form.blade.php ENDPATH**/ ?>