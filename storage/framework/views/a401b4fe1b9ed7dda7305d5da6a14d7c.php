

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    
                    <h2 class="fw-bold mb-2 text-danger" style="font-size: 1.5rem;">
                        Confirm Deletion
                    </h2>
                    <p class="text-muted mb-3">
                        Are you sure you want to delete this user type? This action cannot be undone and may affect related users/roles.
                    </p>

                    
                    <div class="border rounded p-3 bg-light mb-4">
                        <h5 class="fw-semibold mb-1"><?php echo e($userType->name); ?></h5>
                        <p class="mb-1 text-secondary">
                            <?php echo e($userType->description ?? 'No description provided.'); ?>

                        </p>
                        <p class="mb-0 text-muted">
                            <strong>Dashboard URL:</strong> <?php echo e($userType->dashboard_url ?? 'N/A'); ?>

                        </p>
                    </div>

                    
                    <form method="POST" action="<?php echo e(route('admin.user-types.destroy', $userType->id)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-danger px-4">
                                🗑️ Yes, Delete
                            </button>
                            <a href="<?php echo e(route('admin.dashboard', ['page' => 'user-type'])); ?>"
                               class="btn btn-outline-secondary">
                                ❌ Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/user-types-delete.blade.php ENDPATH**/ ?>