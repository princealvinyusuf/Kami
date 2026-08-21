<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin' }} · {{ config('app.name', 'KAMI') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body x-data="{ sidebar:false }" class="min-h-screen bg-neutral-100">
<div class="min-h-screen lg:grid lg:grid-cols-[17rem_1fr]">
    <aside :class="sidebar ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-68 overflow-y-auto bg-brand-black text-white transition lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">
        <div class="flex items-center justify-between border-b border-white/10 px-5 py-5">
            <a href="{{ route('admin.dashboard') }}" class="font-black tracking-widest text-brand-gold">{{ config('app.name', 'KAMI') }} ADMIN</a>
            <button type="button" @click="sidebar=false" class="p-2 lg:hidden" aria-label="Tutup menu">×</button>
        </div>
        @php($groups = [
            'Ringkasan' => ['admin.dashboard'=>'Dasbor', 'admin.registrations.index'=>'Pendaftaran', 'admin.messages.index'=>'Pesan Kontak'],
            'Pelatihan' => ['training-categories'=>'Kategori', 'training-programs'=>'Program', 'training-schedules'=>'Jadwal', 'trainers'=>'Pelatih'],
            'Sertifikasi' => ['certification-categories'=>'Kategori', 'certification-schemes'=>'Skema', 'certification-schedules'=>'Jadwal', 'competency-units'=>'Unit Kompetensi'],
            'Organisasi' => ['team'=>'Tim', 'partners'=>'Mitra', 'portfolios'=>'Portofolio', 'services'=>'Layanan', 'testimonials'=>'Testimoni'],
            'Publikasi' => ['article-categories'=>'Kategori Artikel', 'articles'=>'Artikel', 'faqs'=>'FAQ', 'legal-documents'=>'Dokumen Legal'],
        ])
        <nav class="space-y-5 p-4" aria-label="Navigasi admin">
            @foreach($groups as $group => $links)
                <div><p class="px-3 text-xs font-bold uppercase tracking-widest text-white/40">{{ $group }}</p><div class="mt-2 space-y-1">
                @foreach($links as $routeOrResource => $label)
                    @php($named = str_starts_with($routeOrResource, 'admin.'))
                    <a href="{{ $named ? route($routeOrResource) : route('admin.resources.index', $routeOrResource) }}"
                       class="block rounded-lg px-3 py-2 text-sm {{ $named ? (request()->routeIs($routeOrResource) ? 'bg-brand-gold text-black' : 'hover:bg-white/10') : (request()->route('resource') === $routeOrResource ? 'bg-brand-gold text-black' : 'hover:bg-white/10') }}">{{ $label }}</a>
                @endforeach
                </div></div>
            @endforeach
            <a href="{{ route('admin.settings.edit') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('admin.settings.*') ? 'bg-brand-gold text-black' : 'hover:bg-white/10' }}">Pengaturan Situs</a>
        </nav>
    </aside>
    <div class="min-w-0">
        <header class="sticky top-0 z-30 flex items-center justify-between border-b bg-white px-4 py-3 shadow-sm lg:px-8">
            <button type="button" @click="sidebar=true" class="rounded border px-3 py-2 lg:hidden" aria-label="Buka menu">☰</button>
            <p class="hidden text-sm text-neutral-600 sm:block">Masuk sebagai <strong>{{ auth()->user()->name }}</strong></p>
            <div class="flex items-center gap-3"><a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="text-sm font-bold underline">Lihat situs</a><form method="POST" action="{{ route('logout') }}">@csrf<button class="rounded border px-3 py-2 text-sm font-bold">Keluar</button></form></div>
        </header>
        <main class="p-4 lg:p-8">{{ $slot }}</main>
    </div>
</div>
@if(session('success'))<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)" role="status" class="fixed bottom-5 right-5 z-50 max-w-sm rounded-xl bg-green-800 px-5 py-4 font-bold text-white shadow-xl">{{ session('success') }}</div>@endif
@if(session('error'))<div role="alert" class="fixed bottom-5 right-5 z-50 max-w-sm rounded-xl bg-red-800 px-5 py-4 font-bold text-white shadow-xl">{{ session('error') }}</div>@endif
</body></html>
