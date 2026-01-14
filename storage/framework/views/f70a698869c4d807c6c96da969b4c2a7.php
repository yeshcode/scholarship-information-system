

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    
                    <h2 class="fw-bold mb-1 text-primary" style="font-size: 1.6rem;">
                        Edit User Type
                    </h2>
                    <p class="text-muted mb-4">
                        Update the details of this user type.
                    </p>

                    
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    
                    <form method="POST" action="<?php echo e(route('admin.user-types.update', $userType->id)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">
                                Name
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control"
                                   value="<?php echo e(old('name', $userType->name)); ?>"
                                   required>
                        </div>

                        
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">
                                Description
                            </label>
                            <textarea name="description"
                                      id="description"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Short description of this user type (optional)"><?php echo e(old('description', $userType->description)); ?></textarea>
                        </div>

                        
                        <div class="mb-4">
                            <label for="dashboard_url" class="form-label fw-semibold">
                                Dashboard URL
                            </label>
                            <input type="text"
                                   name="dashboard_url"
                                   id="dashboard_url"
                                   class="form-control"
                                   value="<?php echo e(old('dashboard_url', $userType->dashboard_url)); ?>"
                                   placeholder="/coordinator/dashboard">
                            <small class="text-muted">
                                Optional. Example: <code>/coordinator/dashboard</code>
                            </small>
                        </div>

                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button type="submit" class="btn btn-primary px-4">
                                    💾 Update User Type
                                </button>
                                <a href="<?php echo e(route('admin.dashboard', ['page' => 'user-type'])); ?>"
                                   class="btn btn-outline-secondary ms-2">
                                    ❌ Cancel
                                </a>
                            </div>
                        </div>
                    </form>

                    
                    <hr class="mt-4">

                    <div class="mt-3">
                        <h5 class="text-danger fw-bold mb-2">Danger Zone</h5>
                        <p class="text-muted mb-3">
                            Deleting this user type may affect users assigned to this role. Proceed with caution.
                        </p>
                        <a href="<?php echo e(route('admin.user-types.delete', $userType->id)); ?>"
                           class="btn btn-outline-danger">
                            🗑️ Delete User Type
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/user-types-edit.blade.php ENDPATH**/ ?>