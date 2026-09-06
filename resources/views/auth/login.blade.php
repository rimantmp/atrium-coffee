@extends('layouts.auth', ['title' => 'Login Staf'])

@section('content')
<div class="ui-card p-6 sm:p-8">
    <div class="mb-7">
        <span class="ui-badge bg-coffee-100 text-coffee-700">Portal Internal</span>
        <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900">Selamat datang kembali</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">Masuk menggunakan akun staf Atrium Coffee Anda.</p>
    </div>

    @if($errors->any())
        <div class="mb-5 flex gap-3 rounded-xl border border-red-200 bg-red-50 p-3.5 text-sm text-red-700" role="alert"><span class="font-bold">!</span><span>{{ $errors->first() }}</span></div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <div><label for="email" class="ui-label">Alamat email</label><input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@atrium.test" class="ui-input"></div>
        <div><label for="password" class="ui-label">Kata sandi</label><input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Masukkan kata sandi" class="ui-input"></div>
        <label class="flex items-center gap-2.5 text-sm font-medium text-slate-600"><input type="checkbox" name="remember" class="size-4 rounded border-slate-300 text-coffee-600"> Ingat saya di perangkat ini</label>
        <button class="ui-button-primary w-full">Masuk ke Dashboard <span aria-hidden="true">→</span></button>
    </form>
</div>
<p class="mt-5 text-center text-xs text-slate-400">Gunakan akun yang diberikan administrator.</p>
@endsection
