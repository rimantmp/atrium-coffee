@extends('layouts.dashboard', ['title' => 'Audit Log'])
@section('content')
<section class="ui-card p-5 sm:p-6">
    <div class="mb-5"><h2 class="text-lg font-bold text-slate-800">Filter Aktivitas</h2><p class="mt-1 text-sm text-slate-500">Telusuri perubahan berdasarkan aksi, entitas, atau periode.</p></div>
    <form method="GET" class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <label><span class="ui-label">Aksi</span><select name="action" class="ui-input"><option value="">Semua aksi</option>@foreach($actions as $action)<option value="{{ $action }}" @selected(($filters['action'] ?? '') === $action)>{{ $action }}</option>@endforeach</select></label>
        <label><span class="ui-label">Jenis entitas</span><select name="entity_type" class="ui-input"><option value="">Semua entitas</option>@foreach($entityTypes as $type)<option value="{{ $type }}" @selected(($filters['entity_type'] ?? '') === $type)>{{ $type }}</option>@endforeach</select></label>
        <label><span class="ui-label">Dari tanggal</span><input name="from" type="date" value="{{ $filters['from'] ?? '' }}" class="ui-input"></label>
        <label><span class="ui-label">Sampai tanggal</span><input name="to" type="date" value="{{ $filters['to'] ?? '' }}" class="ui-input"></label>
        <button class="ui-button-primary self-end">Terapkan Filter</button>
    </form>
</section>
<section class="ui-card mt-6 overflow-hidden">
    <div class="border-b border-slate-100 px-5 py-4 sm:px-6"><h2 class="font-bold text-slate-800">Riwayat Aktivitas</h2><p class="mt-1 text-sm text-slate-500">Catatan terbaru ditampilkan terlebih dahulu.</p></div>
    <div class="overflow-x-auto"><table class="min-w-full text-left text-sm">
        <thead class="ui-table-head"><tr><th class="px-5 py-3.5">Waktu</th><th class="px-5 py-3.5">Pengguna</th><th class="px-5 py-3.5">Aksi</th><th class="px-5 py-3.5">Entitas</th><th class="px-5 py-3.5">Alasan</th><th class="px-5 py-3.5">IP</th></tr></thead>
        <tbody class="divide-y divide-slate-100">@forelse($logs as $log)<tr class="transition hover:bg-slate-50/80"><td class="whitespace-nowrap px-5 py-4 text-slate-500">{{ $log->created_at }}</td><td class="px-5 py-4 font-medium">{{ $log->user_name ?? 'Sistem' }}</td><td class="px-5 py-4"><span class="ui-badge bg-coffee-50 text-coffee-700">{{ $log->action }}</span></td><td class="px-5 py-4">{{ $log->entity_type }} #{{ $log->entity_id }}</td><td class="max-w-xs px-5 py-4 text-slate-500">{{ $log->reason ?? '—' }}</td><td class="whitespace-nowrap px-5 py-4 font-mono text-xs text-slate-500">{{ $log->ip_address ?? '—' }}</td></tr>@empty<tr><td colspan="6" class="p-12 text-center"><div class="mx-auto grid size-12 place-items-center rounded-2xl bg-slate-100 text-xl">⌕</div><p class="mt-3 font-semibold text-slate-600">Tidak ada audit log</p><p class="mt-1 text-sm text-slate-400">Coba sesuaikan filter pencarian.</p></td></tr>@endforelse</tbody>
    </table></div><div class="border-t border-slate-100 p-4">{{ $logs->links() }}</div>
</section>
@endsection
