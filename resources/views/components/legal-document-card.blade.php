@props(['document'])

@php
    $hasAttachment = $document->file_path && Storage::disk('local')->exists($document->file_path);
    $extension = $hasAttachment ? strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION)) : null;
    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true);
    $attachmentUrl = $hasAttachment
        ? route('legal-documents.attachment', ['legalDocument' => $document->slug])
        : null;
@endphp

<article class="group flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-black/8 bg-white shadow-[0_18px_55px_-38px_rgba(17,17,15,.5)] transition duration-300 hover:-translate-y-1 hover:border-brand-gold/30 hover:shadow-[0_25px_55px_-32px_rgba(17,17,15,.35)]">
    @if($isImage)
        <a href="{{ $attachmentUrl }}" target="_blank" class="relative block aspect-[16/10] overflow-hidden bg-neutral-100">
            <img src="{{ $attachmentUrl }}" alt="{{ $document->title }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
            <span class="absolute bottom-3 right-3 rounded-full bg-black/75 px-3 py-1.5 text-xs font-bold text-white backdrop-blur">Lihat gambar ↗</span>
        </a>
    @else
        <div class="relative grid aspect-[16/7] place-items-center overflow-hidden bg-brand-black text-brand-gold-light">
            <div class="surface-grid absolute inset-0 opacity-50"></div>
            <svg aria-hidden="true" class="relative size-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M7 3h7l4 4v14H7z"/><path d="M14 3v5h5M10 13h5M10 17h5"/></svg>
        </div>
    @endif

    <div class="flex flex-1 flex-col p-6">
        <div class="flex flex-wrap items-center gap-2 text-[.7rem] font-black uppercase tracking-[.12em] text-brand-gold">
            <span>{{ $document->type }}</span>
            @if($document->version)<span class="text-neutral-300">•</span><span class="text-neutral-500">Versi {{ $document->version }}</span>@endif
        </div>
        <h3 class="mt-3 text-xl font-black leading-snug tracking-[-.02em]">{{ $document->title }}</h3>
        <p class="mt-3 flex-1 text-sm leading-6 text-neutral-600">{{ \Illuminate\Support\Str::limit(strip_tags($document->body), 180) }}</p>

        @if($document->effective_at || $attachmentUrl)
            <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-black/8 pt-4 text-sm">
                @if($document->effective_at)
                    <span class="text-neutral-500">Berlaku {{ $document->effective_at->translatedFormat('d M Y') }}</span>
                @else
                    <span></span>
                @endif
                @if($attachmentUrl)
                    <a href="{{ $attachmentUrl }}" target="_blank" class="inline-flex items-center gap-2 font-black text-brand-black transition hover:text-brand-gold">
                        {{ $isImage ? 'Lihat gambar' : 'Lihat berkas' }} <span class="text-brand-gold" aria-hidden="true">↗</span>
                    </a>
                @endif
            </div>
        @endif
    </div>
</article>
