<x-layouts.public title="Daftar {{ $title }} — {{ config('app.name') }}" :description="'Formulir pendaftaran '.$title">
<section class="page-hero py-12 md:py-16"><div class="mx-auto max-w-7xl"><x-breadcrumb :items="['Beranda'=>route('home'),'Pendaftaran '.$typeLabel]" /><p class="mt-8 font-bold uppercase tracking-widest text-brand-gold">Pendaftaran {{ $typeLabel }}</p><h1 class="mt-3 max-w-4xl text-4xl font-black md:text-5xl">{{ $title }}</h1><p class="mt-4 max-w-2xl text-white/70">Isi data berikut agar tim kami dapat menghubungi Anda untuk proses selanjutnya.</p></div></section>

<section class="page-shell py-10 md:py-16">
@if(session('success'))<div role="status" class="mb-8 rounded-xl border border-green-300 bg-green-50 p-5 font-bold text-green-900">{{ session('success') }}</div>@endif
@if(session('error'))<div role="alert" class="mb-8 rounded-xl border border-red-300 bg-red-50 p-5 font-bold text-red-900">{{ session('error') }}</div>@endif
@if($errors->any())<div role="alert" class="mb-8 rounded-xl border border-red-300 bg-red-50 p-5 text-red-900"><p class="font-bold">Periksa kembali data yang Anda isi.</p><ul class="mt-2 list-disc pl-5 text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

<div class="grid items-start gap-8 lg:grid-cols-[22rem_1fr]">
    <aside class="overflow-hidden rounded-3xl border border-brand-gold/30 bg-white p-6 shadow-[0_24px_60px_-32px_rgba(17,17,15,.4)] lg:sticky lg:top-28">
        <div class="-mx-6 -mt-6 mb-6 h-2 bg-gradient-to-r from-brand-gold to-brand-gold-light"></div>
        <p class="eyebrow">Ringkasan</p>
        <h2 class="mt-2 text-2xl font-black">{{ $title }}</h2>
        @if($target?->summary)<p class="mt-3 text-sm leading-6 text-neutral-600">{{ $target->summary }}</p>@endif
        <dl class="mt-5 grid gap-3 text-sm">
            <div><dt class="text-neutral-500">Jenis</dt><dd class="font-bold">{{ $typeLabel }}</dd></div>
            @if($target?->location)<div><dt class="text-neutral-500">Lokasi</dt><dd class="font-bold">{{ $target->location }}</dd></div>@endif
            @if($target?->duration)<div><dt class="text-neutral-500">Durasi</dt><dd class="font-bold">{{ $target->duration }}</dd></div>@endif
        </dl>
        <p class="mt-6 border-t pt-4 text-xs leading-5 text-neutral-500">Pengiriman formulir bukan berarti Anda telah diterima. Tim kami akan melakukan tindak lanjut terlebih dahulu.</p>
        @if($whatsappUrl)<a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="mt-5 block rounded-lg border border-green-700 px-4 py-3 text-center font-bold text-green-800">Tanya melalui WhatsApp</a>@endif
    </aside>

    <form action="{{ route('registrations.store', ['type'=>$type, 'slug'=>$target?->slug]) }}" method="post" enctype="multipart/form-data" class="panel grid gap-7 p-6 md:p-10">
        @csrf
        <div class="absolute -left-[9999px]" aria-hidden="true"><label for="website">Website</label><input id="website" name="website" tabindex="-1" autocomplete="off"></div>
        <div><p class="eyebrow">Langkah berikutnya</p><h2 class="mt-3 text-2xl font-black">Data calon peserta</h2><p class="mt-2 text-sm text-neutral-600">Kolom bertanda * wajib diisi.</p></div>
        <div class="grid gap-6 md:grid-cols-2">
            <x-form-input name="name" label="Nama lengkap *" autocomplete="name" required />
            <x-form-input name="email" label="Email *" type="email" autocomplete="email" required />
            <x-form-input name="phone" label="Nomor WhatsApp/telepon *" type="tel" autocomplete="tel" required />
            <x-form-input name="city" label="Kota/kabupaten *" autocomplete="address-level2" required />
            <x-form-input name="province" label="Provinsi *" autocomplete="address-level1" required />
            <x-form-input name="education" label="Pendidikan terakhir *" required />
            <x-form-input name="institution" label="Institusi/perusahaan *" autocomplete="organization" required />
            <x-form-input name="occupation" label="Pekerjaan/jabatan *" autocomplete="organization-title" required />
        </div>

        @if($collectSensitive)
        <div class="grid gap-6 border-t pt-7 md:grid-cols-3">
            <x-form-input name="nik" label="NIK{{ $requireSensitive ? ' *' : ' (opsional)' }}" inputmode="numeric" maxlength="16" :required="$requireSensitive" />
            <div><label for="gender" class="mb-1 block text-sm font-bold">Jenis kelamin{{ $requireSensitive ? ' *' : ' (opsional)' }}</label><select id="gender" name="gender" class="filter-input w-full" @required($requireSensitive)><option value="">Pilih</option><option value="male" @selected(old('gender')==='male')>Laki-laki</option><option value="female" @selected(old('gender')==='female')>Perempuan</option></select>@error('gender')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror</div>
            <x-form-input name="birth_date" label="Tanggal lahir{{ $requireSensitive ? ' *' : ' (opsional)' }}" type="date" :required="$requireSensitive" />
        </div>
        @endif

        <div class="grid gap-6 border-t pt-7">
            @if($schedules->isNotEmpty())
            <div><label for="schedule_id" class="mb-1 block text-sm font-bold">Jadwal yang tersedia (opsional)</label><select id="schedule_id" name="schedule_id" class="filter-input w-full"><option value="">Belum menentukan</option>@foreach($schedules as $schedule)<option value="{{ $schedule->id }}" @selected((string)old('schedule_id')===(string)$schedule->id)>{{ $schedule->starts_at->translatedFormat('d F Y, H.i') }}{{ $schedule->location ? ' — '.$schedule->location : '' }}</option>@endforeach</select>@error('schedule_id')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror</div>
            @endif
            <x-form-input name="preferred_schedule" label="Preferensi jadwal (opsional)" placeholder="Contoh: hari kerja pagi atau Maret 2027" />
            <x-form-textarea name="notes" label="Catatan atau kebutuhan khusus (opsional)" rows="5" />
            <div><label for="document" class="mb-1 block text-sm font-bold">Dokumen pendukung (opsional)</label><input id="document" name="document" type="file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2"><p class="mt-1 text-xs text-neutral-500">PDF, JPG, PNG, DOC, atau DOCX. Maksimal 5 MB kecuali dikonfigurasi lain.</p>@error('document')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror</div>
        </div>
        <p class="text-xs leading-5 text-neutral-500">Dengan mengirim formulir, Anda menyetujui penggunaan data ini untuk menindaklanjuti permintaan pendaftaran.</p>
        <x-button type="submit">Kirim pendaftaran</x-button>
    </form>
</div>
</section>
</x-layouts.public>
