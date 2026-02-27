{{-- resources/views/layouts/navigation.blade.php (BOOTSTRAP ONLY) --}}

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

    /* Desktop center nav */
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
        border-radius: 14px;
        box-shadow: var(--shadow);
        padding: 6px;
        overflow: hidden;
        z-index: 1050;
    }

    .dropdown-square{
        display:block;
        padding: .65rem .8rem;
        font-size: .90rem;
        font-weight: 700;
        color: #111827;
        text-decoration: none;
        border-radius: 10px;
        white-space: nowrap;
        transition: background .12s ease;
    }
    .dropdown-square:hover{ background: #f1f3f5; }
    .dropdown-square-active{
        background: rgba(11,46,94,.08);
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

    /* ✅ Mobile controls */
    .mobile-actions{
        display:none;
        gap:8px;
        align-items:center;
    }
    .btn-nav-ghost{
        height:40px;
        padding: 0 .75rem;
        border-radius: 10px;
        border: 1px solid var(--stroke);
        background:#fff;
        color: var(--text);
        font-weight: 800;
    }
    .btn-nav-ghost:hover{ background:#f1f3f5; }

    /* Hide center menu on small screens, show hamburger */
    @media (max-width: 992px){
        .center-zone{ display:none !important; }
        .mobile-actions{ display:flex; }
        .brand-wrap{ max-width: 220px; }
        .brand-title{ max-width: 150px; }
    }

    /* Offcanvas links */
    .off-link{
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding: 10px 12px;
        border-radius: 12px;
        text-decoration:none;
        color:#111827;
        font-weight: 800;
        border: 1px solid transparent;
    }
    .off-link:hover{
        background:#f8fafc;
        border-color: var(--stroke);
    }
    .off-link.active{
        background: rgba(11,46,94,.08);
        border-color: rgba(11,46,94,.18);
        color: var(--brand);
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

    /* ✅ Prevent dropdown cutoff on small screens */
    .dropdown-bg{
        max-width: calc(100vw - 16px);
    }
    /* ✅ Small clear (X) button beside semester name */
    .sem-clear-btn{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        width:20px;
        height:20px;
        margin-left:.45rem;
        border-radius:999px;
        border: 1px solid rgba(11,46,94,.22);
        background:#fff;
        color: var(--text);
        font-weight:900;
        font-size: 14px;
        line-height: 1;
        cursor:pointer;
        transition:.12s ease;
    }
    .sem-clear-btn:hover{
        background: rgba(11,46,94,.08);
    }
    .sem-clear-btn:active{
        transform: scale(.96);
    }
</style>

@php
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
@endphp

<nav class="nav-bg">
    <div class="container-fluid px-3 px-md-4">
        <div class="d-flex align-items-center justify-content-between">

            {{-- LEFT (Brand) --}}
            <div class="brand-wrap py-2">
                @if($settings && $settings->logo_path && file_exists(public_path('storage/' . $settings->logo_path)))
                    <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo" class="brand-logo">
                @else
                    <img src="{{ asset('images/scholarship_logo.jpg') }}" alt="Logo" class="brand-logo">
                @endif

                <div style="min-width:0;">
                    <div class="brand-title" title="{{ $settings->system_name ?? 'Scholarship Management Information System' }}">
                        {{ $settings->system_name ?? 'Scholarship Management Information System' }}
                    </div>
                    <div class="brand-sub">BISU Candijay Campus</div>
                </div>
            </div>

            {{-- ✅ MOBILE ACTIONS (hamburger + user) --}}
            <div class="mobile-actions">
                @auth
                    <button type="button"
                            class="btn-nav-ghost"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#mainNavOffcanvas">
                        Menu
                    </button>

                    <button type="button"
                            class="btn-nav-ghost"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#userOffcanvas">
                        {{ auth()->user()->firstname }}
                    </button>
                @else
                    <a href="{{ route('login') }}" class="btn-nav-ghost text-decoration-none d-inline-flex align-items-center">
                        Login
                    </a>
                @endauth
            </div>

            {{-- CENTER (Desktop Menu) --}}
            <div class="flex-grow-1 d-flex justify-content-center center-zone">
                <div class="topnav">

                    @auth
                        @if(auth()->user()->hasRole('Scholarship Coordinator') || auth()->user()->hasRole('Super Admin'))
                            <button type="button"
                                    id="semesterModalOpenBtn"
                                    class="top-link {{ $activeSemesterId ? 'top-link-sem-active' : '' }}">
                                {{ $activeSemesterName }}
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        @endif
                    @endauth

                    @auth
                        {{-- SUPER ADMIN --}}
                        @if(auth()->user()->hasRole('Super Admin'))

                            <a href="{{ route('admin.dashboard') }}"
                               class="top-link {{ (request()->routeIs('admin.dashboard') && !request('page')) ? 'top-link-active' : '' }}">
                                Dashboard
                            </a>

                            <div class="position-relative">
                                <button type="button" id="users-menu-button"
                                        class="top-link {{ $usersGroupActive ? 'top-link-active' : '' }}">
                                    Users &amp; Roles
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <div id="users-menu" class="d-none position-absolute start-0 top-100 dropdown-bg" style="min-width: 16rem;">
                                    <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}"
                                       class="dropdown-square {{ $page === 'manage-users' ? 'dropdown-square-active' : '' }}">
                                        System Users
                                    </a>
                                    <a href="{{ route('admin.dashboard', ['page' => 'user-type']) }}"
                                       class="dropdown-square {{ $page === 'user-type' ? 'dropdown-square-active' : '' }}">
                                        User Types
                                    </a>
                                </div>
                            </div>

                            <div class="position-relative">
                                <button type="button" id="academic-menu-button"
                                        class="top-link {{ $academicGroupActive ? 'top-link-active' : '' }}">
                                    Academic Structure
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <div id="academic-menu" class="d-none position-absolute start-0 top-100 dropdown-bg" style="min-width: 16rem;">
                                    <a href="{{ route('admin.dashboard', ['page' => 'colleges']) }}"
                                       class="dropdown-square {{ $page === 'colleges' ? 'dropdown-square-active' : '' }}">
                                        Colleges
                                    </a>
                                    <a href="{{ route('admin.dashboard', ['page' => 'courses']) }}"
                                       class="dropdown-square {{ $page === 'courses' ? 'dropdown-square-active' : '' }}">
                                        Courses
                                    </a>
                                    <a href="{{ route('admin.dashboard', ['page' => 'year-levels']) }}"
                                       class="dropdown-square {{ $page === 'year-levels' ? 'dropdown-square-active' : '' }}">
                                        Year Levels
                                    </a>
                                    <a href="{{ route('admin.dashboard', ['page' => 'semesters']) }}"
                                       class="dropdown-square {{ $page === 'semesters' ? 'dropdown-square-active' : '' }}">
                                        Semesters
                                    </a>
                                </div>
                            </div>

                            <div class="position-relative">
                                <button type="button" id="enrollment-menu-button"
                                        class="top-link {{ $enrollmentGroupActive ? 'top-link-active' : '' }}">
                                    Enrollment
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <div id="enrollment-menu" class="d-none position-absolute start-0 top-100 dropdown-bg" style="min-width: 16rem;">
                                    <a href="{{ route('admin.dashboard', ['page' => 'enrollments']) }}"
                                       class="dropdown-square {{ $page === 'enrollments' ? 'dropdown-square-active' : '' }}">
                                        Enrollment Records
                                    </a>
                                </div>
                            </div>

                        {{-- COORDINATOR --}}
                        @elseif(auth()->user()->hasRole('Scholarship Coordinator'))

                            <a href="{{ route('coordinator.dashboard') }}"
                               class="top-link {{ request()->routeIs('coordinator.dashboard') ? 'top-link-active' : '' }}">
                                Dashboard
                            </a>

                            <div class="position-relative">
                                <button type="button" id="coord-scholars-menu-button"
                                        class="top-link {{ $coordScholarsGroupActive ? 'top-link-active' : '' }}">
                                    Student Services
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <div id="coord-scholars-menu" class="d-none position-absolute start-0 top-100 dropdown-bg" style="min-width: 18rem;">
                                    <a href="{{ route('coordinator.scholarship-batches') }}"
                                       class="dropdown-square {{ (request()->routeIs('coordinator.scholarship-batches') || request()->routeIs('coordinator.scholarship-batches.*')) ? 'dropdown-square-active' : '' }}">
                                        Scholarship Batches
                                    </a>
                                    <a href="{{ route('coordinator.manage-scholars') }}"
                                       class="dropdown-square {{ (request()->routeIs('coordinator.manage-scholars') || request()->routeIs('coordinator.scholars.*')) ? 'dropdown-square-active' : '' }}">
                                        Scholars
                                    </a>
                                    <a href="{{ route('coordinator.manage-scholarships') }}"
                                       class="dropdown-square {{ (request()->routeIs('coordinator.manage-scholarships') || request()->routeIs('coordinator.scholarships.*')) ? 'dropdown-square-active' : '' }}">
                                        Scholarships
                                    </a>
                                    <a href="{{ route('coordinator.enrollment-records') }}"
                                       class="dropdown-square {{ request()->routeIs('coordinator.enrollment-records') ? 'dropdown-square-active' : '' }}">
                                        Students Record
                                    </a>
                                </div>
                            </div>

                            <div class="position-relative">
                                <button type="button" id="coord-stipends-menu-button"
                                        class="top-link {{ $coordStipendsGroupActive ? 'top-link-active' : '' }}">
                                    Stipends
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <div id="coord-stipends-menu" class="d-none position-absolute start-0 top-100 dropdown-bg" style="min-width: 18rem;">
                                    <a href="{{ route('coordinator.manage-stipend-releases') }}"
                                       class="dropdown-square {{ (request()->routeIs('coordinator.manage-stipend-releases') || request()->routeIs('coordinator.stipend-releases.*')) ? 'dropdown-square-active' : '' }}">
                                        Stipend Details
                                    </a>
                                    <a href="{{ route('coordinator.manage-stipends') }}"
                                       class="dropdown-square {{ (request()->routeIs('coordinator.manage-stipends') || request()->routeIs('coordinator.stipends.*')) ? 'dropdown-square-active' : '' }}">
                                        Stipend Release Schedule
                                    </a>
                                </div>
                            </div>

                            <div class="position-relative">
                                <button type="button" id="coord-announcements-menu-button"
                                        class="top-link {{ $coordAnnouncementsGroupActive ? 'top-link-active' : '' }}">
                                    Announcements
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <div id="coord-announcements-menu" class="d-none position-absolute start-0 top-100 dropdown-bg" style="min-width: 18rem;">
                                    <a href="{{ route('coordinator.manage-announcements') }}"
                                       class="dropdown-square {{ (request()->routeIs('coordinator.manage-announcements') || request()->routeIs('coordinator.announcements.*')) ? 'dropdown-square-active' : '' }}">
                                        Post Announcements
                                    </a>
                                    <a href="{{ route('clusters.index') }}"
                                       class="dropdown-square {{ request()->routeIs('clusters.*') ? 'dropdown-square-active' : '' }}">
                                        Student Inquiries
                                    </a>
                                </div>
                            </div>

                            <a href="{{ route('coordinator.reports') }}"
                               class="top-link {{ $coordReportsActive ? 'top-link-active' : '' }}">
                                Reports
                            </a>

                        {{-- STUDENT --}}
                        @elseif(auth()->user()->hasRole('Student'))

                            <a href="{{ route('student.dashboard') }}"
                               class="top-link {{ request()->routeIs('student.dashboard') ? 'top-link-active' : '' }}">
                                Home
                            </a>

                            <a href="{{ route('student.announcements') }}"
                               class="top-link {{ request()->routeIs('student.announcements') ? 'top-link-active' : '' }}">
                                Announcements
                            </a>

                            <a href="{{ route('student.scholarships.index') }}"
                               class="top-link {{ request()->routeIs('student.scholarships.*') ? 'top-link-active' : '' }}">
                                Scholarships
                            </a>

                            @if(\App\Models\Scholar::where('student_id', auth()->id())->exists())
                                <a href="{{ route('student.stipend-history') }}"
                                   class="top-link {{ request()->routeIs('student.stipend-history') ? 'top-link-active' : '' }}">
                                    Stipends
                                </a>
                            @endif

                            <a href="{{ route('student.notifications') }}"
                               class="top-link {{ request()->routeIs('student.notifications') ? 'top-link-active' : '' }}">
                                Notifications
                            </a>

                            <a href="{{ route('questions.create') }}"
                               class="top-link {{ request()->routeIs('questions.create') ? 'top-link-active' : '' }}">
                                Ask
                            </a>

                        @endif
                    @endauth
                </div>
            </div>

            {{-- RIGHT (Desktop user dropdown) --}}
            <div class="d-flex justify-content-end center-zone" style="min-width:190px;">
                @auth
                    <div class="position-relative">
                        <button class="user-btn" id="user-menu-button" type="button">
                            {{ auth()->user()->firstname }}
                            <svg style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <div id="user-dropdown"
                            class="d-none position-absolute end-0 top-100 dropdown-bg"
                            style="width: 220px; margin-top: 6px;">
                        <a href="{{ route('profile') }}" class="dropdown-square">Profile</a>

                            @if(auth()->user()->hasRole('Super Admin'))
                                <a href="{{ route('settings.index') }}" class="dropdown-square">Settings</a>
                            @endif

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-square w-100 text-start">Logout</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

        </div>
    </div>
</nav>

<hr class="divider-line">

{{-- ✅ OFFCANVAS: MAIN MENU (MOBILE) --}}
@auth
<div class="offcanvas offcanvas-start" tabindex="-1" id="mainNavOffcanvas">
    <div class="offcanvas-header">
        <div>
            <div class="fw-bold" style="color:var(--brand);">Menu</div>
            <div class="text-muted small">Quick navigation</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-2">

        {{-- Semester (mobile) --}}
        @if(auth()->user()->hasRole('Scholarship Coordinator') || auth()->user()->hasRole('Super Admin'))
            <button type="button"
                    id="semesterModalOpenBtnMobile"
                    class="off-link w-100 border-0 bg-white">
                <span>{{ $activeSemesterName }}</span>
                <span class="text-muted small">Filter</span>
            </button>
            <div class="my-2"></div>
        @endif

        {{-- Role menus (mobile) --}}
        @if(auth()->user()->hasRole('Super Admin'))
            <a class="off-link {{ (request()->routeIs('admin.dashboard') && !request('page')) ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}">Dashboard</a>

            <div class="mt-2 px-2 text-muted small fw-bold">Users & Roles</div>
            <a class="off-link {{ $page === 'manage-users' ? 'active' : '' }}"
               href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}">System Users</a>
            <a class="off-link {{ $page === 'user-type' ? 'active' : '' }}"
               href="{{ route('admin.dashboard', ['page' => 'user-type']) }}">User Types</a>

            <div class="mt-2 px-2 text-muted small fw-bold">Academic Structure</div>
            <a class="off-link {{ $page === 'colleges' ? 'active' : '' }}"
               href="{{ route('admin.dashboard', ['page' => 'colleges']) }}">Colleges</a>
            <a class="off-link {{ $page === 'courses' ? 'active' : '' }}"
               href="{{ route('admin.dashboard', ['page' => 'courses']) }}">Courses</a>
            <a class="off-link {{ $page === 'year-levels' ? 'active' : '' }}"
               href="{{ route('admin.dashboard', ['page' => 'year-levels']) }}">Year Levels</a>
            <a class="off-link {{ $page === 'semesters' ? 'active' : '' }}"
               href="{{ route('admin.dashboard', ['page' => 'semesters']) }}">Semesters</a>

            <div class="mt-2 px-2 text-muted small fw-bold">Enrollment</div>
            <a class="off-link {{ $page === 'enrollments' ? 'active' : '' }}"
               href="{{ route('admin.dashboard', ['page' => 'enrollments']) }}">Enrollment Records</a>

        @elseif(auth()->user()->hasRole('Scholarship Coordinator'))
            <a class="off-link {{ request()->routeIs('coordinator.dashboard') ? 'active' : '' }}"
               href="{{ route('coordinator.dashboard') }}">Dashboard</a>

            <div class="mt-2 px-2 text-muted small fw-bold">Student Services</div>
            <a class="off-link {{ (request()->routeIs('coordinator.scholarship-batches') || request()->routeIs('coordinator.scholarship-batches.*')) ? 'active' : '' }}"
               href="{{ route('coordinator.scholarship-batches') }}">Scholarship Batches</a>
            <a class="off-link {{ (request()->routeIs('coordinator.manage-scholars') || request()->routeIs('coordinator.scholars.*')) ? 'active' : '' }}"
               href="{{ route('coordinator.manage-scholars') }}">Scholars</a>
            <a class="off-link {{ (request()->routeIs('coordinator.manage-scholarships') || request()->routeIs('coordinator.scholarships.*')) ? 'active' : '' }}"
               href="{{ route('coordinator.manage-scholarships') }}">Scholarships</a>
            <a class="off-link {{ request()->routeIs('coordinator.enrollment-records') ? 'active' : '' }}"
               href="{{ route('coordinator.enrollment-records') }}">Students Record</a>

            <div class="mt-2 px-2 text-muted small fw-bold">Stipends</div>
            <a class="off-link {{ (request()->routeIs('coordinator.manage-stipend-releases') || request()->routeIs('coordinator.stipend-releases.*')) ? 'active' : '' }}"
               href="{{ route('coordinator.manage-stipend-releases') }}">Stipend Release Schedule</a>
            <a class="off-link {{ (request()->routeIs('coordinator.manage-stipends') || request()->routeIs('coordinator.stipends.*')) ? 'active' : '' }}"
               href="{{ route('coordinator.manage-stipends') }}">Stipend Details</a>

            <div class="mt-2 px-2 text-muted small fw-bold">Announcements</div>
            <a class="off-link {{ (request()->routeIs('coordinator.manage-announcements') || request()->routeIs('coordinator.announcements.*')) ? 'active' : '' }}"
               href="{{ route('coordinator.manage-announcements') }}">Post Announcements</a>
            <a class="off-link {{ request()->routeIs('clusters.*') ? 'active' : '' }}"
               href="{{ route('clusters.index') }}">Student Inquiries</a>

            <a class="off-link {{ $coordReportsActive ? 'active' : '' }}"
               href="{{ route('coordinator.reports') }}">Reports</a>

        @elseif(auth()->user()->hasRole('Student'))
            <a class="off-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}"
               href="{{ route('student.dashboard') }}">Home</a>
            <a class="off-link {{ request()->routeIs('student.announcements') ? 'active' : '' }}"
               href="{{ route('student.announcements') }}">Announcements</a>
            <a class="off-link {{ request()->routeIs('student.scholarships.*') ? 'active' : '' }}"
               href="{{ route('student.scholarships.index') }}">Scholarships</a>

            @if(\App\Models\Scholar::where('student_id', auth()->id())->exists())
                <a class="off-link {{ request()->routeIs('student.stipend-history') ? 'active' : '' }}"
                   href="{{ route('student.stipend-history') }}">Stipends</a>
            @endif

            <a class="off-link {{ request()->routeIs('student.notifications') ? 'active' : '' }}"
               href="{{ route('student.notifications') }}">Notifications</a>
            <a class="off-link {{ request()->routeIs('questions.create') ? 'active' : '' }}"
               href="{{ route('questions.create') }}">Ask</a>
        @endif

    </div>
</div>

{{-- ✅ OFFCANVAS: USER MENU (MOBILE) --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="userOffcanvas">
    <div class="offcanvas-header">
        <div>
            <div class="fw-bold" style="color:var(--brand);">{{ auth()->user()->firstname }}</div>
            <div class="text-muted small">{{ auth()->user()->email }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-2">
        <a class="off-link" href="{{ route('profile') }}">Profile</a>

        @if(auth()->user()->hasRole('Super Admin'))
            <a class="off-link" href="{{ route('settings.index') }}">Settings</a>
        @endif

        <form action="{{ route('logout') }}" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100 fw-bold">
                Logout
            </button>
        </form>
    </div>
</div>
@endauth

{{-- ✅ Semester Filter Modal --}}
@auth
@if(auth()->user()->hasRole('Scholarship Coordinator') || auth()->user()->hasRole('Super Admin'))
<div id="semesterModalOverlay"
     class="d-none position-fixed top-0 start-0 w-100 h-100"
     style="z-index:1055; background: rgba(0,0,0,.35); backdrop-filter: blur(2px);">
    <div class="position-absolute top-50 start-50 translate-middle bg-white rounded-3 p-3"
        style="width: 280px; max-width: calc(100vw - 24px); border: 1px solid #e7edf6; box-shadow: 0 10px 28px rgba(11,46,94,.08);">

        <div class="d-flex align-items-start justify-content-between mb-3">
            <div>
                <div class="fw-bold" style="color:var(--text)">Change Semester</div>
                {{-- <div class="small" style="color:var(--muted)">Type to search. Click a result to apply.</div> --}}
            </div>
            <button type="button" id="semesterModalCloseBtn" class="user-btn" style="height:38px;padding:0 .55rem;">✕</button>
        </div>

        <input type="text" id="semesterSearchInput"
            class="form-control"
            placeholder="Type semester..."
            autocomplete="off">

        {{-- ✅ Removed All Semesters Button --}}
        <div class="mt-2">
            <div id="semesterSearchStatus" class="small" style="color:var(--muted)"></div>
        </div>

        <div id="semesterSearchResults" class="mt-3 d-none"
             style="border:1px solid #e7edf6; border-radius:12px; overflow:hidden;"></div>

        <form id="semesterSetForm" method="POST" action="{{ route('semester.filter.set') }}" class="d-none">
            @csrf
            <input type="hidden" name="semester_id" id="semesterSelectedId">
        </form>

        <form id="semesterClearForm" method="POST" action="{{ route('semester.filter.clear') }}" class="d-none">
            @csrf
        </form>
    </div>
</div>
@endif
@endauth

<script>
window.ACTIVE_SEMESTER_ID = {{ (int) (session('active_semester_id') ?? 0) }};
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const dropdownPairs = [];

    function showMenu(menu){ menu.classList.remove('d-none'); }
    function hideMenu(menu){ menu.classList.add('d-none'); }
    function isHidden(menu){ return menu.classList.contains('d-none'); }

    function closeAllDropdowns(){
        dropdownPairs.forEach(pair => hideMenu(pair.menu));
    }

    function registerDropdown(buttonId, menuId) {
        const btn = document.getElementById(buttonId);
        const menu = document.getElementById(menuId);
        if (!btn || !menu) return;

        dropdownPairs.push({ btn, menu });

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const willOpen = isHidden(menu);
            closeAllDropdowns();
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
    

    document.addEventListener('click', closeAllDropdowns);

    // ✅ If screen resized to mobile, close dropdowns (prevents floating menus)
    window.addEventListener('resize', closeAllDropdowns);

    document.querySelectorAll('nav a, nav form button').forEach(el => {
        el.addEventListener('click', closeAllDropdowns);
    });

    // SEMESTER MODAL
    const semOpenBtn = document.getElementById('semesterModalOpenBtn');
    const semOpenBtnMobile = document.getElementById('semesterModalOpenBtnMobile');

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
            const url = `{{ route('semester.filter.search') }}?q=${encodeURIComponent(q)}`;
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

    semOpenBtnMobile?.addEventListener('click', (e) => {
        e.stopPropagation();
        // close offcanvas if open
        const off = document.getElementById('mainNavOffcanvas');
        if(off){
            const inst = bootstrap.Offcanvas.getInstance(off);
            inst?.hide();
        }
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