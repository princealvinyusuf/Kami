@props(['item'])
<article class="lift-card group relative flex h-full flex-col overflow-hidden rounded-3xl border border-black/8 bg-white shadow-[0_16px_45px_-34px_rgba(17,17,15,.45)]">
@if($item->cover_path)<div class="overflow-hidden"><img src="{{ asset('storage/'.$item->cover_path) }}" alt="" class="aspect-[16/9] w-full object-cover transition duration-500 group-hover:scale-[1.03]"></div>@else<div class="surface-grid aspect-[16/6] bg-brand-black opacity-95"></div>@endif
<div class="flex flex-1 flex-col p-6"><p class="eyebrow">{{ $item->category?->name ?? 'Pelatihan' }}</p><h3 class="mt-4 text-xl font-black tracking-tight"><a href="{{ route('trainings.show', $item) }}" class="after:absolute after:inset-0">{{ $item->title }}</a></h3>@if($item->summary)<p class="mt-3 line-clamp-3 text-sm leading-6 text-neutral-600">{{ $item->summary }}</p>@endif
<div class="mt-auto flex flex-wrap gap-2 pt-5 text-xs text-neutral-600">@if($item->level)<span class="chip">{{ $item->level }}</span>@endif @if($item->method)<span class="chip">{{ $item->method }}</span>@endif @if($item->duration)<span class="chip">{{ $item->duration }}</span>@endif</div>
<p class="mt-5 inline-flex items-center gap-2 font-black text-brand-gold">Lihat program <span class="transition group-hover:translate-x-1" aria-hidden="true">→</span></p></div>
</article>
