

<?php $__env->startSection('content'); ?>
<style>
  /* Only for College + Course cells */
  .wrap-cell{
    white-space: normal !important;      /* allow wrapping */
    word-break: break-word;              /* break long words */
    overflow-wrap: anywhere;             /* prevent overlay */
    line-height: 1.2;
  }
</style>
<div class="container py-4">

    
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h5 class="mb-0 fw-bold text-primary" style="letter-spacing:.3px;">Bulk Upload Preview</h5>
                    <span class="badge bg-light text-dark border">
                        Preview
                    </span>
                </div>

                <div class="small" style="color:#6c757d;">
                    Review the list before confirming. Rows with issues cannot be uploaded.
                </div>

                <div class="mt-2 d-flex flex-wrap gap-2">
                    <span class="badge bg-primary-subtle text-primary border">
                        Total: <span class="fw-semibold"><?php echo e($totalCount); ?></span>
                    </span>
                    <span class="badge <?php echo e(($issuesCount ?? 0) > 0 ? 'bg-warning-subtle text-warning border' : 'bg-success-subtle text-success border'); ?>">
                        Issues: <span class="fw-semibold"><?php echo e($issuesCount); ?></span>
                    </span>
                </div>
            </div>

            <div class="d-flex gap-2 ms-auto">
                <a href="<?php echo e(route('admin.users.bulk-upload-form')); ?>" class="btn btn-outline-primary btn-sm">
                    Upload another file
                </a>
                <a href="<?php echo e(route('admin.users.bulk-upload-form')); ?>" class="btn btn-outline-primary btn-sm">
                    Cancel
                </a>
            </div>
        </div>
    </div>

    <?php if(session('error')): ?>
        <div class="alert alert-danger py-2 mb-3">
            <div class="small"><?php echo e(session('error')); ?></div>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            
            <div class="table-responsive" style="max-height: 520px; overflow:auto;">
                <table class="table table-striped table-hover table-sm mb-0 align-middle" style="table-layout:fixed;">

                    <thead class="table-light position-sticky top-0" style="z-index:2;">
                        <tr class="small">
                            <th class="py-1 text-primary fw-semibold">LINE</th>
                            <th class="py-1 text-primary fw-semibold">STUDENT ID</th>
                            <th class="py-1 text-primary fw-semibold">NAME</th>
                            <th class="py-1 text-primary fw-semibold">BISU EMAIL</th>
                            <th class="py-1 text-primary fw-semibold">COLLEGE</th>
                            <th class="py-1 text-primary fw-semibold">COURSE</th>
                            <th class="py-1 text-primary fw-semibold">YEAR LEVEL</th>
                            <th class="py-1 text-primary fw-semibold">RESULT</th>
                        </tr>
                    </thead>

                    
                    <tbody class="small text-nowrap">
                        <?php $__currentLoopData = $preview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="<?php echo e(!empty($r['issues']) ? 'table-warning' : ''); ?>">

                                <td class="py-1">
                                    <div class="text-truncate" title="<?php echo e($r['line']); ?>">
                                        <?php echo e($r['line']); ?>

                                    </div>
                                </td>

                                <td class="py-1">
                                    <div class="text-truncate" title="<?php echo e($r['student_id']); ?>">
                                        <?php echo e($r['student_id']); ?>

                                    </div>
                                </td>

                                <td class="py-1">
                                    <?php
                                        $mi = '';
                                        if(!empty($r['middlename'])){
                                            $parts = preg_split('/\s+/', trim($r['middlename']));
                                            $initial = strtoupper(substr($parts[0] ?? '', 0, 1));
                                            $mi = $initial ? ' ' . $initial . '.' : '';
                                        }
                                        $suffix = !empty($r['suffix']) ? ' ' . $r['suffix'] : '';
                                        $full = trim($r['lastname'] . ', ' . $r['firstname'] . $mi . $suffix);
                                    ?>

                                    <div class="text-truncate" title="<?php echo e($full); ?>">
                                        <?php echo e($full); ?>

                                    </div>
                                </td>

                                <td class="py-1">
                                    <div class="text-truncate" title="<?php echo e($r['bisu_email']); ?>">
                                        <?php echo e($r['bisu_email']); ?>

                                    </div>
                                </td>

                               <td class="py-1 wrap-cell"><?php echo e($r['college']); ?></td>

                                <td class="py-1 wrap-cell"><?php echo e($r['course']); ?></td>


                                <td class="py-1"><?php echo e($r['year_level']); ?></td>
                                <td class="py-1" style="min-width: 240px;">
                                    <?php if(empty($r['issues'])): ?>
                                        <span class="badge bg-success">OK</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Has issues</span>
                                        <div class="text-muted mt-1 text-truncate"
                                            style="font-size:.82rem;"
                                            title="<?php echo e(implode('; ', $r['issues'])); ?>">

                                            <?php echo e(implode('; ', $r['issues'])); ?>

                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>

                </table>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end gap-2 bg-white">
            <?php if($issuesCount > 0): ?>
                <button class="btn btn-primary btn-sm" disabled title="Fix issues first">
                    Confirm Upload
                </button>
            <?php else: ?>
                <button type="button" class="btn btn-primary btn-sm" id="openConfirmCard">
                    Confirm Upload
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php if($issuesCount === 0): ?>
<div id="confirmOverlay"
     class="d-none position-fixed top-0 start-0 w-100 h-100"
     style="background: rgba(0,0,0,.35); z-index: 1055;">
    <div class="d-flex align-items-center justify-content-center h-100 p-3">
        <div class="card shadow-lg border-0"
             style="width: 520px; max-width: 100%; border-radius: 14px;">

            
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1 fw-semibold">Confirm Bulk Upload</h6>
                    <div class="text-muted small">Please review before confirming</div>
                </div>
                <button type="button" class="btn-close" aria-label="Close" id="closeConfirmCard"></button>
            </div>

            
            <div class="card-body" style="max-height: 50vh; overflow:auto;">
                <div class="border rounded-3 p-3 bg-light">
                    <div class="mb-2 small">
                        This will register <b><?php echo e($totalCount); ?></b> students.
                    </div>
                    <div class="mb-2 small">
                        Default password will be their <b>student_id</b>.
                    </div>
                    <div class="text-muted small">
                        Make sure the list is correct before confirming.
                    </div>
                </div>

                <div class="mt-3 text-muted small">
                    By confirming, you agree that the uploaded data is accurate and ready to be stored.
                </div>
            </div>

            
            <div class="card-footer bg-white border-0 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm" id="backConfirmCard">
                    Back
                </button>

                <form method="POST" action="<?php echo e(route('admin.users.bulk-upload.confirm')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-primary btn-sm">
                        Yes, Confirm
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const overlay = document.getElementById('confirmOverlay');
    const openBtn = document.getElementById('openConfirmCard');
    const closeBtn = document.getElementById('closeConfirmCard');
    const backBtn = document.getElementById('backConfirmCard');

    const open = () => overlay?.classList.remove('d-none');
    const close = () => overlay?.classList.add('d-none');

    openBtn?.addEventListener('click', open);
    closeBtn?.addEventListener('click', close);
    backBtn?.addEventListener('click', close);

    overlay?.addEventListener('click', (e) => {
        if (e.target === overlay) close();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') close();
    });
});
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/users-bulk-upload-preview.blade.php ENDPATH**/ ?>