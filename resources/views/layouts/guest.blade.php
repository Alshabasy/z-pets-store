<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Z-Pets Store') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-brand-green-dark font-bold text-2xl mb-2">
                    <span>🐾</span> Z-Pets Store
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-md overflow-hidden sm:rounded-xl border border-gray-100">
                {{ $slot }}
            </div>

            <p class="mt-4 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-brand-green transition-colors">← Back to Store</a>
            </p>
        </div>
    </body>
</html>
