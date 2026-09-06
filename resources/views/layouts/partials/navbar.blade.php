<header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">
    <div class="flex h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-3">
            <button data-sidebar-open type="button" class="grid size-11 shrink-0 place-items-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 lg:hidden" aria-label="Buka navigasi">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
            <div class="min-w-0">
                <p class="truncate text-lg font-bold text-slate-800 sm:text-xl">{{ $title ?? 'Dashboard' }}</p>
                <div class="mt-0.5 hidden items-center gap-1.5 text-xs text-slate-400 sm:flex">
                    <span>Atrium Coffee</span><span>/</span><span class="text-slate-600">{{ $title ?? 'Dashboard' }}</span>
                </div>
            </div>
        </div>
        <details class="group relative">
            <summary class="flex list-none items-center gap-3 rounded-xl p-1.5 pr-2 transition hover:bg-slate-50 [&::-webkit-details-marker]:hidden">
                <span class="grid size-10 place-items-center rounded-xl bg-coffee-100 font-bold text-coffee-700">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <span class="hidden text-left sm:block"><strong class="block max-w-40 truncate text-sm text-slate-700">{{ auth()->user()->name }}</strong><small class="block text-xs text-slate-400">{{ auth()->user()->role }}</small></span>
                <svg class="hidden size-4 text-slate-400 transition group-open:rotate-180 sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="m7 10 5 5 5-5"/></svg>
            </summary>
            <div class="absolute right-0 mt-2 w-52 rounded-2xl border border-slate-200 bg-white p-2 shadow-xl shadow-slate-200/70">
                <div class="border-b border-slate-100 px-3 py-2 sm:hidden"><p class="truncate text-sm font-semibold">{{ auth()->user()->name }}</p><p class="text-xs text-slate-400">{{ auth()->user()->role }}</p></div>
                <a href="{{ route('menu') }}" class="flex min-h-10 items-center rounded-lg px-3 text-sm font-medium text-slate-600 hover:bg-slate-50">Buka menu publik</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="flex min-h-10 w-full items-center rounded-lg px-3 text-sm font-medium text-red-600 hover:bg-red-50">Keluar</button></form>
            </div>
        </details>
    </div>
</header>
