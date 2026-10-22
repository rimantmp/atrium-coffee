@if(session('success'))
    <x-alert variant="success">{{ session('success') }}</x-alert>
@endif

@if(session('info'))
    <x-alert variant="info">{{ session('info') }}</x-alert>
@endif

@if(session('warning'))
    <x-alert variant="warning">{{ session('warning') }}</x-alert>
@endif

@if($errors->any())
    <x-alert variant="error" title="Periksa kembali data berikut">
        <ul class="list-disc space-y-0.5 pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif
