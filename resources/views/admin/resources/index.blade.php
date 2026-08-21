<x-layouts.admin :title="$config['label']">
<div class="flex flex-wrap items-end justify-between gap-4">
    <div><p class="text-sm font-bold uppercase tracking-widest text-brand-gold">Kelola Konten</p><h1 class="mt-1 text-3xl font-black">{{ $config['label'] }}</h1></div>
    <a href="{{ route('admin.resources.create', ['resource'=>$resource, ...request()->only(array_keys($config['fields']))]) }}" class="rounded-lg bg-brand-black px-4 py-3 font-bold text-white">Tambah data</a>
</div>
@if($config['publication'] ?? false)<div class="mt-5 rounded-xl border border-amber-300 bg-amber-50 p-4 text-sm text-amber-950"><strong>Batas publikasi aman:</strong> konten hanya tampil di situs jika statusnya <em>Terbit</em>, waktu terbit sudah lewat, dan status verifikasinya <em>Terverifikasi</em>.</div>@endif
<form method="get" class="mt-6 flex flex-wrap gap-3 rounded-xl border bg-white p-4">
    <label class="min-w-64 flex-1"><span class="sr-only">Pencarian</span><input name="q" value="{{ request('q') }}" class="filter-input w-full" placeholder="Cari {{ strtolower($config['label']) }}"></label>
    @foreach($config['fields'] as $name => $field)@if($field['type']==='relation')<select name="{{ $name }}" class="filter-input"><option value="">Semua {{ strtolower($field['label']) }}</option>@foreach($filterOptions[$name] as $option)<option value="{{ $option->id }}" @selected((string)request($name)===(string)$option->id)>{{ $option->{$field['display']} }}</option>@endforeach</select>@endif @endforeach
    @if(isset($config['fields']['is_active']))<select name="is_active" class="filter-input"><option value="">Semua keaktifan</option><option value="1" @selected(request('is_active')==='1')>Aktif</option><option value="0" @selected(request('is_active')==='0')>Nonaktif</option></select>@endif
    @if($config['publication'] ?? false)<select name="status" class="filter-input"><option value="">Semua publikasi</option>@foreach(['draft'=>'Draf','published'=>'Terbit','archived'=>'Arsip'] as $value=>$label)<option value="{{ $value }}" @selected(request('status')===$value)>{{ $label }}</option>@endforeach</select><select name="verification_status" class="filter-input"><option value="">Semua verifikasi</option>@foreach(['unverified'=>'Belum diverifikasi','pending'=>'Menunggu','verified'=>'Terverifikasi','rejected'=>'Ditolak'] as $value=>$label)<option value="{{ $value }}" @selected(request('verification_status')===$value)>{{ $label }}</option>@endforeach</select>@endif
    <button class="rounded-lg bg-brand-gold px-4 py-2 font-bold">Filter</button><a href="{{ route('admin.resources.index', $resource) }}" class="rounded-lg border px-4 py-2">Atur ulang</a>
</form>
<div class="mt-6 overflow-x-auto rounded-xl border bg-white">
<table class="min-w-full divide-y text-sm">
    <thead class="bg-neutral-100 text-left"><tr><th class="p-4">{{ $config['label'] }}</th>@if($config['publication'] ?? false)<th class="p-4">Publikasi</th><th class="p-4">Verifikasi</th>@endif<th class="p-4">Diperbarui</th><th class="p-4 text-right">Aksi</th></tr></thead>
    <tbody class="divide-y">
    @forelse($items as $item)
    @php($title = data_get($item, $config['title']) ?: '#'.$item->id)
    <tr><td class="p-4"><p class="font-bold">{{ $title }}</p>@if(isset($item->slug))<p class="text-xs text-neutral-500">{{ $item->slug }}</p>@endif</td>
        @if($config['publication'] ?? false)<td class="p-4"><span class="rounded-full bg-neutral-100 px-3 py-1">{{ $item->status instanceof \BackedEnum ? $item->status->value : $item->status }}</span></td><td class="p-4"><span class="rounded-full {{ ($item->verification_status instanceof \BackedEnum ? $item->verification_status->value : $item->verification_status) === 'verified' ? 'bg-green-100 text-green-900' : 'bg-amber-100 text-amber-900' }} px-3 py-1">{{ $item->verification_status instanceof \BackedEnum ? $item->verification_status->value : $item->verification_status }}</span></td>@endif
        <td class="whitespace-nowrap p-4">{{ $item->updated_at?->format('d/m/Y H:i') }}</td>
        <td class="p-4"><div class="flex justify-end gap-2"><a href="{{ route('admin.resources.edit', [$resource,$item->id]) }}" class="rounded border px-3 py-2 font-bold">Edit</a><form x-data method="post" action="{{ route('admin.resources.destroy', [$resource,$item->id]) }}" @submit.prevent="if(confirm('Hapus data ini? Berkas terkait juga akan dihapus.')) $el.submit()">@csrf @method('DELETE')<button class="rounded border border-red-300 px-3 py-2 font-bold text-red-800">Hapus</button></form></div></td>
    </tr>
    @empty<tr><td colspan="5" class="p-12 text-center text-neutral-500">Belum ada data yang sesuai.</td></tr>@endforelse
    </tbody>
</table>
</div>
<div class="mt-6">{{ $items->links() }}</div>
</x-layouts.admin>
