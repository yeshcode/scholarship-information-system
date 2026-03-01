

<?php $__env->startSection('content'); ?>
<?php
    // Theme (same as your system)
    $theme = '#003366';
    $soft  = '#eaf2ff';
    $bg    = '#f4f7fb';
    $line  = '#e5e7eb';

    $fullName = trim(($user->firstname ?? '').' '.($user->lastname ?? ''));
    $initials = strtoupper(substr($user->firstname ?? 'U', 0, 1) . substr($user->lastname ?? 'S', 0, 1));
    $enrolledStatus = \App\Models\Enrollment::STATUS_ENROLLED;

    // Defaults (student-only values)
    $collegeName = 'N/A';
    $courseName  = 'N/A';
    $yearLevel   = 'N/A';
    $scholarshipName = '';
    $batchNumber = '';

    if ($isStudent && !$isAdminLike) {
        $courseName  = $activeEnrollment?->course?->course_name ?? 'N/A';
        $collegeName = $activeEnrollment?->course?->college?->college_name ?? 'N/A';
        $yearLevel   = $user->yearLevel?->year_level_name ?? 'N/A';

        $scholarshipName = $scholarRecord?->scholarship?->scholarship_name ?? '';
        $batchNumber     = $scholarRecord?->batch_number ?? '';
    }

    $isScholar = ($isStudent && !$isAdminLike && !is_null($scholarRecord));
?>

<style>
    body{ background: <?php echo e($bg); ?>; }

    .profile-wrap{
        font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
    }

    .card-soft{
        border: 1px solid <?php echo e($line); ?>;
        border-radius: 18px;
        box-shadow: 0 10px 26px rgba(0,0,0,.06);
        overflow: hidden;
        background: #fff;
    }

    .profile-hero{
        background: linear-gradient(135deg, <?php echo e($theme); ?> 0%, #0b3d8f 55%, #1b5fbf 100%);
        color: #fff;
    }

    .avatar{
        width: 72px; height: 72px;
        border-radius: 20px;
        background: rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.22);
        display:flex; align-items:center; justify-content:center;
        font-weight: 800;
        font-size: 1.35rem;
        letter-spacing: .04em;
        flex: 0 0 auto;
    }

    .pill{
        display:inline-flex;
        align-items:center;
        padding: .35rem .65rem;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 700;
        border: 1px solid rgba(255,255,255,.22);
        background: rgba(255,255,255,.12);
        color:#fff;
        gap:.35rem;
        white-space: nowrap;
    }

    .pill-light{
        border: 1px solid rgba(11,46,94,.18);
        background: rgba(11,46,94,.06);
        color: <?php echo e($theme); ?>;
        white-space: nowrap;
    }

    .label{
        font-size: .78rem;
        color: #6b7280;
        font-weight: 700;
        margin-bottom: .35rem;
    }

    .value-box{
        background: #f9fbff;
        border: 1px solid <?php echo e($line); ?>;
        border-radius: 12px;
        padding: .65rem .8rem;
        min-height: 44px;
        display:flex;
        align-items:center;
        color: #111827;
        font-weight: 600;
        font-size: .95rem;
        overflow-wrap: anywhere;
        word-break: break-word;
    }
    .value-box.muted{ color:#6b7280; font-weight:600; }

    /* Tabs */
    .nav-pills .nav-link{
        font-weight: 800;
        color: <?php echo e($theme); ?>;
        border-radius: 12px;
    }
    .nav-pills .nav-link.active{
        background: <?php echo e($theme); ?>;
        color:#fff;
    }

    /* Brand button */
    .btn-brand{
        background: <?php echo e($theme); ?>;
        color:#fff;
        font-weight: 800;
        border-radius: 12px;
        padding: .55rem 1rem;
    }
    .btn-brand:hover{ opacity: .92; color:#fff; }

    /* Table header */
    .table thead th{
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #6b7280;
        border-bottom: 1px solid <?php echo e($line); ?>;
    }

    /* ===================== RESPONSIVE UPGRADES ===================== */

    /* Make tabs scrollable on mobile */
    .tabs-scroll{
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 2px;
        scrollbar-width: thin;
    }
    .tabs-scroll::-webkit-scrollbar{ height: 6px; }
    .tabs-scroll::-webkit-scrollbar-thumb{ background: rgba(0,0,0,.15); border-radius: 999px; }

    .tabs-scroll .nav{
        flex-wrap: nowrap;
        white-space: nowrap;
    }
    .tabs-scroll .nav .nav-item{ flex: 0 0 auto; }

    /* Reduce hero padding on small screens */
    @media (max-width: 575.98px){
        .profile-hero .p-4{ padding: 1rem !important; }
        .card-soft .p-3{ padding: 1rem !important; }
        .avatar{ width: 58px; height: 58px; border-radius: 16px; font-size: 1.1rem; }
        .hero-name{ font-size: 1.05rem !important; }
        .hero-email{ font-size: .85rem !important; }
        .btn-brand{ width: 100%; }
    }

    /* Better hero layout on small screens */
    .hero-top{
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .hero-left{
        display:flex;
        align-items:center;
        gap: 1rem;
        min-width: 0;
        flex: 1 1 520px;
    }
    .hero-meta{
        min-width: 0;
    }
    .hero-meta .hero-email{
        opacity:.9;
        font-size:.92rem;
        overflow-wrap:anywhere;
        word-break: break-word;
    }
    .hero-right{
        max-width: 340px;
        flex: 1 1 260px;
    }
    @media (max-width: 767.98px){
        .hero-right{
            max-width: 100%;
            text-align: left !important;
        }
    }

    /* Make inner card sections not feel tight on small screens */
    .section-card{
        background:#fff;
    }

    /* Make hero pills smaller on mobile only */
    @media (max-width: 575.98px){
        .pill{
            padding: .22rem .5rem;
            font-size: .68rem;
            font-weight: 700;
            gap: .25rem;
            border-radius: 999px;
        }

        /* tighter spacing between pills */
        .hero-meta .d-flex.flex-wrap.gap-2{
            gap: .35rem !important;
            margin-top: .5rem !important;
        }
    }
</style>

<div class="container py-4 profile-wrap">
    <div class="mx-auto" style="max-width: 1050px;">

        
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

        
        <div class="card-soft profile-hero mb-4">
            <div class="p-4 p-md-5">
                <div class="hero-top">
                    <div class="hero-left">
                        <div class="avatar"><?php echo e($initials); ?></div>

                        <div class="hero-meta">
                            <div class="fw-bold hero-name" style="font-size:1.25rem; line-height:1.2;">
                                <?php echo e($fullName ?: 'N/A'); ?>

                            </div>
                            <div class="hero-email">
                                <?php echo e($user->bisu_email ?? $user->email ?? 'N/A'); ?>

                            </div>

                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <span class="pill">
                                    <?php echo e($user->userType->name ?? 'User'); ?>

                                </span>

                                <?php if($isStudent && !$isAdminLike): ?>
                                    <span class="pill">
                                        Semester: <?php echo e($semesterLabel ?? 'N/A'); ?>

                                    </span>
                                <?php endif; ?>

                                <?php if($isScholar): ?>
                                    <span class="pill" style="background: rgba(34,197,94,.20); border-color: rgba(34,197,94,.25);">
                                        Scholar
                                    </span>
                                <?php else: ?>
                                    <?php if($isStudent && !$isAdminLike): ?>
                                        <span class="pill" style="background: rgba(255,255,255,.10);">
                                            Non-Scholar
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="hero-right text-start text-md-end">
                        <div class="fw-bold" style="font-size:1rem;">My Profile</div>
                        <div style="opacity:.9; font-size:.9rem;">
                            <?php if($isStudent && !$isAdminLike): ?>
                                Your academic information is updated from your enrollment record.
                            <?php else: ?>
                                Account overview and security settings.
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card-soft">
            <div class="p-3 p-md-4">

                
                <div class="tabs-scroll mb-4">
                    <ul class="nav nav-pills gap-2" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tabOverview" type="button">
                                Overview
                            </button>
                        </li>

                        <?php if($isStudent && !$isAdminLike): ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabAcademic" type="button">
                                    Academic
                                </button>
                            </li>
                        <?php endif; ?>

                        <?php if($isStudent && !$isAdminLike): ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabContact" type="button">
                                    Contact
                                </button>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabSecurity" type="button">
                                Security
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">

                    
                    <div class="tab-pane fade show active" id="tabOverview">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="card border-0 section-card">
                                    <div class="card-body p-0">
                                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                            <div class="fw-bold" style="color:<?php echo e($theme); ?>;">Account Information</div>
                                            <span class="pill-light">Read-only</span>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="label">Complete Name</div>
                                                <div class="value-box"><?php echo e($fullName ?: 'N/A'); ?></div>
                                            </div>

                                            <?php if($isStudent && !$isAdminLike): ?>
                                                <div class="col-md-6">
                                                    <div class="label">Student ID</div>
                                                    <div class="value-box"><?php echo e($user->student_id ?? 'N/A'); ?></div>
                                                </div>
                                            <?php endif; ?>

                                            <div class="col-md-6">
                                                <div class="label">Role</div>
                                                <div class="value-box"><?php echo e($user->userType->name ?? 'N/A'); ?></div>
                                            </div>

                                            <div class="col-12">
                                                <div class="label">Email</div>
                                                <div class="value-box"><?php echo e($user->bisu_email ?? $user->email ?? 'N/A'); ?></div>
                                            </div>

                                            <?php if($isStudent && !$isAdminLike): ?>
                                                <div class="col-12">
                                                    <div class="label">Year Level</div>
                                                    <div class="value-box"><?php echo e($yearLevel); ?></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="col-lg-6">
                                <div class="card border-0 section-card">
                                    <div class="card-body p-0">
                                        <div class="fw-bold mb-3" style="color:<?php echo e($theme); ?>;">Quick Summary</div>

                                        <div class="row g-3">
                                            <?php if($isStudent && !$isAdminLike): ?>
                                                <div class="col-12">
                                                    <div class="label">Current Semester</div>
                                                    <div class="value-box"><?php echo e($semesterLabel ?? 'N/A'); ?></div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="label">College</div>
                                                    <div class="value-box"><?php echo e($collegeName); ?></div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="label">Course</div>
                                                    <div class="value-box"><?php echo e($courseName); ?></div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="label">Scholar Status</div>
                                                    <div class="value-box <?php echo e($isScholar ? '' : 'muted'); ?>">
                                                        <?php echo e($isScholar ? 'Scholar' : 'Non-Scholar'); ?>

                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="col-12">
                                                    <div class="value-box muted">
                                                        No academic profile is shown for this role.
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <?php if($isStudent && !$isAdminLike): ?>
                    <div class="tab-pane fade" id="tabAcademic">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="fw-bold mb-3" style="color:<?php echo e($theme); ?>;">Current Academic Details</div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="label">Current Semester</div>
                                        <div class="value-box"><?php echo e($semesterLabel ?? 'N/A'); ?></div>
                                    </div>

                                    <div class="col-12">
                                        <div class="label">College</div>
                                        <div class="value-box"><?php echo e($collegeName); ?></div>
                                    </div>

                                    <div class="col-12">
                                        <div class="label">Course</div>
                                        <div class="value-box"><?php echo e($courseName); ?></div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="label">Year Level</div>
                                        <div class="value-box"><?php echo e($yearLevel); ?></div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="label">Status</div>
                                        <div class="value-box <?php echo e($isScholar ? '' : 'muted'); ?>">
                                            <?php echo e($isScholar ? 'Scholar' : 'Non-Scholar'); ?>

                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="label">Scholarship</div>
                                        <?php if($isScholar): ?>
                                            <div class="value-box" style="background:#ecfdf5; border:1px solid #bbf7d0;">
                                                <div>
                                                    <div class="fw-bold text-success">
                                                        <?php echo e($scholarshipName); ?>

                                                    </div>
                                                    <?php if(!empty($batchNumber)): ?>
                                                        <div class="small text-muted">
                                                            Batch: <?php echo e($batchNumber); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="value-box muted">
                                                No active scholarship
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if(!empty($batchNumber)): ?>
                                        <div class="col-12">
                                            <div class="label">Batch Number</div>
                                            <div class="value-box"><?php echo e($batchNumber); ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                    <div class="fw-bold" style="color:<?php echo e($theme); ?>;">Enrollment History</div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Semester</th>
                                                <th>Course</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $enrollmentHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <?php
                                                $sem = $enr->semester
                                                    ? ($enr->semester->term . ' ' . $enr->semester->academic_year)
                                                    : 'N/A';
                                                $crs = $enr->course?->course_name ?? 'N/A';
                                                $st  = $enr->status ?? 'N/A';
                                                $isActive = $activeEnrollment && $enr->id === $activeEnrollment->id;
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold"><?php echo e($sem); ?></div>
                                                    <?php if($isActive): ?>
                                                        <div class="small text-success">Current</div>
                                                    <?php endif; ?>
                                                </td>
                                                <td style="white-space:normal;"><?php echo e($crs); ?></td>
                                                <td>
                                                    <span class="badge <?php echo e(strtolower($st) === 'enrolled' ? 'bg-success' : 'bg-secondary'); ?>">
                                                        <?php echo e(strtoupper($st)); ?>

                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="3" class="text-muted small py-3">
                                                    No enrollment history available.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <?php if($isStudent && !$isAdminLike): ?>
                    <div class="tab-pane fade" id="tabContact">
                        <div class="row g-4">
                            <div class="col-lg-7">
                                <div class="fw-bold mb-3" style="color:<?php echo e($theme); ?>;">Contact Information</div>

                                <form action="<?php echo e(route('profile.update-contact')); ?>" method="POST" class="card border-0 section-card">
                                    <?php echo csrf_field(); ?>
                                    <div class="card-body p-0">

                                        <div class="mb-3">
                                            <div class="label">Contact Number</div>
                                            <input type="text"
                                                name="contact_no"
                                                class="form-control"
                                                value="<?php echo e(old('contact_no', $user->contact_no ?? '')); ?>"
                                                placeholder="Enter contact number">

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

                                        <button class="btn btn-brand">
                                            Save Changes
                                        </button>

                                        <div class="text-muted small mt-2">
                                            This information is visible for contact and coordination purposes.
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-lg-5">
                                <div class="fw-bold mb-3" style="color:<?php echo e($theme); ?>;">Current Contact</div>
                                <div class="value-box <?php echo e($user->contact_no ? '' : 'muted'); ?>">
                                    <?php echo e($user->contact_no ?: 'N/A'); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <div class="tab-pane fade" id="tabSecurity">
                        <div class="row g-4">
                            <div class="col-lg-7">
                                <div class="fw-bold mb-3" style="color:<?php echo e($theme); ?>;">Change Password</div>

                                <form action="<?php echo e(route('profile.update-password')); ?>" method="POST" class="card border-0 section-card">
                                    <?php echo csrf_field(); ?>
                                    <div class="card-body p-0">

                                        <div class="mb-3">
                                            <div class="label">Current Password</div>
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
                                            <div class="label">New Password</div>
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
                                            <div class="label">Confirm New Password</div>
                                            <input type="password" name="password_confirmation" class="form-control" required>
                                        </div>

                                        <button class="btn btn-brand">
                                            Update Password
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="col-lg-5">
                                <div class="fw-bold mb-3" style="color:<?php echo e($theme); ?>;">Security Tips</div>
                                <div class="value-box muted" style="white-space:normal; align-items:flex-start;">
                                    Use a password you don’t reuse elsewhere, and avoid sharing it with anyone.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/profile.blade.php ENDPATH**/ ?>