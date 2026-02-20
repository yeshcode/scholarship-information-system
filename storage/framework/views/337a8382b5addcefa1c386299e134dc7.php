

<style>
    :root{
        --brand:#0b2e5e;
        --brand-2:#123f85;
        --bg:#f4f7fb;
        --card:#ffffff;
        --stroke:#dee2e6;
        --text:#0b2e5e;
        --muted:#6c7a92;
        --shadow: 0 12px 30px rgba(0,0,0,.12);
    }

    .nav-bg{
        background: #f8f9fa;
        border-bottom: 1px solid var(--stroke);
        position: sticky;
        top: 0;
        z-index: 1030;
    }

    .brand-wrap{
        display:flex;
        align-items:center;
        gap:.6rem;
        min-width: 0;
        max-width: 340px;
    }
    .brand-logo{
        height:40px;
        width:40px;
        object-fit:cover;
        border-radius:10px;
        border: 1px solid rgba(11,46,94,.15);
        background:#fff;
    }
    .brand-title{
        font-weight:800;
        color:var(--text);
        letter-spacing:.1px;
        font-size: .95rem;
        line-height:1.15;
        max-width: 255px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .brand-sub{
        font-size:.78rem;
        color:var(--muted);
        line-height:1.1;
    }

    .topnav{
        display:flex;
        align-items:center;
        flex-wrap: nowrap;
        gap: 0;
    }

    .top-link{
        height: 48px;
        display:inline-flex;
        align-items:center;
        padding: 0 1rem;
        background: transparent;
        border: 0;
        border-radius: 0;
        font-size: .90rem;
        font-weight: 700;
        color: var(--text);
        text-decoration: none;
        transition: background .15s ease;
        white-space: nowrap;
    }
    .top-link:hover{ background: #e9ecef; }
    .top-link-active{
        background: #e9ecef;
        border-bottom: 3px solid var(--brand);
    }

    .top-link-sem-active{
        background: transparent !important;
        border-bottom: 0 !important;
        font-weight: 800;
    }
    #semesterModalOpenBtn:hover{ background: transparent !important; }

    .top-link svg{
        margin-left: .35rem;
        width: 16px;
        height: 16px;
        opacity: .85;
    }

    .dropdown-bg{
        background: #ffffff;
        border: 1px solid var(--stroke);
        border-radius: 0;
        box-shadow: var(--shadow);
        padding: 0;
        overflow: hidden;
        z-index: 1050;
    }

    .dropdown-square{
        display:block;
        padding: .75rem 1rem;
        font-size: .90rem;
        font-weight: 600;
        color: #212529;
        text-decoration: none;
        border-radius: 0;
        white-space: nowrap;
        transition: background .12s ease;
    }
    .dropdown-square:hover{ background: #f1f3f5; }
    .dropdown-square-active{
        background: #e7f1ff;
        border-left: 4px solid var(--brand);
    }

    .user-btn{
        height: 48px;
        display:inline-flex;
        align-items:center;
        gap:.4rem;
        padding: 0 1rem;
        border-radius: 0;
        background: transparent;
        border: 0;
        font-weight: 800;
        color: var(--text);
        transition: background .15s ease;
        white-space: nowrap;
    }
    .user-btn:hover{ background: #e9ecef; }

    .divider-line{
        border: 0;
        border-top: 1px solid var(--stroke);
        margin: 0;
    }

    /* Modal (Bootstrap-only) */
    .sem-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding:.5rem .65rem;
        font-weight:700;
        font-size:.82rem;
        color:var(--text);
        background:#fff;
        border-bottom:1px solid #e7edf6;
        transition:.12s ease;
        width:100%;
        text-align:left;
        border: 0;
    }
    .sem-row:hover{ background: rgba(11,46,94,.06); }
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

<nav class="nav-bg">
    <div class="container-fluid px-3 px-md-4">
        <div class="d-flex align-items-center justify-content-between">

            
            <div class="brand-wrap py-2">
                <?php if($settings && $settings->logo_path && file_exists(public_path('storage/' . $settings->logo_path))): ?>
                    <img src="<?php echo e(asset('storage/' . $settings->logo_path)); ?>" alt="Logo" class="brand-logo">
                <?php else: ?>
                    <img src="<?php echo e(asset('images/scholarship_logo.jpg')); ?>" alt="Logo" class="brand-logo">
                <?php endif; ?>

                <div style="min-width:0;">
                    <div class="brand-title" title="<?php echo e($settings->system_name ?? 'Scholarship Management Information System'); ?>">
                        <?php echo e($settings->system_name ?? 'Scholarship Management Information System'); ?>

                    </div>
                    <div class="brand-sub">BISU Candijay Campus</div>
                </div>
            </div>

            
            <div class="flex-grow-1 d-flex justify-content-center">
                <div class="topnav">

                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->hasRole('Scholarship Coordinator') || auth()->user()->hasRole('Super Admin')): ?>
                            <button type="button"
                                    id="semesterModalOpenBtn"
                                    class="top-link <?php echo e($activeSemesterId ? 'top-link-sem-active' : ''); ?>">
                                <?php echo e($activeSemesterName); ?>

                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if(auth()->guard()->check()): ?>
                        
                        <?php if(auth()->user()->hasRole('Super Admin')): ?>

                            <a href="<?php echo e(route('admin.dashboard')); ?>"
                               class="top-link <?php echo e((request()->routeIs('admin.dashboard') && !request('page')) ? 'top-link-active' : ''); ?>">
                                Dashboard
                            </a>

                            <div class="position-relative">
                                <button type="button" id="users-menu-button"
                                        class="top-link <?php echo e($usersGroupActive ? 'top-link-active' : ''); ?>">
                                    Users &amp; Roles
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <div id="users-menu" class="d-none position-absolute start-0 top-100 dropdown-bg" style="min-width: 16rem;">
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

                            <div class="position-relative">
                                <button type="button" id="academic-menu-button"
                                        class="top-link <?php echo e($academicGroupActive ? 'top-link-active' : ''); ?>">
                                    Academic Structure
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <div id="academic-menu" class="d-none position-absolute start-0 top-100 dropdown-bg" style="min-width: 16rem;">
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
                                    <a href="<?php echo e(route('admin.dashboard', ['page' => 'semesters'])); ?>"
                                       class="dropdown-square <?php echo e($page === 'semesters' ? 'dropdown-square-active' : ''); ?>">
                                        Semesters
                                    </a>
                                </div>
                            </div>

                            <div class="position-relative">
                                <button type="button" id="enrollment-menu-button"
                                        class="top-link <?php echo e($enrollmentGroupActive ? 'top-link-active' : ''); ?>">
                                    Enrollment
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <div id="enrollment-menu" class="d-none position-absolute start-0 top-100 dropdown-bg" style="min-width: 16rem;">
                                    <a href="<?php echo e(route('admin.dashboard', ['page' => 'enrollments'])); ?>"
                                       class="dropdown-square <?php echo e($page === 'enrollments' ? 'dropdown-square-active' : ''); ?>">
                                        Enrollment Records
                                    </a>
                                </div>
                            </div>

                        
                        <?php elseif(auth()->user()->hasRole('Scholarship Coordinator')): ?>

                            <a href="<?php echo e(route('coordinator.dashboard')); ?>"
                               class="top-link <?php echo e(request()->routeIs('coordinator.dashboard') ? 'top-link-active' : ''); ?>">
                                Dashboard
                            </a>

                            <div class="position-relative">
                                <button type="button" id="coord-scholars-menu-button"
                                        class="top-link <?php echo e($coordScholarsGroupActive ? 'top-link-active' : ''); ?>">
                                    Student Services
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <div id="coord-scholars-menu" class="d-none position-absolute start-0 top-100 dropdown-bg" style="min-width: 18rem;">
                                    <a href="<?php echo e(route('coordinator.scholarship-batches')); ?>"
                                       class="dropdown-square <?php echo e((request()->routeIs('coordinator.scholarship-batches') || request()->routeIs('coordinator.scholarship-batches.*')) ? 'dropdown-square-active' : ''); ?>">
                                        Scholarship Batches
                                    </a>
                                    <a href="<?php echo e(route('coordinator.manage-scholars')); ?>"
                                       class="dropdown-square <?php echo e((request()->routeIs('coordinator.manage-scholars') || request()->routeIs('coordinator.scholars.*')) ? 'dropdown-square-active' : ''); ?>">
                                        Scholars
                                    </a>
                                    <a href="<?php echo e(route('coordinator.manage-scholarships')); ?>"
                                       class="dropdown-square <?php echo e((request()->routeIs('coordinator.manage-scholarships') || request()->routeIs('coordinator.scholarships.*')) ? 'dropdown-square-active' : ''); ?>">
                                        Scholarships
                                    </a>
                                    <a href="<?php echo e(route('coordinator.enrollment-records')); ?>"
                                       class="dropdown-square <?php echo e(request()->routeIs('coordinator.enrollment-records') ? 'dropdown-square-active' : ''); ?>">
                                        Students Record
                                    </a>
                                </div>
                            </div>

                            <div class="position-relative">
                                <button type="button" id="coord-stipends-menu-button"
                                        class="top-link <?php echo e($coordStipendsGroupActive ? 'top-link-active' : ''); ?>">
                                    Stipends
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <div id="coord-stipends-menu" class="d-none position-absolute start-0 top-100 dropdown-bg" style="min-width: 18rem;">
                                    <a href="<?php echo e(route('coordinator.manage-stipend-releases')); ?>"
                                       class="dropdown-square <?php echo e((request()->routeIs('coordinator.manage-stipend-releases') || request()->routeIs('coordinator.stipend-releases.*')) ? 'dropdown-square-active' : ''); ?>">
                                        Stipend Release Schedule
                                    </a>
                                    <a href="<?php echo e(route('coordinator.manage-stipends')); ?>"
                                       class="dropdown-square <?php echo e((request()->routeIs('coordinator.manage-stipends') || request()->routeIs('coordinator.stipends.*')) ? 'dropdown-square-active' : ''); ?>">
                                        Stipend Details
                                    </a>
                                </div>
                            </div>

                            <div class="position-relative">
                                <button type="button" id="coord-announcements-menu-button"
                                        class="top-link <?php echo e($coordAnnouncementsGroupActive ? 'top-link-active' : ''); ?>">
                                    Announcements
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <div id="coord-announcements-menu" class="d-none position-absolute start-0 top-100 dropdown-bg" style="min-width: 18rem;">
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

                            <a href="<?php echo e(route('coordinator.reports')); ?>"
                               class="top-link <?php echo e($coordReportsActive ? 'top-link-active' : ''); ?>">
                                Reports
                            </a>

                        
                        <?php elseif(auth()->user()->hasRole('Student')): ?>

                            <a href="<?php echo e(route('student.dashboard')); ?>"
                               class="top-link <?php echo e(request()->routeIs('student.dashboard') ? 'top-link-active' : ''); ?>">
                                Home
                            </a>

                            <a href="<?php echo e(route('student.announcements')); ?>"
                               class="top-link <?php echo e(request()->routeIs('student.announcements') ? 'top-link-active' : ''); ?>">
                                Announcements
                            </a>

                            <a href="<?php echo e(route('student.scholarships.index')); ?>"
                               class="top-link <?php echo e(request()->routeIs('student.scholarships.*') ? 'top-link-active' : ''); ?>">
                                Scholarships
                            </a>

                            <?php if(\App\Models\Scholar::where('student_id', auth()->id())->exists()): ?>
                                <a href="<?php echo e(route('student.stipend-history')); ?>"
                                   class="top-link <?php echo e(request()->routeIs('student.stipend-history') ? 'top-link-active' : ''); ?>">
                                    Stipends
                                </a>
                            <?php endif; ?>

                            <a href="<?php echo e(route('student.notifications')); ?>"
                               class="top-link <?php echo e(request()->routeIs('student.notifications') ? 'top-link-active' : ''); ?>">
                                Notifications
                            </a>

                            <a href="<?php echo e(route('questions.create')); ?>"
                               class="top-link <?php echo e(request()->routeIs('questions.create') ? 'top-link-active' : ''); ?>">
                                Ask
                            </a>

                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="d-flex justify-content-end" style="min-width:190px;">
                <?php if(auth()->guard()->check()): ?>
                    <div class="position-relative">
                        <button class="user-btn" id="user-menu-button" type="button">
                            <?php echo e(auth()->user()->firstname); ?>

                            <svg style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <div id="user-dropdown" class="d-none position-absolute start-50 translate-middle-x top-100 dropdown-bg" style="width:200px;">
                            <a href="<?php echo e(route('profile')); ?>" class="dropdown-square">Profile</a>

                            <?php if(auth()->user()->hasRole('Super Admin')): ?>
                                <a href="<?php echo e(route('settings.index')); ?>" class="dropdown-square">Settings</a>
                            <?php endif; ?>

                            <form action="<?php echo e(route('logout')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="dropdown-square w-100 text-start">Logout</button>
                            </form>
                        </div>

                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</nav>

<hr class="divider-line">


<?php if(auth()->guard()->check()): ?>
<?php if(auth()->user()->hasRole('Scholarship Coordinator') || auth()->user()->hasRole('Super Admin')): ?>
<div id="semesterModalOverlay"
     class="d-none position-fixed top-0 start-0 w-100 h-100"
     style="z-index:1055; background: rgba(0,0,0,.35); backdrop-filter: blur(2px);">
    <div class="position-absolute top-50 start-50 translate-middle bg-white rounded-3 p-3"
        style="width: 280px; max-width: calc(100vw - 24px); border: 1px solid #e7edf6; box-shadow: 0 10px 28px rgba(11,46,94,.08);">

        <div class="d-flex align-items-start justify-content-between mb-3">
            <div>
                <div class="fw-bold" style="color:var(--text)">Filter by Semester</div>
                <div class="small" style="color:var(--muted)">Type to search. Click a result to apply.</div>
            </div>
            <button type="button" id="semesterModalCloseBtn" class="user-btn" style="height:38px;padding:0 .55rem;">✕</button>
        </div>

        <input type="text" id="semesterSearchInput"
            class="form-control"
            placeholder="Type semester..."
            autocomplete="off">

        <div class="mt-3 d-flex align-items-center justify-content-between">
            <form method="POST" action="<?php echo e(route('semester.filter.clear')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-sm btn-outline-secondary">
                    All Semesters
                </button>
            </form>
            <div id="semesterSearchStatus" class="small" style="color:var(--muted)"></div>
        </div>

        <div id="semesterSearchResults" class="mt-3 d-none"
             style="border:1px solid #e7edf6; border-radius:12px; overflow:hidden;"></div>

        <form id="semesterSetForm" method="POST" action="<?php echo e(route('semester.filter.set')); ?>" class="d-none">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="semester_id" id="semesterSelectedId">
        </form>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<script>
window.ACTIVE_SEMESTER_ID = <?php echo e((int) (session('active_semester_id') ?? 0)); ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const dropdownPairs = [];

    function showMenu(menu){ menu.classList.remove('d-none'); }
    function hideMenu(menu){ menu.classList.add('d-none'); }
    function isHidden(menu){ return menu.classList.contains('d-none'); }

    function registerDropdown(buttonId, menuId) {
        const btn = document.getElementById(buttonId);
        const menu = document.getElementById(menuId);
        if (!btn || !menu) return;

        dropdownPairs.push({ btn, menu });

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const willOpen = isHidden(menu);
            dropdownPairs.forEach(pair => hideMenu(pair.menu));
            if (willOpen) showMenu(menu);
        });
    }

    registerDropdown('users-menu-button', 'users-menu');
    registerDropdown('academic-menu-button', 'academic-menu');
    registerDropdown('enrollment-menu-button', 'enrollment-menu');

    registerDropdown('coord-scholars-menu-button', 'coord-scholars-menu');
    registerDropdown('coord-stipends-menu-button', 'coord-stipends-menu');
    registerDropdown('coord-announcements-menu-button', 'coord-announcements-menu');

    registerDropdown('user-menu-button', 'user-dropdown');

    document.addEventListener('click', function () {
        dropdownPairs.forEach(pair => hideMenu(pair.menu));
    });

    document.querySelectorAll('nav a, nav form button').forEach(el => {
        el.addEventListener('click', function () {
            dropdownPairs.forEach(pair => hideMenu(pair.menu));
        });
    });

    // SEMESTER MODAL
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
        semOverlay.classList.remove('d-none');
        semInput.value = '';
        semResults.innerHTML = '';
        semResults.classList.add('d-none');
        semStatus.textContent = 'Type to search...';
        setTimeout(() => semInput.focus(), 50);
        fetchSemesters('');
    }

    function closeSemModal(){
        if(!semOverlay) return;
        semOverlay.classList.add('d-none');
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

            semResults.classList.remove('d-none');

            if(items.length === 0){
                semResults.innerHTML = `<div style="padding:.8rem; color:var(--muted); font-size:.85rem;">No matches found.</div>`;
                semStatus.textContent = '';
                return;
            }

            semResults.innerHTML = items.map(it => {
                const isSelected = Number(it.id) === Number(window.ACTIVE_SEMESTER_ID);
                const badge = isSelected
                    ? `<span class="sem-badge">selected</span>`
                    : (it.is_current ? `<span class="sem-badge">current</span>` : '');

                return `
                    <button type="button" class="sem-row" data-id="${it.id}">
                        <span>${escapeHtml(it.label)}</span>
                        ${badge}
                    </button>
                `;
            }).join('');

            semStatus.textContent = 'Click one to apply filter.';
        } catch(e){
            semResults.classList.remove('d-none');
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