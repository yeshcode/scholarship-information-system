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
        display: block;
        padding: 0.35rem 0.75rem;
        margin: 0.2rem 0.5rem;
        border-radius: 0.375rem;
        border: 1px solid #003366;
        background-color: #ffffff;
        font-size: 0.85rem;
        color: #003366;
        text-align: left;
    }
    .dropdown-square:hover {
        background-color: #e2e8f0;
    }
    .dropdown-square-active {
        background-color: #003366;
        color: #ffffff;
    }
</style>

@php
    $settings = \App\Models\SystemSetting::first();
    $page = request('page');

    $usersGroupActive = in_array($page, ['manage-users', 'user-type']);
    $academicGroupActive = in_array($page, ['colleges', 'courses', 'year-levels', 'sections', 'semesters']);
    $enrollmentGroupActive = $page === 'enrollments';
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
            <div class="flex space-x-4 items-center">
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
                                <a href="{{ route('admin.dashboard', ['page' => 'sections']) }}"
                                   class="dropdown-square {{ $page === 'sections' ? 'dropdown-square-active' : '' }}">
                                    Sections
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
                        <x-nav-link :href="route('coordinator.dashboard')"
                                    :active="request()->routeIs('coordinator.dashboard')"
                                    class="nav-pill nav-text">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('coordinator.manage-scholars')"
                                    :active="request()->routeIs('coordinator.manage-scholars') || request()->routeIs('coordinator.scholars.*')"
                                    class="nav-pill nav-text">
                            {{ __('Manage Scholars') }}
                        </x-nav-link>
                        <x-nav-link :href="route('coordinator.enrolled-users')"
                                    :active="request()->routeIs('coordinator.enrolled-users')"
                                    class="nav-pill nav-text">
                            {{ __('Enrolled Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('coordinator.manage-scholarships')"
                                    :active="request()->routeIs('coordinator.manage-scholarships') || request()->routeIs('coordinator.scholarships.*')"
                                    class="nav-pill nav-text">
                            {{ __('Manage Scholarships') }}
                        </x-nav-link>
                        <x-nav-link :href="route('coordinator.scholarship-batches')"
                                    :active="request()->routeIs('coordinator.scholarship-batches') || request()->routeIs('coordinator.scholarship-batches.*')"
                                    class="nav-pill nav-text">
                            {{ __('Scholarship Batches') }}
                        </x-nav-link>
                        <x-nav-link :href="route('coordinator.manage-stipends')"
                                    :active="request()->routeIs('coordinator.manage-stipends') || request()->routeIs('coordinator.stipends.*')"
                                    class="nav-pill nav-text">
                            {{ __('Manage Stipends') }}
                        </x-nav-link>
                        <x-nav-link :href="route('coordinator.manage-stipend-releases')"
                                    :active="request()->routeIs('coordinator.manage-stipend-releases') || request()->routeIs('coordinator.stipend-releases.*')"
                                    class="nav-pill nav-text">
                            {{ __('Stipend Releases') }}
                        </x-nav-link>
                        <x-nav-link :href="route('coordinator.manage-announcements')"
                                    :active="request()->routeIs('coordinator.manage-announcements') || request()->routeIs('coordinator.announcements.*')"
                                    class="nav-pill nav-text">
                            {{ __('Manage Announcements') }}
                        </x-nav-link>
                        {{-- ⭐ NEW: Student Queries (clustered questions) --}}
                        <x-nav-link :href="route('clusters.index')"
                                    :active="request()->routeIs('clusters.*')"
                                    class="nav-pill nav-text">
                            {{ __('Student Queries') }}
                        </x-nav-link>

                    {{-- STUDENT NAVIGATION (unchanged) --}}
                    @elseif(auth()->user()->hasRole('Student'))
                        <x-nav-link :href="route('student.dashboard')"
                                    :active="request()->routeIs('student.dashboard')"
                                    class="nav-pill nav-text">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.announcements')"
                                    :active="request()->routeIs('student.announcements')"
                                    class="nav-pill nav-text">
                            {{ __('Announcements') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.scholarships')"
                                    :active="request()->routeIs('student.scholarships')"
                                    class="nav-pill nav-text">
                            {{ __('Scholarships') }}
                        </x-nav-link>
                        @if(\App\Models\Scholar::where('student_id', auth()->id())->exists())
                            <x-nav-link :href="route('student.stipend-history')"
                                        :active="request()->routeIs('student.stipend-history')"
                                        class="nav-pill nav-text">
                                {{ __('Stipend History') }}
                            </x-nav-link>
                        @endif
                        <x-nav-link :href="route('student.notifications')"
                                    :active="request()->routeIs('student.notifications')"
                                    class="nav-pill nav-text">
                            {{ __('Notifications') }}
                        </x-nav-link>
                         {{-- ⭐ NEW: Ask Question --}}
                        <x-nav-link :href="route('questions.create')"
                                    :active="request()->routeIs('questions.create')"
                                    class="nav-pill nav-text">
                            {{ __('Ask Question') }}
                        </x-nav-link>
                        {{-- ⭐ NEW: My Questions --}}
                        <x-nav-link :href="route('questions.my')"
                                    :active="request()->routeIs('questions.my')"
                                    class="nav-pill nav-text">
                            {{ __('My Questions') }}
                        </x-nav-link>
                    @endif
                @endauth
            </div>
        </div>

        {{-- RIGHT: User Dropdown --}}
        <div class="flex items-center">
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
                    <div class="hidden absolute right-0 mt-2 w-48 dropdown-bg rounded-md shadow-lg py-1 z-50"
                         id="user-dropdown">
                        <a href="{{ route('profile') }}"
                           class="block px-4 py-2 text-sm nav-text dropdown-item">Profile</a>
                        @if(auth()->user()->hasRole('Super Admin'))
                            <a href="{{ route('settings.index') }}"
                               class="block px-4 py-2 text-sm nav-text dropdown-item">Settings</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm nav-text dropdown-item">
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
        function setupToggle(buttonId, menuId) {
            const btn = document.getElementById(buttonId);
            const menu = document.getElementById(menuId);
            if (!btn || !menu) return;

            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            });

            // close when clicking outside
            document.addEventListener('click', function (e) {
                if (!menu.contains(e.target) && !btn.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        }

        setupToggle('users-menu-button', 'users-menu');
        setupToggle('academic-menu-button', 'academic-menu');
        setupToggle('enrollment-menu-button', 'enrollment-menu');

        const userBtn = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-dropdown');
        if (userBtn && userMenu) {
            userBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                userMenu.classList.toggle('hidden');
            });
            document.addEventListener('click', function (e) {
                if (!userMenu.contains(e.target) && !userBtn.contains(e.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        }
    });
</script>
