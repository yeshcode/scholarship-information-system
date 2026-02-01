<!-- Custom Styles for Blue Theme -->
<style>
    :root{
        --brand:#0b2e5e;
        --brand-2:#123f85;
        --bg:#f4f7fb;
        --card:#ffffff;
        --stroke:#e7edf6;
        --text:#0b2e5e;
        --muted:#6c7a92;
        --shadow: 0 10px 28px rgba(11,46,94,.08);
    }

    .nav-bg{
        background: rgba(244,247,251,.92);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--stroke);
    }

    .brand-wrap{
        display:flex;
        align-items:center;
        gap:.6rem;
        min-width: 260px;
    }
    .brand-logo{
        height:38px;
        width:38px;
        object-fit:cover;
        border-radius:12px;
        border: 1px solid rgba(11,46,94,.25);
        box-shadow: 0 6px 14px rgba(11,46,94,.10);
        background:#fff;
    }
    .brand-title{
        font-weight:800;
        color:var(--text);
        letter-spacing:.2px;
        font-size: .98rem;
        line-height:1.15;
        max-width: 320px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .brand-sub{
        font-size:.78rem;
        color:var(--muted);
        line-height:1.1;
    }

    .nav-pill{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:.35rem;
        padding:.45rem .9rem;
        border-radius: 10px;
        border:1px solid var(--stroke);
        background: var(--card);
        font-size: .86rem;
        font-weight:700;
        color: var(--text);
        white-space: nowrap;
        box-shadow: 0 6px 16px rgba(11,46,94,.06);
        transition: .15s ease;
    }
    .nav-pill:hover{
        transform: translateY(-1px);
        border-color: rgba(11,46,94,.25);
    }
    .nav-pill-active{
        background: var(--brand);
        border-color: var(--brand);
        color:#fff;
        box-shadow: 0 14px 30px rgba(11,46,94,.22);
    }
    .nav-pill-active svg{ color:#fff; }

    .dropdown-bg{
        background: #fff;
        border:1px solid var(--stroke);
        border-radius: 14px;
        box-shadow: var(--shadow);
        padding: .4rem;
    }
    .dropdown-square{
        display:flex;
        align-items:center;
        width:100%;
        padding:.55rem .7rem;
        border-radius: 10px;
        font-size:.84rem;
        font-weight:700;
        color: var(--text);
        transition: .12s ease;
    }
    .dropdown-square:hover{ background: rgba(11,46,94,.06); }
    .dropdown-square-active{
        background: rgba(11,46,94,.10);
        border: 1px solid rgba(11,46,94,.18);
    }

    .user-btn{
        display:inline-flex;
        align-items:center;
        gap:.4rem;
        padding:.4rem .65rem;
        border-radius: 12px;
        border:1px solid var(--stroke);
        background:#fff;
        font-weight:800;
        color: var(--text);
        box-shadow: 0 6px 16px rgba(11,46,94,.06);
        transition:.15s ease;
    }
    .user-btn:hover{
        transform: translateY(-1px);
        border-color: rgba(11,46,94,.25);
    }

    .divider-line{
        border: 0;
        border-top: 1px solid var(--stroke);
        margin: 0;
    }

    .student-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.95rem;
        border-radius: 9999px;
        background: #eaf2ff;
        color: #0b3a75;
        font-weight: 700;
        font-size: 0.9rem;
        border: 1px solid #cfe0ff;
        transition: 0.2s ease;
        white-space: nowrap;
    }
    .student-pill:hover {
        background: #d9e9ff;
        transform: translateY(-1px);
    }
    .student-pill-active {
        background: #0b3a75;
        color: #ffffff;
        border-color: #0b3a75;
        box-shadow: 0 6px 14px rgba(11, 58, 117, 0.18);
    }

    .coord-pill {
        width: auto;
        min-width: unset;
        height: 36px;
        padding: 0.25rem 0.7rem;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
        border-radius: 6px;
    }
    .coord-pill svg {
        width: 12px;
        height: 12px;
    }

    /* ✅ Modal results style */
    .sem-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding:.5rem .65rem;
        font-weight:700;
        font-size:.82rem;
        color:var(--text);
        background:#fff;
        border-bottom:1px solid var(--stroke);
        transition:.12s ease;
        width:100%;
        text-align:left;
    }
    .sem-row:hover{
        background: rgba(11,46,94,.06);
    }
    .sem-badge{
        font-size:.72rem;
        padding:.15rem .45rem;
        border-radius:9999px;
        border:1px solid rgba(11,46,94,.18);
        background: rgba(11,46,94,.08);
        color: var(--text);
    }

    #semesterSearchResults{
        max-height: 220px;
        overflow:auto;
    }
</style>

<?php
    $settings = \App\Models\SystemSetting::first();
    $page = request('page');

    $usersGroupActive = in_array($page, ['manage-users', 'user-type']);
    $academicGroupActive = in_array($page, ['colleges', 'courses', 'year-levels', 'semesters']);
    $enrollmentGroupActive = $page === 'enrollments';

    $allSemesters = \App\Models\Semester::orderByDesc('created_at')->get();
    $activeSemesterId = session('active_semester_id');

    // ✅ if no session filter, show current semester label
    $activeSemester = $activeSemesterId
        ? $allSemesters->firstWhere('id', $activeSemesterId)
        : \App\Models\Semester::where('is_current', true)->first();

    $activeSemesterName = $activeSemester
        ? ($activeSemester->term . ' ' . $activeSemester->academic_year)
        : 'Select Semester';

    $coordScholarsGroupActive =
        request()->routeIs('coordinator.manage-scholars')
        || request()->routeIs('coordinator.scholars.*')
        || request()->routeIs('coordinator.enrollment-records')
        || request()->routeIs('coordinator.manage-scholarships')
        || request()->routeIs('coordinator.scholarships.*')
        || request()->routeIs('coordinator.scholarship-batches')
        || request()->routeIs('coordinator.scholarship-batches.*');

    $coordStipendsGroupActive =
        request()->routeIs('coordinator.manage-stipends')
        || request()->routeIs('coordinator.stipends.*')
        || request()->routeIs('coordinator.manage-stipend-releases')
        || request()->routeIs('coordinator.stipend-releases.*');

    $coordAnnouncementsGroupActive =
        request()->routeIs('coordinator.manage-announcements')
        || request()->routeIs('coordinator.announcements.*')
        || request()->routeIs('clusters.*');

    $coordReportsActive =
        request()->routeIs('coordinator.reports')
        || request()->routeIs('coordinator.reports.*');
?>

<nav class="nav-bg shadow-sm">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-4 py-3">

        
        <div class="brand-wrap">
            <?php if($settings && $settings->logo_path && file_exists(public_path('storage/' . $settings->logo_path))): ?>
                <img src="<?php echo e(asset('storage/' . $settings->logo_path)); ?>" alt="Logo" class="brand-logo">
            <?php else: ?>
                <img src="<?php echo e(asset('images/scholarship_logo.jpg')); ?>" alt="Logo" class="brand-logo">
            <?php endif; ?>

            <div style="min-width:0;">
                <div class="brand-title">
                    <?php echo e($settings->system_name ?? 'Scholarship Management Information System'); ?>

                </div>
                <div class="brand-sub">BISU Candijay Campus</div>
            </div>
        </div>

        
        <div class="flex-1 flex justify-center">
            <div class="flex space-x-2 items-center flex-nowrap">

                
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->hasRole('Scholarship Coordinator') || auth()->user()->hasRole('Super Admin')): ?>
                        <div class="relative">
                            <button type="button"
                                    id="semesterModalOpenBtn"
                                    class="nav-pill coord-pill <?php echo e($activeSemesterId ? 'nav-pill-active' : ''); ?>">
                                <?php echo e($activeSemesterName); ?>

                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(auth()->guard()->check()): ?>
                    
                    <?php if(auth()->user()->hasRole('Super Admin')): ?>
                        <?php if (isset($component)) { $__componentOriginalc295f12dca9d42f28a259237a5724830 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc295f12dca9d42f28a259237a5724830 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-link','data' => ['href' => route('admin.dashboard'),'active' => request()->routeIs('admin.dashboard') && !request('page'),'class' => 'nav-pill '.e((request()->routeIs('admin.dashboard') && !request('page')) ? 'nav-pill-active' : '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.dashboard')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.dashboard') && !request('page')),'class' => 'nav-pill '.e((request()->routeIs('admin.dashboard') && !request('page')) ? 'nav-pill-active' : '').'']); ?>
                            <?php echo e(__('Dashboard')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $attributes = $__attributesOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__attributesOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $component = $__componentOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__componentOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>

                        <div class="relative">
                            <button type="button" id="users-menu-button" class="nav-pill <?php echo e($usersGroupActive ? 'nav-pill-active' : ''); ?>">
                                Users &amp; Roles
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="users-menu" class="hidden absolute left-0 mt-2 w-60 dropdown-bg rounded-md shadow-lg py-2 z-50">
                                <a href="<?php echo e(route('admin.dashboard', ['page' => 'manage-users'])); ?>" class="dropdown-square <?php echo e($page === 'manage-users' ? 'dropdown-square-active' : ''); ?>">System Users</a>
                                <a href="<?php echo e(route('admin.dashboard', ['page' => 'user-type'])); ?>" class="dropdown-square <?php echo e($page === 'user-type' ? 'dropdown-square-active' : ''); ?>">User Types</a>
                            </div>
                        </div>

                        <div class="relative">
                            <button type="button" id="academic-menu-button" class="nav-pill <?php echo e($academicGroupActive ? 'nav-pill-active' : ''); ?>">
                                Academic Structure
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="academic-menu" class="hidden absolute left-0 mt-2 w-60 dropdown-bg rounded-md shadow-lg py-2 z-50">
                                <a href="<?php echo e(route('admin.dashboard', ['page' => 'colleges'])); ?>" class="dropdown-square <?php echo e($page === 'colleges' ? 'dropdown-square-active' : ''); ?>">Colleges</a>
                                <a href="<?php echo e(route('admin.dashboard', ['page' => 'courses'])); ?>" class="dropdown-square <?php echo e($page === 'courses' ? 'dropdown-square-active' : ''); ?>">Courses</a>
                                <a href="<?php echo e(route('admin.dashboard', ['page' => 'year-levels'])); ?>" class="dropdown-square <?php echo e($page === 'year-levels' ? 'dropdown-square-active' : ''); ?>">Year Levels</a>
                                <a href="<?php echo e(route('admin.dashboard', ['page' => 'semesters'])); ?>" class="dropdown-square <?php echo e($page === 'semesters' ? 'dropdown-square-active' : ''); ?>">Semesters</a>
                            </div>
                        </div>

                        <div class="relative">
                            <button type="button" id="enrollment-menu-button" class="nav-pill <?php echo e($enrollmentGroupActive ? 'nav-pill-active' : ''); ?>">
                                Enrollment
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="enrollment-menu" class="hidden absolute left-0 mt-2 w-60 dropdown-bg rounded-md shadow-lg py-2 z-50">
                                <a href="<?php echo e(route('admin.dashboard', ['page' => 'enrollments'])); ?>" class="dropdown-square <?php echo e($page === 'enrollments' ? 'dropdown-square-active' : ''); ?>">
                                    Enrollment Records
                                </a>
                            </div>
                        </div>

                    
                    <?php elseif(auth()->user()->hasRole('Scholarship Coordinator')): ?>

                        <?php if (isset($component)) { $__componentOriginalc295f12dca9d42f28a259237a5724830 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc295f12dca9d42f28a259237a5724830 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-link','data' => ['href' => route('coordinator.dashboard'),'active' => request()->routeIs('coordinator.dashboard'),'class' => 'nav-pill coord-pill '.e(request()->routeIs('coordinator.dashboard') ? 'nav-pill-active' : '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('coordinator.dashboard')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('coordinator.dashboard')),'class' => 'nav-pill coord-pill '.e(request()->routeIs('coordinator.dashboard') ? 'nav-pill-active' : '').'']); ?>
                            <?php echo e(__('Dashboard')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $attributes = $__attributesOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__attributesOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $component = $__componentOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__componentOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>

                        <div class="relative">
                            <button type="button" id="coord-scholars-menu-button"
                                    class="nav-pill coord-pill <?php echo e($coordScholarsGroupActive ? 'nav-pill-active' : ''); ?>">
                                Student Services
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="coord-scholars-menu" class="hidden absolute left-0 mt-2 w-64 dropdown-bg rounded-md shadow-lg py-2 z-50">
                                <a href="<?php echo e(route('coordinator.manage-scholars')); ?>" class="dropdown-square <?php echo e((request()->routeIs('coordinator.manage-scholars') || request()->routeIs('coordinator.scholars.*')) ? 'dropdown-square-active' : ''); ?>">Scholars</a>
                                <a href="<?php echo e(route('coordinator.enrollment-records')); ?>" class="dropdown-square <?php echo e(request()->routeIs('coordinator.enrollment-records') ? 'dropdown-square-active' : ''); ?>">Students Record</a>
                                <a href="<?php echo e(route('coordinator.manage-scholarships')); ?>" class="dropdown-square <?php echo e((request()->routeIs('coordinator.manage-scholarships') || request()->routeIs('coordinator.scholarships.*')) ? 'dropdown-square-active' : ''); ?>">Scholarships</a>
                                <a href="<?php echo e(route('coordinator.scholarship-batches')); ?>" class="dropdown-square <?php echo e((request()->routeIs('coordinator.scholarship-batches') || request()->routeIs('coordinator.scholarship-batches.*')) ? 'dropdown-square-active' : ''); ?>">Scholarship Batches</a>
                            </div>
                        </div>

                        <div class="relative">
                            <button type="button" id="coord-stipends-menu-button"
                                    class="nav-pill coord-pill <?php echo e($coordStipendsGroupActive ? 'nav-pill-active' : ''); ?>">
                                Stipends
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="coord-stipends-menu" class="hidden absolute left-0 mt-2 w-64 dropdown-bg rounded-md shadow-lg py-2 z-50">
                                <a href="<?php echo e(route('coordinator.manage-stipends')); ?>" class="dropdown-square <?php echo e((request()->routeIs('coordinator.manage-stipends') || request()->routeIs('coordinator.stipends.*')) ? 'dropdown-square-active' : ''); ?>">Stipend Details</a>
                                <a href="<?php echo e(route('coordinator.manage-stipend-releases')); ?>" class="dropdown-square <?php echo e((request()->routeIs('coordinator.manage-stipend-releases') || request()->routeIs('coordinator.stipend-releases.*')) ? 'dropdown-square-active' : ''); ?>">Stipend Release Schedule</a>
                            </div>
                        </div>

                        <div class="relative">
                            <button type="button" id="coord-announcements-menu-button"
                                    class="nav-pill coord-pill <?php echo e($coordAnnouncementsGroupActive ? 'nav-pill-active' : ''); ?>">
                                Announcements
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="coord-announcements-menu" class="hidden absolute left-0 mt-2 w-64 dropdown-bg rounded-md shadow-lg py-2 z-50">
                                <a href="<?php echo e(route('coordinator.manage-announcements')); ?>" class="dropdown-square <?php echo e((request()->routeIs('coordinator.manage-announcements') || request()->routeIs('coordinator.announcements.*')) ? 'dropdown-square-active' : ''); ?>">Post Announcements</a>
                                <a href="<?php echo e(route('clusters.index')); ?>" class="dropdown-square <?php echo e(request()->routeIs('clusters.*') ? 'dropdown-square-active' : ''); ?>">Student Inquiries</a>
                            </div>
                        </div>

                        <?php if (isset($component)) { $__componentOriginalc295f12dca9d42f28a259237a5724830 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc295f12dca9d42f28a259237a5724830 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-link','data' => ['href' => route('coordinator.reports'),'active' => $coordReportsActive,'class' => 'nav-pill coord-pill '.e($coordReportsActive ? 'nav-pill-active' : '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('coordinator.reports')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($coordReportsActive),'class' => 'nav-pill coord-pill '.e($coordReportsActive ? 'nav-pill-active' : '').'']); ?>
                            <?php echo e(__('Reports')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $attributes = $__attributesOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__attributesOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $component = $__componentOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__componentOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>

                    
                    <?php elseif(auth()->user()->hasRole('Student')): ?>

                        <?php if (isset($component)) { $__componentOriginalc295f12dca9d42f28a259237a5724830 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc295f12dca9d42f28a259237a5724830 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-link','data' => ['href' => route('student.dashboard'),'active' => request()->routeIs('student.dashboard'),'class' => 'student-pill '.e(request()->routeIs('student.dashboard') ? 'student-pill-active' : '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('student.dashboard')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('student.dashboard')),'class' => 'student-pill '.e(request()->routeIs('student.dashboard') ? 'student-pill-active' : '').'']); ?>
                            <?php echo e(__('Home')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $attributes = $__attributesOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__attributesOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $component = $__componentOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__componentOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>

                        <?php if (isset($component)) { $__componentOriginalc295f12dca9d42f28a259237a5724830 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc295f12dca9d42f28a259237a5724830 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-link','data' => ['href' => route('student.announcements'),'active' => request()->routeIs('student.announcements'),'class' => 'student-pill '.e(request()->routeIs('student.announcements') ? 'student-pill-active' : '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('student.announcements')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('student.announcements')),'class' => 'student-pill '.e(request()->routeIs('student.announcements') ? 'student-pill-active' : '').'']); ?>
                            <?php echo e(__('Announcements')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $attributes = $__attributesOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__attributesOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $component = $__componentOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__componentOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>

                        <?php if (isset($component)) { $__componentOriginalc295f12dca9d42f28a259237a5724830 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc295f12dca9d42f28a259237a5724830 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-link','data' => ['href' => route('student.scholarships.index'),'active' => request()->routeIs('student.scholarships.*'),'class' => 'student-pill '.e(request()->routeIs('student.scholarships.*') ? 'student-pill-active' : '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('student.scholarships.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('student.scholarships.*')),'class' => 'student-pill '.e(request()->routeIs('student.scholarships.*') ? 'student-pill-active' : '').'']); ?>
                            <?php echo e(__('Scholarships')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $attributes = $__attributesOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__attributesOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $component = $__componentOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__componentOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>

                        <?php if(\App\Models\Scholar::where('student_id', auth()->id())->exists()): ?>
                            <?php if (isset($component)) { $__componentOriginalc295f12dca9d42f28a259237a5724830 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc295f12dca9d42f28a259237a5724830 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-link','data' => ['href' => route('student.stipend-history'),'active' => request()->routeIs('student.stipend-history'),'class' => 'student-pill '.e(request()->routeIs('student.stipend-history') ? 'student-pill-active' : '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('student.stipend-history')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('student.stipend-history')),'class' => 'student-pill '.e(request()->routeIs('student.stipend-history') ? 'student-pill-active' : '').'']); ?>
                                <?php echo e(__('Stipends')); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $attributes = $__attributesOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__attributesOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $component = $__componentOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__componentOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>
                        <?php endif; ?>

                        <?php if (isset($component)) { $__componentOriginalc295f12dca9d42f28a259237a5724830 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc295f12dca9d42f28a259237a5724830 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-link','data' => ['href' => route('student.notifications'),'active' => request()->routeIs('student.notifications'),'class' => 'student-pill '.e(request()->routeIs('student.notifications') ? 'student-pill-active' : '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('student.notifications')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('student.notifications')),'class' => 'student-pill '.e(request()->routeIs('student.notifications') ? 'student-pill-active' : '').'']); ?>
                            <?php echo e(__('Notifications')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $attributes = $__attributesOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__attributesOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $component = $__componentOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__componentOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>

                        <?php if (isset($component)) { $__componentOriginalc295f12dca9d42f28a259237a5724830 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc295f12dca9d42f28a259237a5724830 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-link','data' => ['href' => route('questions.create'),'active' => request()->routeIs('questions.create'),'class' => 'student-pill '.e(request()->routeIs('questions.create') ? 'student-pill-active' : '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('questions.create')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('questions.create')),'class' => 'student-pill '.e(request()->routeIs('questions.create') ? 'student-pill-active' : '').'']); ?>
                            <?php echo e(__('Ask')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $attributes = $__attributesOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__attributesOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $component = $__componentOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__componentOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>

                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="flex items-center justify-end min-w-[200px]">
            <?php if(auth()->guard()->check()): ?>
                <div class="relative">
                    <button class="user-btn" id="user-menu-button" aria-expanded="false">
                        <?php echo e(auth()->user()->firstname); ?>

                        <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    <div class="hidden absolute left-1/2 -translate-x-1/2 mt-2 dropdown-bg rounded-md shadow-lg z-50 px-1 py-1"
                         id="user-dropdown" style="width: 180px;">
                        <a href="<?php echo e(route('profile')); ?>" class="dropdown-square">Profile</a>

                        <?php if(auth()->user()->hasRole('Super Admin')): ?>
                            <a href="<?php echo e(route('settings.index')); ?>" class="dropdown-square">Settings</a>
                        <?php endif; ?>

                        <form action="<?php echo e(route('logout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="dropdown-square w-full">Logout</button>
                        </form>
                    </div>

                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<hr class="divider-line">


<?php if(auth()->guard()->check()): ?>
<?php if(auth()->user()->hasRole('Scholarship Coordinator') || auth()->user()->hasRole('Super Admin')): ?>
<div id="semesterModalOverlay"
     class="hidden fixed inset-0 z-50 items-center justify-center"
     style="background: rgba(0,0,0,.35); backdrop-filter: blur(2px);">
    <div class="bg-white rounded-lg shadow-lg p-3"
        style="
            width: 280px;            /* small modal */
            max-width: calc(100vw - 24px);
            border: 1px solid var(--stroke);
            box-shadow: var(--shadow);
        ">

        <div class="flex items-center justify-between mb-3">
            <div>
                <div class="font-extrabold" style="color:var(--text)">Filter by Semester</div>
                <div class="text-xs" style="color:var(--muted)">Type to search. Click a result to apply.</div>
            </div>
            <button type="button" id="semesterModalCloseBtn" class="user-btn" style="padding:.25rem .55rem; border-radius:10px;">✕</button>
        </div>

        <input type="text" id="semesterSearchInput"
            class="w-full border rounded-lg px-3 py-1.5"
            style="border:1px solid var(--stroke); outline:none;"
            placeholder="Type semester..."
            autocomplete="off">


        <div class="mt-3 flex items-center justify-between">
            <form method="POST" action="<?php echo e(route('semester.filter.clear')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="nav-pill coord-pill" style="height:34px;">
                    All Semesters
                </button>
            </form>
            <div id="semesterSearchStatus" class="text-xs" style="color:var(--muted)"></div>
        </div>

        <div id="semesterSearchResults" class="mt-3 hidden"
             style="border:1px solid var(--stroke); border-radius:12px; overflow:hidden;"></div>

        <form id="semesterSetForm" method="POST" action="<?php echo e(route('semester.filter.set')); ?>" class="hidden">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="semester_id" id="semesterSelectedId">
        </form>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ✅ keep your dropdown system (without semester dropdown)
    const dropdownPairs = [];

    function registerDropdown(buttonId, menuId) {
        const btn = document.getElementById(buttonId);
        const menu = document.getElementById(menuId);
        if (!btn || !menu) return;

        dropdownPairs.push({ btn, menu });

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const willOpen = menu.classList.contains('hidden');
            dropdownPairs.forEach(pair => pair.menu.classList.add('hidden'));
            if (willOpen) menu.classList.remove('hidden');
        });
    }

    // Register all dropdown menus
    registerDropdown('users-menu-button', 'users-menu');
    registerDropdown('academic-menu-button', 'academic-menu');
    registerDropdown('enrollment-menu-button', 'enrollment-menu');

    registerDropdown('coord-scholars-menu-button', 'coord-scholars-menu');
    registerDropdown('coord-stipends-menu-button', 'coord-stipends-menu');
    registerDropdown('coord-announcements-menu-button', 'coord-announcements-menu');

    registerDropdown('user-menu-button', 'user-dropdown');

    // Close all dropdowns when clicking outside
    document.addEventListener('click', function () {
        dropdownPairs.forEach(pair => pair.menu.classList.add('hidden'));
    });

    // Close dropdowns when clicking any nav link
    document.querySelectorAll('nav a, nav form button').forEach(el => {
        el.addEventListener('click', function () {
            dropdownPairs.forEach(pair => pair.menu.classList.add('hidden'));
        });
    });

    // ✅ SEMESTER MODAL LOGIC
    const semOpenBtn = document.getElementById('semesterModalOpenBtn');
    const semOverlay = document.getElementById('semesterModalOverlay');
    const semCloseBtn = document.getElementById('semesterModalCloseBtn');

    const semInput = document.getElementById('semesterSearchInput');
    const semResults = document.getElementById('semesterSearchResults');
    const semStatus = document.getElementById('semesterSearchStatus');

    const semSetForm = document.getElementById('semesterSetForm');
    const semSelectedId = document.getElementById('semesterSelectedId');

    let semTimer = null;

    function openSemModal(){
        if(!semOverlay) return;
        semOverlay.classList.remove('hidden');
        semOverlay.classList.add('flex');
        semInput.value = '';
        semResults.innerHTML = '';
        semResults.classList.add('hidden');
        semStatus.textContent = 'Type to search...';
        setTimeout(() => semInput.focus(), 50);
        fetchSemesters('');
    }

    function closeSemModal(){
        if(!semOverlay) return;
        semOverlay.classList.add('hidden');
        semOverlay.classList.remove('flex');
    }

    function escapeHtml(str){
        return (str || '').replace(/[&<>"']/g, m => ({
            '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
        }[m]));
    }

    async function fetchSemesters(q){
        try{
            semStatus.textContent = 'Searching...';
            const url = `<?php echo e(route('semester.filter.search')); ?>?q=${encodeURIComponent(q)}`;
            const res = await fetch(url, { headers:{ 'Accept':'application/json' }});
            const json = await res.json();
            const items = json?.data || [];

            if(items.length === 0){
                semResults.classList.remove('hidden');
                semResults.innerHTML = `<div style="padding:.8rem; color:var(--muted); font-size:.85rem;">No matches found.</div>`;
                semStatus.textContent = '';
                return;
            }

            semResults.classList.remove('hidden');
            semResults.innerHTML = items.map(it => {
                const badge = it.is_current ? `<span class="sem-badge">current</span>` : '';
                return `
                    <button type="button" class="sem-row" data-id="${it.id}">
                        <span>${escapeHtml(it.label)}</span>
                        ${badge}
                    </button>
                `;
            }).join('');

            semStatus.textContent = 'Click one to apply filter.';
        } catch(e){
            semResults.classList.remove('hidden');
            semResults.innerHTML = `<div style="padding:.8rem; color:#b91c1c; font-size:.85rem;">Error loading semesters.</div>`;
            semStatus.textContent = '';
        }
    }

    semOpenBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        openSemModal();
    });

    semCloseBtn?.addEventListener('click', closeSemModal);

    semOverlay?.addEventListener('click', (e) => {
        if(e.target === semOverlay) closeSemModal();
    });

    document.addEventListener('keydown', (e) => {
        if(e.key === 'Escape') closeSemModal();
    });

    semInput?.addEventListener('input', () => {
        clearTimeout(semTimer);
        semTimer = setTimeout(() => {
            fetchSemesters(semInput.value.trim());
        }, 200);
    });

    semResults?.addEventListener('click', (e) => {
        const btn = e.target.closest('button[data-id]');
        if(!btn) return;

        semSelectedId.value = btn.dataset.id;
        semSetForm.submit();
    });

});
</script>
<?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>