@php
    $navigation = [
        ['show' => auth()->user()->hasRole('CASHIER', 'ADMIN'), 'route' => 'cashier.index', 'active' => 'cashier.*', 'label' => 'Kasir', 'icon' => 'M4 7h16M6 3h12l1 4H5l1-4Zm0 4v14h12V7M9 11h6M9 15h6'],
        ['show' => auth()->user()->hasRole('KITCHEN', 'ADMIN'), 'route' => 'kitchen.index', 'active' => 'kitchen.*', 'label' => 'Dapur', 'icon' => 'M6 3v8m3-8v8M4 7h7M7.5 11v10M15 3c3 2 4 5 4 9h-4V3Zm2 9v9'],
        ['show' => auth()->user()->hasRole('ADMIN'), 'route' => 'admin.index', 'active' => 'admin.index', 'label' => 'Administrasi', 'icon' => 'M4 5h16v14H4V5Zm0 4h16M8 13h3m-3 3h6'],
        ['show' => auth()->user()->hasRole('ADMIN'), 'route' => 'admin.audit-logs.index', 'active' => 'admin.audit-logs.*', 'label' => 'Audit Log', 'icon' => 'M9 5h6M9 9h6M9 13h4M6 3h12v18H6V3Z'],
        ['show' => auth()->user()->hasRole('ADMIN'), 'route' => 'admin.operations.index', 'active' => 'admin.operations.*', 'label' => 'Operasional', 'icon' => 'M4 12h3l2-5 4 10 2-5h5M4 4v16h16'],
        ['show' => auth()->user()->hasRole('OWNER', 'ADMIN'), 'route' => 'owner.index', 'active' => 'owner.*', 'label' => 'Laporan Pemilik', 'icon' => 'M5 20V10m7 10V4m7 16v-7M3 20h18'],
    ];
@endphp

<div data-sidebar-backdrop data-sidebar-close class="fixed inset-0 z-40 hidden bg-slate-950/50 backdrop-blur-sm lg:hidden"></div>
<aside data-sidebar class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform duration-300 lg:translate-x-0">
    <div class="flex h-20 items-center justify-between px-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <span class="grid size-11 place-items-center rounded-2xl bg-coffee-500 text-xl font-bold text-white shadow-lg shadow-coffee-200">A</span>
            <span><strong class="block text-base tracking-wide text-slate-900">ATRIUM</strong><small class="block text-xs font-semibold tracking-[0.2em] text-coffee-500">COFFEE</small></span>
        </a>
        <button data-sidebar-close type="button" class="grid size-10 place-items-center rounded-xl text-slate-500 hover:bg-slate-100 lg:hidden" aria-label="Tutup navigasi">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="m6 6 12 12M18 6 6 18"/></svg>
        </button>
    </div>

    <div class="mx-4 rounded-2xl bg-gradient-to-br from-coffee-50 to-white p-4 ring-1 ring-coffee-100">
        <div class="flex items-center gap-3">
            <span class="grid size-10 place-items-center rounded-xl bg-coffee-500 font-bold text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            <div class="min-w-0"><p class="truncate text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p><p class="text-xs font-medium text-slate-500">{{ auth()->user()->role }}</p></div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-6" aria-label="Navigasi utama">
        <p class="px-3 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Menu Utama</p>
        <div class="mt-3 space-y-1">
            @foreach($navigation as $item)
                @if($item['show'])
                    <a href="{{ route($item['route']) }}" @class([
                        'group flex min-h-11 items-center gap-3 rounded-xl px-3.5 text-sm font-semibold transition',
                        'bg-coffee-500 text-white shadow-md shadow-coffee-200' => request()->routeIs($item['active']),
                        'text-slate-600 hover:bg-coffee-50 hover:text-coffee-700' => ! request()->routeIs($item['active']),
                    ])>
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}"/></svg>
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        </div>
        <p class="mt-7 px-3 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Lainnya</p>
        <a href="{{ route('menu') }}" class="mt-3 flex min-h-11 items-center gap-3 rounded-xl px-3.5 text-sm font-semibold text-slate-600 transition hover:bg-coffee-50 hover:text-coffee-700">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5h16v14H4V5Zm4 4h8m-8 4h5"/></svg>
            Menu Publik
        </a>
    </nav>

    <div class="border-t border-slate-100 p-4">
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="flex min-h-11 w-full items-center justify-center gap-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-700">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="1.8" d="M15 8l4 4-4 4m4-4H9m3-8H5v16h7"/></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>
