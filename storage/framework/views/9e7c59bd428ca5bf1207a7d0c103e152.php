

<?php $__env->startSection('page-content'); ?>

<style>
    :root{
        /* BISU / system navy theme */
        --brand:#003366;
        --brand-2:#00284f;
        --soft: rgba(0,51,102,.10);
        --soft-2: rgba(0,51,102,.06);
        --border: rgba(0,0,0,.08);
        --muted:#6b7280;
    }

    .brand-text{ color: var(--brand) !important; }

    .page-head{
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 16px;
        background: #fff;
        box-shadow: 0 6px 18px rgba(0,0,0,.06);
    }

    .form-card{
        border: 1px solid var(--border);
        border-radius: 18px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 10px 26px rgba(0,0,0,.08);
    }

    .topbar{ height: 6px; background: var(--brand); }

    .btn-brand{
        background: var(--brand);
        border-color: var(--brand);
        color:#fff;
    }
    .btn-brand:hover{
        background: var(--brand-2);
        border-color: var(--brand-2);
        color:#fff;
    }
    .btn-outline-brand{
        border-color: rgba(0,51,102,.35);
        color: var(--brand);
    }
    .btn-outline-brand:hover{
        background: var(--soft);
        border-color: rgba(0,51,102,.35);
        color: var(--brand);
    }

    .section-box{
        border: 1px solid var(--border);
        background: var(--soft-2);
        border-radius: 14px;
        padding: 14px;
    }

    .help-text{ color: var(--muted); font-size: .86rem; }
    .req-textarea{ min-height: 220px; }
    .desc-textarea{ min-height: 140px; }
</style>


<div class="page-head mb-3">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h4 class="mb-1 fw-bold brand-text">Create Scholarship</h4>
            <div class="text-muted">
                Add a new scholarship and set its status and application timeline.
            </div>
        </div>

        <a href="<?php echo e(route('coordinator.manage-scholarships')); ?>" class="btn btn-outline-brand">
            ← Back
        </a>
    </div>
</div>


<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <div class="fw-semibold">Please fix the following:</div>
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($e); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?php echo e(route('coordinator.scholarships.store')); ?>" method="POST" class="form-card">
    <?php echo csrf_field(); ?>
    <div class="topbar"></div>

    <div class="card-body p-4">

        
        <div class="mb-3">
            <label class="form-label fw-semibold brand-text mb-1">Scholarship Name <span class="text-danger">*</span></label>
            <input type="text"
                   name="scholarship_name"
                   class="form-control"
                   value="<?php echo e(old('scholarship_name')); ?>"
                   placeholder="e.g., DOST, TES, TDP"
                   required>
            
        </div>

        
        <div class="row g-3">
            <div class="col-12 col-md-8">
                <label class="form-label fw-semibold brand-text mb-1">Benefactor <span class="text-danger">*</span></label>
                <input type="text"
                       name="benefactor"
                       class="form-control"
                       value="<?php echo e(old('benefactor')); ?>"
                       placeholder="e.g., Government / Private Sponsor"
                       required>
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold brand-text mb-1">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="open" <?php echo e(old('status','open') === 'open' ? 'selected' : ''); ?>>Open</option>
                    <option value="closed" <?php echo e(old('status') === 'closed' ? 'selected' : ''); ?>>Closed</option>
                </select>
                
            </div>
        </div>

        
        <div class="row g-3 mt-1">
            <div class="col-12 col-md-6">
                <div class="section-box">
                    <label class="form-label fw-semibold brand-text mb-1">Application Date</label>
                    <input type="date"
                           name="application_date"
                           class="form-control"
                           value="<?php echo e(old('application_date')); ?>">
                    
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="section-box">
                    <label class="form-label fw-semibold brand-text mb-1">Deadline</label>
                    <input type="date"
                           name="deadline"
                           class="form-control"
                           value="<?php echo e(old('deadline')); ?>">
                    
                </div>
            </div>
        </div>

        
        <div class="mt-3">
            <div class="section-box">
                <div class="fw-bold brand-text mb-2">Description <span class="text-danger">*</span></div>
                <textarea name="description"
                          class="form-control desc-textarea"
                          rows="5"
                          placeholder="Brief overview of the scholarship..."
                          required><?php echo e(old('description')); ?></textarea>
                
            </div>
        </div>

        
        <div class="mt-3">
            <div class="section-box">
                <div class="fw-bold brand-text mb-2">Requirements <span class="text-danger">*</span></div>
                <textarea name="requirements"
                          class="form-control req-textarea"
                          rows="8"
                          placeholder="Enter the requirement..."
                          required><?php echo e(old('requirements')); ?></textarea>
                
            </div>
        </div>

    </div>

    <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 px-4 py-3">
        <div class="text-muted small">
            Fields marked with <span class="text-danger">*</span> are required.
        </div>

        <div class="d-flex gap-2">
            <a href="<?php echo e(route('coordinator.manage-scholarships')); ?>" class="btn btn-outline-brand">
                Cancel
            </a>
            <button type="submit" class="btn btn-brand">
                Create Scholarship
            </button>
        </div>
    </div>
</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/create-scholarship.blade.php ENDPATH**/ ?>