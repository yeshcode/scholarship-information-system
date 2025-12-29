<!-- Navigation Links: Shows "Dashboard" link based on role -->
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
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

        @elseif(auth()->user()->hasRole('Scholarship Coordinator'))
            <x-nav-link :href="route('coordinator.dashboard')" :active="request()->routeIs('coordinator.dashboard')">
                {{ __('Dashboard') }}
            </x-nav-link>
            <!-- Future: Add more Coordinator links here -->
        @elseif(auth()->user()->hasRole('Student'))
            <x-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')">
                {{ __('Dashboard') }}
            </x-nav-link>
            <!-- Future: Add more Student links here -->
        @endif
    @endauth
</div>