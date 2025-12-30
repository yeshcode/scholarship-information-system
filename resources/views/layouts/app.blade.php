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
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="py-6">
            <div class="{{ isset($fullWidth) ? 'w-full px-4' : 'max-w-7xl mx-auto sm:px-6 lg:px-8' }}">  {{-- Changed px-0 to px-4 for margins --}}
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