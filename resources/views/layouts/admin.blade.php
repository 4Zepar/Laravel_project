<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel | {{ config('app.name') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-800 antialiased text-slate-200">
    <div id="modal-container"></div>
    
    <livewire:header />

    <div class="min-h-screen py-5">
        <main class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900/50 border border-slate-800 backdrop-blur-md rounded-3xl p-8 shadow-2xl">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>