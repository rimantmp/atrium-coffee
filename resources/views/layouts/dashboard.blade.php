<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} · Atrium Coffee</title>
    <meta name="theme-color" content="#8b563e">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50">
    <a href="#konten-utama" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-xl focus:bg-coffee-600 focus:px-4 focus:py-3 focus:text-sm focus:font-semibold focus:text-white">Lewati ke konten utama</a>
    @include('layouts.partials.sidebar')
    <div class="flex min-h-screen flex-col lg:pl-64">
        @include('layouts.partials.navbar')
        <main id="konten-utama" class="flex-1 p-4 sm:p-6 lg:p-7">
            <div class="mx-auto max-w-[1600px]">
                @include('layouts.partials.alerts')
                @yield('content')
            </div>
        </main>
        @include('layouts.partials.footer')
    </div>
</body>
</html>
