<?php $fullWidth = true; ?>


<?php $__env->startSection('page-content'); ?>
<style>
    :root{
        --bisu-blue:#003366;
        --bisu-blue-2:#0b4a85;
        --danger:#dc3545;
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
</style>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo e(session('success')); ?>

        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="d-flex align-items-end justify-content-between flex-wrap gap-2 mb-3">
    <div>
        <h2 class="page-title-bisu">Cheque Claim Notifications</h2>
        <div class="subtext">Notifications triggered when scholars confirm they already claimed their cheque.</div>
        <div class="mt-1">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                Unread: <strong><?php echo e($unreadCount ?? 0); ?></strong>
            </span>
        </div>
    </div>

    <a href="<?php echo e(route('coordinator.manage-stipends')); ?>" class="btn btn-bisu btn-sm">
        Back to Manage Stipends
    </a>
</div>

<div class="card card-bisu shadow-sm">
    <div class="card-body">
        <?php if($notifications->isEmpty()): ?>
            <div class="text-center text-muted py-4">No claim notifications yet.</div>
        <?php else: ?>
            <div class="list-group">
                <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="list-group-item d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <strong><?php echo e($n->title); ?></strong>
                                <?php if(!$n->is_read): ?>
                                    <span class="badge bg-danger">NEW</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-muted small">
                                <?php echo e(\Carbon\Carbon::parse($n->sent_at ?? $n->created_at)->format('M d, Y h:i A')); ?>

                            </div>
                            <div class="mt-2">
                                <?php echo e($n->message); ?>

                            </div>
                        </div>

                        <div class="text-end">
                            <?php if(!$n->is_read): ?>
                                <form method="POST" action="<?php echo e(route('coordinator.notifications.read', $n->id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn btn-outline-secondary btn-sm" type="submit">
                                        Mark as read
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted small">Read</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="mt-3">
                <?php echo e($notifications->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/stipend-claim-notifications.blade.php ENDPATH**/ ?>