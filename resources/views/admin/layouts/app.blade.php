<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <title>{{ isset($title) ? $title . ' - Admin - ' . config('app.name', 'ToDo') : 'Admin - ' . config('app.name', 'ToDo') }}</title>

        <!-- Prevent dark mode flash -->
        <script>if(localStorage.getItem('darkMode')==='true')document.documentElement.classList.add('dark');</script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        <div class="min-h-screen">
            @include('partials.maintenance-banner')
            @include('admin.layouts.navigation')

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('scripts')
    </body>
</html>
