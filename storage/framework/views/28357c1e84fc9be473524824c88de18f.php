

<?php $__env->startSection('content'); ?>
<style>
    :root{
        --bisu-blue:#003366;
        --bisu-blue-2:#0b4a85;
        --soft:#f5f7fb;
    }
    .page-title-bisu{ font-weight:800; font-size:1.6rem; color:var(--bisu-blue); margin:0; }
    .subtext{ color:#6b7280; font-size:.9rem; }

    .btn-bisu{
        background:var(--bisu-blue)!important;
        border-color:var(--bisu-blue)!important;
        color:#fff!important;
        font-weight:700;
    }
    .btn-bisu:hover{ background:var(--bisu-blue-2)!important; border-color:var(--bisu-blue-2)!important; }

    .card-bisu{ border:1px solid #e5e7eb; border-radius:14px; overflow:hidden; }
    .thead-bisu th{
        background:var(--bisu-blue)!important;
        color:#fff!important;
        font-size:.78rem;
        letter-spacing:.03em;
        text-transform:uppercase;
        white-space:nowrap;
        border:0 !important;
        padding: .85rem .85rem;
    }

    /* cleaner table look */
    .table-clean td{
        vertical-align:middle;
        white-space:nowrap;
        font-size:.92rem;
        padding: .85rem .85rem;
        border-color:#eef2f7 !important;
    }
    .table-clean tbody tr:hover{
        background:#f8fbff;
        transition:.12s ease-in-out;
    }

    .badge-soft{
        background:#f1f5f9 !important;
        color:#334155 !important;
        border:1px solid #e2e8f0;
        font-weight:700;
    }
</style>

<div class="mx-auto" style="max-width: 980px;">
    <div class="d-flex align-items-end justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h2 class="page-title-bisu">Stipend History</h2>
            <div class="subtext">View your stipend records and confirm once you have claimed your cheque.</div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo e(session('error')); ?>

            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card card-bisu shadow-sm">
        <div class="card-body p-0">
            <?php if($stipends->isEmpty()): ?>
                <div class="p-4 text-center text-muted">No stipend history available.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 table-clean">
                        <thead class="thead-bisu">
                            <tr>
                                <th>Release Title</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Released At</th>
                                <th>Confirmed At</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php $__currentLoopData = $stipends; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stipend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $status = strtolower((string)$stipend->status);
                                $isReleased = $status === 'released';
                                $isClaimed = !empty($stipend->claimed_at);

                                // status badge class
                                $badgeClass = 'badge-soft';
                                if ($status === 'for_release') $badgeClass = 'bg-primary-subtle text-primary border border-primary-subtle';
                                if ($status === 'released')     $badgeClass = 'bg-success-subtle text-success border border-success-subtle';
                                if ($status === 'returned')     $badgeClass = 'bg-danger-subtle text-danger border border-danger-subtle';
                                if ($status === 'waiting')      $badgeClass = 'bg-warning-subtle text-warning border border-warning-subtle';
                            ?>

                            <tr>
                                <td class="fw-semibold">
                                    <?php echo e($stipend->stipendRelease->title ?? 'N/A'); ?>

                                </td>

                                <td>
                                    ₱ <?php echo e(number_format((float)$stipend->amount_received, 2)); ?>

                                </td>

                                <td>
                                    <span class="badge <?php echo e($badgeClass); ?>">
                                        <?php echo e(strtoupper(str_replace('_',' ', $stipend->status))); ?>

                                    </span>
                                </td>

                                <td class="text-muted">
                                    <?php if($stipend->received_at): ?>
                                        <?php echo e(\Carbon\Carbon::parse($stipend->received_at)->format('M d, Y h:i A')); ?>

                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>

                                <td class="text-muted">
                                    <?php if($stipend->claimed_at): ?>
                                        <?php echo e(\Carbon\Carbon::parse($stipend->claimed_at)->format('M d, Y h:i A')); ?>

                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>

                                <td class="text-end">
                                    
                                    <?php if($isReleased && !$isClaimed): ?>
                                        <form method="POST"
                                            action="<?php echo e(route('student.stipends.claim', $stipend->id)); ?>"
                                            onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerText='Processing...'; return confirm('Confirm: You already claimed your cheque?');"
                                            class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-bisu btn-sm">
                                                Confirmed
                                            </button>
                                        </form>
                                    <?php elseif($isClaimed): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            CLAIMED
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="p-3">
                    <?php echo e($stipends->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/stipend-history.blade.php ENDPATH**/ ?>