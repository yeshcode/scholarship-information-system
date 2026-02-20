

<?php $__env->startSection('content'); ?>

<style>
    :root{
        --brand:#0b2e5e;
        --brand-2:#123f85;
        --muted:#6b7280;
        --line:#e5e7eb;
        --bg:#f4f7fb;
        --danger-soft:#fdecec;
    }

    body{ background: var(--bg); }

    .edit-shell{
        border:1px solid var(--line);
        border-radius:20px;
        background:#fff;
        box-shadow: 0 18px 38px rgba(15,23,42,.08);
        overflow:hidden;
    }

    .edit-head{
        padding: 1.2rem 1.3rem;
        border-bottom:1px solid var(--line);
        background: linear-gradient(180deg,#ffffff 0%,#f9fbff 100%);
    }

    .edit-title{
        font-weight:900;
        font-size:1.6rem;
        color:var(--brand);
        margin:0;
        letter-spacing:.2px;
    }

    .edit-sub{
        font-size:.9rem;
        color:var(--muted);
        margin-top:.3rem;
    }

    .edit-body{
        padding:1.3rem;
    }

    .form-label{
        font-weight:800;
        font-size:.9rem;
        color:#0f172a;
    }

    .form-control{
        border-radius:14px;
        border:1px solid var(--line);
        padding:.7rem .9rem;
    }

    .form-control:focus{
        border-color: rgba(11,46,94,.35);
        box-shadow: 0 0 0 .2rem rgba(11,46,94,.12);
    }

    .btn-bisu{
        background: var(--brand);
        border:1px solid rgba(11,46,94,.3);
        color:#fff;
        font-weight:800;
        border-radius:14px;
        padding:.6rem 1.1rem;
    }

    .btn-bisu:hover{
        background: var(--brand-2);
        color:#fff;
    }

    .btn-soft{
        background:#f8fafc;
        border:1px solid var(--line);
        font-weight:800;
        border-radius:14px;
        padding:.6rem 1rem;
    }

    .btn-soft:hover{
        background:#eef2ff;
    }

    .danger-zone{
        margin-top:2rem;
        border:1px solid rgba(220,53,69,.25);
        background: var(--danger-soft);
        border-radius:16px;
        padding:1rem 1.1rem;
    }

    .danger-title{
        font-weight:900;
        color:#b91c1c;
        margin-bottom:.4rem;
    }

    .danger-text{
        font-size:.88rem;
        color:#7f1d1d;
    }

</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">

            <div class="edit-shell">

                
                <div class="edit-head">
                    <h2 class="edit-title">Edit User Type</h2>
                    <div class="edit-sub">
                        Update the details of this user type.
                    </div>
                </div>

                <div class="edit-body">

                    
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
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
                            <label for="name" class="form-label">Name</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control"
                                   value="<?php echo e(old('name', $userType->name)); ?>"
                                   required>
                        </div>

                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description"
                                      id="description"
                                      rows="3"
                                      class="form-control"
                                      placeholder="Short description (optional)"><?php echo e(old('description', $userType->description)); ?></textarea>
                        </div>

                        
                        <div class="mb-4">
                            <label for="dashboard_url" class="form-label">Dashboard URL</label>
                            <input type="text"
                                   name="dashboard_url"
                                   id="dashboard_url"
                                   class="form-control"
                                   value="<?php echo e(old('dashboard_url', $userType->dashboard_url)); ?>"
                                   placeholder="/coordinator/dashboard">
                            <div class="form-text">
                                Example: <code>/coordinator/dashboard</code>
                            </div>
                        </div>

                        
                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-bisu">
                                💾 Update User Type
                            </button>

                            <a href="<?php echo e(route('admin.dashboard', ['page' => 'user-type'])); ?>"
                               class="btn btn-soft">
                                Cancel
                            </a>
                        </div>

                    </form>

                    
                    <div class="danger-zone">
                        <div class="danger-title">Danger Zone</div>
                        <div class="danger-text mb-3">
                            Deleting this user type may affect users assigned to this role.
                            Proceed carefully.
                        </div>

                        <a href="<?php echo e(route('admin.user-types.delete', $userType->id)); ?>"
                           class="btn btn-outline-danger fw-bold">
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