<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Atrium Coffee' }}</title>
    <meta name="theme-color" content="#7367f0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">
        <div class="mx-auto flex h-18 max-w-6xl items-center justify-between px-4 sm:px-6">
            <a href="{{ route('menu') }}" class="flex items-center gap-2.5"><span class="grid size-9 place-items-center rounded-xl bg-coffee-500 font-bold text-white">A</span><span class="text-base font-bold tracking-wide text-slate-800">ATRIUM <span class="font-medium text-coffee-500">COFFEE</span></span></a>
            <a href="{{ route('login') }}" class="ui-button-secondary min-h-10 px-3.5">Login Staf</a>
        </div>
    </header>
    <main class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:py-10">
        @include('layouts.partials.alerts')
        @yield('content')
    </main>
    <div class="mx-auto max-w-6xl px-4 sm:px-6">@include('layouts.partials.footer')</div>
</body>
</html>
