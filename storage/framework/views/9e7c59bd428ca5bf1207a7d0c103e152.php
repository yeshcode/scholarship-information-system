

<?php $__env->startSection('page-content'); ?>
<h3 class="fw-bold mb-3">Create Scholarship</h3>

<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($e); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?php echo e(route('coordinator.scholarships.store')); ?>" method="POST" class="card border-0 shadow-sm">
    <?php echo csrf_field(); ?>

    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-semibold">Name</label>
            <input type="text" name="scholarship_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Requirements</label>
            <textarea name="requirements" class="form-control" rows="5" required></textarea>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">Benefactor</label>
                <input type="text" name="benefactor" class="form-control" required>
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select" required>
                    <option value="open">Open</option>
                    <option value="closed">Closed</option>
                </select>
            </div>

                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold">Application Date</label>
                    <input type="date" name="application_date" class="form-control">
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold">Deadline</label>
                    <input type="date" name="deadline" class="form-control">
                </div>
        </div>
    </div>

    <div class="card-footer bg-white d-flex gap-2">
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="<?php echo e(route('coordinator.manage-scholarships')); ?>" class="btn btn-light">Cancel</a>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/create-scholarship.blade.php ENDPATH**/ ?>