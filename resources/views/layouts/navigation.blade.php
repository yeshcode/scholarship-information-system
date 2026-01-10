<!-- Custom Styles for Blue Theme -->
<style>
    /* Light blue background for header and nav */
    .nav-bg {
        background-color: #f0f4f8;
    }
    /* Dark blue text for titles and links */
    .nav-text {
        color: #003366;
        font-weight: bold;
    }
    /* Hover effect for links */
    .nav-link:hover {
        color: #0056b3;
    }
    /* Dropdown background */
    .dropdown-bg {
        background-color: #ffffff;
        border: 1px solid #007bff;
    }
    /* Dropdown hover */
    .dropdown-item:hover {
        background-color: #f0f4f8;
    }
    /* Logo border */
    .logo-border {
        border: 2px solid #007bff;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    /* Bold horizontal divider line */
    .divider-line {
        border: 2px solid #003366; /* Bold blue line */
        margin: 0; /* No extra margin */
    }
</style>

<!-- Fetch system settings for logo and name -->
@php
    $settings = \App\Models\SystemSetting::first();
@endphp

<!-- Upper Part: Logo and System Title (Top Row) -->
<div class="flex items-center justify-center space-x-2 py-4 nav-bg shadow-sm">
    @if($settings && $settings->logo_path && file_exists(public_path('storage/' . $settings->logo_path)))
        <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo" class="h-8 w-8 object-contain logo-border">
    @else
        <span class="nav-text">[No Logo]</span>
    @endif
    <span class="text-lg nav-text">{{ $settings->system_name ?? 'Scholarship Information Management System' }}</span>
</div>

<!-- Bold Horizontal Divider Line Between Header and Navigation -->
<hr class="divider-line">

<!-- Below: Navigation Links Row (with Dropdown on the Right) -->
<div class="flex items-center justify-between nav-bg px-4 py-2 shadow-sm">
    <!-- Navigation Links: Centered Horizontally -->
    <div class="flex justify-center flex-1 space-x-8">  <!-- Centered with flex-1 for balance -->
        @auth
            @if(auth()->user()->hasRole('Super Admin'))
                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard') && !request('page')" class="nav-link nav-text">
                    {{ __('SMIS') }}
                </x-nav-link>
                <!-- Super Admin Section Links -->
                <x-nav-link :href="route('admin.dashboard', ['page' => 'manage-users'])" :active="request('page') === 'manage-users'" class="nav-link nav-text">
                    {{ __('Manage Users') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.dashboard', ['page' => 'enrollments'])" :active="request('page') === 'enrollments'" class="nav-link nav-text">
                    {{ __('Enrollment') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.dashboard', ['page' => 'semesters'])" :active="request('page') === 'semesters'" class="nav-link nav-text">
                    {{ __('Semester') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.dashboard', ['page' => 'colleges'])" :active="request('page') === 'colleges'" class="nav-link nav-text">
                    {{ __('College') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.dashboard', ['page' => 'courses'])" :active="request('page') === 'courses'" class="nav-link nav-text">
                    {{ __('Course') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.dashboard', ['page' => 'year-levels'])" :active="request('page') === 'year-levels'" class="nav-link nav-text">
                    {{ __('Year Level') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.dashboard', ['page' => 'sections'])" :active="request('page') === 'sections'" class="nav-link nav-text">
                    {{ __('Sections') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.dashboard', ['page' => 'user-type'])" :active="request('page') === 'user-type'" class="nav-link nav-text">
                    {{ __('User Type') }}
                </x-nav-link>

                <!-- Scholarship Coordinator Section Links -->
            @elseif(auth()->user()->hasRole('Scholarship Coordinator'))
                <x-nav-link :href="route('coordinator.dashboard')" :active="request()->routeIs('coordinator.dashboard')" class="nav-link nav-text">
                    {{ __('Dashboard') }}
                </x-nav-link>
                <x-nav-link :href="route('coordinator.manage-scholars')" :active="request()->routeIs('coordinator.manage-scholars') || request()->routeIs('coordinator.scholars.*')" class="nav-link nav-text">
                    {{ __('Manage Scholars') }}
                </x-nav-link>
                <x-nav-link :href="route('coordinator.enrolled-users')" :active="request()->routeIs('coordinator.enrolled-users')" class="nav-link nav-text">
                    {{ __('Enrolled Users') }}
                </x-nav-link>
                <x-nav-link :href="route('coordinator.manage-scholarships')" :active="request()->routeIs('coordinator.manage-scholarships') || request()->routeIs('coordinator.scholarships.*')" class="nav-link nav-text">
                    {{ __('Manage Scholarships') }}
                </x-nav-link>
                <x-nav-link :href="route('coordinator.scholarship-batches')" :active="request()->routeIs('coordinator.scholarship-batches') || request()->routeIs('coordinator.scholarship-batches.*')" class="nav-link nav-text">
                    {{ __('Scholarship Batches') }}
                </x-nav-link>
                <x-nav-link :href="route('coordinator.manage-stipends')" :active="request()->routeIs('coordinator.manage-stipends') || request()->routeIs('coordinator.stipends.*')" class="nav-link nav-text">
                    {{ __('Manage Stipends') }}
                </x-nav-link>
                <x-nav-link :href="route('coordinator.manage-stipend-releases')" :active="request()->routeIs('coordinator.manage-stipend-releases') || request()->routeIs('coordinator.stipend-releases.*')" class="nav-link nav-text">
                    {{ __('Stipend Releases') }}
                </x-nav-link>
                <x-nav-link :href="route('coordinator.manage-announcements')" :active="request()->routeIs('coordinator.manage-announcements') || request()->routeIs('coordinator.announcements.*')" class="nav-link nav-text">
                    {{ __('Manage Announcements') }}
                </x-nav-link>

                <!-- Student Section Links -->
            @elseif(auth()->user()->hasRole('Student'))
                <x-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')" class="nav-link nav-text">
                    {{ __('Dashboard') }}
                </x-nav-link>
                <!-- Future: Add more Student links here -->
            @endif
        @endauth
    </div>

    <!-- Right Side of Links Row: User Dropdown -->
    <div class="flex items-center">
        @auth
            <div class="relative">
                <button class="flex items-center text-sm nav-text focus:outline-none" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown">
                    {{ auth()->user()->firstname }}
                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <!-- Dropdown Menu -->
                <div class="hidden absolute right-0 mt-2 w-48 dropdown-bg rounded-md shadow-lg py-1 z-50" id="user-dropdown">
                    <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm nav-text dropdown-item">Profile</a>
                    @if(auth()->user()->hasRole('Super Admin'))
                        <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm nav-text dropdown-item">Settings</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm nav-text dropdown-item">Logout</button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</div>

<!-- Bold Horizontal Divider Line Between Navigation and Content -->
<hr class="divider-line">

<!-- Add JavaScript for Dropdown Toggle -->
<script>
    document.getElementById('user-menu-button').addEventListener('click', function() {
        const dropdown = document.getElementById('user-dropdown');
        dropdown.classList.toggle('hidden');
    });
</script>