@extends('layouts.dashboard', ['title' => 'Monitoring Operasional'])

@section('content')
@php
    $statusStyles = [
        'healthy' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        'degraded' => 'border-amber-200 bg-amber-50 text-amber-800',
        'unhealthy' => 'border-red-200 bg-red-50 text-red-800',
    ];
    $metricLabels = [
        'failed_jobs' => 'Failed Jobs',
        'queued_jobs' => 'Queued Jobs',
        'stale_notifications' => 'Notifikasi Bermasalah',
        'pending_payments' => 'Pembayaran Pending > 15 Menit',
        'overdue_queues' => 'Antrean Overdue',
    ];
@endphp

<section class="rounded-2xl border p-5 shadow-sm {{ $statusStyles[$health['status']] }}">
    <p class="text-sm font-semibold uppercase tracking-wide">Status Sistem</p>
    <div class="mt-2 flex flex-wrap items-end justify-between gap-3">
        <p class="text-2xl font-bold uppercase">{{ strtoupper($health['status']) }}</p>
        <p class="text-sm">Pemeriksaan: {{ $health['checked_at'] }}</p>
    </div>
</section>

<section class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
    @foreach($metricLabels as $key => $label)
        <article class="ui-card p-5">
            <p class="text-sm font-medium text-stone-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold text-stone-900">{{ $health['metrics'][$key] ?? '—' }}</p>
        </article>
    @endforeach
</section>

<section class="ui-card mt-6 p-5 sm:p-6">
    <h2 class="text-lg font-semibold">Komponen Utama</h2>
    <div class="mt-4 grid gap-3 sm:grid-cols-2">
        @foreach(['database' => 'Database', 'cache' => 'Cache'] as $key => $label)
            <div class="flex items-center justify-between rounded-xl border border-stone-200 p-4">
                <span class="font-medium">{{ $label }}</span>
                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $health['checks'][$key] ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                    {{ $health['checks'][$key] ? 'TERHUBUNG' : 'BERMASALAH' }}
                </span>
            </div>
        @endforeach
    </div>
</section>
@endsection
