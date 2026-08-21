<x-layouts.public title="Kontak — {{ config('app.name') }}">
<section class="page-hero"><div class="mx-auto max-w-7xl"><x-breadcrumb :items="['Beranda'=>route('home'),'Kontak']" /><h1 class="mt-8 text-5xl font-black">Mari mulai percakapan</h1><p class="mt-4 max-w-2xl text-white/70">Sampaikan tujuan, konteks, dan kebutuhan Anda. Kami akan merespons berdasarkan informasi yang tersedia.</p></div></section>
<section class="page-shell py-16 md:py-20">
    <div class="grid items-start gap-8 lg:grid-cols-[.7fr_1.3fr]">
        <aside class="rounded-3xl bg-brand-black p-7 text-white lg:sticky lg:top-28">
            <p class="eyebrow text-brand-gold-light">Sebelum mengirim</p>
            <h2 class="mt-4 text-3xl font-black tracking-tight">Bantu kami memahami konteksmu.</h2>
            <ul class="mt-6 grid gap-4 text-sm leading-6 text-white/65">
                <li class="flex gap-3"><span class="text-brand-gold-light" aria-hidden="true">01</span> Ceritakan tujuan yang ingin dicapai.</li>
                <li class="flex gap-3"><span class="text-brand-gold-light" aria-hidden="true">02</span> Sertakan kebutuhan waktu atau metode bila ada.</li>
                <li class="flex gap-3"><span class="text-brand-gold-light" aria-hidden="true">03</span> Hindari mengirim data pribadi sensitif.</li>
            </ul>
        </aside>
        <div>@if(session('success'))<div role="status" class="mb-6 rounded-xl bg-green-100 p-4 text-green-900">{{ session('success') }}</div>@endif @if(session('error'))<div role="alert" class="mb-6 rounded-xl bg-red-100 p-4 text-red-900">{{ session('error') }}</div>@endif
            <form action="{{ route('contact.store') }}" method="post" class="panel relative grid gap-6 p-6 md:p-10">@csrf
                <div class="absolute -left-[9999px]" aria-hidden="true"><label for="website">Website</label><input id="website" name="website" tabindex="-1" autocomplete="off"></div>
                <div class="grid gap-6 md:grid-cols-2"><x-form-input name="name" label="Nama" required /><x-form-input name="email" label="Email" type="email" required /><x-form-input name="phone" label="Nomor telepon (opsional)" /><x-form-input name="subject" label="Topik (opsional)" :value="request('subject')" /></div>
                <x-form-textarea name="message" label="Pesan" rows="7" required />
                <div class="flex flex-col gap-4 border-t border-black/8 pt-6 sm:flex-row sm:items-center sm:justify-between"><p class="text-xs text-neutral-500">Data digunakan untuk menindaklanjuti pesan ini.</p><x-button type="submit">Kirim pesan <span aria-hidden="true">→</span></x-button></div>
            </form>
        </div>
    </div>
</section>
</x-layouts.public>
