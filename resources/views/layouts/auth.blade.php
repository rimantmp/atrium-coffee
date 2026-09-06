<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#7367f0">
    <title>{{ $title ?? 'Autentikasi' }} — Atrium Coffee</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    <main class="grid min-h-screen lg:grid-cols-2">
        <section class="relative hidden overflow-hidden bg-coffee-900 p-12 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="absolute -right-24 -top-24 size-96 rounded-full bg-coffee-500/30 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-20 size-96 rounded-full bg-indigo-400/20 blur-3xl"></div>
            <a href="{{ route('menu') }}" class="relative flex items-center gap-3">
                <span class="grid size-12 place-items-center rounded-2xl bg-white text-xl font-bold text-coffee-600">A</span>
                <span class="text-lg font-bold tracking-wider">ATRIUM COFFEE</span>
            </a>
            <div class="relative max-w-xl">
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-coffee-200">Workspace Staf</p>
                <h1 class="mt-5 text-4xl font-bold leading-tight xl:text-5xl">Kelola pesanan dengan alur yang lebih cepat dan terarah.</h1>
                <p class="mt-5 max-w-lg text-base leading-7 text-coffee-100">Satu dashboard untuk kasir, dapur, administrasi, serta laporan operasional Atrium Coffee.</p>
            </div>
            <p class="relative text-sm text-coffee-200">Sistem Pemesanan & Antrean</p>
        </section>
        <section class="flex items-center justify-center bg-slate-50 px-4 py-10 sm:px-8">
            <div class="w-full max-w-md">
                <a href="{{ route('menu') }}" class="mb-8 inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-coffee-600 lg:hidden">
                    <span aria-hidden="true">←</span> Kembali ke menu
                </a>
                @yield('content')
            </div>
        </section>
    </main>
</body>
</html>
