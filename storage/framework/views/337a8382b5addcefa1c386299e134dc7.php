<!-- Custom Styles for Blue Theme -->
<style>
    .nav-bg {
        background-color: #f0f4f8;
    }
    .nav-text {
        color: #003366;
        font-weight: bold;
    }
    .nav-link:hover {
        color: #0056b3;
    }
    .dropdown-bg {
        background-color: #ffffff;
        border: 1px solid #007bff;
    }
    .dropdown-item:hover {
        background-color: #f0f4f8;
    }
    .logo-border {
        border: 2px solid #007bff;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .divider-line {
        border: 2px solid #003366;
        margin: 0;
    }

    /* NEW: square/tab style for top links */
    .nav-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.4rem 0.9rem;
        border-radius: 0.375rem;
        border: 1px solid #003366;
        background-color: #ffffff;
        font-size: 0.9rem;
        text-align: center;
        min-width: 130px;
        color: #003366;
        font-weight: bold;
        cursor: pointer;
        white-space: nowrap;
    }
    .nav-pill:hover {
        background-color: #e2e8f0;
    }
    .nav-pill-active {
        background-color: #003366;
        color: #ffffff;
    }

    /* NEW: square items inside dropdowns */
    .dropdown-square {
        display: flex;
        align-items: center;

        width: 100%;
        box-sizing: border-box;

        padding: 0.4rem 0.7rem;   /* compact but readable */
        margin: 0;               /* ❗ remove margins */
        
        border-radius: 0.35rem;
        border: none;            /* cleaner look */
        background-color: transparent;

        font-size: 0.8rem;
        font-weight: 600;
        color: #003366;
        text-align: left;
        cursor: pointer;
    }

    .dropdown-square:hover {
        background-color: #e2e8f0;
    }



    .dropdown-square-active {
        background-color: #003366;
        color: #ffffff;
    }

    /* STUDENT: chip style (different from admin/coordinator boxes) */
    .student-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.95rem;
        border-radius: 9999px; /* fully rounded */
        background: #eaf2ff;   /* light blue */
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

    /* COORDINATOR: uniform square buttons *//* COORDINATOR: compact modern nav buttons */
    .coord-pill {
        width: auto;                 /* no fixed width */
        min-width: unset;
        height: 36px;                /* slim height */
        padding: 0.25rem 0.7rem;     /* compact padding */
        font-size: 0.8rem;           /* readable but small */
        font-weight: 600;
        white-space: nowrap;         /* single line */
        border-radius: 6px;          /* subtle rounding */
    }

    /* smaller dropdown arrow */
    .coord-pill svg {
        width: 12px;
        height: 12px;
    }



</style>

<?php
    $settings = \App\Models\SystemSetting::first();
    $page = request('page');

    $usersGroupActive = in_array($page, ['manage-users', 'user-type']);
    $academicGroupActive = in_array($page, ['colleges', 'courses', 'year-levels', 'sections', 'semesters']);
    $enrollmentGroupActive = $page === 'enrollments';

    $allSemesters = \App\Models\Semester::orderByDesc('created_at')->get();
    $activeSemesterId = session('active_semester_id');
    $activeSemester = $allSemesters->firstWhere('id', $activeSemesterId);
    $activeSemesterName = $activeSemester
            ? ($activeSemester->term . ' ' . $activeSemester->academic_year)
            : 'All Semesters';



     $coordScholarsGroupActive =
        request()->routeIs('coordinator.manage-scholars')
        || request()->routeIs('coordinator.scholars.*')
        || request()->routeIs('coordinator.enrolled-users')
        || request()->routeIs('coordinator.manage-scholarships')
        || request()->routeIs('coordinator.scholarships.*');

    $coordStipendsGroupActive =
        request()->routeIs('coordinator.manage-stipends')
        || request()->routeIs('coordinator.stipends.*')
        || request()->routeIs('coordinator.manage-stipend-releases')
        || request()->routeIs('coordinator.stipend-releases.*');

    $coordAnnouncementsGroupActive =
        request()->routeIs('coordinator.manage-announcements')
        || request()->routeIs('coordinator.announcements.*')
        || request()->routeIs('clusters.*');

    // Optional: set this once your reports route exists
    $coordReportsActive =
        request()->routeIs('coordinator.reports')
        || request()->routeIs('coordinator.reports.*');

?>

<!-- SINGLE TOP BAR: logo + title + nav links + user menu -->
<nav class="nav-bg shadow-sm">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-4 py-3">

        
        <div class="flex items-center space-x-2">
            <?php if($settings && $settings->logo_path && file_exists(public_path('storage/' . $settings->logo_path))): ?>
                <img src="<?php echo e(asset('storage/' . $settings->logo_path)); ?>"
                     alt="Logo"
                     class="h-8 w-8 object-contain logo-border">
            <?php else: ?>
                <span class="nav-text">[No Logo]</span>
            <?php endif; ?>
            <span class="text-lg nav-text">
                <?php echo e($settings->system_name ?? 'Scholarship Management Information System'); ?>

            </span>
        </div>

        
        <div class="flex-1 flex justify-center">
            <div class="flex space-x-2 items-center flex-nowrap">
                
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->hasRole('Scholarship Coordinator') || auth()->user()->hasRole('Super Admin')): ?>
                        
                        <div class="relative">
                            <button type="button"
                                    id="semester-menu-button"
                                    class="nav-pill coord-pill <?php echo e($activeSemesterId ? 'nav-pill-active' : ''); ?>">
                                <?php echo e($activeSemesterName); ?>

                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>

                            <div id="semester-menu"
                                class="hidden absolute left-0 mt-2 w-64 dropdown-bg rounded-md shadow-lg py-2 z-50">

                                
                                <form method="POST" action="<?php echo e(route('semester.filter.clear')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit"
                                            class="dropdown-square w-full <?php echo e(!$activeSemesterId ? 'dropdown-square-active' : ''); ?>">
                                        All Semesters
                                    </button>
                                </form>

                                <div class="my-1 border-t border-gray-200"></div>

                                
                                <?php $__currentLoopData = $allSemesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <form method="POST" action="<?php echo e(route('semester.filter.set')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="semester_id" value="<?php echo e($sem->id); ?>">
                                        <button type="submit"
                                                class="dropdown-square w-full <?php echo e($activeSemesterId == $sem->id ? 'dropdown-square-active' : ''); ?>">
                                            <?php echo e($sem->term); ?> <?php echo e($sem->academic_year); ?>

                                        </button>
                                    </form>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
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
                            <button type="button"
                                    id="users-menu-button"
                                    class="nav-pill <?php echo e($usersGroupActive ? 'nav-pill-active' : ''); ?>">
                                Users &amp; Roles
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="users-menu"
                                 class="hidden absolute left-0 mt-2 w-60 dropdown-bg rounded-md shadow-lg py-2 z-50">
                                <a href="<?php echo e(route('admin.dashboard', ['page' => 'manage-users'])); ?>"
                                   class="dropdown-square <?php echo e($page === 'manage-users' ? 'dropdown-square-active' : ''); ?>">
                                    System Users
                                </a>
                                <a href="<?php echo e(route('admin.dashboard', ['page' => 'user-type'])); ?>"
                                   class="dropdown-square <?php echo e($page === 'user-type' ? 'dropdown-square-active' : ''); ?>">
                                    User Types
                                </a>
                            </div>
                        </div>

                        
                        <div class="relative">
                            <button type="button"
                                    id="academic-menu-button"
                                    class="nav-pill <?php echo e($academicGroupActive ? 'nav-pill-active' : ''); ?>">
                                Academic Structure
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="academic-menu"
                                 class="hidden absolute left-0 mt-2 w-60 dropdown-bg rounded-md shadow-lg py-2 z-50">
                                <a href="<?php echo e(route('admin.dashboard', ['page' => 'colleges'])); ?>"
                                   class="dropdown-square <?php echo e($page === 'colleges' ? 'dropdown-square-active' : ''); ?>">
                                    Colleges
                                </a>
                                <a href="<?php echo e(route('admin.dashboard', ['page' => 'courses'])); ?>"
                                   class="dropdown-square <?php echo e($page === 'courses' ? 'dropdown-square-active' : ''); ?>">
                                    Courses
                                </a>
                                <a href="<?php echo e(route('admin.dashboard', ['page' => 'year-levels'])); ?>"
                                   class="dropdown-square <?php echo e($page === 'year-levels' ? 'dropdown-square-active' : ''); ?>">
                                    Year Levels
                                </a>
                                <a href="<?php echo e(route('admin.dashboard', ['page' => 'sections'])); ?>"
                                   class="dropdown-square <?php echo e($page === 'sections' ? 'dropdown-square-active' : ''); ?>">
                                    Sections
                                </a>
                                <a href="<?php echo e(route('admin.dashboard', ['page' => 'semesters'])); ?>"
                                   class="dropdown-square <?php echo e($page === 'semesters' ? 'dropdown-square-active' : ''); ?>">
                                    Semesters
                                </a>
                            </div>
                        </div>

                        
                        <div class="relative">
                            <button type="button"
                                    id="enrollment-menu-button"
                                    class="nav-pill <?php echo e($enrollmentGroupActive ? 'nav-pill-active' : ''); ?>">
                                Enrollment
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="enrollment-menu"
                                 class="hidden absolute left-0 mt-2 w-60 dropdown-bg rounded-md shadow-lg py-2 z-50">
                                <a href="<?php echo e(route('admin.dashboard', ['page' => 'enrollments'])); ?>"
                                   class="dropdown-square <?php echo e($page === 'enrollments' ? 'dropdown-square-active' : ''); ?>">
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
                                <button type="button"
                                        id="coord-scholars-menu-button"
                                        class="nav-pill coord-pill<?php echo e($coordScholarsGroupActive ? 'nav-pill-active' : ''); ?>">
                                    Student Services
                                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>

                                <div id="coord-scholars-menu"
                                    class="hidden absolute left-0 mt-2 w-64 dropdown-bg rounded-md shadow-lg py-2 z-50">
                                    <a href="<?php echo e(route('coordinator.manage-scholars')); ?>"
                                    class="dropdown-square <?php echo e((request()->routeIs('coordinator.manage-scholars') || request()->routeIs('coordinator.scholars.*')) ? 'dropdown-square-active' : ''); ?>">
                                        Scholars
                                    </a>

                                    <a href="<?php echo e(route('coordinator.enrollment-records')); ?>"
                                    class="dropdown-square <?php echo e(request()->routeIs('coordinator.enrollment-records') ? 'dropdown-square-active' : ''); ?>">
                                        Students Record
                                    </a>

                                    <a href="<?php echo e(route('coordinator.manage-scholarships')); ?>"
                                    class="dropdown-square <?php echo e((request()->routeIs('coordinator.manage-scholarships') || request()->routeIs('coordinator.scholarships.*')) ? 'dropdown-square-active' : ''); ?>">
                                        Scholarships
                                    </a>
                                </div>
                            </div>

                            
                            <div class="relative">
                                <button type="button"
                                        id="coord-stipends-menu-button"
                                        class="nav-pill coord-pill <?php echo e($coordStipendsGroupActive ? 'nav-pill-active' : ''); ?>">
                                    Stipends
                                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>

                                <div id="coord-stipends-menu"
                                    class="hidden absolute left-0 mt-2 w-64 dropdown-bg rounded-md shadow-lg py-2 z-50">
                                    <a href="<?php echo e(route('coordinator.manage-stipends')); ?>"
                                    class="dropdown-square <?php echo e((request()->routeIs('coordinator.manage-stipends') || request()->routeIs('coordinator.stipends.*')) ? 'dropdown-square-active' : ''); ?>">
                                        Stipend Details
                                    </a>

                                    <a href="<?php echo e(route('coordinator.manage-stipend-releases')); ?>"
                                    class="dropdown-square <?php echo e((request()->routeIs('coordinator.manage-stipend-releases') || request()->routeIs('coordinator.stipend-releases.*')) ? 'dropdown-square-active' : ''); ?>">
                                        Stipend Release Schedule
                                    </a>
                                </div>
                            </div>

                            
                            <div class="relative">
                                <button type="button"
                                        id="coord-announcements-menu-button"
                                        class="nav-pill coord-pill <?php echo e($coordAnnouncementsGroupActive ? 'nav-pill-active' : ''); ?>">
                                    Announcements
                                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>

                                <div id="coord-announcements-menu"
                                    class="hidden absolute left-0 mt-2 w-64 dropdown-bg rounded-md shadow-lg py-2 z-50">
                                    <a href="<?php echo e(route('coordinator.manage-announcements')); ?>"
                                    class="dropdown-square <?php echo e((request()->routeIs('coordinator.manage-announcements') || request()->routeIs('coordinator.announcements.*')) ? 'dropdown-square-active' : ''); ?>">
                                        Post Announcements
                                    </a>

                                    <a href="<?php echo e(route('clusters.index')); ?>"
                                    class="dropdown-square <?php echo e(request()->routeIs('clusters.*') ? 'dropdown-square-active' : ''); ?>">
                                       Student Inquiries
                                    </a>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-link','data' => ['href' => route('student.scholarships'),'active' => request()->routeIs('student.scholarships'),'class' => 'student-pill '.e(request()->routeIs('student.scholarships') ? 'student-pill-active' : '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('student.scholarships')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('student.scholarships')),'class' => 'student-pill '.e(request()->routeIs('student.scholarships') ? 'student-pill-active' : '').'']); ?>
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

                        <?php if (isset($component)) { $__componentOriginalc295f12dca9d42f28a259237a5724830 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc295f12dca9d42f28a259237a5724830 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-link','data' => ['href' => route('questions.my'),'active' => request()->routeIs('questions.my'),'class' => 'student-pill '.e(request()->routeIs('questions.my') ? 'student-pill-active' : '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('questions.my')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('questions.my')),'class' => 'student-pill '.e(request()->routeIs('questions.my') ? 'student-pill-active' : '').'']); ?>
                            <?php echo e(__('My Questions')); ?>

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
                    <button class="flex items-center text-sm nav-text focus:outline-none"
                            id="user-menu-button"
                            aria-expanded="false">
                        <?php echo e(auth()->user()->firstname); ?>

                        <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div class="hidden absolute left-1/2 -translate-x-1/2 mt-2 dropdown-bg rounded-md shadow-lg z-50 px-1 py-1"
                        id="user-dropdown"
                        style="width: 180px;">

                        <a href="<?php echo e(route('profile')); ?>" class="dropdown-square">
                            Profile
                        </a>

                        <?php if(auth()->user()->hasRole('Super Admin')): ?>
                            <a href="<?php echo e(route('settings.index')); ?>" class="dropdown-square">
                                Settings
                            </a>
                        <?php endif; ?>

                        <form action="<?php echo e(route('logout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="dropdown-square w-full">
                                Logout
                            </button>
                        </form>
                    </div>


                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Divider between nav bar and page content -->
<hr class="divider-line">

<script>
document.addEventListener('DOMContentLoaded', function () {

    const dropdownPairs = [];

    function registerDropdown(buttonId, menuId) {
        const btn = document.getElementById(buttonId);
        const menu = document.getElementById(menuId);
        if (!btn || !menu) return;

        dropdownPairs.push({ btn, menu });

        btn.addEventListener('click', function (e) {
            e.stopPropagation();

            const willOpen = menu.classList.contains('hidden'); // it is currently closed?

            // Close all dropdowns first
            dropdownPairs.forEach(pair => pair.menu.classList.add('hidden'));

            // Open only the clicked one (if it was closed)
            if (willOpen) menu.classList.remove('hidden');
        });
    }

    registerDropdown('semester-menu-button', 'semester-menu');

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

    // Close dropdowns when clicking any nav link (Dashboard, Reports, etc.)
    document.querySelectorAll('nav a, nav form button').forEach(el => {
        el.addEventListener('click', function () {
            dropdownPairs.forEach(pair => pair.menu.classList.add('hidden'));
        });
    });

});
</script>

<?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>