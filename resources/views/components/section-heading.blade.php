@props(['eyebrow' => null, 'title', 'align' => 'left'])
<div {{ $attributes->class([$align === 'center' ? 'text-center' : 'text-left']) }}>
    @if($eyebrow)<p @class(['eyebrow', 'justify-center' => $align === 'center'])>{{ $eyebrow }}</p>@endif
    <h2 class="mt-4 max-w-4xl text-4xl font-black leading-[1.03] tracking-[-.04em] md:text-5xl">{{ $title }}</h2>
    @if($slot->isNotEmpty())<div class="mt-5 max-w-2xl leading-7 text-neutral-600">{{ $slot }}</div>@endif
</div>
