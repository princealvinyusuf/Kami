<x-layouts.admin title="Pendaftaran">
<div class="flex flex-wrap items-end justify-between gap-4">
    <div><p class="text-sm font-bold uppercase tracking-widest text-brand-gold">Calon peserta</p><h1 class="mt-1 text-3xl font-black">Pendaftaran</h1></div>
    <a href="{{ route('admin.registrations.export', request()->query()) }}" class="rounded-lg bg-brand-black px-4 py-2 font-bold text-white">Ekspor CSV</a>
</div>

@if(session('success'))<div role="status" class="mt-6 rounded-lg bg-green-100 p-4 text-green-900">{{ session('success') }}</div>@endif

<form method="get" class="mt-8 grid gap-3 rounded-xl border bg-white p-4 md:grid-cols-6">
    <input class="filter-input md:col-span-2" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nama, email, telepon, institusi" aria-label="Cari pendaftaran">
    <select class="filter-input" name="type" aria-label="Jenis pendaftaran"><option value="">Semua jenis</option>@foreach(['training'=>'Pelatihan','certification'=>'Sertifikasi','consultation'=>'Konsultasi','internship'=>'Pemagangan'] as $value=>$label)<option value="{{ $value }}" @selected(($filters['type'] ?? '') === $value)>{{ $label }}</option>@endforeach</select>
    <select class="filter-input" name="status" aria-label="Status pendaftaran"><option value="">Semua status</option>@foreach(\App\Enums\RegistrationStatus::cases() as $status)<option value="{{ $status->value }}" @selected(($filters['status'] ?? '') === $status->value)>{{ $status->label() }}</option>@endforeach</select>
    <input class="filter-input" type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" aria-label="Tanggal mulai">
    <input class="filter-input" type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" aria-label="Tanggal akhir">
    <div class="flex gap-2 md:col-span-6"><button class="rounded-lg bg-brand-gold px-4 py-2 font-bold">Terapkan filter</button><a href="{{ route('admin.registrations.index') }}" class="rounded-lg border px-4 py-2">Atur ulang</a></div>
</form>

<div class="mt-6 overflow-x-auto rounded-xl border bg-white">
<table class="min-w-full divide-y text-sm">
    <thead class="bg-neutral-100 text-left"><tr><th class="p-4">Tanggal</th><th class="p-4">Pendaftar</th><th class="p-4">Jenis / Program</th><th class="p-4">Lokasi / Institusi</th><th class="p-4">Dokumen</th><th class="p-4">Status</th></tr></thead>
    <tbody class="divide-y">
    @forelse($registrations as $registration)
        <tr class="align-top">
            <td class="whitespace-nowrap p-4">{{ $registration->created_at->format('d/m/Y H:i') }}</td>
            <td class="p-4"><p class="font-bold">{{ $registration->name }}</p><p>{{ $registration->email }}</p><p>{{ $registration->phone }}</p>@if($registration->notes)<details class="mt-2"><summary class="cursor-pointer text-neutral-600">Catatan</summary><p class="mt-1 max-w-sm whitespace-pre-line">{{ $registration->notes }}</p></details>@endif</td>
            <td class="p-4"><p class="font-bold">{{ match($registration->registration_type) {'training'=>'Pelatihan','certification'=>'Sertifikasi','consultation'=>'Konsultasi','internship'=>'Pemagangan',default=>$registration->registration_type} }}</p><p>{{ $registration->registrable?->title ?? $registration->registrable?->name ?? 'Umum' }}</p>@if($registration->preferred_schedule)<p class="mt-1 text-neutral-600">{{ $registration->preferred_schedule }}</p>@endif</td>
            <td class="p-4"><p>{{ $registration->city }}, {{ $registration->province }}</p><p class="text-neutral-600">{{ $registration->institution }}</p></td>
            <td class="p-4">@if($registration->document_path)<a class="font-bold underline" href="{{ route('admin.registrations.document', $registration) }}">Unduh</a>@else—@endif</td>
            <td class="p-4"><form method="post" action="{{ route('admin.registrations.update', $registration) }}" class="flex min-w-52 gap-2">@csrf @method('PATCH')<select name="status" class="filter-input min-w-0 flex-1">@foreach(\App\Enums\RegistrationStatus::cases() as $status)<option value="{{ $status->value }}" @selected($registration->status === $status)>{{ $status->label() }}</option>@endforeach</select><button class="rounded-lg border px-3 font-bold">Simpan</button></form></td>
        </tr>
    @empty
        <tr><td colspan="6" class="p-10 text-center text-neutral-600">Tidak ada pendaftaran yang sesuai dengan filter.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
<div class="mt-6">{{ $registrations->links() }}</div>
</x-layouts.admin>
