<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DMS Meksiko') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-gray-900">
    
   <div x-data="{ sidebarOpen: false, sidebarMinimized: false }" class="relative flex h-screen overflow-hidden bg-gray-50">
    
    @include('layouts.sidebar')

    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
        @include('layouts.navigation')

        <main class="flex-1 overflow-y-auto focus:outline-none">
            <div class="py-6 px-4 sm:px-6 lg:px-8">
                
                {{ $slot }}
            </div>
        </main>
    </div>
</div>

    @livewireScripts
    
</body>
</html>