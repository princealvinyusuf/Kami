@php
    $articleImage = $article->featured_image_path ? Storage::disk('public')->url($article->featured_image_path) : null;
    $articleSchema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $article->title,
        'description' => $article->meta_description ?: $article->excerpt,
        'image' => $articleImage,
        'datePublished' => $article->published_at?->toAtomString(),
        'dateModified' => $article->updated_at?->toAtomString(),
        'url' => $article->canonical_url ?: url()->current(),
        'author' => $article->author ? ['@type' => 'Person', 'name' => $article->author->name] : null,
    ]);
@endphp
<x-layouts.public :title="($article->meta_title ?: $article->title).' — '.config('app.name')" :description="$article->meta_description ?: $article->excerpt" :canonical="$article->canonical_url ?: url()->current()" :image="$articleImage" type="article" :structured-data="[$articleSchema]"><section class="page-hero"><div class="mx-auto max-w-4xl"><x-breadcrumb :items="['Beranda'=>route('home'),'Artikel'=>route('articles.index'),$article->title]" /><p class="mt-8 font-bold uppercase tracking-widest text-brand-gold">{{ $article->category?->name }}</p><h1 class="mt-3 text-4xl font-black md:text-6xl">{{ $article->title }}</h1><p class="mt-5 text-sm text-white/60">{{ $article->published_at?->translatedFormat('d F Y') }}@if($article->author) · {{ $article->author->name }}@endif</p></div></section><article class="mx-auto max-w-4xl px-4 py-16">@if($articleImage)<img src="{{ $articleImage }}" alt="" class="mb-10 aspect-[16/8] w-full rounded-2xl object-cover">@endif<div class="prose-copy whitespace-pre-line">{{ $article->body }}</div></article>@if($related->isNotEmpty())<section class="bg-neutral-100 py-16"><div class="page-shell"><h2 class="text-3xl font-black">Artikel terkait</h2><div class="mt-8 grid gap-6 md:grid-cols-3">@foreach($related as $item)<x-article-card :item="$item" />@endforeach</div></div></section>@endif</x-layouts.public>
