<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Atrium Coffee' }}</title>
    <meta name="theme-color" content="#8b563e">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-50">
    <a href="#konten-utama" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-xl focus:bg-coffee-600 focus:px-4 focus:py-3 focus:text-sm focus:font-semibold focus:text-white">Lewati ke konten utama</a>
    <header class="sticky top-0 z-30 border-b border-stone-200 bg-white/95 backdrop-blur-sm">
        <div class="mx-auto flex min-h-16 max-w-[1440px] items-center gap-5 px-4 py-2 sm:px-6 xl:px-8">
            <a href="{{ route('menu') }}" class="flex shrink-0 items-center gap-2.5">
                <span class="grid size-10 place-items-center rounded-xl bg-coffee-600 text-lg font-bold text-white">A</span>
                <span class="text-base font-extrabold tracking-wide text-stone-900">ATRIUM <span class="font-semibold text-coffee-600">COFFEE</span></span>
            </a>
            <div class="flex flex-1 items-center justify-end gap-8 md:justify-center">@yield('header-tools')</div>
            <x-button :href="route('login')" variant="secondary">Login Staf</x-button>
        </div>
    </header>
    <main id="konten-utama" class="mx-auto max-w-[1440px] px-4 py-4 sm:px-6 xl:px-8">
        @include('layouts.partials.alerts')
        @yield('content')
    </main>
    @yield('mobile-cart-bar')
    <div class="mx-auto max-w-[1440px] px-4 sm:px-6 xl:px-8">@include('layouts.partials.footer')</div>
</body>
</html>
