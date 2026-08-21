@props(['item'])
<article class="flex h-full flex-col rounded-2xl border border-neutral-800 bg-brand-black p-6 text-white shadow-lg">
<p class="text-xs font-bold uppercase tracking-widest text-brand-gold">{{ $item->category?->name ?? 'Sertifikasi' }}</p><h3 class="mt-2 text-xl font-black"><a href="{{ route('certifications.show', $item) }}">{{ $item->title }}</a></h3>@if($item->summary)<p class="mt-3 line-clamp-3 text-sm text-white/70">{{ $item->summary }}</p>@endif
@if($item->partner_lsp)<p class="mt-4 text-xs text-white/60">LSP mitra: <span class="text-white">{{ $item->partner_lsp }}</span></p>@endif
<a href="{{ route('certifications.show', $item) }}" class="mt-auto pt-6 font-bold text-brand-gold">Pelajari skema <span aria-hidden="true">→</span></a>
</article>
