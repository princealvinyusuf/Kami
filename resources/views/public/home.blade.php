<x-layouts.public title="{{ config('app.name', 'KAMI') }} — Pengembangan Kompetensi">
@php($settings = app(\App\Support\Settings::class))
<section class="relative isolate overflow-hidden bg-brand-black py-16 text-white sm:py-20 lg:py-28">
    <div class="surface-grid absolute inset-0 -z-10 opacity-60"></div>
    <div class="absolute -right-24 top-10 -z-10 size-96 rounded-full bg-brand-gold/20 blur-3xl"></div>
    <div class="page-shell grid items-center gap-14 lg:grid-cols-[1.08fr_.92fr]">
        <div>
            <p class="eyebrow text-brand-gold-light">{{ $settings->get('hero.eyebrow', 'Tumbuh dengan arah yang jelas') }}</p>
            <h1 class="mt-6 max-w-4xl text-[clamp(3rem,8vw,6.5rem)] font-black leading-[.88] tracking-[-.06em]">{{ $settings->get('hero.title', 'Kompetensi dibangun. Kepercayaan dijaga.') }}</h1>
            <p class="mt-7 max-w-xl text-base leading-8 text-white/65 md:text-lg">{{ $settings->get('hero.description', 'Temukan program pelatihan, skema sertifikasi, dan layanan profesional melalui informasi yang telah diverifikasi.') }}</p>
            <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                <x-button href="{{ route('trainings.index') }}">Jelajahi pelatihan <span aria-hidden="true">→</span></x-button>
                <x-button href="{{ route('contact') }}" variant="secondary">Diskusikan kebutuhan</x-button>
            </div>
            <p class="mt-7 flex items-center gap-2 text-xs font-semibold text-white/50"><span class="grid size-5 place-items-center rounded-full bg-brand-gold/20 text-brand-gold-light" aria-hidden="true">✓</span> Informasi publik melalui proses verifikasi</p>
        </div>
        <div class="relative mx-auto w-full max-w-xl lg:rotate-2">
            <div class="rounded-[2rem] border border-white/15 bg-white/8 p-4 shadow-2xl backdrop-blur sm:p-6">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <div><p class="text-xs font-bold uppercase tracking-widest text-white/40">Peta pengembangan</p><p class="mt-1 font-black">Rancang langkahmu</p></div>
                    <span class="rounded-full border border-brand-gold/40 bg-brand-gold/10 px-3 py-1 text-xs font-bold text-brand-gold-light">Terarah</span>
                </div>
                <div class="mt-6 grid gap-3">
                    @foreach([['01','Kenali tujuan','Petakan kebutuhan dan arah yang ingin dicapai.'],['02','Bangun kemampuan','Pilih proses belajar dan praktik yang relevan.'],['03','Validasi saat siap','Pertimbangkan asesmen untuk pengakuan kompetensi.']] as [$number,$label,$copy])
                        <div class="group grid grid-cols-[3rem_1fr_auto] items-center gap-3 rounded-2xl border border-white/10 bg-black/20 p-4 transition hover:border-brand-gold/40 hover:bg-white/8">
                            <span class="grid size-10 place-items-center rounded-xl bg-brand-gold/15 text-xs font-black text-brand-gold-light">{{ $number }}</span>
                            <div><p class="font-bold">{{ $label }}</p><p class="mt-1 text-xs leading-5 text-white/50">{{ $copy }}</p></div>
                            <span class="text-brand-gold-light" aria-hidden="true">↗</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 flex items-center gap-3 rounded-2xl bg-gradient-to-r from-brand-gold to-brand-gold-light p-4 text-black">
                    <svg aria-hidden="true" class="size-6 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/><circle cx="12" cy="12" r="10"/></svg>
                    <p class="text-sm font-black">Informasi ditampilkan bila data memadai</p>
                </div>
            </div>
            <div class="absolute -bottom-5 -left-3 rounded-xl border border-white/10 bg-brand-charcoal px-4 py-3 text-xs font-bold shadow-xl sm:-left-8">Belajar <span class="mx-2 text-brand-gold-light">•</span> Bertumbuh <span class="mx-2 text-brand-gold-light">•</span> Terukur</div>
        </div>
    </div>
</section>

<section class="page-shell py-20 md:py-28"><div class="grid gap-12 lg:grid-cols-[.85fr_1.15fr]"><x-section-heading eyebrow="Kepercayaan" title="Informasi yang bisa ditelusuri"><p>Kami hanya menampilkan program, pihak, dan pengalaman yang telah berstatus terbit dan terverifikasi. Bagian tanpa data yang memadai tidak ditampilkan.</p></x-section-heading><div class="grid gap-4 sm:grid-cols-2"><x-stat-card value="Terpilih" label="Konten melalui verifikasi sebelum tampil" /><x-stat-card value="Transparan" label="Jadwal, biaya, dan mitra ditampilkan bila tersedia" /><x-stat-card value="Relevan" label="Pilihan berdasarkan kebutuhan kompetensi" /><x-stat-card value="Terhubung" label="Langkah lanjut menuju konsultasi" /></div></div></section>

@if($services->isNotEmpty())<section class="bg-brand-cream py-20 md:py-28"><div class="page-shell"><x-section-heading eyebrow="Kompetensi" title="Layanan untuk langkah nyata"><p>Pilih jalur pendampingan sesuai kebutuhan individu atau organisasi.</p></x-section-heading><div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">@foreach($services as $item)<x-service-card :item="$item" />@endforeach</div></div></section>@endif

<section class="page-shell py-20 md:py-28"><div class="mb-10 max-w-3xl"><p class="eyebrow">Pilih jalur</p><h2 class="mt-4 text-4xl font-black tracking-tight md:text-6xl">Belajar dan pengakuan kompetensi adalah proses berbeda.</h2></div><div class="grid overflow-hidden rounded-[2rem] border border-black/10 lg:grid-cols-2"><div class="bg-gradient-to-br from-brand-gold-light to-[#c89b2d] p-8 md:p-12"><p class="text-xs font-black uppercase tracking-[.2em]">01 · Pelatihan</p><h3 class="mt-5 text-3xl font-black">Membangun pengetahuan dan keterampilan</h3><p class="mt-5 leading-7 text-black/70">Pelatihan berfokus pada proses belajar dan praktik. Hasilnya mendukung kesiapan kerja, tetapi bukan pengakuan kompetensi formal.</p></div><div class="relative bg-brand-black p-8 text-white md:p-12"><div class="surface-grid absolute inset-0 opacity-30"></div><div class="relative"><p class="text-xs font-black uppercase tracking-[.2em] text-brand-gold-light">02 · Sertifikasi</p><h3 class="mt-5 text-3xl font-black">Mengukur kompetensi melalui asesmen</h3><p class="mt-5 leading-7 text-white/65">Sertifikasi menilai bukti kompetensi terhadap skema. Bila melibatkan LSP mitra, identitas LSP ditampilkan secara jelas; KAMI tidak diposisikan sebagai LSP kecuali ada data resmi yang menyatakannya.</p></div></div></div></section>

@if($trainingSchedules->isNotEmpty() || $certificationSchedules->isNotEmpty())
<section class="relative overflow-hidden bg-brand-cream py-20 md:py-28">
    <div class="pointer-events-none absolute -right-32 top-0 size-96 rounded-full bg-brand-gold-light/20 blur-3xl"></div>
    <div class="page-shell">
        <div class="relative flex flex-wrap items-end justify-between gap-6">
            <x-section-heading eyebrow="Agenda mendatang" title="Jadwal terdekat">
                <p>Pilih jadwal pelatihan atau sertifikasi yang sesuai dengan rencana pengembangan kompetensimu.</p>
            </x-section-heading>
            <p class="hidden items-center gap-2 rounded-full border border-brand-gold/20 bg-white/60 px-4 py-2 text-xs font-bold text-neutral-600 sm:inline-flex">
                <span class="size-2 rounded-full bg-brand-gold"></span>
                Diperbarui sesuai ketersediaan
            </p>
        </div>

        <div class="relative mt-12 grid gap-6 lg:grid-cols-2">
            @if($trainingSchedules->isNotEmpty())
            <div class="schedule-column">
                <div class="schedule-column__header">
                    <div>
                        <p class="eyebrow">Belajar</p>
                        <h3 class="mt-2 text-2xl font-black">Jadwal Pelatihan</h3>
                    </div>
                    <a href="{{ route('trainings.index') }}" class="schedule-column__link">Semua program <span aria-hidden="true">→</span></a>
                </div>
                <div class="grid gap-4">
                    @foreach($trainingSchedules as $schedule)
                        <x-schedule-card :schedule="$schedule" type="training" />
                    @endforeach
                </div>
            </div>
            @endif

            @if($certificationSchedules->isNotEmpty())
            <div class="schedule-column">
                <div class="schedule-column__header">
                    <div>
                        <p class="eyebrow">Asesmen</p>
                        <h3 class="mt-2 text-2xl font-black">Jadwal Sertifikasi</h3>
                    </div>
                    <a href="{{ route('certifications.index') }}" class="schedule-column__link">Semua skema <span aria-hidden="true">→</span></a>
                </div>
                <div class="grid gap-4">
                    @foreach($certificationSchedules as $schedule)
                        <x-schedule-card :schedule="$schedule" type="certification" />
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

@if($trainings->isNotEmpty())<section class="bg-neutral-100 py-20"><div class="page-shell"><div class="flex flex-wrap items-end justify-between gap-5"><x-section-heading eyebrow="Pelatihan" title="Program untuk bertumbuh" /><x-button href="{{ route('trainings.index') }}" variant="secondary">Semua program</x-button></div><div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">@foreach($trainings as $item)<x-training-card :item="$item" />@endforeach</div></div></section>@endif

@if($certifications->isNotEmpty())<section class="bg-brand-black py-20 text-white"><div class="page-shell"><div class="flex flex-wrap items-end justify-between gap-5"><x-section-heading eyebrow="Sertifikasi" title="Buktikan kompetensi" class="[&_div]:!text-white/60" /><x-button href="{{ route('certifications.index') }}" variant="secondary">Semua skema</x-button></div><div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">@foreach($certifications as $item)<x-certification-card :item="$item" />@endforeach</div></div></section>@endif

<section class="page-shell py-20"><div class="relative overflow-hidden rounded-[2rem] bg-brand-black p-8 text-white md:p-16"><div class="surface-grid absolute inset-0 opacity-40"></div><div class="absolute -right-20 -top-20 size-72 rounded-full bg-brand-gold/20 blur-3xl"></div><div class="relative"><p class="eyebrow text-brand-gold-light">Karier</p><h2 class="mt-5 max-w-4xl text-4xl font-black tracking-tight md:text-6xl">Masa depan tidak datang dengan peta. Mulai susun arahmu.</h2><p class="mt-6 max-w-2xl leading-8 text-white/65">Mulai dari pemetaan kebutuhan, pilih proses belajar, lalu pertimbangkan asesmen kompetensi ketika sudah siap.</p><x-button href="{{ route('contact') }}" class="mt-8">Mulai percakapan <span aria-hidden="true">→</span></x-button></div></div></section>

@if($testimonials->isNotEmpty())<section class="bg-neutral-100 py-20"><div class="page-shell"><x-section-heading eyebrow="Pengalaman terverifikasi" title="Cerita peserta" /><div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">@foreach($testimonials as $item)<x-testimonial-card :item="$item" />@endforeach</div></div></section>@endif
@if($partners->isNotEmpty())<section class="page-shell py-20"><x-section-heading eyebrow="Kolaborasi" title="Mitra terverifikasi" /><div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">@foreach($partners as $item)<x-partner-card :item="$item" />@endforeach</div></section>@endif
@if($articles->isNotEmpty())<section class="bg-neutral-100 py-20"><div class="page-shell"><x-section-heading eyebrow="Wawasan" title="Artikel terbaru" /><div class="mt-10 grid gap-6 md:grid-cols-3">@foreach($articles as $item)<x-article-card :item="$item" />@endforeach</div></div></section>@endif
</x-layouts.public>
