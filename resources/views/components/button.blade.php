@props(['href' => null, 'type' => 'button', 'variant' => 'primary'])
@php($classes = $variant === 'secondary'
    ? 'border border-brand-gold/60 bg-transparent text-brand-gold hover:bg-brand-gold hover:text-black'
    : 'border border-transparent bg-gradient-to-r from-brand-gold to-brand-gold-light text-black shadow-[0_12px_30px_-16px_rgba(138,106,19,.9)] hover:brightness-105')
@if($href)
    <a href="{{ $href }}" {{ $attributes->class("inline-flex min-h-12 items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-black transition duration-300 hover:-translate-y-0.5 $classes") }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->class("inline-flex min-h-12 items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-black transition duration-300 hover:-translate-y-0.5 $classes") }}>{{ $slot }}</button>
@endif
