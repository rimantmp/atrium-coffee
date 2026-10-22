@extends('layouts.dashboard', ['title' => 'Audit Log'])

@section('content')
<div>
    <p class="text-sm font-medium text-stone-500">Jejak operasional</p>
    <h1 class="mt-1 text-2xl font-bold tracking-tight text-stone-900">Audit Log</h1>
    <p class="mt-1 text-sm text-stone-500">Telusuri perubahan berdasarkan aksi, entitas, atau periode.</p>
</div>

<x-card class="mt-6">
    <form method="GET" class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <x-form.select name="action" label="Aksi">
            <option value="">Semua aksi</option>
            @foreach($actions as $action)
                <option value="{{ $action }}" @selected(($filters['action'] ?? '') === $action)>{{ $action }}</option>
            @endforeach
        </x-form.select>
        <x-form.select name="entity_type" label="Jenis entitas">
            <option value="">Semua entitas</option>
            @foreach($entityTypes as $type)
                <option value="{{ $type }}" @selected(($filters['entity_type'] ?? '') === $type)>{{ $type }}</option>
            @endforeach
        </x-form.select>
        <x-form.input name="from" label="Dari tanggal" type="date" :value="$filters['from'] ?? ''" />
        <x-form.input name="to" label="Sampai tanggal" type="date" :value="$filters['to'] ?? ''" />
        <div class="flex items-end">
            <x-button type="submit" class="w-full">Terapkan Filter</x-button>
        </div>
    </form>
</x-card>

<x-card class="mt-6" padding="none">
    <div class="border-b border-stone-200 px-5 py-4 sm:px-6">
        <h2 class="font-bold text-stone-900">Riwayat Aktivitas</h2>
        <p class="mt-1 text-sm text-stone-500">Catatan terbaru ditampilkan terlebih dahulu.</p>
    </div>

    <x-data-table :head="['Waktu', 'Pengguna', 'Aksi', 'Entitas', 'Alasan', 'IP']">
        <x-slot:desktop>
            @forelse($logs as $log)
                <tr class="transition hover:bg-stone-50">
                    <td class="whitespace-nowrap px-5 py-4 text-stone-500">{{ $log->created_at }}</td>
                    <td class="px-5 py-4 font-medium text-stone-800">{{ $log->user_name ?? 'Sistem' }}</td>
                    <td class="px-5 py-4"><x-badge variant="coffee">{{ $log->action }}</x-badge></td>
                    <td class="px-5 py-4 text-stone-700">{{ $log->entity_type }} #{{ $log->entity_id }}</td>
                    <td class="max-w-xs px-5 py-4 text-stone-500">{{ $log->reason ?? '-' }}</td>
                    <td class="whitespace-nowrap px-5 py-4 font-mono text-xs text-stone-500">{{ $log->ip_address ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <x-empty-state icon="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" title="Tidak ada audit log" description="Coba sesuaikan filter pencarian." />
                    </td>
                </tr>
            @endforelse
        </x-slot:desktop>

        <x-slot:mobile>
            @forelse($logs as $log)
                <li class="space-y-3 p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="truncate font-bold text-stone-900">{{ $log->user_name ?? 'Sistem' }}</p>
                            <p class="text-xs text-stone-500">{{ $log->created_at }}</p>
                        </div>
                        <x-badge variant="coffee">{{ $log->action }}</x-badge>
                    </div>
                    <dl class="space-y-2 text-sm">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-stone-500">Entitas</dt>
                            <dd class="text-stone-800">{{ $log->entity_type }} #{{ $log->entity_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-stone-500">Alasan</dt>
                            <dd class="text-stone-700">{{ $log->reason ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-stone-500">IP</dt>
                            <dd class="font-mono text-xs text-stone-500">{{ $log->ip_address ?? '-' }}</dd>
                        </div>
                    </dl>
                </li>
            @empty
                <li>
                    <x-empty-state icon="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" title="Tidak ada audit log" description="Coba sesuaikan filter pencarian." />
                </li>
            @endforelse
        </x-slot:mobile>
    </x-data-table>

    <div class="border-t border-stone-200 p-4">{{ $logs->links() }}</div>
</x-card>
@endsection
