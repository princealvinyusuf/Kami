@php
    $schemas = [];
    foreach ($certification->schedules as $schedule) {
        $schemas[] = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $certification->title,
            'startDate' => $schedule->starts_at->toAtomString(),
            'endDate' => $schedule->ends_at?->toAtomString(),
            'eventStatus' => 'https://schema.org/EventScheduled',
            'location' => $schedule->location ? ['@type' => 'Place', 'name' => $schedule->location] : null,
            'url' => url()->current(),
        ]);
    }
@endphp
<x-layouts.public title="{{ $certification->title }} — {{ config('app.name') }}" :description="$certification->summary" :image="$certification->cover_path ? Storage::disk('public')->url($certification->cover_path) : null" :structured-data="$schemas">
<section class="page-hero"><div class="mx-auto max-w-7xl"><x-breadcrumb :items="['Beranda'=>route('home'),'Sertifikasi'=>route('certifications.index'),$certification->title]" /><p class="mt-8 font-bold uppercase tracking-widest text-brand-gold">{{ $certification->category?->name }}</p><h1 class="mt-3 max-w-4xl text-5xl font-black">{{ $certification->title }}</h1>@if($certification->summary)<p class="mt-5 max-w-2xl text-lg text-white/70">{{ $certification->summary }}</p>@endif</div></section>
<section class="page-shell py-16"><div class="grid gap-12 lg:grid-cols-[1fr_22rem]"><article class="space-y-12">@if($certification->description)<section><h2 class="text-3xl font-black">Tentang skema</h2><div class="prose-copy mt-5 whitespace-pre-line">{{ $certification->description }}</div></section>@endif
@if($certification->partner_lsp)<section class="rounded-2xl border-l-4 border-brand-gold bg-neutral-100 p-6"><p class="text-xs font-bold uppercase tracking-widest text-brand-gold">LSP mitra</p><h2 class="mt-2 text-2xl font-black">{{ $certification->partner_lsp }}</h2><p class="mt-3 text-sm text-neutral-600">Asesmen pada skema ini melibatkan LSP mitra yang tercantum. Peran KAMI dan LSP merupakan peran yang berbeda.</p></section>@endif
@foreach(['intended_participants'=>'Peserta yang dituju','requirements'=>'Persyaratan','process'=>'Proses'] as $field=>$label)@if($certification->{$field})<section><h2 class="text-2xl font-black">{{ $label }}</h2><ul class="detail-list">@foreach($certification->{$field} as $row)<li>{{ $row }}</li>@endforeach</ul></section>@endif @endforeach
@if($certification->competencyUnits->isNotEmpty())<section><h2 class="text-2xl font-black">Unit kompetensi</h2><div class="mt-5 divide-y rounded-2xl border">@foreach($certification->competencyUnits as $unit)<div class="p-5"><p class="font-black">@if($unit->code)<span class="text-brand-gold">{{ $unit->code }}</span> · @endif{{ $unit->title }}</p>@if($unit->description)<p class="mt-2 text-sm text-neutral-600">{{ $unit->description }}</p>@endif</div>@endforeach</div></section>@endif
@if($certification->faqs->isNotEmpty())<section><h2 class="text-2xl font-black">Pertanyaan umum</h2><div class="mt-5 divide-y rounded-2xl border">@foreach($certification->faqs as $faq)<details class="p-5"><summary class="cursor-pointer font-bold">{{ $faq->question }}</summary><p class="mt-3 text-neutral-600">{{ $faq->answer }}</p></details>@endforeach</div></section>@endif</article>
<aside><div class="sticky top-28 rounded-2xl border border-brand-gold/40 p-6 shadow-lg"><h2 class="text-xl font-black">Informasi asesmen</h2><dl class="mt-5 grid gap-4 text-sm">@foreach(['assessment_method'=>'Metode','validity_period'=>'Masa berlaku','location'=>'Lokasi'] as $field=>$label)@if($certification->{$field})<div><dt class="text-neutral-500">{{ $label }}</dt><dd class="font-bold">{{ $certification->{$field} }}</dd></div>@endif @endforeach</dl><h3 class="mt-7 font-black">Jadwal mendatang</h3>@if($certification->schedules->isEmpty())<p class="mt-2 text-sm text-neutral-600">Belum ada jadwal yang dipublikasikan.</p>@else@foreach($certification->schedules as $schedule)<p class="mt-3 border-t pt-3 text-sm font-bold">{{ $schedule->starts_at->translatedFormat('d M Y, H.i') }}</p>@endforeach @endif<x-button href="{{ route('registrations.create', ['type'=>'certification','slug'=>$certification->slug]) }}" class="mt-6 w-full">Daftar sertifikasi</x-button>@if($wa = app(\App\Support\WhatsApp::class)->url($certification->title, url()->current()))<a href="{{ $wa }}" target="_blank" rel="noopener noreferrer" class="mt-3 block text-center text-sm font-bold text-green-800 underline">Tanya melalui WhatsApp</a>@endif</div></aside></div></section>
@if($related->isNotEmpty())<section class="bg-neutral-100 py-16"><div class="page-shell"><h2 class="text-3xl font-black">Skema terkait</h2><div class="mt-8 grid gap-6 md:grid-cols-3">@foreach($related as $item)<x-certification-card :item="$item" />@endforeach</div></div></section>@endif
</x-layouts.public>
