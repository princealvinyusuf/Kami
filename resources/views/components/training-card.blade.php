@props(['item'])
<article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
@if($item->cover_path)<img src="{{ asset('storage/'.$item->cover_path) }}" alt="" class="aspect-[16/9] w-full object-cover">@endif
<div class="flex flex-1 flex-col p-6"><p class="text-xs font-bold uppercase tracking-widest text-brand-gold">{{ $item->category?->name ?? 'Pelatihan' }}</p><h3 class="mt-2 text-xl font-black"><a href="{{ route('trainings.show', $item) }}" class="after:absolute">{{ $item->title }}</a></h3>@if($item->summary)<p class="mt-3 line-clamp-3 text-sm text-neutral-600">{{ $item->summary }}</p>@endif
<div class="mt-auto flex flex-wrap gap-2 pt-5 text-xs text-neutral-600">@if($item->level)<span class="chip">{{ $item->level }}</span>@endif @if($item->method)<span class="chip">{{ $item->method }}</span>@endif @if($item->duration)<span class="chip">{{ $item->duration }}</span>@endif</div>
<p class="mt-4 font-bold text-brand-gold">Lihat program <span aria-hidden="true">→</span></p></div>
</article>
