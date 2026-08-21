@props(['item'])
<article class="lift-card group relative rounded-3xl border border-black/8 bg-white p-7 shadow-[0_16px_45px_-34px_rgba(17,17,15,.45)]">
    <div class="grid size-11 place-items-center rounded-2xl bg-brand-black text-brand-gold-light" aria-hidden="true"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v18M3 12h18"/><circle cx="12" cy="12" r="9"/></svg></div>
    @if($item->type)<p class="eyebrow mt-6">{{ str($item->type)->headline() }}</p>@endif
    <h3 class="mt-4 text-2xl font-black tracking-tight"><a href="{{ route('services.show', $item) }}" class="after:absolute after:inset-0">{{ $item->name }}</a></h3>
    @if($item->summary)<p class="mt-3 text-sm leading-6 text-neutral-600">{{ $item->summary }}</p>@endif
    <p class="mt-6 font-black text-brand-gold">Selengkapnya <span class="inline-block transition group-hover:translate-x-1" aria-hidden="true">→</span></p>
</article>
