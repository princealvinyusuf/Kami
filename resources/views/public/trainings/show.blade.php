@php
    $schemas = [[
        '@context' => 'https://schema.org',
        '@type' => 'Course',
        'name' => $training->title,
        'description' => $training->summary ?: $training->description,
        'url' => url()->current(),
    ]];
    foreach ($training->schedules as $schedule) {
        $schemas[] = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $training->title,
            'startDate' => $schedule->starts_at->toAtomString(),
            'endDate' => $schedule->ends_at?->toAtomString(),
            'eventStatus' => 'https://schema.org/EventScheduled',
            'location' => $schedule->location ? ['@type' => 'Place', 'name' => $schedule->location] : null,
            'url' => url()->current(),
        ]);
    }
@endphp
<x-layouts.public title="{{ $training->title }} — {{ config('app.name') }}" :description="$training->summary" :image="$training->cover_path ? Storage::disk('public')->url($training->cover_path) : null" :structured-data="$schemas">
<section class="page-hero"><div class="mx-auto max-w-7xl"><x-breadcrumb :items="['Beranda'=>route('home'),'Pelatihan'=>route('trainings.index'),$training->title]" /><p class="mt-8 font-bold uppercase tracking-widest text-brand-gold">{{ $training->category?->name }}</p><h1 class="mt-3 max-w-4xl text-5xl font-black">{{ $training->title }}</h1>@if($training->summary)<p class="mt-5 max-w-2xl text-lg text-white/70">{{ $training->summary }}</p>@endif<div class="mt-7 flex flex-wrap gap-2 text-sm">@foreach(collect([$training->level,$training->method,$training->duration,$training->location])->filter() as $meta)<span class="rounded-full border border-white/20 px-4 py-2">{{ $meta }}</span>@endforeach</div></div></section>
<section class="page-shell py-16"><div class="grid gap-12 lg:grid-cols-[1fr_22rem]"><article class="space-y-12">@if($training->description)<div><h2 class="text-3xl font-black">Tentang program</h2><div class="prose-copy mt-5 whitespace-pre-line">{{ $training->description }}</div></div>@endif
@foreach(['objectives'=>'Tujuan','curriculum'=>'Kurikulum','benefits'=>'Manfaat','requirements'=>'Persyaratan'] as $field=>$label)@if($training->{$field})<section><h2 class="text-2xl font-black">{{ $label }}</h2><ul class="detail-list">@foreach($training->{$field} as $row)<li>{{ $row }}</li>@endforeach</ul></section>@endif @endforeach
@if($training->trainers->isNotEmpty())<section><h2 class="text-2xl font-black">Pelatih</h2><div class="mt-5 grid gap-5 sm:grid-cols-2">@foreach($training->trainers as $item)<x-trainer-card :item="$item" />@endforeach</div></section>@endif
@if($training->faqs->isNotEmpty())<section><h2 class="text-2xl font-black">Pertanyaan umum</h2><div class="mt-5 divide-y rounded-2xl border">@foreach($training->faqs as $faq)<details class="p-5"><summary class="cursor-pointer font-bold">{{ $faq->question }}</summary><p class="mt-3 text-neutral-600">{{ $faq->answer }}</p></details>@endforeach</div></section>@endif</article>
<aside><div class="sticky top-28 overflow-hidden rounded-3xl border border-brand-gold/25 bg-white p-6 shadow-[0_24px_60px_-32px_rgba(17,17,15,.4)]"><div class="-mx-6 -mt-6 mb-6 h-2 bg-gradient-to-r from-brand-gold to-brand-gold-light"></div><p class="eyebrow">Informasi program</p><h2 class="mt-3 text-xl font-black">Jadwal mendatang</h2>@if($training->schedules->isEmpty())<p class="mt-4 text-sm leading-6 text-neutral-600">Belum ada jadwal yang dipublikasikan. Hubungi kami untuk informasi berikutnya.</p>@else<div class="mt-4 grid gap-4">@foreach($training->schedules as $schedule)<div class="border-b border-black/8 pb-4"><p class="font-bold">{{ $schedule->starts_at->translatedFormat('d M Y, H.i') }}</p>@if($schedule->location)<p class="mt-1 text-sm text-neutral-600">{{ $schedule->location }}</p>@endif</div>@endforeach</div>@endif<x-button href="{{ route('registrations.create', ['type'=>'training','slug'=>$training->slug]) }}" class="mt-6 w-full">Daftar pelatihan</x-button>@if($wa = app(\App\Support\WhatsApp::class)->url($training->title, url()->current()))<a href="{{ $wa }}" target="_blank" rel="noopener noreferrer" class="mt-4 block text-center text-sm font-bold text-green-800 underline">Tanya melalui WhatsApp</a>@endif</div></aside></div></section>
@if($related->isNotEmpty())<section class="bg-neutral-100 py-16"><div class="page-shell"><h2 class="text-3xl font-black">Program terkait</h2><div class="mt-8 grid gap-6 md:grid-cols-3">@foreach($related as $item)<x-training-card :item="$item" />@endforeach</div></div></section>@endif
</x-layouts.public>
