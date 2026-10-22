@extends('layouts.auth', ['title' => 'Login Staf'])

@section('content')
<div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm sm:p-10">
    <div class="mb-8">
        <span class="inline-flex items-center gap-2 rounded-full bg-coffee-100 px-4 py-2 text-xs font-bold uppercase tracking-[0.13em] text-coffee-700">
            <x-icon name="lock-closed" class="size-4" />
            Portal Staf
        </span>
        <h1 class="mt-5 text-3xl font-extrabold tracking-tight text-stone-900 sm:text-4xl">Selamat datang kembali</h1>
        <p class="mt-2 text-sm leading-6 text-stone-500 sm:text-base">Masuk menggunakan akun staf Atrium Coffee Anda.</p>
    </div>

    @if($errors->any())
        <x-alert variant="error" class="mb-6">{{ $errors->first() }}</x-alert>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-1.5 block text-sm font-semibold text-stone-800">Alamat email</label>
            <div class="relative">
                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-stone-500" aria-hidden="true"><x-icon name="mail" /></span>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="nama@atrium.test"
                    aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
                    @if($errors->has('email')) aria-invalid="true" @endif
                    class="ui-input h-13 pl-12 text-base"
                >
            </div>
            @error('email')
                <p id="email-error" class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-1.5 block text-sm font-semibold text-stone-800">Kata sandi</label>
            <div class="relative">
                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-stone-500" aria-hidden="true"><x-icon name="eye-slash" /></span>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan kata sandi"
                    class="ui-input h-13 pl-12 pr-14 text-base"
                >
                <button
                    type="button"
                    data-password-toggle
                    data-password-target="password"
                    class="absolute right-2.5 top-1/2 grid size-10 -translate-y-1/2 place-items-center rounded-lg text-stone-500 transition hover:bg-stone-100 hover:text-stone-800"
                    aria-label="Tampilkan kata sandi"
                    aria-pressed="false"
                >
                    <x-icon name="eye" data-password-visible-icon />
                    <x-icon name="eye-slash" data-password-hidden-icon hidden />
                </button>
            </div>
        </div>

        <label class="flex w-fit cursor-pointer items-center gap-3 text-sm font-medium text-stone-700">
            <input type="checkbox" name="remember" class="size-5 rounded border-stone-300 text-coffee-600 focus:ring-coffee-500/30">
            Ingat saya di perangkat ini
        </label>

        <x-button type="submit" size="lg" class="w-full">
            Masuk ke Dashboard
            <x-icon name="arrow-right" class="size-5" />
        </x-button>
    </form>

    <div class="mt-7 border-t border-stone-200 pt-5 text-center">
        <p class="text-sm text-stone-500">Gunakan akun yang diberikan administrator.</p>
    </div>
</div>
@endsection
