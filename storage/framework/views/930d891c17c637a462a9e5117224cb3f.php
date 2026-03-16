

<?php $__env->startSection('content'); ?>
<style>
    :root{
        --brand:#003366;
        --brand-2:#0b2e5e;
        --bg:#f4f7fb;
        --line:rgba(0,0,0,.08);
        --muted:#6c757d;
    }

    .page-wrap{
        background: var(--bg);
        border-radius: 16px;
        padding: 1.25rem;
    }

    .card-soft{
        border: 1px solid rgba(0,0,0,.04);
        border-radius: 16px;
        box-shadow: 0 10px 26px rgba(0,51,102,.08);
        background: #fff;
        overflow: hidden;
    }

    /* Only for College + Course cells */
    .wrap-cell{
        white-space: normal !important;
        word-break: break-word;
        overflow-wrap: anywhere;
        line-height: 1.25;
    }

    /* Table */
    .table-modern thead th{
        position: sticky;
        top: 0;
        z-index: 2;
        background: linear-gradient(0deg, rgba(0,51,102,.06), rgba(0,51,102,.06)), #fff;
        color: var(--brand);
        text-transform: uppercase;
        font-size: .72rem;
        letter-spacing: .08em;
        border-bottom: 1px solid var(--line) !important;
        padding-top: .55rem !important;
        padding-bottom: .55rem !important;
    }

    .table-modern td{
        font-size: .86rem;
        padding-top: .45rem !important;
        padding-bottom: .45rem !important;
        border-color: rgba(0,0,0,.06);
    }

    .row-issue{
        background: rgba(255,193,7,.18) !important;
    }

    .btn-brand{
        background: var(--brand);
        border-color: var(--brand);
    }
    .btn-brand:hover{
        background: var(--brand-2);
        border-color: var(--brand-2);
    }

    .chip{
        border-radius: 999px;
        font-weight: 700;
        padding: .35rem .65rem;
        border: 1px solid rgba(0,51,102,.16);
        background: #fff;
        color: var(--brand);
        font-size: .85rem;
    }

    .badge-soft-success{
        background: rgba(25,135,84,.12);
        color: #198754;
        border: 1px solid rgba(25,135,84,.22);
    }
    .badge-soft-danger{
        background: rgba(220,53,69,.10);
        color: #dc3545;
        border: 1px solid rgba(220,53,69,.18);
    }

    /* Confirm overlay */
    .overlay{
        background: rgba(10, 18, 32, .45);
        backdrop-filter: blur(3px);
    }
    .confirm-card{
        border-radius: 18px;
        box-shadow: 0 18px 45px rgba(0,0,0,.22);
        border: 1px solid rgba(255,255,255,.18);
        overflow: hidden;
    }
</style>

<div class="container py-4">
    <div class="page-wrap">

        
        <div class="card card-soft mb-3">
            <div class="card-body d-flex flex-wrap align-items-start justify-content-between gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h5 class="mb-0 fw-bold" style="color:var(--brand); letter-spacing:.2px;">
                            Bulk Upload Preview
                        </h5>
                        <span class="badge rounded-pill bg-primary-subtle text-primary border">
                            Preview
                        </span>
                    </div>

                    <div class="small mt-1" style="color:var(--muted);">
                        Review the list before confirming. Rows with issues cannot be uploaded.
                    </div>

                    <div class="mt-2 d-flex flex-wrap gap-2">
                        <span class="chip">
                            Total: <span class="fw-bold"><?php echo e($totalCount); ?></span>
                        </span>
                        <span class="chip <?php echo e(($issuesCount ?? 0) > 0 ? 'text-warning border-warning-subtle' : 'text-success border-success-subtle'); ?>">
                            Issues: <span class="fw-bold"><?php echo e($issuesCount); ?></span>
                        </span>
                    </div>
                </div>

                <div class="d-flex gap-2 ms-auto">
                    <a href="<?php echo e(route('admin.users.bulk-upload-form')); ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                        Upload another file
                    </a>
                    <a href="<?php echo e(route('admin.dashboard', ['page' => 'manage-users'])); ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        Back to Users
                    </a>
                </div>
            </div>
        </div>

        <?php if(session('error')): ?>
            <div class="alert alert-danger border-0 shadow-sm py-2 mb-3" style="border-radius:14px;">
                <div class="small"><?php echo e(session('error')); ?></div>
            </div>
        <?php endif; ?>

        
        <div class="card card-soft">
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 560px; overflow:auto;">
                    <table class="table table-hover table-sm mb-0 align-middle table-modern" style="table-layout:fixed;">
                        <thead>
                            <tr>
                                <th>Line</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Bisu Email</th>
                                <th>College</th>
                                <th>Course</th>
                                <th>Year Level</th>
                                <th>Result</th>
                            </tr>
                        </thead>

                        <tbody class="text-nowrap">
                            <?php $__currentLoopData = $preview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="<?php echo e(!empty($r['issues']) ? 'row-issue' : ''); ?>">

                                    <td><div class="text-truncate" title="<?php echo e($r['line']); ?>"><?php echo e($r['line']); ?></div></td>
                                    <td><div class="text-truncate fw-semibold" title="<?php echo e($r['student_id']); ?>"><?php echo e($r['student_id']); ?></div></td>

                                    <td>
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
                                        <div class="text-truncate" title="<?php echo e($full); ?>"><?php echo e($full); ?></div>
                                    </td>

                                    <td><div class="text-truncate" title="<?php echo e($r['bisu_email']); ?>"><?php echo e($r['bisu_email']); ?></div></td>

                                    <td class="wrap-cell"><?php echo e($r['college']); ?></td>
                                    <td class="wrap-cell"><?php echo e($r['course']); ?></td>

                                    <td>
                                        <span class="badge rounded-pill bg-light text-dark border">
                                            <?php echo e($r['year_level']); ?>

                                        </span>
                                    </td>

                                    <td style="min-width: 260px;">
                                        <?php if(empty($r['issues'])): ?>
                                            <span class="badge rounded-pill badge-soft-success">✓ OK</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill badge-soft-danger">Already Registered</span>
                                            <div class="text-muted mt-1 text-truncate" style="font-size:.82rem;"
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

            <div class="card-footer d-flex justify-content-between align-items-center gap-2 bg-white"
                 style="border-top:1px solid rgba(0,0,0,.06);">

                <div class="small text-muted">
                    Only valid rows can be confirmed.
                </div>

                <?php if($issuesCount > 0): ?>
                    <button class="btn btn-brand btn-sm rounded-pill px-4" disabled title="Fix issues first">
                        Confirm Upload
                    </button>
                <?php else: ?>
                    <button type="button" class="btn btn-brand btn-sm rounded-pill px-4" id="openConfirmCard">
                        Confirm Upload
                    </button>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>


<?php if($issuesCount === 0): ?>
<div id="confirmOverlay" class="d-none position-fixed top-0 start-0 w-100 h-100 overlay" style="z-index:1055;">
    <div class="d-flex align-items-center justify-content-center h-100 p-3">
        <div class="card confirm-card" style="width: 560px; max-width: 100%;">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-start px-4 py-3">
                <div>
                    <h6 class="mb-1 fw-bold" style="color:var(--brand);">Confirm Bulk Upload</h6>
                    <div class="text-muted small">Please review before confirming.</div>
                </div>
                <button type="button" class="btn-close" aria-label="Close" id="closeConfirmCard"></button>
            </div>

            <div class="card-body px-4" style="max-height: 50vh; overflow:auto;">
                <div class="p-3 rounded-4 border" style="background: rgba(0,51,102,.04); border-color: rgba(0,51,102,.12) !important;">
                    <div class="mb-2 small">This will register <b><?php echo e($totalCount); ?></b> students.</div>
                    <div class="mb-2 small">Default password will be their <b>student_id</b>.</div>
                    <div class="text-muted small mb-0">Make sure the list is correct before confirming.</div>
                </div>

                <div class="mt-3 small text-muted">
                    By confirming, you agree that the uploaded data is accurate and ready to be stored.
                </div>
            </div>

            <div class="card-footer bg-white border-0 d-flex justify-content-end gap-2 px-4 py-3">
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" id="backConfirmCard">
                    Back
                </button>

                <form method="POST" action="<?php echo e(route('admin.users.bulk-upload.confirm')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-brand btn-sm rounded-pill px-4">
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