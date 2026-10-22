@extends('layouts.dashboard', ['title' => 'Manajemen Pengguna'])

@section('content')
@php
    $roleLabels = ['ADMIN' => 'Admin', 'CASHIER' => 'Kasir', 'KITCHEN' => 'Dapur', 'OWNER' => 'Pemilik'];
    $roleVariants = ['ADMIN' => 'coffee', 'CASHIER' => 'info', 'KITCHEN' => 'warning', 'OWNER' => 'neutral'];
@endphp

<div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="text-sm font-medium text-stone-500">Administrasi akses</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-stone-900">Manajemen Pengguna</h1>
        <p class="mt-1 text-sm text-stone-500">Kelola akun, peran, dan status akses staf.</p>
    </div>
    <x-button type="button" data-modal-open="create-user" class="shrink-0">
        <x-icon name="user-plus" class="size-4" /> Tambah Pengguna
    </x-button>
</div>

<x-card class="mt-6" padding="none">
    <form method="GET" class="grid gap-3 border-b border-stone-200 p-4 sm:grid-cols-[minmax(200px,1fr)_180px_180px_auto] sm:p-5">
        <label>
            <span class="sr-only">Cari pengguna</span>
            <input name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="ui-input">
        </label>
        <label>
            <span class="sr-only">Filter peran</span>
            <select name="role" class="ui-input">
                <option value="">Semua peran</option>
                @foreach($roleLabels as $value => $label)
                    <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label>
            <span class="sr-only">Filter status</span>
            <select name="status" class="ui-input">
                <option value="">Semua status</option>
                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
            </select>
        </label>
        <x-button type="submit" variant="secondary">Terapkan</x-button>
    </form>

    <x-data-table :head="['Pengguna', 'Peran', 'Status', 'Login Terakhir', 'Aksi']">
        <x-slot:desktop>
            @forelse($users as $user)
                <tr class="transition hover:bg-stone-50">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-coffee-100 font-bold text-coffee-800">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            <div class="min-w-0">
                                <p class="font-semibold text-stone-900">{{ $user->name }}</p>
                                <p class="text-sm text-stone-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4"><x-badge :variant="$roleVariants[$user->role] ?? 'neutral'">{{ $roleLabels[$user->role] ?? $user->role }}</x-badge></td>
                    <td class="px-5 py-4">
                        <form method="POST" action="{{ route('admin.users.status', $user) }}" data-quick-toggle>
                            @csrf
                            @method('PATCH')
                            <x-form.toggle name="is_active" :checked="$user->is_active" :label="'Status akses '.$user->name" bare />
                        </form>
                    </td>
                    <td class="whitespace-nowrap px-5 py-4 text-sm text-stone-500">{{ $user->last_login_at?->diffForHumans() ?? 'Belum pernah' }}</td>
                    <td class="px-5 py-4">
                        <div class="flex justify-end gap-1">
                            <x-button type="button" variant="text" size="sm" data-modal-open="edit-user-{{ $user->id }}" class="admin-icon-button" aria-label="Edit {{ $user->name }}">
                                <x-icon name="pencil" class="size-4" />
                            </x-button>
                            @unless(auth()->user()->is($user))
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="text" size="sm" class="admin-icon-button hover:bg-red-50 hover:text-red-600" aria-label="Hapus {{ $user->name }}">
                                        <x-icon name="trash" class="size-4" />
                                    </x-button>
                                </form>
                            @endunless
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <x-empty-state icon="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" title="Pengguna tidak ditemukan" description="Ubah filter pencarian atau tambahkan pengguna baru." />
                    </td>
                </tr>
            @endforelse
        </x-slot:desktop>

        <x-slot:mobile>
            @forelse($users as $user)
                <li class="space-y-3 p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex min-w-0 items-center gap-3">
                            <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-coffee-100 font-bold text-coffee-800">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            <div class="min-w-0">
                                <p class="truncate font-bold text-stone-900">{{ $user->name }}</p>
                                <p class="truncate text-sm text-stone-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <x-badge :variant="$roleVariants[$user->role] ?? 'neutral'">{{ $roleLabels[$user->role] ?? $user->role }}</x-badge>
                    </div>
                    <dl class="flex items-center justify-between gap-4 text-sm">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-stone-500">Login terakhir</dt>
                            <dd class="font-semibold text-stone-900">{{ $user->last_login_at?->diffForHumans() ?? 'Belum pernah' }}</dd>
                        </div>
                        <form method="POST" action="{{ route('admin.users.status', $user) }}" data-quick-toggle>
                            @csrf
                            @method('PATCH')
                            <x-form.toggle name="is_active" :checked="$user->is_active" :label="'Status akses '.$user->name" bare />
                        </form>
                    </dl>
                    <div class="flex justify-end gap-1">
                        <x-button type="button" variant="text" size="sm" data-modal-open="edit-user-{{ $user->id }}" class="admin-icon-button" aria-label="Edit {{ $user->name }}">
                            <x-icon name="pencil" class="size-4" />
                        </x-button>
                        @unless(auth()->user()->is($user))
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" variant="text" size="sm" class="admin-icon-button hover:bg-red-50 hover:text-red-600" aria-label="Hapus {{ $user->name }}">
                                    <x-icon name="trash" class="size-4" />
                                </x-button>
                            </form>
                        @endunless
                    </div>
                </li>
            @empty
                <li>
                    <x-empty-state icon="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" title="Pengguna tidak ditemukan" description="Ubah filter pencarian atau tambahkan pengguna baru." />
                </li>
            @endforelse
        </x-slot:mobile>
    </x-data-table>

    @if($users->hasPages())
        <div class="border-t border-stone-200 px-5 py-4">{{ $users->links() }}</div>
    @endif
</x-card>

<x-modal name="create-user" title="Tambah Pengguna" description="Buat akun staf baru.">
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
        @csrf
        <x-form.input name="name" label="Nama pengguna" required maxlength="100" />
        <x-form.input name="email" label="Alamat email" type="email" required />
        <x-form.select name="role" label="Peran" required>
            @foreach($roleLabels as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </x-form.select>
        <div class="grid gap-4 sm:grid-cols-2">
            <x-form.input name="password" label="Kata sandi" type="password" required minlength="8" hint="Minimal 8 karakter." />
            <x-form.input name="password_confirmation" label="Konfirmasi kata sandi" type="password" required minlength="8" />
        </div>
        <x-form.toggle name="is_active" label="Status aktif" description="Izinkan pengguna masuk." :checked="true" />
        <div class="flex justify-end gap-3 border-t border-stone-200 pt-4">
            <x-button type="button" variant="secondary" data-modal-close>Batal</x-button>
            <x-button type="submit">Simpan Pengguna</x-button>
        </div>
    </form>
</x-modal>

@foreach($users as $user)
    <x-modal name="edit-user-{{ $user->id }}" title="Edit Pengguna" :description="'Perbarui informasi '.$user->name.'.'">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <x-form.input name="name" label="Nama pengguna" :value="$user->name" required maxlength="100" />
            <x-form.input name="email" label="Alamat email" type="email" :value="$user->email" required />
            <x-form.select name="role" label="Peran" required>
                @foreach($roleLabels as $value => $label)
                    <option value="{{ $value }}" @selected($user->role === $value)>{{ $label }}</option>
                @endforeach
            </x-form.select>
            <div class="grid gap-4 sm:grid-cols-2">
                <x-form.input name="password" label="Kata sandi baru" type="password" minlength="8" hint="Kosongkan bila tidak diubah." />
                <x-form.input name="password_confirmation" label="Konfirmasi kata sandi baru" type="password" minlength="8" />
            </div>
            <x-form.toggle name="is_active" label="Status aktif" description="Izinkan pengguna masuk." :checked="$user->is_active" />
            <div class="flex justify-end gap-3 border-t border-stone-200 pt-4">
                <x-button type="button" variant="secondary" data-modal-close>Batal</x-button>
                <x-button type="submit">Simpan Perubahan</x-button>
            </div>
        </form>
    </x-modal>
@endforeach
@endsection
