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

@php
    $settings = \App\Models\SystemSetting::first();
    $page = request('page');

    $usersGroupActive = in_array($page, ['manage-users', 'user-type']);
    $academicGroupActive = in_array($page, ['colleges', 'courses', 'year-levels', 'semesters']);
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

@endphp

<!-- SINGLE TOP BAR: logo + title + nav links + user menu -->
<nav class="nav-bg shadow-sm">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-4 py-3">

        {{-- LEFT: Logo + System Name --}}
        <div class="flex items-center space-x-2">
            @if($settings && $settings->logo_path && file_exists(public_path('storage/' . $settings->logo_path)))
                <img src="{{ asset('storage/' . $settings->logo_path) }}"
                     alt="Logo"
                     class="h-8 w-8 object-contain logo-border">
            @else
                <span class="nav-text">[No Logo]</span>
            @endif
            <span class="text-lg nav-text">
                {{ $settings->system_name ?? 'Scholarship Management Information System' }}
            </span>
        </div>

        {{-- CENTER: Navigation Links --}}
        <div class="flex-1 flex justify-center">
            <div class="flex space-x-2 items-center flex-nowrap">
                {{-- ✅ SEMESTER FILTER (visible to all logged-in users) --}}
                @auth
                    @if(auth()->user()->hasRole('Scholarship Coordinator') || auth()->user()->hasRole('Super Admin'))
                        {{-- ✅ SEMESTER FILTER HERE --}}
                        <div class="relative">
                            <button type="button"
                                    id="semester-menu-button"
                                    class="nav-pill coord-pill {{ $activeSemesterId ? 'nav-pill-active' : '' }}">
                                {{ $activeSemesterName }}
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>

                            <div id="semester-menu"
                                class="hidden absolute left-0 mt-2 w-64 dropdown-bg rounded-md shadow-lg py-2 z-50">

                                {{-- All Semesters --}}
                                <form method="POST" action="{{ route('semester.filter.clear') }}">
                                    @csrf
                                    <button type="submit"
                                            class="dropdown-square w-full {{ !$activeSemesterId ? 'dropdown-square-active' : '' }}">
                                        All Semesters
                                    </button>
                                </form>

                                <div class="my-1 border-t border-gray-200"></div>

                                {{-- Specific Semesters --}}
                                @foreach($allSemesters as $sem)
                                    <form method="POST" action="{{ route('semester.filter.set') }}">
                                        @csrf
                                        <input type="hidden" name="semester_id" value="{{ $sem->id }}">
                                        <button type="submit"
                                                class="dropdown-square w-full {{ $activeSemesterId == $sem->id ? 'dropdown-square-active' : '' }}">
                                            {{ $sem->term }} {{ $sem->academic_year }}
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endauth


                @auth
                    {{-- SUPER ADMIN NAVIGATION --}}
                    @if(auth()->user()->hasRole('Super Admin'))
                        {{-- Dashboard (square) --}}
                        <x-nav-link
                            :href="route('admin.dashboard')"
                            :active="request()->routeIs('admin.dashboard') && !request('page')"
                            class="nav-pill {{ (request()->routeIs('admin.dashboard') && !request('page')) ? 'nav-pill-active' : '' }}">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        {{-- Users & Roles (square + dropdown) --}}
                        <div class="relative">
                            <button type="button"
                                    id="users-menu-button"
                                    class="nav-pill {{ $usersGroupActive ? 'nav-pill-active' : '' }}">
                                Users &amp; Roles
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="users-menu"
                                 class="hidden absolute left-0 mt-2 w-60 dropdown-bg rounded-md shadow-lg py-2 z-50">
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

                        {{-- Academic Structure (square + dropdown) --}}
                        <div class="relative">
                            <button type="button"
                                    id="academic-menu-button"
                                    class="nav-pill {{ $academicGroupActive ? 'nav-pill-active' : '' }}">
                                Academic Structure
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="academic-menu"
                                 class="hidden absolute left-0 mt-2 w-60 dropdown-bg rounded-md shadow-lg py-2 z-50">
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

                        {{-- Enrollment (square + dropdown) --}}
                        <div class="relative">
                            <button type="button"
                                    id="enrollment-menu-button"
                                    class="nav-pill {{ $enrollmentGroupActive ? 'nav-pill-active' : '' }}">
                                Enrollment
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="enrollment-menu"
                                 class="hidden absolute left-0 mt-2 w-60 dropdown-bg rounded-md shadow-lg py-2 z-50">
                                <a href="{{ route('admin.dashboard', ['page' => 'enrollments']) }}"
                                   class="dropdown-square {{ $page === 'enrollments' ? 'dropdown-square-active' : '' }}">
                                    Enrollment Records
                                </a>
                                {{-- Optional extra later:
                                <a href="{{ route('admin.enrollments.enroll-students') }}"
                                   class="dropdown-square">
                                    Enroll Students
                                </a>
                                --}}
                            </div>
                        </div>

                    {{-- SCHOLARSHIP COORDINATOR NAVIGATION (unchanged) --}}
                        @elseif(auth()->user()->hasRole('Scholarship Coordinator'))

                            {{-- 1) Dashboard --}}
                            <x-nav-link :href="route('coordinator.dashboard')"
                                        :active="request()->routeIs('coordinator.dashboard')"
                                        class="nav-pill coord-pill {{ request()->routeIs('coordinator.dashboard') ? 'nav-pill-active' : '' }}">
                                {{ __('Dashboard') }}
                            </x-nav-link>

                            {{-- 2) Manage Scholars (Dropdown) --}}
                            <div class="relative">
                                <button type="button"
                                        id="coord-scholars-menu-button"
                                        class="nav-pill coord-pill{{ $coordScholarsGroupActive ? 'nav-pill-active' : '' }}">
                                    Student Services
                                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>

                                <div id="coord-scholars-menu"
                                    class="hidden absolute left-0 mt-2 w-64 dropdown-bg rounded-md shadow-lg py-2 z-50">
                                    <a href="{{ route('coordinator.manage-scholars') }}"
                                    class="dropdown-square {{ (request()->routeIs('coordinator.manage-scholars') || request()->routeIs('coordinator.scholars.*')) ? 'dropdown-square-active' : '' }}">
                                        Scholars
                                    </a>

                                    <a href="{{ route('coordinator.enrollment-records') }}"
                                    class="dropdown-square {{ request()->routeIs('coordinator.enrollment-records') ? 'dropdown-square-active' : '' }}">
                                        Students Record
                                    </a>

                                    <a href="{{ route('coordinator.manage-scholarships') }}"
                                    class="dropdown-square {{ (request()->routeIs('coordinator.manage-scholarships') || request()->routeIs('coordinator.scholarships.*')) ? 'dropdown-square-active' : '' }}">
                                        Scholarships
                                    </a>
                                </div>
                            </div>

                            {{-- 3) Manage Stipends (Dropdown) --}}
                            <div class="relative">
                                <button type="button"
                                        id="coord-stipends-menu-button"
                                        class="nav-pill coord-pill {{ $coordStipendsGroupActive ? 'nav-pill-active' : '' }}">
                                    Stipends
                                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>

                                <div id="coord-stipends-menu"
                                    class="hidden absolute left-0 mt-2 w-64 dropdown-bg rounded-md shadow-lg py-2 z-50">
                                    <a href="{{ route('coordinator.manage-stipends') }}"
                                    class="dropdown-square {{ (request()->routeIs('coordinator.manage-stipends') || request()->routeIs('coordinator.stipends.*')) ? 'dropdown-square-active' : '' }}">
                                        Stipend Details
                                    </a>

                                    <a href="{{ route('coordinator.manage-stipend-releases') }}"
                                    class="dropdown-square {{ (request()->routeIs('coordinator.manage-stipend-releases') || request()->routeIs('coordinator.stipend-releases.*')) ? 'dropdown-square-active' : '' }}">
                                        Stipend Release Schedule
                                    </a>
                                </div>
                            </div>

                            {{-- 4) Announcements (Dropdown: Announcements + Student Queries) --}}
                            <div class="relative">
                                <button type="button"
                                        id="coord-announcements-menu-button"
                                        class="nav-pill coord-pill {{ $coordAnnouncementsGroupActive ? 'nav-pill-active' : '' }}">
                                    Announcements
                                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>

                                <div id="coord-announcements-menu"
                                    class="hidden absolute left-0 mt-2 w-64 dropdown-bg rounded-md shadow-lg py-2 z-50">
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

                            {{-- 5) Reports (add your route here) --}}
                            <x-nav-link :href="route('coordinator.reports')"  {{-- change if your route name differs --}}
                                        :active="$coordReportsActive"
                                        class="nav-pill coord-pill {{ $coordReportsActive ? 'nav-pill-active' : '' }}">
                                {{ __('Reports') }}
                            </x-nav-link>





                        {{-- STUDENT NAVIGATION (new chip design) --}}
                    @elseif(auth()->user()->hasRole('Student'))
                        <x-nav-link :href="route('student.dashboard')"
                                    :active="request()->routeIs('student.dashboard')"
                                    class="student-pill {{ request()->routeIs('student.dashboard') ? 'student-pill-active' : '' }}">
                            {{ __('Home') }}
                        </x-nav-link>

                        <x-nav-link :href="route('student.announcements')"
                                    :active="request()->routeIs('student.announcements')"
                                    class="student-pill {{ request()->routeIs('student.announcements') ? 'student-pill-active' : '' }}">
                            {{ __('Announcements') }}
                        </x-nav-link>

                        <x-nav-link :href="route('student.scholarships')"
                                    :active="request()->routeIs('student.scholarships')"
                                    class="student-pill {{ request()->routeIs('student.scholarships') ? 'student-pill-active' : '' }}">
                            {{ __('Scholarships') }}
                        </x-nav-link>

                        @if(\App\Models\Scholar::where('student_id', auth()->id())->exists())
                            <x-nav-link :href="route('student.stipend-history')"
                                        :active="request()->routeIs('student.stipend-history')"
                                        class="student-pill {{ request()->routeIs('student.stipend-history') ? 'student-pill-active' : '' }}">
                                {{ __('Stipends') }}
                            </x-nav-link>
                        @endif

                        <x-nav-link :href="route('student.notifications')"
                                    :active="request()->routeIs('student.notifications')"
                                    class="student-pill {{ request()->routeIs('student.notifications') ? 'student-pill-active' : '' }}">
                            {{ __('Notifications') }}
                        </x-nav-link>

                        <x-nav-link :href="route('questions.create')"
                                    :active="request()->routeIs('questions.create')"
                                    class="student-pill {{ request()->routeIs('questions.create') ? 'student-pill-active' : '' }}">
                            {{ __('Ask') }}
                        </x-nav-link>

                        <x-nav-link :href="route('questions.my')"
                                    :active="request()->routeIs('questions.my')"
                                    class="student-pill {{ request()->routeIs('questions.my') ? 'student-pill-active' : '' }}">
                            {{ __('My Questions') }}
                        </x-nav-link>
                    @endif

                @endauth
            </div>
        </div>

        {{-- RIGHT: User Dropdown --}}
        <div class="flex items-center justify-end min-w-[200px]">
            @auth
                <div class="relative">
                    <button class="flex items-center text-sm nav-text focus:outline-none"
                            id="user-menu-button"
                            aria-expanded="false">
                        {{ auth()->user()->firstname }}
                        <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div class="hidden absolute left-1/2 -translate-x-1/2 mt-2 dropdown-bg rounded-md shadow-lg z-50 px-1 py-1"
                        id="user-dropdown"
                        style="width: 180px;">

                        <a href="{{ route('profile') }}" class="dropdown-square">
                            Profile
                        </a>

                        @if(auth()->user()->hasRole('Super Admin'))
                            <a href="{{ route('settings.index') }}" class="dropdown-square">
                                Settings
                            </a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-square w-full">
                                Logout
                            </button>
                        </form>
                    </div>


                </div>
            @endauth
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

