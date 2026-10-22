@props([
    'head' => [],
])

{{--
    Markup ganda (rencana 1.6): tabel untuk md ke atas, daftar kartu untuk mobile.
    Setiap view memilih field mana yang penting di layar kecil lewat slot mobile,
    jadi tidak perlu trik CSS dan tidak ada kolom yang terpotong.
--}}
<div {{ $attributes->merge(['class' => 'rounded-2xl border border-stone-200 bg-white shadow-sm overflow-hidden']) }}>
    <div class="hidden overflow-x-auto md:block">
        <table class="w-full text-left text-sm">
            <thead class="ui-table-head border-b border-stone-200">
                <tr>
                    @foreach($head as $column)
                        <th scope="col" class="px-5 py-3.5 font-semibold">{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                {{ $desktop }}
            </tbody>
        </table>
    </div>

    <ul class="divide-y divide-stone-100 md:hidden">
        {{ $mobile }}
    </ul>
</div>
