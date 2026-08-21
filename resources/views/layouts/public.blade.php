<!DOCTYPE html>
<html lang="id">
<head>
    @php
        $settings = app(\App\Support\Settings::class);
        $configuredSiteName = $settings->get('branding.site_name');
        $siteName = $configuredSiteName ?: config('app.name', 'Situs Kompetensi');
        $pageTitle = $title ?: $settings->get('seo.default_title', $siteName);
        $pageDescription = $description ?: $settings->get('seo.default_description', 'Informasi pelatihan, sertifikasi, dan layanan pengembangan kompetensi.');
        $canonicalUrl = $canonical ?: url()->current();
        $logoPath = $settings->get('branding.logo');
        $shareImage = $image ?: ($logoPath ? Storage::disk('public')->url($logoPath) : null);
        $organizationName = $settings->get('company.legal_name') ?: $configuredSiteName;
        $organization = $organizationName ? array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'EducationalOrganization',
            '@id' => rtrim(config('app.url'), '/').'/#organization',
            'name' => $organizationName,
            'url' => config('app.url'),
            'logo' => $logoPath ? Storage::disk('public')->url($logoPath) : null,
            'description' => $settings->get('company.description'),
            'email' => $settings->get('contact.email'),
            'telephone' => $settings->get('contact.phone'),
            'address' => $settings->get('contact.address'),
            'sameAs' => array_values(array_filter([
                $settings->get('social.instagram'),
                $settings->get('social.linkedin'),
                $settings->get('social.youtube'),
            ])),
        ], fn ($value) => filled($value)) : null;
        $schemas = collect($structuredData)->filter();
        if ($organization) {
            $schemas->prepend($organization);
        }
    @endphp
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="{{ $robots }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="icon" href="{{ ($favicon = $settings->get('branding.favicon')) ? Storage::disk('public')->url($favicon) : asset('favicon.ico') }}">
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="{{ $type }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    @if($shareImage)<meta property="og:image" content="{{ $shareImage }}">@endif
    <meta name="twitter:card" content="{{ $shareImage ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    @if($shareImage)<meta name="twitter:image" content="{{ $shareImage }}">@endif
    @foreach($schemas as $schema)
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    @endforeach
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ mobile: false, more: false, top: false }" @scroll.window="top = window.scrollY > 400" @keydown.escape.window="mobile = false; more = false" class="min-h-screen pb-16 md:pb-0">
<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:z-50 focus:bg-white focus:p-3">Lewati ke konten</a>
<header class="sticky top-0 z-40 border-b border-brand-gold/30 bg-brand-black text-white shadow-lg">
    <nav aria-label="Navigasi utama" class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 lg:px-8">
        <a href="{{ route('home') }}" class="text-xl font-black tracking-widest text-brand-gold">@if($logo = $settings->get('branding.logo'))<img src="{{ Storage::disk('public')->url($logo) }}" alt="{{ $siteName }}" class="h-10 max-w-48 object-contain">@else{{ $siteName }}@endif</a>
        <div class="hidden items-center gap-5 lg:flex">
            <a href="{{ route('home') }}" class="hover:text-brand-gold">Beranda</a>
            <a href="{{ route('about') }}" class="hover:text-brand-gold">Tentang</a>
            <a href="{{ route('trainings.index') }}" class="hover:text-brand-gold">Pelatihan</a>
            <a href="{{ route('certifications.index') }}" class="hover:text-brand-gold">Sertifikasi</a>
            <a href="{{ route('services.index') }}" class="hover:text-brand-gold">Layanan</a>
            <div class="relative" @click.outside="more=false">
                <button x-ref="moreButton" type="button" @click="more=!more; if (more) $nextTick(() => $refs.moreFirst.focus())" :aria-expanded="more" aria-controls="more-menu" class="hover:text-brand-gold">Lainnya <span aria-hidden="true">▾</span></button>
                <div id="more-menu" x-cloak x-show="more" x-transition class="absolute right-0 mt-3 w-52 rounded-lg border border-brand-gold/30 bg-brand-black p-2 shadow-xl">
                    <a x-ref="moreFirst" href="{{ route('trainers.index') }}" class="nav-drop">Pelatih</a><a href="{{ route('partners') }}" class="nav-drop">Mitra</a><a href="{{ route('portfolio') }}" class="nav-drop">Portofolio</a><a href="{{ route('apprenticeship') }}" class="nav-drop">Pemagangan</a><a href="{{ route('consulting') }}" class="nav-drop">Konsultan</a><a href="{{ route('articles.index') }}" class="nav-drop">Artikel</a>
                </div>
            </div>
            <a href="{{ route('search') }}" aria-label="Pencarian" class="hover:text-brand-gold">Cari</a>
            <x-button href="{{ route('contact') }}" class="!px-4 !py-2">Hubungi kami</x-button>
        </div>
        <button x-ref="mobileButton" type="button" @click="mobile=!mobile; if (mobile) $nextTick(() => $refs.mobileFirst.focus())" :aria-expanded="mobile" aria-controls="mobile-menu" class="rounded p-2 lg:hidden"><span class="sr-only" x-text="mobile ? 'Tutup menu' : 'Buka menu'"></span><span aria-hidden="true">☰</span></button>
    </nav>
    <div id="mobile-menu" x-cloak x-show="mobile" x-transition class="max-h-[75vh] overflow-y-auto border-t border-white/10 px-4 py-3 lg:hidden">
        @foreach(['home'=>'Beranda','about'=>'Tentang Kami','trainings.index'=>'Pelatihan','certifications.index'=>'Sertifikasi','services.index'=>'Layanan','trainers.index'=>'Pelatih','partners'=>'Mitra','portfolio'=>'Portofolio','apprenticeship'=>'Pemagangan','consulting'=>'Konsultan','articles.index'=>'Artikel','search'=>'Pencarian','contact'=>'Kontak'] as $route => $label)<a @if($loop->first) x-ref="mobileFirst" @endif href="{{ route($route) }}" class="block py-2">{{ $label }}</a>@endforeach
    </div>
</header>
<main id="main">{{ $slot }}</main>
<footer class="mt-16 border-t-4 border-brand-gold bg-brand-black text-white"><div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 md:grid-cols-3"><div><p class="text-xl font-black tracking-widest text-brand-gold">{{ $siteName }}</p><p class="mt-3 text-sm text-white/70">{{ $settings->get('footer.description', 'Informasi pengembangan kompetensi disajikan secara jelas dan bertanggung jawab.') }}</p></div><div><p class="font-bold">Jelajahi</p><div class="mt-3 grid gap-2 text-sm text-white/70"><a href="{{ route('trainings.index') }}">Program Pelatihan</a><a href="{{ route('certifications.index') }}">Sertifikasi</a><a href="{{ route('services.index') }}">Layanan</a><a href="{{ route('articles.index') }}">Artikel</a></div></div><div><p class="font-bold">Informasi</p><div class="mt-3 grid gap-2 text-sm text-white/70"><a href="{{ route('about') }}">Tentang Kami</a><a href="{{ route('partners') }}">Mitra</a><a href="{{ route('contact') }}">Kontak</a><a href="{{ route('sitemap') }}">Peta situs</a></div></div></div><div class="border-t border-white/10 px-4 py-5 text-center text-xs text-white/60">{{ $settings->get('footer.copyright', '© '.date('Y').' '.$siteName) }}</div></footer>
@php($whatsappUrl = app(\App\Support\WhatsApp::class)->url($title ?? null, request()->url()))
<div class="fixed inset-x-0 bottom-0 z-40 flex border-t border-brand-gold bg-brand-black p-2 md:hidden"><a href="{{ $whatsappUrl ?: route('contact') }}" @if($whatsappUrl) target="_blank" rel="noopener noreferrer" @endif class="flex-1 rounded bg-brand-gold px-4 py-3 text-center font-bold text-black">Konsultasikan kebutuhan</a></div>
@if($whatsappUrl)<a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Hubungi melalui WhatsApp" class="fixed bottom-20 left-5 rounded-full bg-green-700 px-4 py-3 font-bold text-white shadow-lg md:bottom-5">WhatsApp</a>@endif
<button x-cloak x-show="top" @click="window.scrollTo({top:0,behavior:'smooth'})" type="button" aria-label="Kembali ke atas" class="fixed bottom-20 right-5 rounded-full bg-brand-gold p-3 font-bold text-black shadow-lg md:bottom-5">↑</button>
</body>
</html>
