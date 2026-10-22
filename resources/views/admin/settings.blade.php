@extends('layouts.dashboard', ['title' => 'Pengaturan Sistem'])

@section('content')
@php
    $fields = [
        'default_priority' => ['label' => 'Prioritas default', 'fallback' => 3, 'hint' => 'Prioritas awal setiap pesanan baru. Angka lebih kecil berarti lebih tinggi.'],
        'minimum_priority' => ['label' => 'Prioritas minimum', 'fallback' => 1, 'hint' => 'Batas tertinggi yang bisa dicapai aging. Angka 1 berarti P1 Khusus.'],
        'aging_interval_minutes' => ['label' => 'Interval aging', 'fallback' => 10, 'hint' => 'Setiap berapa menit menunggu, prioritas naik satu tingkat.'],
        'overdue_minutes' => ['label' => 'Batas overdue', 'fallback' => 30, 'hint' => 'Antrean ditandai lewat estimasi setelah melewati durasi ini.'],
    ];
@endphp

<div>
    <p class="text-sm font-medium text-stone-500">Konfigurasi aplikasi</p>
    <h1 class="mt-1 text-2xl font-bold tracking-tight text-stone-900">Pengaturan Sistem</h1>
    <p class="mt-1 text-sm text-stone-500">Atur parameter antrean tanpa mengganggu aktivitas operasional harian.</p>
</div>

<x-card class="mt-6 max-w-3xl" padding="none">
    <div class="border-b border-stone-200 p-5 sm:p-6">
        <div class="flex items-center gap-3">
            <span class="grid size-11 place-items-center rounded-xl bg-coffee-50 text-coffee-700" aria-hidden="true"><x-icon name="cog" /></span>
            <div>
                <h2 class="font-bold text-stone-900">Konfigurasi Scheduling</h2>
                <p class="text-sm text-stone-500">Priority lebih kecil berarti lebih tinggi.</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings') }}" class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">
        @csrf
        @foreach($fields as $key => $field)
            <x-form.input
                :name="$key"
                :label="$field['label']"
                type="number"
                min="1"
                :value="$settings[$key] ?? $field['fallback']"
                :hint="$field['hint']"
                required
            />
        @endforeach

        <div class="rounded-xl bg-stone-50 p-4 text-sm text-stone-600 sm:col-span-2">
            Perubahan berlaku untuk antrean berikutnya. Pesanan yang sudah masuk antrean tidak dihitung ulang.
        </div>

        <div class="flex justify-end border-t border-stone-200 pt-5 sm:col-span-2">
            <x-button type="submit">Simpan Konfigurasi</x-button>
        </div>
    </form>
</x-card>
@endsection
