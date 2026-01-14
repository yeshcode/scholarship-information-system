<?php $fullWidth = true; ?>  


<?php $__env->startSection('content'); ?>

<style>
    /* Page Title */
    .page-title-blue {
        font-weight: 700;
        font-size: 1.9rem;
        color: #003366;
    }

    /* Table Wrapper */
    .table-card {
        background: #ffffff;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e5e7eb; /* thin border */
    }

    /* Table Styling */
    .modern-table thead {
        background-color: #003366;
        color: white;
    }

    .modern-table th,
    .modern-table td {
        border: 1px solid #e5e7eb; /* thin cell borders */
        padding: 12px 14px;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .modern-table tbody tr:nth-child(even) {
        background-color: #f9fafb; /* zebra */
    }

    .modern-table tbody tr:hover {
        background-color: #e8f1ff; /* subtle blue hover */
        transition: 0.15s ease-in-out;
    }

    /* Buttons */
    .btn-bisu {
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 6px;
    }

    .btn-bisu-primary {
        background-color: #003366;
        color: #fff;
        border: 1px solid #003366;
    }

    .btn-bisu-primary:hover {
        background-color: #002244;
        border-color: #002244;
    }

    .btn-bisu-outline-primary {
        color: #003366;
        border: 1px solid #003366;
    }

    .btn-bisu-outline-primary:hover {
        background-color: #003366;
        color: #fff;
    }

    .btn-bisu-outline-danger {
        color: #b30000;
        border: 1px solid #b30000;
    }

    .btn-bisu-outline-danger:hover {
        background-color: #b30000;
        color: #fff;
    }

</style>

<div class="container py-4">

    
    <div class="mb-4">
        <h2 class="page-title-blue">Manage User Types</h2>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <div class="d-flex justify-content-end mb-3">
        <a href="<?php echo e(route('admin.user-types.create')); ?>" 
           class="btn btn-bisu btn-bisu-primary shadow-sm">
            + Add User Type
        </a>
    </div>

    
    <div class="table-card shadow-sm">
        <div class="table-responsive" style="max-height: calc(100vh - 260px);">

            <table class="table modern-table mb-0">

                <thead class="sticky-top">
                    <tr>
                        <th>User Type Name</th>
                        <th>Description</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $userTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-semibold text-dark">
                                <?php echo e($userType->name); ?>

                            </td>

                            <td class="text-secondary">
                                <?php echo e($userType->description ?? 'No description'); ?>

                            </td>

                            <td class="text-center">

                                <a href="<?php echo e(route('admin.user-types.edit', $userType->id)); ?>"
                                   class="btn btn-sm btn-bisu btn-bisu-outline-primary me-1">
                                    ✏️ Edit
                                </a>

                                <a href="<?php echo e(route('admin.user-types.delete', $userType->id)); ?>"
                                   class="btn btn-sm btn-bisu btn-bisu-outline-danger">
                                    🗑️ Delete
                                </a>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                No user types found.  
                                <a href="<?php echo e(route('admin.user-types.create')); ?>" class="text-primary fw-bold">
                                    Add one now
                                </a>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>

        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/user-type.blade.php ENDPATH**/ ?>