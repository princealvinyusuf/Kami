@props(['href' => null, 'type' => 'button', 'variant' => 'primary'])
@php($classes = $variant === 'secondary' ? 'border border-brand-gold bg-transparent text-brand-gold hover:bg-brand-gold hover:text-black' : 'bg-brand-gold text-black hover:bg-brand-gold-light')
@if($href)<a href="{{ $href }}" {{ $attributes->class("inline-flex items-center justify-center rounded-md px-5 py-3 font-bold transition $classes") }}>{{ $slot }}</a>
@else<button type="{{ $type }}" {{ $attributes->class("inline-flex items-center justify-center rounded-md px-5 py-3 font-bold transition $classes") }}>{{ $slot }}</button>@endif
