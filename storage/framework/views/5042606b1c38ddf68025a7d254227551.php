

<?php $__env->startSection('content'); ?>
<?php
    // Theme
    $theme = '#003366';
    $soft  = '#e3f2fd';

    $fullName = trim(($user->firstname ?? '').' '.($user->lastname ?? ''));
    $initials = strtoupper(substr($user->firstname ?? 'U', 0, 1) . substr($user->lastname ?? 'S', 0, 1));

    // Safe defaults
    $collegeName = 'N/A';
    $courseName  = 'N/A';
    $yearLevel   = 'N/A';
    $scholarshipName = '';
    $batchNumber = '';

    if ($isStudent && !$isAdminLike) {
        // Enrollment is source of truth for Course + Semester (real-time)
        $courseName  = $activeEnrollment?->course?->course_name ?? 'N/A';

        // College derived from course->college (make sure Course has college() relationship)
        $collegeName = $activeEnrollment?->course?->college?->college_name ?? 'N/A';

        // Year level from users table (because Enrollment model currently has no year_level_id)
        $yearLevel   = $user->yearLevel?->year_level_name ?? 'N/A';

        // Scholar info (blank if none)
        $scholarshipName = $scholarRecord?->scholarship?->name ?? '';
        $batchNumber     = $scholarRecord?->batch_number ?? '';
    }
?>

<div class="container py-4">
    <div class="d-flex justify-content-center">
        <div class="w-100" style="max-width: 1050px;">

            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width:64px;height:64px;background:<?php echo e($soft); ?>;color:<?php echo e($theme); ?>;font-weight:800;font-size:1.4rem;">
                                <?php echo e($initials); ?>

                            </div>

                            <div>
                                <div class="fw-bold" style="color:<?php echo e($theme); ?>; font-size:1.25rem;">
                                    <?php echo e($fullName ?: 'N/A'); ?>

                                </div>
                                <div class="text-muted small"><?php echo e($user->bisu_email ?? 'N/A'); ?></div>

                                <div class="mt-2 d-flex flex-wrap gap-2">
                                    <span class="badge rounded-pill" style="background:<?php echo e($theme); ?>;">
                                        <?php echo e($user->userType->name ?? 'User'); ?>

                                    </span>

                                    <?php if($isStudent && !$isAdminLike && $scholarRecord): ?>
                                        <span class="badge rounded-pill bg-success">Scholar</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <div class="fw-bold" style="color:<?php echo e($theme); ?>;">My Profile</div>
                            <div class="text-muted small">
                                <?php if($isStudent && !$isAdminLike): ?>
                                    Academic information is managed by the administration.
                                <?php else: ?>
                                    Account information overview.
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
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

                
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="fw-bold mb-0" style="color:<?php echo e($theme); ?>;">
                                    <?php echo e(($isStudent && !$isAdminLike) ? 'Account & Academic Details' : 'Account Details'); ?>

                                </h5>
                                <span class="badge rounded-pill" style="background:<?php echo e($soft); ?>; color:<?php echo e($theme); ?>;">
                                    Read-only
                                </span>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="text-muted small fw-semibold">Complete Name</label>
                                    <input class="form-control" value="<?php echo e($fullName ?: 'N/A'); ?>" disabled>
                                </div>

                                
                                <?php if($isStudent && !$isAdminLike): ?>
                                    <div class="col-md-6">
                                        <label class="text-muted small fw-semibold">Student ID</label>
                                        <input class="form-control" value="<?php echo e($user->student_id ?? 'N/A'); ?>" disabled>
                                    </div>
                                <?php endif; ?>

                                <div class="col-md-6">
                                    <label class="text-muted small fw-semibold">Role</label>
                                    <input class="form-control" value="<?php echo e($user->userType->name ?? 'N/A'); ?>" disabled>
                                </div>

                                
                                <?php if($isStudent && !$isAdminLike): ?>

                                    <div class="col-12">
                                        <label class="text-muted small fw-semibold">Current Semester</label>
                                        <input class="form-control" value="<?php echo e($semesterLabel ?? 'N/A'); ?>" disabled>
                                    </div>

                                    <div class="col-12">
                                        <label class="text-muted small fw-semibold">College</label>
                                        <input class="form-control" value="<?php echo e($collegeName); ?>" disabled>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-muted small fw-semibold">Course</label>
                                        <div class="form-control bg-light" style="white-space: normal; height:auto;">
                                            <?php echo e($courseName); ?>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-muted small fw-semibold">Year Level</label>
                                        <input class="form-control" value="<?php echo e($yearLevel); ?>" disabled>
                                    </div>

                                    <div class="col-12">
                                        <label class="text-muted small fw-semibold">Scholarship</label>
                                        <input class="form-control"
                                               value="<?php echo e($scholarshipName); ?>"
                                               placeholder="(Blank if not a scholar)"
                                               disabled>
                                    </div>

                                    <?php if(!empty($batchNumber)): ?>
                                        <div class="col-md-6">
                                            <label class="text-muted small fw-semibold">Batch Number</label>
                                            <input class="form-control" value="<?php echo e($batchNumber); ?>" disabled>
                                        </div>
                                    <?php endif; ?>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>


                <?php if($isStudent && !$isAdminLike): ?>
                
                <div class="col-lg-6">

                    
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="fw-bold mb-0" style="color:<?php echo e($theme); ?>;">Contact Information</h5>
                                <span class="badge rounded-pill" style="background:<?php echo e($soft); ?>; color:<?php echo e($theme); ?>;">
                                    Editable
                                </span>
                            </div>

                            <form action="<?php echo e(route('profile.update-contact')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-semibold">Contact Number</label>

                                    <input type="text"
                                        name="contact_no"
                                        class="form-control"
                                        value="<?php echo e(old('contact_no', $user->contact_no ?? '')); ?>"
                                        placeholder="Enter contact number">

                                    <div class="small mt-1">
                                        <span class="text-muted">Current:</span>
                                        <span class="fw-semibold" style="color:#003366;">
                                            <?php echo e($user->contact_no ? $user->contact_no : 'N/A'); ?>

                                        </span>
                                    </div>

                                    <?php $__errorArgs = ['contact_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn fw-semibold px-4" style="background:#003366; color:#fff;">
                                        Save Contact
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3" style="color:<?php echo e($theme); ?>;">Change Password</h5>

                            <form action="<?php echo e(route('profile.update-password')); ?>" method="POST">
                                <?php echo csrf_field(); ?>

                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-semibold">Current Password</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                    <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-semibold">New Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                    <div class="text-muted small mt-1">Minimum 8 characters, with letters & numbers.</div>
                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-semibold">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn fw-semibold px-4"
                                            style="background:<?php echo e($theme); ?>; color:#fff;">
                                        Update Password
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/profile.blade.php ENDPATH**/ ?>