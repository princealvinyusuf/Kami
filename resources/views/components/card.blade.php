@props(['title' => null])
<article {{ $attributes->class('lift-card rounded-2xl border border-black/8 bg-white p-6 shadow-[0_16px_45px_-34px_rgba(17,17,15,.45)]') }}>
@if($title)<h3 class="text-xl font-black tracking-tight">{{ $title }}</h3>@endif<div @class(['mt-3' => $title])>{{ $slot }}</div>
</article>
