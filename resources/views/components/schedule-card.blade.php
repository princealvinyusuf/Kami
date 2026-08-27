@props(['schedule', 'type'])

@php
    $isTraining = $type === 'training';
    $item = $isTraining ? $schedule->program : $schedule->scheme;
    $detailUrl = route($isTraining ? 'trainings.show' : 'certifications.show', $item);
    $price = $isTraining ? $schedule->price : $schedule->fee;
@endphp

<article class="schedule-card group">
    <div class="schedule-card__date">
        <p class="bg-brand-gold px-2 py-1.5 text-[.65rem] font-black uppercase tracking-[.14em] text-white">
            {{ $schedule->starts_at->translatedFormat('M') }}
        </p>
        <p class="px-2 pt-2.5 text-2xl font-black leading-none">{{ $schedule->starts_at->format('d') }}</p>
        <p class="px-2 pb-2.5 pt-1 text-[.65rem] font-bold uppercase text-neutral-500">
            {{ $schedule->starts_at->translatedFormat('D') }}
        </p>
    </div>

    <div class="min-w-0 self-center">
        @if ($item->category)
            <p class="truncate text-[.7rem] font-black uppercase tracking-[.12em] text-brand-gold">{{ $item->category->name }}</p>
        @endif
        <h3 class="mt-1.5 text-lg font-black leading-snug tracking-[-.01em]">
            <a href="{{ $detailUrl }}" class="transition group-hover:text-brand-gold">
                {{ $item->title }}
            </a>
        </h3>
        <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-neutral-600">
            <span class="inline-flex items-center gap-1.5">
                <svg aria-hidden="true" class="size-4 shrink-0 text-brand-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                {{ $schedule->starts_at->translatedFormat('H.i') }} WIB
            </span>
            @if ($schedule->location)
                <span class="inline-flex items-center gap-1.5">
                    <svg aria-hidden="true" class="size-4 shrink-0 text-brand-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                    {{ $schedule->location }}
                </span>
            @endif
            @if ($price !== null)
                <span class="inline-flex items-center gap-1.5 font-bold text-neutral-900">
                    <svg aria-hidden="true" class="size-4 shrink-0 text-brand-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M9 8h4a2 2 0 0 1 0 4H9V7v10m0-3h5"/></svg>
                    Rp {{ number_format((float) $price, 0, ',', '.') }}
                </span>
            @endif
        </div>
        <a href="{{ $detailUrl }}" class="mt-4 inline-flex items-center gap-2 text-sm font-black text-brand-black transition group-hover:gap-3 group-hover:text-brand-gold">
            Lihat detail <span class="text-brand-gold" aria-hidden="true">→</span>
        </a>
    </div>
</article>
