<header class="sticky top-0 z-30 border-b border-stone-200 bg-white/95 backdrop-blur-sm">
    <div class="flex h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-3">
            <button data-sidebar-open type="button" class="admin-icon-button border border-stone-200 lg:hidden" aria-label="Buka navigasi">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>
            <div class="min-w-0">
                <p class="truncate text-lg font-extrabold tracking-tight text-stone-900 sm:text-xl">{{ $title ?? 'Dashboard' }}</p>
                <nav class="mt-0.5 hidden items-center gap-2 text-xs text-stone-500 sm:flex" aria-label="Remah roti">
                    <a href="{{ route('dashboard') }}" class="transition hover:text-coffee-700">Atrium Coffee</a>
                    <span aria-hidden="true">/</span>
                    <span aria-current="page">{{ $title ?? 'Dashboard' }}</span>
                </nav>
            </div>
        </div>

        <div class="flex items-center gap-3 sm:gap-5">
            <div class="hidden items-center gap-2 rounded-xl border border-stone-200 bg-white px-3 py-2 text-xs font-semibold text-stone-600 sm:flex">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                {{ now()->translatedFormat('l, j F Y') }}
            </div>

            <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false" @click.outside="open = false">
                <button
                    type="button"
                    @click="open = !open"
                    :aria-expanded="open ? 'true' : 'false'"
                    aria-haspopup="menu"
                    class="flex min-h-11 items-center gap-3 rounded-xl p-1.5 transition duration-150 ease-out hover:bg-stone-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-coffee-600"
                >
                    <span class="grid size-10 shrink-0 place-items-center rounded-full bg-coffee-100 font-bold text-coffee-800">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    <span class="hidden text-left sm:block">
                        <strong class="block max-w-40 truncate text-sm text-stone-900">{{ auth()->user()->name }}</strong>
                        <small class="block text-xs text-stone-500">{{ auth()->user()->role }}</small>
                    </span>
                    <svg class="hidden size-4 text-stone-500 transition duration-150 ease-out sm:block" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>

                <div
                    x-cloak
                    x-show="open"
                    x-transition.opacity.duration.150ms
                    role="menu"
                    aria-label="Menu akun"
                    class="absolute right-0 mt-2 w-56 rounded-xl border border-stone-200 bg-white p-2 shadow-md"
                >
                    <a href="{{ route('menu') }}" role="menuitem" class="flex min-h-11 items-center rounded-lg px-3 text-sm font-medium text-stone-700 transition duration-150 ease-out hover:bg-stone-100 hover:text-stone-900">Buka menu publik</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" role="menuitem" class="flex min-h-11 w-full items-center rounded-lg px-3 text-sm font-medium text-red-700 transition duration-150 ease-out hover:bg-red-50">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
