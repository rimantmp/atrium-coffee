@php
    $navigation = [
        ['show' => auth()->user()->hasRole('CASHIER', 'ADMIN'), 'route' => 'cashier.index', 'active' => 'cashier.*', 'label' => 'Kasir', 'icon' => 'M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z'],
        ['show' => auth()->user()->hasRole('KITCHEN', 'ADMIN'), 'route' => 'kitchen.index', 'active' => 'kitchen.*', 'label' => 'Dapur', 'icon' => 'M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z'],
        ['show' => auth()->user()->hasRole('ADMIN'), 'route' => 'admin.index', 'active' => 'admin.index', 'label' => 'Dashboard Admin', 'icon' => 'M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z'],
        ['show' => auth()->user()->hasRole('ADMIN'), 'route' => 'admin.menus.index', 'active' => 'admin.menus.*', 'label' => 'Menu', 'icon' => 'M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25'],
        ['show' => auth()->user()->hasRole('ADMIN'), 'route' => 'admin.categories.index', 'active' => 'admin.categories.*', 'label' => 'Kategori', 'icon' => 'M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z M6 6h.008v.008H6V6Z'],
        ['show' => auth()->user()->hasRole('ADMIN'), 'route' => 'admin.users.index', 'active' => 'admin.users.*', 'label' => 'Pengguna', 'icon' => 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z'],
        ['show' => auth()->user()->hasRole('ADMIN'), 'route' => 'admin.settings.index', 'active' => 'admin.settings*', 'label' => 'Pengaturan Sistem', 'icon' => 'M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75'],
        ['show' => auth()->user()->hasRole('ADMIN'), 'route' => 'admin.audit-logs.index', 'active' => 'admin.audit-logs.*', 'label' => 'Audit Log', 'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Zm-2.25 6h3.75m-3.75 3h6M8.25 15h6'],
        ['show' => auth()->user()->hasRole('ADMIN'), 'route' => 'admin.operations.index', 'active' => 'admin.operations.*', 'label' => 'Operasional', 'icon' => 'M5.25 14.25h13.5m-13.5 0a3 3 0 0 1-3-3V6a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3v5.25a3 3 0 0 1-3 3m-13.5 0a3 3 0 0 0-3 3v.75a3 3 0 0 0 3 3h13.5a3 3 0 0 0 3-3v-.75a3 3 0 0 0-3-3M6.75 6.75h.008v.008H6.75V6.75Zm0 10.5h.008v.008H6.75v-.008Z'],
        ['show' => auth()->user()->hasRole('OWNER', 'ADMIN'), 'route' => 'owner.index', 'active' => 'owner.*', 'label' => 'Laporan Pemilik', 'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z'],
    ];
@endphp

<div data-sidebar-backdrop data-sidebar-close class="fixed inset-0 z-40 hidden bg-stone-950/50 lg:hidden"></div>
<aside data-sidebar class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-stone-200 bg-white transition-transform duration-300 lg:translate-x-0">
    <div class="flex h-20 items-center justify-between px-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-coffee-900">
            <svg class="size-10" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                <path d="M24 3c-8 7-13 15-13 24 0 9 6 16 13 18V3Z" fill="currentColor"/>
                <path d="M26 9c8 5 12 12 11 20-1 8-5 13-11 16V9Z" fill="#a66f4f"/>
                <path d="M13 27c5 0 9-2 12-6M13 35c5-1 9-4 12-8M35 23c-4 1-7 3-9 6M34 33c-3 0-6 2-8 4" stroke="#fff" stroke-width="1.5"/>
            </svg>
            <span><strong class="block text-base tracking-[0.14em]">ATRIUM</strong><small class="block text-[11px] font-bold tracking-[0.24em] text-coffee-600">COFFEE</small></span>
        </a>
        <button data-sidebar-close type="button" class="admin-icon-button lg:hidden" aria-label="Tutup navigasi">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18 18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <div class="mx-4 rounded-xl border border-stone-200 bg-stone-50 p-3">
        <div class="flex items-center gap-3">
            <span class="grid size-10 place-items-center rounded-full bg-coffee-100 font-bold text-coffee-800">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            <div class="min-w-0">
                <p class="truncate text-sm font-bold text-stone-900">{{ auth()->user()->name }}</p>
                <p class="text-xs font-medium text-stone-500">{{ auth()->user()->role }}</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-5" aria-label="Navigasi utama">
        <p class="px-3 text-[10px] font-bold uppercase tracking-[0.2em] text-stone-500">Menu Utama</p>
        <div class="mt-3 space-y-1">
            @foreach($navigation as $item)
                @if($item['show'])
                    @php($isActive = request()->routeIs($item['active']))
                    <a
                        href="{{ route($item['route']) }}"
                        @if($isActive) aria-current="page" @endif
                        @class([
                            'group flex min-h-11 items-center gap-3 rounded-xl px-3.5 text-sm font-semibold transition duration-150 ease-out',
                            'bg-coffee-50 text-coffee-700' => $isActive,
                            'text-stone-700 hover:bg-stone-100 hover:text-stone-900' => ! $isActive,
                        ])
                    >
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/>
                        </svg>{{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        </div>
        <div class="my-5 border-t border-stone-200"></div>
        <p class="px-3 text-[10px] font-bold uppercase tracking-[0.2em] text-stone-500">Lainnya</p>
        <a href="{{ route('menu') }}" class="mt-3 flex min-h-11 items-center gap-3 rounded-xl px-3.5 text-sm font-semibold text-stone-700 transition duration-150 ease-out hover:bg-stone-100 hover:text-stone-900">
            <svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
            Menu Publik
        </a>
    </nav>

    <div class="border-t border-stone-200 p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="flex min-h-11 w-full items-center justify-center gap-2 rounded-xl border border-stone-300 text-sm font-semibold text-stone-700 transition duration-150 ease-out hover:border-red-200 hover:bg-red-50 hover:text-red-700">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25"/></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>
