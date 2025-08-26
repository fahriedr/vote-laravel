<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        {{-- Toast for Errors --}}
        @if ($errors->any())
        <x-toast type="error">
            {{ $errors->first() }}
        </x-toast>
        @endif

        {{-- Toast for Session Error Messages --}}
        @if (session('error'))
        <x-toast type="error">
            {{ session('error') }}
        </x-toast>
        @endif

        {{-- Toast for Success --}}
        @if (session('success'))
        <x-toast type="success">
            {{ session('success') }}
        </x-toast>
        @endif
    </body>
</html>
