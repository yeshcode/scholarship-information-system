

<?php $__env->startSection('content'); ?>

<?php
    $user = auth()->user();
    $isAdminLike = $user->hasRole('Super Admin') || $user->hasRole('Scholarship Coordinator');

    // Safely get active enrollment for students
    $activeEnrollment = !$isAdminLike 
        ? $user->enrollments->where('status', 'active')->first()
        : null;

    $semesterLabel = $activeEnrollment && $activeEnrollment->semester
        ? $activeEnrollment->semester->term . ' ' . $activeEnrollment->semester->academic_year
        : 'N/A';

    // Scholar info (if any)
    $scholarRecord = (!$isAdminLike && $user->isScholar())
        ? $user->scholarsAsStudent->first()
        : null;
?>

<div class="container py-4 d-flex justify-content-center">
    <div class="card shadow-sm border-0 w-100" style="max-width: 900px;">
        <div class="card-body p-4 p-md-5">

            
            
<div class="text-center mb-4">
    <h1 class="fw-bold" style="color:#003366; font-size:2rem;">
        My Profile
    </h1>
</div>


<div class="d-flex align-items-center mb-4">
    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
         style="width: 56px; height: 56px; background-color: #e3f2fd; color: #003366; font-weight: 700; font-size: 1.3rem;">
        <?php echo e(strtoupper(substr($user->firstname, 0, 1) . substr($user->lastname, 0, 1))); ?>

    </div>
    <div>
        <h5 class="mb-0 fw-semibold" style="color:#003366;"><?php echo e($user->firstname); ?> <?php echo e($user->lastname); ?></h5>
        <small class="text-muted">
            <?php echo e($user->bisu_email); ?>

        </small>
    </div>
</div>


            
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show mb-3">
                    <?php echo e(session('success')); ?>

                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-3">
                    <strong>There were some issues:</strong>
                    <ul class="mb-0 mt-1">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="small"><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row g-4">

                
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3" style="color:#003366;">Account Information</h5>

                    <div class="mb-2">
                        <span class="d-block text-muted small fw-semibold">Name</span>
                        <span class="fw-semibold">
                            <?php echo e($user->firstname); ?> <?php echo e($user->lastname); ?>

                        </span>
                    </div>

                    <?php if($isAdminLike): ?>
                        <div class="mb-2">
                            <span class="d-block text-muted small fw-semibold">Position / Role</span>
                            <span class="badge bg-primary">
                                <?php echo e($user->userType->name ?? 'N/A'); ?>

                            </span>
                        </div>
                    <?php else: ?>
                        
                        <div class="mb-2">
                            <span class="d-block text-muted small fw-semibold">College</span>
                            <span class="fw-semibold">
                                <?php echo e($user->college->college_name ?? 'N/A'); ?>

                            </span>
                        </div>

                        <div class="mb-2">
                            <span class="d-block text-muted small fw-semibold">Course & Year Level</span>
                            <span class="fw-semibold">
                                <?php echo e($user->section->course->course_name ?? 'N/A'); ?>

                                <?php if($user->yearLevel): ?>
                                    • <?php echo e($user->yearLevel->year_level_name); ?>

                                <?php endif; ?>
                            </span>
                        </div>

                        <div class="mb-2">
                            <span class="d-block text-muted small fw-semibold">Section</span>
                            <span class="fw-semibold">
                                <?php echo e($user->section->section_name ?? 'N/A'); ?>

                            </span>
                        </div>

                        <div class="mb-2">
                            <span class="d-block text-muted small fw-semibold">Current Semester</span>
                            <span class="fw-semibold">
                                <?php echo e($semesterLabel); ?>

                            </span>
                        </div>

                        <?php if($scholarRecord): ?>
                            <hr class="my-3">
                            <h6 class="fw-bold mb-2" style="color:#003366;">Scholarship Details</h6>

                            <div class="mb-2">
                                <span class="d-block text-muted small fw-semibold">Scholarship</span>
                                <span class="fw-semibold">
                                    <?php echo e($scholarRecord->scholarship->name ?? 'N/A'); ?>

                                </span>
                            </div>

                            <div class="mb-2">
                                <span class="d-block text-muted small fw-semibold">Batch Number</span>
                                <span class="fw-semibold">
                                    <?php echo e($scholarRecord->batch_number ?? 'N/A'); ?>

                                </span>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3" style="color:#003366;">Change Password</h5>

                    <form action="<?php echo e(route('profile.update-password')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-bold text-dark">
                                Current Password
                            </label>
                            <input
                                type="password"
                                name="current_password"
                                id="current_password"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold text-dark">
                                New Password
                            </label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-bold text-dark">
                                Confirm New Password
                            </label>
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary fw-semibold px-4">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

            </div> 
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/profile.blade.php ENDPATH**/ ?>