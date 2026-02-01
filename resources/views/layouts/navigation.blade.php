{{-- resources/views/layouts/navigation.blade.php (UPDATED) --}}

<!-- ✅ SCHOOL-STYLE NAV (flat, simple, professional) -->
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

    /* NAVBAR */
    .nav-bg{
        background: #f8f9fa;
        border-bottom: 1px solid var(--stroke);
        position: sticky;
        top: 0;
        z-index: 1030;
        backdrop-filter: none;
    }

    /* LEFT BRAND: keep it "just enough" even with long title */
    .brand-wrap{
        display:flex;
        align-items:center;
        gap:.6rem;
        min-width: 0;
        max-width: 340px; /* ✅ adjust if needed (280-380) */
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
        max-width: 255px;  /* ✅ truncates long system title */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .brand-sub{
        font-size:.78rem;
        color:var(--muted);
        line-height:1.1;
    }

    /* CENTER TOP NAV LINKS (flat, school-like) */
    .topnav{
        display:flex;
        align-items:center;
        gap:0; /* school style: no "pill gap" */
        flex-wrap: nowrap;
    }

    /* Works for <a> and <button> */
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
    .top-link:hover{
        background: #e9ecef;
    }
    .top-link-active{
        background: #e9ecef;
        border-bottom: 3px solid var(--brand);
    }

    /* ✅ Semester filter: active WITHOUT underline */
    .top-link-sem-active{
        background: transparent !important;
        border-bottom: 0 !important;
        font-weight: 800;
    }

    /* also remove hover gray only for the semester filter */
    #semesterModalOpenBtn:hover{
        background: transparent !important;
    }


    .top-link svg{
        margin-left: .35rem;
        width: 16px;
        height: 16px;
        opacity: .85;
    }

    /* Dropdown: flat panel */
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
    .dropdown-square:hover{
        background: #f1f3f5;
    }
    .dropdown-square-active{
        background: #e7f1ff;
        border-left: 4px solid var(--brand);
    }

    /* Right profile button (same style as top-link) */
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
    .user-btn:hover{
        background: #e9ecef;
    }

    /* separator line */
    .divider-line{
        border: 0;
        border-top: 1px solid var(--stroke);
        margin: 0;
    }

    /* ✅ Modal results style (kept from your original) */
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

    /* Prevent clipping */
    nav, .max-w-7xl{
        overflow: visible !important;
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
    <div class="max-w-7xl mx-auto flex items-center justify-between px-4">

        {{-- LEFT: Logo + System Name --}}
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

        {{-- CENTER: Navigation Links --}}
        <div class="flex-1 flex justify-center">
            <div class="topnav">

                {{-- ✅ SEMESTER FILTER (Super Admin / Coordinator only) --}}
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

                        <div class="relative">
                            <button type="button" id="users-menu-button"
                                    class="top-link {{ $usersGroupActive ? 'top-link-active' : '' }}">
                                Users &amp; Roles
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="users-menu" class="hidden absolute left-0 mt-0 w-64 dropdown-bg">
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

                        <div class="relative">
                            <button type="button" id="academic-menu-button"
                                    class="top-link {{ $academicGroupActive ? 'top-link-active' : '' }}">
                                Academic Structure
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="academic-menu" class="hidden absolute left-0 mt-0 w-64 dropdown-bg">
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

                        <div class="relative">
                            <button type="button" id="enrollment-menu-button"
                                    class="top-link {{ $enrollmentGroupActive ? 'top-link-active' : '' }}">
                                Enrollment
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="enrollment-menu" class="hidden absolute left-0 mt-0 w-64 dropdown-bg">
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

                        <div class="relative">
                            <button type="button" id="coord-scholars-menu-button"
                                    class="top-link {{ $coordScholarsGroupActive ? 'top-link-active' : '' }}">
                                Student Services
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="coord-scholars-menu" class="hidden absolute left-0 mt-0 w-72 dropdown-bg">
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
                                <a href="{{ route('coordinator.scholarship-batches') }}"
                                   class="dropdown-square {{ (request()->routeIs('coordinator.scholarship-batches') || request()->routeIs('coordinator.scholarship-batches.*')) ? 'dropdown-square-active' : '' }}">
                                    Scholarship Batches
                                </a>
                            </div>
                        </div>

                        <div class="relative">
                            <button type="button" id="coord-stipends-menu-button"
                                    class="top-link {{ $coordStipendsGroupActive ? 'top-link-active' : '' }}">
                                Stipends
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="coord-stipends-menu" class="hidden absolute left-0 mt-0 w-72 dropdown-bg">
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

                        <div class="relative">
                            <button type="button" id="coord-announcements-menu-button"
                                    class="top-link {{ $coordAnnouncementsGroupActive ? 'top-link-active' : '' }}">
                                Announcements
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="coord-announcements-menu" class="hidden absolute left-0 mt-0 w-72 dropdown-bg">
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

                    {{-- STUDENT (unchanged look, but still fine) --}}
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

        {{-- RIGHT: User Dropdown --}}
        <div class="flex items-center justify-end" style="min-width: 190px;">
            @auth
                <div class="relative">
                    <button class="user-btn" id="user-menu-button" aria-expanded="false">
                        {{ auth()->user()->firstname }}
                        <svg class="ml-1" style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    <div class="hidden absolute left-1/2 -translate-x-1/2 mt-0 dropdown-bg z-50"
                         id="user-dropdown" style="width: 200px;">
                        <a href="{{ route('profile') }}" class="dropdown-square">Profile</a>

                        @if(auth()->user()->hasRole('Super Admin'))
                            <a href="{{ route('settings.index') }}" class="dropdown-square">Settings</a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-square w-full text-left">Logout</button>
                        </form>
                    </div>

                </div>
            @endauth
        </div>

    </div>
</nav>

<hr class="divider-line">

{{-- ✅ Semester Filter Modal (kept) --}}
@auth
@if(auth()->user()->hasRole('Scholarship Coordinator') || auth()->user()->hasRole('Super Admin'))
<div id="semesterModalOverlay"
     class="hidden fixed inset-0 z-50 items-center justify-center"
     style="background: rgba(0,0,0,.35); backdrop-filter: blur(2px);">
    <div class="bg-white rounded-lg shadow-lg p-3"
        style="
            width: 280px;
            max-width: calc(100vw - 24px);
            border: 1px solid #e7edf6;
            box-shadow: 0 10px 28px rgba(11,46,94,.08);
        ">

        <div class="flex items-center justify-between mb-3">
            <div>
                <div class="font-extrabold" style="color:var(--text)">Filter by Semester</div>
                <div class="text-xs" style="color:var(--muted)">Type to search. Click a result to apply.</div>
            </div>
            <button type="button" id="semesterModalCloseBtn" class="user-btn" style="padding:0 .55rem;">✕</button>
        </div>

        <input type="text" id="semesterSearchInput"
            class="w-full border rounded-lg px-3 py-1.5"
            style="border:1px solid #e7edf6; outline:none;"
            placeholder="Type semester..."
            autocomplete="off">

        <div class="mt-3 flex items-center justify-between">
            <form method="POST" action="{{ route('semester.filter.clear') }}">
                @csrf
                <button type="submit" class="top-link" style="height:38px;">
                    All Semesters
                </button>
            </form>
            <div id="semesterSearchStatus" class="text-xs" style="color:var(--muted)"></div>
        </div>

        <div id="semesterSearchResults" class="mt-3 hidden"
             style="border:1px solid #e7edf6; border-radius:12px; overflow:hidden;"></div>

        <form id="semesterSetForm" method="POST" action="{{ route('semester.filter.set') }}" class="hidden">
            @csrf
            <input type="hidden" name="semester_id" id="semesterSelectedId">
        </form>
    </div>
</div>
@endif
@endauth

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ✅ Dropdown system
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

    // Close dropdowns when clicking any nav link / dropdown item
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
            const url = `{{ route('semester.filter.search') }}?q=${encodeURIComponent(q)}`;
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
