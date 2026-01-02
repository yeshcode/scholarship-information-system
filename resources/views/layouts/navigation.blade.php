<!-- Fetch system settings for logo and name -->
@php
    $settings = \App\Models\SystemSetting::first();
@endphp

<!-- Upper Part: Logo and System Title (Top Row) -->

<div class="flex items-center justify-center space-x-2 py-4 bg-white shadow-sm">  <!-- Reduced space-x-4 to space-x-2 for smaller gap -->
    @if($settings && $settings->logo_path && file_exists(public_path('storage/' . $settings->logo_path)))
        <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo" class="h-8 w-8 object-contain" onerror="this.style.display='none';">  <!-- Changed to h-6 w-6 (24px) for smaller size -->
    @else
        <span class="text-gray-500">[No Logo]</span> <!-- Fallback if logo missing -->
    @endif
    <span class="text-lg font-bold">{{ $settings->system_name ?? 'Scholarship Information Management System' }}</span>
</div>

<!-- Below: Navigation Links Row (with Dropdown on the Right) -->
<div class="flex items-center justify-between bg-gray-100 px-4 py-2">
    <!-- Navigation Links: Shows based on role -->
    <div class="flex space-x-8">
        @auth
            @if(auth()->user()->hasRole('Super Admin'))
                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard') && !request('page')">
                    {{ __('SMIS') }}
                </x-nav-link>
                <!-- Super Admin Section Links -->
                <x-nav-link :href="route('admin.dashboard', ['page' => 'manage-users'])" :active="request('page') === 'manage-users'">
                    {{ __('Manage Users') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.dashboard', ['page' => 'enrollments'])" :active="request('page') === 'enrollments'">
                    {{ __('Enrollment') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.dashboard', ['page' => 'semesters'])" :active="request('page') === 'semesters'">
                    {{ __('Semester') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.dashboard', ['page' => 'colleges'])" :active="request('page') === 'colleges'">
                    {{ __('College') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.dashboard', ['page' => 'courses'])" :active="request('page') === 'courses'">
                    {{ __('Course') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.dashboard', ['page' => 'year-levels'])" :active="request('page') === 'year-levels'">
                    {{ __('Year Level') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.dashboard', ['page' => 'sections'])" :active="request('page') === 'sections'">
                    {{ __('Sections') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.dashboard', ['page' => 'user-type'])" :active="request('page') === 'user-type'">
                    {{ __('User Type') }}
                </x-nav-link>

                <!-- Scholarship Coordinator Section Links -->

                @elseif(auth()->user()->hasRole('Scholarship Coordinator'))
                <x-nav-link :href="route('coordinator.dashboard')" :active="request()->routeIs('coordinator.dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>
                <x-nav-link :href="route('coordinator.manage-scholars')" :active="request()->routeIs('coordinator.manage-scholars') || request()->routeIs('coordinator.scholars.*')">
                    {{ __('Manage Scholars') }}
                </x-nav-link>
                <x-nav-link :href="route('coordinator.enrolled-users')" :active="request()->routeIs('coordinator.enrolled-users')">
                    {{ __('Enrolled Users') }}
                </x-nav-link>
                <x-nav-link :href="route('coordinator.manage-scholarships')" :active="request()->routeIs('coordinator.manage-scholarships') || request()->routeIs('coordinator.scholarships.*')">
                    {{ __('Manage Scholarships') }}
                </x-nav-link>
                <x-nav-link :href="route('coordinator.scholarship-batches')" :active="request()->routeIs('coordinator.scholarship-batches') || request()->routeIs('coordinator.scholarship-batches.*')">
                    {{ __('Scholarship Batches') }}
                </x-nav-link>
                <x-nav-link :href="route('coordinator.manage-stipends')" :active="request()->routeIs('coordinator.manage-stipends') || request()->routeIs('coordinator.stipends.*')">
                    {{ __('Manage Stipends') }}
                </x-nav-link>
                <x-nav-link :href="route('coordinator.manage-stipend-releases')" :active="request()->routeIs('coordinator.manage-stipend-releases') || request()->routeIs('coordinator.stipend-releases.*')">
                    {{ __('Stipend Releases') }}
                </x-nav-link>
                <x-nav-link :href="route('coordinator.manage-announcements')" :active="request()->routeIs('coordinator.manage-announcements') || request()->routeIs('coordinator.announcements.*')">
                    {{ __('Manage Announcements') }}
                </x-nav-link>


                <!-- Student Section Links -->
            @elseif(auth()->user()->hasRole('Student'))
                <x-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')">
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
                <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown">
                    {{ auth()->user()->firstname }}
                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <!-- Dropdown Menu -->
                <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" id="user-dropdown">
                    <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                    @if(auth()->user()->hasRole('Super Admin'))
                        <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</div>

<!-- Add JavaScript for Dropdown Toggle -->
<script>
    document.getElementById('user-menu-button').addEventListener('click', function() {
        const dropdown = document.getElementById('user-dropdown');
        dropdown.classList.toggle('hidden');
    });
</script>