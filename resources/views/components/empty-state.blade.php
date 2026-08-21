@props(['title' => 'Nothing here yet'])
<div {{ $attributes->class('rounded-3xl border border-dashed border-brand-gold/40 bg-brand-cream/50 p-10 text-center md:p-14') }}>
    <div class="mx-auto grid size-12 place-items-center rounded-2xl bg-brand-black text-brand-gold-light" aria-hidden="true">
        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9Z"/><path d="m4.5 7.7 7.5 4.2 7.5-4.2M12 12v9"/></svg>
    </div>
    <h3 class="mt-5 text-xl font-black">{{ $title }}</h3>
    <div class="mx-auto mt-2 max-w-md text-sm leading-6 text-neutral-600">{{ $slot }}</div>
</div>
