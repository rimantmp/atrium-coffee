<section class="ui-card mt-6 p-5 sm:p-6">
    <h2 class="text-lg font-semibold">Manajemen Pengguna</h2>
    <form method="POST" action="{{ route('admin.users.store') }}" class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">@csrf
        <input name="name" required maxlength="100" placeholder="Nama pengguna" class="min-h-11 rounded-xl border border-stone-300 px-3">
        <input name="email" type="email" required placeholder="Email" class="min-h-11 rounded-xl border border-stone-300 px-3">
        <select name="role" required class="min-h-11 rounded-xl border border-stone-300 px-3">@foreach(['ADMIN'=>'Admin','CASHIER'=>'Kasir','KITCHEN'=>'Dapur','OWNER'=>'Pemilik'] as $value=>$label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select>
        <label class="flex items-center gap-2 text-sm"><input name="is_active" type="checkbox" value="1" checked class="size-4 rounded border-stone-300"> Aktif</label>
        <input name="password" type="password" required minlength="8" placeholder="Password minimal 8 karakter" class="min-h-11 rounded-xl border border-stone-300 px-3">
        <input name="password_confirmation" type="password" required minlength="8" placeholder="Konfirmasi password" class="min-h-11 rounded-xl border border-stone-300 px-3">
        <button class="min-h-11 rounded-xl bg-coffee-600 px-4 font-semibold text-white md:col-span-2">Tambah Pengguna</button>
    </form>
    <div class="mt-6 grid gap-4 lg:grid-cols-2">
        @foreach($users as $user)
            <details class="rounded-xl border border-stone-200 p-4"><summary class="cursor-pointer font-semibold">{{ $user->name }} <span class="text-sm font-normal text-stone-500">· {{ $user->role }} · {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span></summary>
                <form method="POST" action="{{ route('admin.users.update',$user) }}" class="mt-4 grid gap-3 sm:grid-cols-2">@csrf @method('PUT')
                    <input name="name" value="{{ $user->name }}" required class="min-h-11 rounded-xl border border-stone-300 px-3"><input name="email" type="email" value="{{ $user->email }}" required class="min-h-11 rounded-xl border border-stone-300 px-3">
                    <select name="role" required class="min-h-11 rounded-xl border border-stone-300 px-3">@foreach(['ADMIN','CASHIER','KITCHEN','OWNER'] as $role)<option value="{{ $role }}" @selected($user->role===$role)>{{ $role }}</option>@endforeach</select>
                    <label class="flex items-center gap-2 text-sm"><input name="is_active" type="checkbox" value="1" @checked($user->is_active) class="size-4 rounded border-stone-300"> Aktif</label>
                    <input name="password" type="password" minlength="8" placeholder="Password baru (opsional)" class="min-h-11 rounded-xl border border-stone-300 px-3"><input name="password_confirmation" type="password" minlength="8" placeholder="Konfirmasi password" class="min-h-11 rounded-xl border border-stone-300 px-3">
                    <button class="min-h-11 rounded-xl bg-stone-800 px-4 text-sm font-semibold text-white sm:col-span-2">Perbarui Pengguna</button>
                </form>
                @unless(auth()->user()->is($user))<form method="POST" action="{{ route('admin.users.destroy',$user) }}" class="mt-3" onsubmit="return confirm('Hapus pengguna ini?')">@csrf @method('DELETE')<button class="text-sm font-semibold text-red-700">Hapus pengguna</button></form>@endunless
            </details>
        @endforeach
    </div>
</section>
