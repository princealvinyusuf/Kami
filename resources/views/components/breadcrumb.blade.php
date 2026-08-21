@props(['items' => []])
@php
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => collect($items)->map(function ($url, $label) {
            $isUnlinked = is_int($label);

            return [
                '@type' => 'ListItem',
                'position' => 0,
                'name' => $isUnlinked ? $url : $label,
                'item' => $isUnlinked ? url()->current() : $url,
            ];
        })->values()->map(function ($item, $index) {
            $item['position'] = $index + 1;

            return $item;
        })->all(),
    ];
@endphp
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
<nav aria-label="Jejak navigasi"><ol class="flex flex-wrap gap-2 text-sm text-neutral-600">
@foreach($items as $label => $url)<li>@if(!$loop->last)<a class="hover:text-brand-gold" href="{{ $url }}">{{ $label }}</a><span aria-hidden="true" class="ml-2">/</span>@else<span aria-current="page">{{ is_int($label) ? $url : $label }}</span>@endif</li>@endforeach
</ol></nav>
