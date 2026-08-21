<x-layouts.admin :title="($item->exists ? 'Edit ' : 'Tambah ').$config['label']">
<div class="flex flex-wrap items-center justify-between gap-4"><div><p class="text-sm font-bold uppercase tracking-widest text-brand-gold">Kelola Konten</p><h1 class="mt-1 text-3xl font-black">{{ $item->exists ? 'Edit' : 'Tambah' }} {{ $config['label'] }}</h1></div><a href="{{ route('admin.resources.index', $resource) }}" class="rounded-lg border bg-white px-4 py-2">Kembali</a></div>
@if($errors->any())<div role="alert" class="mt-6 rounded-xl border border-red-300 bg-red-50 p-5 text-red-900"><p class="font-bold">Periksa kembali isian berikut.</p><ul class="mt-2 list-disc pl-5 text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@if($config['publication'] ?? false)<div class="mt-6 rounded-xl border border-amber-300 bg-amber-50 p-4 text-sm"><strong>Perhatian:</strong> memilih “Terbit” saja tidak mempublikasikan konten. Konten publik mensyaratkan status Terbit + waktu terbit yang valid + Terverifikasi. Pastikan semua fakta telah diperiksa.</div>@endif
<form x-data="{ slugTouched: {{ old('slug', $item->slug ?? '') ? 'true' : 'false' }} }" method="post" enctype="multipart/form-data" action="{{ $item->exists ? route('admin.resources.update', [$resource,$item->id]) : route('admin.resources.store', $resource) }}" class="mt-6 grid gap-6 rounded-xl border bg-white p-5 md:grid-cols-2 md:p-8">
@csrf @if($item->exists) @method('PUT') @endif
@foreach($config['fields'] as $name => $field)
@php
    $raw = old($name, data_get($item, $name, request($name)));
    if ($raw instanceof \BackedEnum) $raw = $raw->value;
    if ($field['type'] === 'list' && is_array($raw)) $raw = implode("\n", $raw);
    if ($field['type'] === 'datetime-local' && $raw) $raw = $raw instanceof \DateTimeInterface ? $raw->format('Y-m-d\TH:i') : \Illuminate\Support\Carbon::parse($raw)->format('Y-m-d\TH:i');
    if ($field['type'] === 'date' && $raw instanceof \DateTimeInterface) $raw = $raw->format('Y-m-d');
@endphp
<div class="{{ in_array($field['type'], ['textarea','list','images','multi'], true) ? 'md:col-span-2' : '' }}">
    @if($field['type'] === 'checkbox')
        <input type="hidden" name="{{ $name }}" value="0"><label class="flex min-h-11 items-center gap-3 rounded-lg border p-3"><input type="checkbox" name="{{ $name }}" value="1" @checked((bool)$raw)><span class="font-bold">{{ $field['label'] }}</span></label>
    @elseif($field['type'] === 'relation')
        <label for="{{ $name }}" class="mb-1 block text-sm font-bold">{{ $field['label'] }}</label><select id="{{ $name }}" name="{{ $name }}" class="filter-input w-full"><option value="">Pilih</option>@foreach($options[$name] as $option)<option value="{{ $option->id }}" @selected((string)$raw===(string)$option->id)>{{ $option->{$field['display']} }}</option>@endforeach</select>
    @elseif($field['type'] === 'multi')
        <fieldset><legend class="mb-2 text-sm font-bold">{{ $field['label'] }}</legend><div class="grid max-h-56 gap-2 overflow-y-auto rounded-lg border p-3 sm:grid-cols-2">@forelse($options[$name] as $option)<label class="flex items-center gap-2"><input type="checkbox" name="{{ $name }}[]" value="{{ $option->id }}" @checked(in_array($option->id, old($name, $item->exists ? $item->trainers->pluck('id')->all() : [])))>{{ $option->{$field['display']} }}</label>@empty<p class="text-sm text-neutral-500">Belum ada pilihan.</p>@endforelse</div></fieldset>
    @elseif($field['type'] === 'select')
        <label for="{{ $name }}" class="mb-1 block text-sm font-bold">{{ $field['label'] }}</label><select id="{{ $name }}" name="{{ $name }}" class="filter-input w-full">@foreach($field['options'] as $value=>$label)<option value="{{ $value }}" @selected((string)$raw===(string)$value)>{{ $label }}</option>@endforeach</select>
    @elseif(in_array($field['type'], ['textarea','list'], true))
        <label for="{{ $name }}" class="mb-1 block text-sm font-bold">{{ $field['label'] }}</label><textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $field['rows'] ?? 6 }}" class="filter-input w-full">{{ $raw }}</textarea>
    @elseif(in_array($field['type'], ['image','file'], true))
        <label for="{{ $name }}" class="mb-1 block text-sm font-bold">{{ $field['label'] }}</label><input id="{{ $name }}" name="{{ $name }}" type="file" class="filter-input w-full" @if($field['type']==='image') accept="image/jpeg,image/png,image/webp,image/x-icon" @endif>
        @if($raw)<div class="mt-2 flex items-center gap-3 text-sm">@if($field['type']==='image')<img src="{{ Storage::disk($field['disk'])->url($raw) }}" alt="" class="h-16 w-16 rounded object-cover">@else<a class="font-bold underline" href="{{ route('admin.resources.download', [$resource,$item->id,$name]) }}">Unduh berkas saat ini</a>@endif<label><input type="checkbox" name="remove_uploads[]" value="{{ $name }}"> Hapus berkas saat disimpan</label></div>@endif
    @elseif($field['type'] === 'images')
        <label for="{{ $name }}" class="mb-1 block text-sm font-bold">{{ $field['label'] }}</label><input id="{{ $name }}" name="{{ $name }}[]" type="file" multiple accept="image/jpeg,image/png,image/webp" class="filter-input w-full"><p class="mt-1 text-xs text-neutral-500">Maksimal 12 gambar, masing-masing 4 MB.</p>
        @if($item->exists && $item->images->isNotEmpty())<div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-4">@foreach($item->images as $image)<label class="rounded border p-2"><img src="{{ Storage::disk('public')->url($image->path) }}" alt="{{ $image->alt_text }}" class="aspect-square w-full object-cover"><span class="mt-2 block text-xs"><input type="checkbox" name="remove_portfolio_images[]" value="{{ $image->id }}"> Hapus</span></label>@endforeach</div>@endif
    @else
        <label for="{{ $name }}" class="mb-1 block text-sm font-bold">{{ $field['label'] }}</label><input id="{{ $name }}" name="{{ $name }}" type="{{ $field['type']==='slug' ? 'text' : $field['type'] }}" value="{{ $raw }}" class="filter-input w-full" @if($field['type']==='slug') @input="slugTouched=true" @endif>
        @if($field['type']==='slug')<p class="mt-1 text-xs text-neutral-500">Dibuat otomatis jika dikosongkan; tetap dapat diedit dan harus unik.</p>@endif
    @endif
    @error($name)<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
</div>
@endforeach
<div class="flex flex-wrap gap-3 border-t pt-5 md:col-span-2"><button class="rounded-lg bg-brand-gold px-5 py-3 font-bold">Simpan</button><a href="{{ route('admin.resources.index', $resource) }}" class="rounded-lg border px-5 py-3">Batal</a>
@if($item->exists && $resource==='training-programs')<a href="{{ route('admin.resources.index',['resource'=>'training-schedules','training_program_id'=>$item->id]) }}" class="rounded-lg border px-5 py-3">Kelola jadwal program</a>@endif
@if($item->exists && $resource==='certification-schemes')<a href="{{ route('admin.resources.index',['resource'=>'certification-schedules','certification_scheme_id'=>$item->id]) }}" class="rounded-lg border px-5 py-3">Kelola jadwal</a><a href="{{ route('admin.resources.index',['resource'=>'competency-units','certification_scheme_id'=>$item->id]) }}" class="rounded-lg border px-5 py-3">Kelola unit kompetensi</a>@endif
</div>
</form>
</x-layouts.admin>
