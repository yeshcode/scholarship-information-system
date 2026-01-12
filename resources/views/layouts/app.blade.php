<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Scholarship Management Information System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#f0f4f8]">  {{-- Changed to light blue background for consistency --}}
    <div class="min-h-screen bg-[#f0f4f8]">  {{-- Changed to light blue background --}}
        @include('layouts.navigation')

        <!-- Page Heading (Now with Blue Background) -->
        @isset($header)
            <header class="bg-[#003366] shadow border-b border-[#007bff]">  {{-- Dark blue header with subtle border --}}
                <div class="max-w-7xl mx-auto py-6 px-6 sm:px-6 lg:px-8">  {{-- Added padding for margins --}}
                    <div class="text-white">  {{-- White text for contrast on dark blue --}}
                        {{ $header }}
                    </div>
                </div>
            </header>
        @endisset

        <!-- Page Content (Wider with Margins) -->
        <main class="py-6">
            <div class="{{ isset($fullWidth) ? 'w-full px-4' : 'mx-auto px-3 sm:px-6 lg:px-8' }}">  {{-- Adjusted for wider fit with margins: fullWidth keeps px-4, others use px-6 for balanced spacing --}}
                <div class="{{ isset($fullWidth) ? 'bg-white overflow-hidden shadow-sm sm:rounded-lg' : 'bg-white overflow-hidden shadow-sm sm:rounded-lg p-6' }}">
                    @isset($slot)
                        {{ $slot }}
                    @endisset
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</body>
</html>