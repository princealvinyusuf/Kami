<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin' }} · {{ config('app.name', 'KAMI') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body x-data="{ sidebar:false }" @keydown.escape.window="sidebar=false" class="min-h-screen bg-brand-cream/60">
<div class="min-h-screen lg:grid lg:grid-cols-[17rem_1fr]">
    <div x-cloak x-show="sidebar" x-transition.opacity @click="sidebar=false" class="fixed inset-0 z-40 bg-black/55 backdrop-blur-sm lg:hidden"></div>
    <aside :class="sidebar ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-68 overflow-y-auto border-r border-white/10 bg-brand-black text-white shadow-2xl transition lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">
        <div class="flex min-h-18 items-center justify-between border-b border-white/10 px-5">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 font-black tracking-tight"><span class="grid size-9 place-items-center rounded-xl bg-brand-gold text-sm text-black">K</span><span>{{ config('app.name', 'KAMI') }} <span class="block text-[10px] uppercase tracking-[.2em] text-white/40">Workspace</span></span></a>
            <button type="button" @click="sidebar=false" class="grid size-10 place-items-center rounded-xl hover:bg-white/10 lg:hidden" aria-label="Tutup menu">×</button>
        </div>
        @php($groups = [
            'Ringkasan' => ['admin.dashboard'=>'Dasbor', 'admin.registrations.index'=>'Pendaftaran', 'admin.messages.index'=>'Pesan Kontak'],
            'Pelatihan' => ['training-categories'=>'Kategori', 'training-programs'=>'Program', 'training-schedules'=>'Jadwal', 'trainers'=>'Pelatih'],
            'Sertifikasi' => ['certification-categories'=>'Kategori', 'certification-schemes'=>'Skema', 'certification-schedules'=>'Jadwal', 'competency-units'=>'Unit Kompetensi'],
            'Organisasi' => ['team'=>'Tim', 'partners'=>'Mitra', 'portfolios'=>'Portofolio', 'services'=>'Layanan', 'testimonials'=>'Testimoni'],
            'Publikasi' => ['article-categories'=>'Kategori Artikel', 'articles'=>'Artikel', 'faqs'=>'FAQ', 'legal-documents'=>'Dokumen Legal'],
        ])
        <nav class="space-y-6 p-4" aria-label="Navigasi admin">
            @foreach($groups as $group => $links)
                <div><p class="px-3 text-[10px] font-black uppercase tracking-[.2em] text-white/35">{{ $group }}</p><div class="mt-2 space-y-1">
                @foreach($links as $routeOrResource => $label)
                    @php($named = str_starts_with($routeOrResource, 'admin.'))
                    <a href="{{ $named ? route($routeOrResource) : route('admin.resources.index', $routeOrResource) }}"
                       class="block rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ $named ? (request()->routeIs($routeOrResource) ? 'bg-brand-gold text-black shadow-lg' : 'text-white/65 hover:bg-white/8 hover:text-white') : (request()->route('resource') === $routeOrResource ? 'bg-brand-gold text-black shadow-lg' : 'text-white/65 hover:bg-white/8 hover:text-white') }}">{{ $label }}</a>
                @endforeach
                </div></div>
            @endforeach
            <a href="{{ route('admin.settings.edit') }}" class="block rounded-xl px-3 py-2.5 text-sm font-semibold {{ request()->routeIs('admin.settings.*') ? 'bg-brand-gold text-black' : 'text-white/65 hover:bg-white/8 hover:text-white' }}">Pengaturan Situs</a>
        </nav>
    </aside>
    <div class="min-w-0">
        <header class="sticky top-0 z-30 flex min-h-18 items-center justify-between border-b border-black/8 bg-brand-white/90 px-4 backdrop-blur-xl lg:px-8">
            <button type="button" @click="sidebar=true" class="grid size-10 place-items-center rounded-xl border border-black/10 bg-white lg:hidden" aria-label="Buka menu">☰</button>
            <div class="hidden sm:block"><p class="text-xs font-semibold text-neutral-500">Workspace administrator</p><p class="text-sm font-black">{{ auth()->user()->name }}</p></div>
            <div class="flex items-center gap-2"><a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="rounded-xl px-3 py-2 text-sm font-bold text-neutral-600 hover:bg-black/5">Lihat situs ↗</a><form method="POST" action="{{ route('logout') }}">@csrf<button class="rounded-xl border border-black/10 bg-white px-3 py-2 text-sm font-bold hover:border-red-200 hover:text-red-700">Keluar</button></form></div>
        </header>
        <main class="mx-auto max-w-[100rem] p-4 sm:p-6 lg:p-8">{{ $slot }}</main>
    </div>
</div>
@if(session('success'))<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)" role="status" class="fixed bottom-5 right-5 z-50 max-w-sm rounded-xl bg-green-800 px-5 py-4 font-bold text-white shadow-xl">{{ session('success') }}</div>@endif
@if(session('error'))<div role="alert" class="fixed bottom-5 right-5 z-50 max-w-sm rounded-xl bg-red-800 px-5 py-4 font-bold text-white shadow-xl">{{ session('error') }}</div>@endif
</body></html>
