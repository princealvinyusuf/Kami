@props(['title' => 'Nothing here yet'])
<div {{ $attributes->class('rounded-xl border-2 border-dashed border-neutral-300 p-10 text-center') }}><h3 class="font-bold">{{ $title }}</h3><div class="mt-2 text-sm text-neutral-600">{{ $slot }}</div></div>
