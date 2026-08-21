@props(['item'])
<article class="lift-card group relative flex h-full flex-col overflow-hidden rounded-3xl border border-white/10 bg-brand-black p-6 text-white shadow-lg">
<div class="surface-grid pointer-events-none absolute inset-0 opacity-25"></div><div class="relative flex h-full flex-col"><p class="eyebrow text-brand-gold-light">{{ $item->category?->name ?? 'Sertifikasi' }}</p><h3 class="mt-4 text-xl font-black tracking-tight"><a href="{{ route('certifications.show', $item) }}" class="after:absolute after:inset-0">{{ $item->title }}</a></h3>@if($item->summary)<p class="mt-3 line-clamp-3 text-sm leading-6 text-white/65">{{ $item->summary }}</p>@endif
@if($item->partner_lsp)<p class="mt-4 text-xs text-white/60">LSP mitra: <span class="text-white">{{ $item->partner_lsp }}</span></p>@endif
<p class="mt-auto pt-6 font-black text-brand-gold-light">Pelajari skema <span class="inline-block transition group-hover:translate-x-1" aria-hidden="true">→</span></p></div>
</article>
