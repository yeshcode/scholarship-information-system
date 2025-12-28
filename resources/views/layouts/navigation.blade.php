<!-- This is the navigation bar for your app. It shows role-based links after login. -->
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo: Links to the correct dashboard based on user role -->
                <div class="shrink-0 flex items-center">
                    @auth
                        @if(auth()->user()->hasRole('Super Admin'))
                            <a href="{{ route('admin.dashboard') }}">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        @elseif(auth()->user()->hasRole('Scholarship Coordinator'))
                            <a href="{{ route('coordinator.dashboard') }}">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        @elseif(auth()->user()->hasRole('Student'))
                            <a href="{{ route('student.dashboard') }}">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        @else
                            <a href="{{ url('/') }}">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        @endif
                    @else
                        <a href="{{ url('/') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        </a>
                    @endauth
                </div>

                <!-- Navigation Links: Shows "Dashboard" link based on role -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        @if(auth()->user()->hasRole('Super Admin'))
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <!-- Future: Add more Super Admin links here, e.g., <x-nav-link href="/admin/users">Users</x-nav-link> -->
                        @elseif(auth()->user()->hasRole('Scholarship Coordinator'))
                            <x-nav-link :href="route('coordinator.dashboard')" :active="request()->routeIs('coordinator.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <!-- Future: Add more Coordinator links here, e.g., <x-nav-link href="/coordinator/applications">Applications</x-nav-link> -->
                        @elseif(auth()->user()->hasRole('Student'))
                            <x-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <!-- Future: Add more Student links here, e.g., <x-nav-link href="/student/scholarships">Scholarships</x-nav-link> -->
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Logout link -->
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @if(auth()->user()->hasRole('Super Admin'))
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <!-- Future: Add more Super Admin links here for mobile -->
                @elseif(auth()->user()->hasRole('Scholarship Coordinator'))
                    <x-responsive-nav-link :href="route('coordinator.dashboard')" :active="request()->routeIs('coordinator.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <!-- Future: Add more Coordinator links here for mobile -->
                @elseif(auth()->user()->hasRole('Student'))
                    <x-responsive-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <!-- Future: Add more Student links here for mobile -->
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>

                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</nav>