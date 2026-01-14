

<?php $__env->startSection('content'); ?>

<div class="container py-5 d-flex justify-content-center">

    <div class="card shadow-sm border-0 p-4" style="width: 100%; max-width: 600px;">

        
        <h2 class="fw-bold text-center mb-4" style="color: #003366;">
            System Settings
        </h2>

        
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo e(session('success')); ?>

                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <strong>Fix the following errors:</strong>
                <ul class="mt-2 mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="small"><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        
        <form action="<?php echo e(route('settings.update')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            
            <div class="mb-3">
                <label class="form-label fw-bold text-dark">System Name</label>
                <input 
                    type="text" 
                    name="system_name" 
                    value="<?php echo e($settings->system_name); ?>"
                    class="form-control"
                    required
                >
            </div>

            
            <div class="mb-3">
                <label class="form-label fw-bold text-dark">System Logo</label>
                <input 
                    type="file" 
                    name="logo_path" 
                    class="form-control"
                    accept="image/png, image/jpeg"
                >

                <?php if($settings->logo_path): ?>
                    <div class="text-center mt-3">
                        <img 
                            src="<?php echo e(asset('storage/' . $settings->logo_path)); ?>"
                            alt="Logo"
                            class="img-fluid rounded border p-2"
                            style="max-height: 120px;">
                    </div>
                <?php endif; ?>

                <small class="text-muted">Recommended: PNG/JPG (128×128 or higher)</small>
            </div>

            
            <div class="text-center mt-4">
                <button class="btn btn-primary px-4 py-2 fw-bold">
                    Save Changes
                </button>
            </div>

        </form>

    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/settings.blade.php ENDPATH**/ ?>