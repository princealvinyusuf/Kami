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
<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-lg focus:bg-white focus:px-4 focus:py-3 focus:font-bold focus:shadow-xl">Lewati ke konten</a>
<header class="sticky top-0 z-40 border-b border-white/10 bg-brand-black/92 text-white shadow-[0_8px_30px_-20px_rgba(0,0,0,.8)] backdrop-blur-xl">
    <nav aria-label="Navigasi utama" class="page-shell flex min-h-18 items-center justify-between gap-5">
        <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3 text-lg font-black tracking-tight text-white">
            @if($logo = $settings->get('branding.logo'))
                <img src="{{ Storage::disk('public')->url($logo) }}" alt="{{ $siteName }}" class="h-10 max-w-44 object-contain">
            @else
                <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-brand-gold-light to-brand-gold text-sm text-black">K</span>
                <span class="truncate">{{ $siteName }}</span>
            @endif
        </a>
        <div class="hidden items-center gap-1 lg:flex">
            @foreach(['home'=>'Beranda','about'=>'Tentang','trainings.*'=>'Pelatihan','certifications.*'=>'Sertifikasi','services.*'=>'Layanan'] as $routeName => $label)
                <a href="{{ route(str_replace('.*', '.index', $routeName)) }}" @class(['nav-link', 'nav-link-active' => request()->routeIs($routeName)])>{{ $label }}</a>
            @endforeach
            <div class="relative" @click.outside="more=false">
                <button x-ref="moreButton" type="button" @click="more=!more; if (more) $nextTick(() => $refs.moreFirst.focus())" :aria-expanded="more" aria-controls="more-menu" class="nav-link inline-flex items-center gap-1">
                    Jelajahi
                    <svg aria-hidden="true" class="size-4 transition" :class="more && 'rotate-180'" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.2 7.2a.75.75 0 0 1 1.06 0L10 10.94l3.74-3.74a.75.75 0 1 1 1.06 1.06l-4.27 4.27a.75.75 0 0 1-1.06 0L5.2 8.26a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                </button>
                <div id="more-menu" x-cloak x-show="more" x-transition.origin.top.right class="absolute right-0 mt-3 w-64 rounded-2xl border border-white/10 bg-brand-charcoal/98 p-2 shadow-2xl backdrop-blur-xl">
                    @foreach(['trainers.index'=>'Pelatih','partners'=>'Mitra','portfolio'=>'Portofolio','apprenticeship'=>'Pemagangan','consulting'=>'Konsultan','articles.index'=>'Artikel'] as $route => $label)
                        <a @if($loop->first) x-ref="moreFirst" @endif href="{{ route($route) }}" class="nav-drop">{{ $label }} <span aria-hidden="true">↗</span></a>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('search') }}" aria-label="Pencarian" class="nav-link grid size-10 place-items-center p-0">
                <svg aria-hidden="true" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
            </a>
            <x-button href="{{ route('contact') }}" class="ml-2 !px-4 !py-2.5">Mulai diskusi</x-button>
        </div>
        <button x-ref="mobileButton" type="button" @click="mobile=!mobile; if (mobile) $nextTick(() => $refs.mobileFirst.focus())" :aria-expanded="mobile" aria-controls="mobile-menu" class="grid size-11 place-items-center rounded-xl border border-white/15 bg-white/5 lg:hidden">
            <span class="sr-only" x-text="mobile ? 'Tutup menu' : 'Buka menu'"></span>
            <svg aria-hidden="true" class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path x-show="!mobile" d="M4 7h16M4 12h16M4 17h16"/><path x-cloak x-show="mobile" d="m6 6 12 12M18 6 6 18"/></svg>
        </button>
    </nav>
    <div id="mobile-menu" x-cloak x-show="mobile" x-transition.origin.top class="max-h-[calc(100vh-4.5rem)] overflow-y-auto border-t border-white/10 bg-brand-black px-4 py-4 lg:hidden">
        <div class="mx-auto grid max-w-7xl gap-1">
            @foreach(['home'=>'Beranda','about'=>'Tentang Kami','trainings.index'=>'Pelatihan','certifications.index'=>'Sertifikasi','services.index'=>'Layanan','trainers.index'=>'Pelatih','partners'=>'Mitra','portfolio'=>'Portofolio','apprenticeship'=>'Pemagangan','consulting'=>'Konsultan','articles.index'=>'Artikel','search'=>'Pencarian'] as $route => $label)
                <a @if($loop->first) x-ref="mobileFirst" @endif href="{{ route($route) }}" class="flex min-h-11 items-center justify-between rounded-xl px-4 py-3 font-semibold text-white/80 hover:bg-white/8 hover:text-white">{{ $label }} <span aria-hidden="true">→</span></a>
            @endforeach
            <x-button href="{{ route('contact') }}" class="mt-3 w-full">Hubungi kami</x-button>
        </div>
    </div>
</header>
<main id="main">{{ $slot }}</main>
<footer class="relative mt-20 overflow-hidden border-t border-white/10 bg-brand-black text-white">
    <div class="surface-grid absolute inset-0 opacity-30"></div>
    <div class="page-shell relative py-14 md:py-20">
        <div class="grid gap-12 lg:grid-cols-[1.5fr_1fr_1fr]">
            <div>
                <p class="text-2xl font-black tracking-tight text-brand-gold-light">{{ $siteName }}</p>
                <p class="mt-4 max-w-md text-sm leading-7 text-white/60">{{ $settings->get('footer.description', 'Informasi pengembangan kompetensi disajikan secara jelas dan bertanggung jawab.') }}</p>
                <x-button href="{{ route('contact') }}" variant="secondary" class="mt-7">Rencanakan langkah berikutnya</x-button>
            </div>
            @foreach(['Jelajahi'=>['trainings.index'=>'Program Pelatihan','certifications.index'=>'Sertifikasi','services.index'=>'Layanan','articles.index'=>'Artikel'],'Informasi'=>['about'=>'Tentang Kami','partners'=>'Mitra','contact'=>'Kontak','sitemap'=>'Peta situs']] as $heading => $links)
                <div><p class="text-xs font-black uppercase tracking-[.2em] text-white/40">{{ $heading }}</p><div class="mt-5 grid gap-3 text-sm">@foreach($links as $route => $label)<a href="{{ route($route) }}" class="w-fit text-white/70 transition hover:text-brand-gold-light">{{ $label }}</a>@endforeach</div></div>
            @endforeach
        </div>
    </div>
    <div class="relative border-t border-white/10"><div class="page-shell flex flex-col gap-2 py-5 text-xs text-white/45 sm:flex-row sm:items-center sm:justify-between"><p>{{ $settings->get('footer.copyright', '© '.date('Y').' '.$siteName) }}</p><p>Informasi jelas. Pilihan lebih percaya diri.</p></div></div>
</footer>
@php($whatsappUrl = app(\App\Support\WhatsApp::class)->url($title ?? null, request()->url()))
<div class="fixed inset-x-0 bottom-0 z-40 flex border-t border-white/10 bg-brand-black/95 p-2 backdrop-blur md:hidden"><a href="{{ $whatsappUrl ?: route('contact') }}" @if($whatsappUrl) target="_blank" rel="noopener noreferrer" @endif class="flex-1 rounded-xl bg-gradient-to-r from-brand-gold to-brand-gold-light px-4 py-3 text-center text-sm font-black text-black">Konsultasikan kebutuhan</a></div>
@if($whatsappUrl)<a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Hubungi melalui WhatsApp" class="fixed bottom-20 left-4 z-30 rounded-full bg-green-700 px-4 py-3 text-sm font-bold text-white shadow-xl transition hover:bg-green-800 md:bottom-5">WhatsApp</a>@endif
<button x-cloak x-show="top" x-transition @click="window.scrollTo({top:0,behavior:'smooth'})" type="button" aria-label="Kembali ke atas" class="fixed bottom-20 right-4 z-30 grid size-11 place-items-center rounded-full bg-brand-gold font-bold text-black shadow-xl md:bottom-5">↑</button>
</body>
</html>
