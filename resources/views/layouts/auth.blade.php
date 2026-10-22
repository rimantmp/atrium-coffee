<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#8b563e">
    <title>{{ $title ?? 'Autentikasi' }} · Atrium Coffee</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-50">
    <main class="grid min-h-screen lg:grid-cols-[minmax(0,1.1fr)_minmax(540px,0.9fr)]">
        <section class="relative hidden min-h-screen overflow-hidden bg-stone-950 p-12 text-white lg:flex lg:flex-col lg:justify-between xl:p-16">
            <img src="{{ asset('images/atrium-login-cafe.png') }}" alt="Barista Atrium Coffee sedang menyiapkan kopi" class="absolute inset-0 size-full object-cover object-center">
            <div class="absolute inset-0 bg-linear-to-b from-black/50 via-black/20 to-black/75"></div>

            <a href="{{ route('menu') }}" class="relative z-10 flex w-fit items-center gap-4">
                <span class="grid size-14 place-items-center rounded-2xl bg-white text-2xl font-extrabold text-coffee-600 shadow-lg">A</span>
                <span class="text-xl font-extrabold tracking-[0.08em] xl:text-2xl">ATRIUM COFFEE</span>
            </a>

            <div class="relative z-10 max-w-2xl">
                <p class="text-sm font-bold uppercase tracking-[0.3em] text-white/85">Workspace Staf</p>
                <h1 class="mt-5 text-4xl font-extrabold leading-[1.12] tracking-tight xl:text-5xl">Kelola pesanan dengan cepat dan terarah.</h1>
                <p class="mt-5 max-w-xl text-base leading-7 text-white/85 xl:text-lg">Satu dashboard untuk kasir, dapur, administrasi, serta laporan operasional Atrium Coffee.</p>
                <div class="mt-12 h-px w-10 bg-white/80"></div>
                <p class="mt-5 text-sm font-medium text-white/85">Sistem Pemesanan &amp; Antrean</p>
            </div>
        </section>

        <section class="relative flex min-h-screen items-center justify-center overflow-hidden bg-stone-50 px-4 py-10 sm:px-8 lg:px-12">
            <div class="relative w-full max-w-[560px]">
                <a href="{{ route('menu') }}" class="mb-7 inline-flex items-center gap-2 text-sm font-semibold text-stone-600 transition hover:text-coffee-600 lg:hidden">
                    <span aria-hidden="true">←</span> Kembali ke menu
                </a>
                @yield('content')
            </div>
        </section>
    </main>
</body>
</html>
