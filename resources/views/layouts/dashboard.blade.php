<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} — Atrium Coffee</title>
    <meta name="theme-color" content="#7367f0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50">
    @include('layouts.partials.sidebar')
    <div class="flex min-h-screen flex-col lg:pl-72">
        @include('layouts.partials.navbar')
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="mx-auto max-w-[1600px]">
                @include('layouts.partials.alerts')
                @yield('content')
            </div>
        </main>
        @include('layouts.partials.footer')
    </div>
</body>
</html>
