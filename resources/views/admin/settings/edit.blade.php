<x-layouts.admin title="Pengaturan Situs">
<div><p class="text-sm font-bold uppercase tracking-widest text-brand-gold">Konfigurasi</p><h1 class="mt-1 text-3xl font-black">Pengaturan Situs</h1><p class="mt-2 text-neutral-600">Nilai faktual bertanda “Perlu verifikasi” tidak boleh dianggap resmi sebelum diperiksa terhadap dokumen sumber.</p></div>
@if($errors->any())<div role="alert" class="mt-6 rounded-xl border border-red-300 bg-red-50 p-5 text-red-900"><p class="font-bold">Periksa kembali isian.</p><ul class="mt-2 list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="post" enctype="multipart/form-data" action="{{ route('admin.settings.update') }}" class="mt-6 space-y-6">@csrf @method('PUT')
@foreach(\App\Support\Settings::DEFINITIONS as $groupKey => $group)
<fieldset class="rounded-xl border bg-white p-5 md:p-7"><legend class="px-2 text-xl font-black">{{ $group['label'] }}</legend><div class="grid gap-5 md:grid-cols-2">
@foreach($group['fields'] as $key => $field)
@php($input = str_replace('.', '__', $key))
<div class="{{ $field['type']==='text' ? 'md:col-span-2' : '' }}">
@if($field['type']==='boolean')
    <input type="hidden" name="{{ $input }}" value="0"><label class="flex min-h-11 items-center gap-3 rounded-lg border p-3"><input type="checkbox" name="{{ $input }}" value="1" @checked(old($input,$values[$key] ?? false))><span class="font-bold">{{ $field['label'] }}</span></label>
@elseif($field['type']==='text')
    <label for="{{ $input }}" class="mb-1 block text-sm font-bold">{{ $field['label'] }} @if($field['verify'] ?? false)<span class="rounded bg-amber-100 px-2 py-1 text-xs text-amber-900">Perlu verifikasi</span>@endif</label><textarea id="{{ $input }}" name="{{ $input }}" rows="4" class="filter-input w-full">{{ old($input,$values[$key] ?? '') }}</textarea>
@elseif($field['type']==='image')
    <label for="{{ $input }}" class="mb-1 block text-sm font-bold">{{ $field['label'] }}</label><input id="{{ $input }}" name="{{ $input }}" type="file" accept="image/*,.ico" class="filter-input w-full">@if($values[$key] ?? null)<div class="mt-2 flex items-center gap-3"><img src="{{ Storage::disk('public')->url($values[$key]) }}" alt="" class="h-16 max-w-40 object-contain"><label class="text-sm"><input type="checkbox" name="remove__{{ $input }}" value="1"> Hapus</label></div>@endif
@else
    <label for="{{ $input }}" class="mb-1 block text-sm font-bold">{{ $field['label'] }} @if($field['verify'] ?? false)<span class="rounded bg-amber-100 px-2 py-1 text-xs text-amber-900">Perlu verifikasi</span>@endif</label><input id="{{ $input }}" name="{{ $input }}" type="{{ $field['type']==='email' ? 'email' : ($field['type']==='url' ? 'url' : ($field['type']==='integer' ? 'number' : 'text')) }}" value="{{ old($input,$values[$key] ?? '') }}" class="filter-input w-full">
@endif
@error($input)<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
</div>
@endforeach
</div></fieldset>
@endforeach
<div class="sticky bottom-4 flex justify-end"><button class="rounded-lg bg-brand-gold px-6 py-3 font-black shadow-lg">Simpan pengaturan</button></div>
</form>
</x-layouts.admin>
