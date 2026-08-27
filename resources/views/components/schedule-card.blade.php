@props(['schedule', 'type'])

@php
    $isTraining = $type === 'training';
    $item = $isTraining ? $schedule->program : $schedule->scheme;
    $detailUrl = route($isTraining ? 'trainings.show' : 'certifications.show', $item);
    $price = $isTraining ? $schedule->price : $schedule->fee;
@endphp

<article class="group grid grid-cols-[4.5rem_1fr] gap-5 rounded-2xl border border-black/10 bg-white p-5 transition hover:-translate-y-0.5 hover:border-brand-gold/50 hover:shadow-lg">
    <div class="self-start overflow-hidden rounded-xl border border-brand-gold/30 text-center">
        <p class="bg-brand-gold px-2 py-1 text-[.65rem] font-black uppercase tracking-wider text-black">
            {{ $schedule->starts_at->translatedFormat('M') }}
        </p>
        <p class="px-2 pt-2 text-2xl font-black leading-none">{{ $schedule->starts_at->format('d') }}</p>
        <p class="px-2 pb-2 pt-1 text-[.65rem] font-bold uppercase text-neutral-500">
            {{ $schedule->starts_at->translatedFormat('D') }}
        </p>
    </div>

    <div class="min-w-0">
        @if ($item->category)
            <p class="text-xs font-black uppercase tracking-wider text-brand-gold">{{ $item->category->name }}</p>
        @endif
        <h3 class="mt-1 text-lg font-black leading-snug">
            <a href="{{ $detailUrl }}" class="transition group-hover:text-brand-gold">
                {{ $item->title }}
            </a>
        </h3>
        <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-sm text-neutral-600">
            <span>{{ $schedule->starts_at->translatedFormat('H.i') }} WIB</span>
            @if ($schedule->location)
                <span>{{ $schedule->location }}</span>
            @endif
            @if ($price !== null)
                <span class="font-bold text-neutral-900">Rp {{ number_format((float) $price, 0, ',', '.') }}</span>
            @endif
        </div>
        <a href="{{ $detailUrl }}" class="mt-4 inline-flex items-center gap-2 text-sm font-black underline decoration-brand-gold decoration-2 underline-offset-4">
            Lihat detail <span aria-hidden="true">→</span>
        </a>
    </div>
</article>
