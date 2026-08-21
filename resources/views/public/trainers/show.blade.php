@php
    $personSchema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $trainer->name,
        'jobTitle' => $trainer->title,
        'description' => $trainer->bio,
        'image' => $trainer->photo_path ? Storage::disk('public')->url($trainer->photo_path) : null,
        'url' => url()->current(),
        'sameAs' => array_values(array_filter([$trainer->linkedin_url, $trainer->instagram_url, $trainer->website_url])),
    ], fn ($value) => filled($value));
@endphp
<x-layouts.public title="{{ $trainer->name }} — Pelatih" :description="$trainer->bio" :image="$trainer->photo_path ? Storage::disk('public')->url($trainer->photo_path) : null" :structured-data="[$personSchema]"><section class="page-hero"><div class="mx-auto max-w-7xl"><x-breadcrumb :items="['Beranda'=>route('home'),'Pelatih'=>route('trainers.index'),$trainer->name]" /><div class="mt-8 grid items-center gap-8 md:grid-cols-[14rem_1fr]">@if($trainer->photo_path)<img src="{{ Storage::disk('public')->url($trainer->photo_path) }}" alt="Foto {{ $trainer->name }}" class="aspect-square w-full rounded-2xl object-cover">@endif<div><h1 class="text-5xl font-black">{{ $trainer->name }}</h1>@if($trainer->title)<p class="mt-3 text-xl text-brand-gold">{{ $trainer->title }}</p>@endif</div></div></div></section><section class="page-shell py-16"><div class="grid gap-12 lg:grid-cols-3"><div class="lg:col-span-2">@if($trainer->bio)<h2 class="text-3xl font-black">Profil</h2><p class="prose-copy mt-5 whitespace-pre-line">{{ $trainer->bio }}</p>@endif @if($trainer->experience)<h2 class="mt-10 text-2xl font-black">Pengalaman</h2><p class="prose-copy mt-4 whitespace-pre-line">{{ $trainer->experience }}</p>@endif</div><aside>@foreach(['expertise'=>'Keahlian','certifications'=>'Sertifikasi'] as $field=>$label)@if($trainer->{$field})<h2 class="mt-6 text-xl font-black first:mt-0">{{ $label }}</h2><ul class="detail-list">@foreach($trainer->{$field} as $row)<li>{{ $row }}</li>@endforeach</ul>@endif @endforeach</aside></div>@if($trainer->programs->isNotEmpty())<h2 class="mt-16 text-3xl font-black">Program yang diampu</h2><div class="mt-8 grid gap-6 md:grid-cols-3">@foreach($trainer->programs as $item)<x-training-card :item="$item" />@endforeach</div>@endif</section></x-layouts.public>
