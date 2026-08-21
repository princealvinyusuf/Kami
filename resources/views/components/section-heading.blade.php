@props(['eyebrow' => null, 'title', 'align' => 'left'])
<div {{ $attributes->class([$align === 'center' ? 'text-center' : 'text-left']) }}>
    @if($eyebrow)<p class="mb-2 text-sm font-bold uppercase tracking-widest text-brand-gold">{{ $eyebrow }}</p>@endif
    <h2 class="text-3xl font-black tracking-tight md:text-4xl">{{ $title }}</h2>
    @if($slot->isNotEmpty())<div class="mt-3 text-neutral-600">{{ $slot }}</div>@endif
</div>
