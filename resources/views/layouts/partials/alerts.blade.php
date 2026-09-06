@if(session('success'))
    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm" role="alert">
        <span class="grid size-7 shrink-0 place-items-center rounded-full bg-emerald-100 font-bold">✓</span><div><p class="font-semibold">Berhasil</p><p class="mt-0.5">{{ session('success') }}</p></div>
    </div>
@endif
@if(session('info'))
    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-sky-200 bg-sky-50 p-4 text-sm text-sky-800 shadow-sm" role="status">
        <span class="grid size-7 shrink-0 place-items-center rounded-full bg-sky-100 font-bold">i</span><div><p class="font-semibold">Informasi</p><p class="mt-0.5">{{ session('info') }}</p></div>
    </div>
@endif
@if($errors->any())
    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 shadow-sm" role="alert">
        <span class="grid size-7 shrink-0 place-items-center rounded-full bg-red-100 font-bold">!</span>
        <div><p class="font-semibold">Periksa kembali data berikut</p><ul class="mt-1 list-disc space-y-0.5 pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    </div>
@endif
