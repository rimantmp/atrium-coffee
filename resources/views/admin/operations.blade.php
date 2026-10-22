@extends('layouts.dashboard', ['title' => 'Monitoring Operasional'])

@section('content')
@php
    $statusStyles = [
        'healthy' => 'border-emerald-200 bg-emerald-50 text-emerald-900',
        'degraded' => 'border-amber-200 bg-amber-50 text-amber-900',
        'unhealthy' => 'border-red-200 bg-red-50 text-red-900',
    ];
    $bannerStyle = $statusStyles[$health['status']] ?? $statusStyles['unhealthy'];
    $metricLabels = [
        'failed_jobs' => 'Failed Jobs',
        'queued_jobs' => 'Queued Jobs',
        'stale_notifications' => 'Notifikasi Bermasalah',
        'pending_payments' => 'Pembayaran Pending > 15 Menit',
        'overdue_queues' => 'Antrean Overdue',
    ];
@endphp

<div>
    <p class="text-sm font-medium text-stone-500">Kesehatan sistem</p>
    <h1 class="mt-1 text-2xl font-bold tracking-tight text-stone-900">Monitoring Operasional</h1>
    <p class="mt-1 text-sm text-stone-500">Pantau kondisi antrean, pekerjaan latar, dan komponen utama.</p>
</div>

<section class="mt-6 rounded-2xl border p-5 shadow-sm sm:p-6 {{ $bannerStyle }}">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide">Status Sistem</p>
            <p class="mt-2 text-2xl font-bold uppercase tracking-tight">{{ strtoupper($health['status']) }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm">Pemeriksaan terakhir</p>
            <p class="text-sm font-semibold">{{ $health['checked_at'] }}</p>
        </div>
    </div>
</section>

<div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
    @foreach($metricLabels as $key => $label)
        @php($value = (int) ($health['metrics'][$key] ?? 0))
        @php($isAlert = $key !== 'queued_jobs' && $value > 0)
        <x-stat-card
            :label="$label"
            :value="$value"
            :tone="$isAlert ? 'warning' : 'neutral'"
            :hint="$isAlert ? 'Perlu ditindaklanjuti' : 'Dalam batas normal'"
        />
    @endforeach
</div>

<x-card as="section" class="mt-6">
    <h2 class="text-lg font-bold text-stone-900">Komponen Utama</h2>
    <div class="mt-4 grid gap-3 sm:grid-cols-2">
        @foreach(['database' => 'Database', 'cache' => 'Cache'] as $key => $label)
            <div class="flex items-center justify-between gap-4 rounded-xl border border-stone-200 p-4">
                <span class="font-medium text-stone-800">{{ $label }}</span>
                <x-badge :variant="$health['checks'][$key] ? 'success' : 'error'">
                    {{ $health['checks'][$key] ? 'TERHUBUNG' : 'BERMASALAH' }}
                </x-badge>
            </div>
        @endforeach
    </div>
</x-card>
@endsection
