@props(['title' => null])
<article {{ $attributes->class('rounded-xl border border-neutral-200 bg-white p-6 shadow-sm') }}>
@if($title)<h3 class="text-xl font-bold">{{ $title }}</h3>@endif<div @class(['mt-3' => $title])>{{ $slot }}</div>
</article>
