<?php

namespace App\Http\Controllers;

use App\Enums\ContentStatus;
use App\Enums\VerificationStatus;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\CertificationCategory;
use App\Models\CertificationScheme;
use App\Models\Partner;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\Trainer;
use App\Models\TrainingCategory;
use App\Models\TrainingProgram;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PublicController extends Controller
{
    private function visible(Builder $query): Builder
    {
        return $query->published()->verified();
    }

    private function ensureVisible(object $model): void
    {
        abort_unless(
            $model->status === ContentStatus::Published
            && $model->verification_status === VerificationStatus::Verified
            && $model->published_at?->isPast(),
            404
        );
    }

    public function home()
    {
        return view('public.home', [
            'services' => $this->visible(Service::query())->orderBy('sort_order')->limit(6)->get(),
            'trainings' => $this->visible(TrainingProgram::query())->with(['category', 'schedules' => fn ($q) => $q->where('status', 'scheduled')->where('starts_at', '>=', now())->orderBy('starts_at')])->latest('published_at')->limit(6)->get(),
            'certifications' => $this->visible(CertificationScheme::query())->with(['category', 'schedules' => fn ($q) => $q->where('status', 'scheduled')->where('starts_at', '>=', now())->orderBy('starts_at')])->latest('published_at')->limit(6)->get(),
            'partners' => Partner::query()->published()->verified()->where('is_active', true)->orderBy('sort_order')->get(),
            'testimonials' => $this->visible(Testimonial::query())->latest('published_at')->limit(6)->get(),
            'articles' => $this->visible(Article::query())->with('category')->latest('published_at')->limit(3)->get(),
        ]);
    }

    public function about()
    {
        return view('public.about', [
            'team' => TeamMember::query()->published()->verified()->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function trainings(Request $request)
    {
        $query = $this->visible(TrainingProgram::query())->with(['category', 'schedules' => fn ($q) => $q->where('status', 'scheduled')->where('starts_at', '>=', now())->orderBy('starts_at')]);
        $query->when($request->filled('q'), fn ($q) => $q->where(fn ($q) => $q->where('title', 'like', '%'.$request->q.'%')->orWhere('summary', 'like', '%'.$request->q.'%')))
            ->when($request->filled('category'), fn ($q) => $q->whereHas('category', fn ($q) => $q->where('slug', $request->category)))
            ->when($request->filled('method'), fn ($q) => $q->where('method', $request->method))
            ->when($request->filled('level'), fn ($q) => $q->where('level', $request->level));

        return view('public.trainings.index', [
            'items' => $query->latest('published_at')->paginate(9)->withQueryString(),
            'categories' => TrainingCategory::query()->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function training(TrainingProgram $training)
    {
        $this->ensureVisible($training);
        $training->load(['category', 'trainers' => fn ($q) => $q->published()->verified()->where('is_active', true), 'faqs' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'), 'schedules' => fn ($q) => $q->where('status', 'scheduled')->where('starts_at', '>=', now())->orderBy('starts_at')]);
        $related = $this->visible(TrainingProgram::query())->with('category')->where('training_category_id', $training->training_category_id)->whereKeyNot($training->id)->limit(3)->get();

        return view('public.trainings.show', compact('training', 'related'));
    }

    public function certifications(Request $request)
    {
        $query = $this->visible(CertificationScheme::query())->with(['category', 'schedules' => fn ($q) => $q->where('status', 'scheduled')->where('starts_at', '>=', now())->orderBy('starts_at')]);
        $query->when($request->filled('q'), fn ($q) => $q->where(fn ($q) => $q->where('title', 'like', '%'.$request->q.'%')->orWhere('summary', 'like', '%'.$request->q.'%')))
            ->when($request->filled('category'), fn ($q) => $q->whereHas('category', fn ($q) => $q->where('slug', $request->category)));

        return view('public.certifications.index', [
            'items' => $query->latest('published_at')->paginate(9)->withQueryString(),
            'categories' => CertificationCategory::query()->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function certification(CertificationScheme $certification)
    {
        $this->ensureVisible($certification);
        $certification->load(['category', 'competencyUnits' => fn ($q) => $q->orderBy('sort_order'), 'faqs' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'), 'schedules' => fn ($q) => $q->where('status', 'scheduled')->where('starts_at', '>=', now())->orderBy('starts_at')]);
        $related = $this->visible(CertificationScheme::query())->with('category')->where('certification_category_id', $certification->certification_category_id)->whereKeyNot($certification->id)->limit(3)->get();

        return view('public.certifications.show', compact('certification', 'related'));
    }

    public function services(Request $request)
    {
        $items = $this->visible(Service::query())->with(['faqs' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))->orderBy('sort_order')->paginate(12)->withQueryString();

        return view('public.services.index', compact('items'));
    }

    public function service(Service $service)
    {
        $this->ensureVisible($service);
        $service->load(['faqs' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')]);
        $related = $this->visible(Service::query())->whereKeyNot($service->id)->orderBy('sort_order')->limit(3)->get();

        return view('public.services.show', compact('service', 'related'));
    }

    public function trainers(Request $request)
    {
        $items = Trainer::query()->published()->verified()->where('is_active', true)->withCount(['programs' => fn ($q) => $q->published()->verified()])
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($q) => $q->where('name', 'like', '%'.$request->q.'%')->orWhere('title', 'like', '%'.$request->q.'%')))
            ->orderBy('name')->paginate(12)->withQueryString();

        return view('public.trainers.index', compact('items'));
    }

    public function trainer(Trainer $trainer)
    {
        $this->ensureVisible($trainer);
        abort_unless($trainer->is_active, 404);
        $trainer->load(['programs' => fn ($q) => $q->published()->verified()->with('category')]);

        return view('public.trainers.show', compact('trainer'));
    }

    public function partners()
    {
        return view('public.partners', ['partners' => Partner::query()->published()->verified()->where('is_active', true)->orderBy('type')->orderBy('sort_order')->get()->groupBy('type')]);
    }

    public function portfolio(Request $request)
    {
        return view('public.portfolio', ['items' => $this->visible(Portfolio::query())->with('images')->when($request->filled('category'), fn ($q) => $q->where('category', $request->category))->latest('event_date')->paginate(12)->withQueryString()]);
    }

    public function apprenticeship()
    {
        return $this->serviceLanding('pemagangan', 'Pemagangan', 'Program pembelajaran berbasis pengalaman kerja yang disusun sesuai kebutuhan dan ketersediaan program terverifikasi.');
    }

    public function consulting()
    {
        return $this->serviceLanding('konsultan', 'Konsultan', 'Pendampingan profesional untuk membantu organisasi memetakan kebutuhan dan langkah pengembangan kompetensi.');
    }

    private function serviceLanding(string $type, string $title, string $intro)
    {
        $services = $this->visible(Service::query())->where('type', $type)->with(['faqs' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])->orderBy('sort_order')->get();

        return view('public.service-landing', compact('services', 'title', 'intro', 'type'));
    }

    public function articles(Request $request)
    {
        $query = $this->visible(Article::query())->with('category');
        $query->when($request->filled('q'), fn ($q) => $q->where(fn ($q) => $q->where('title', 'like', '%'.$request->q.'%')->orWhere('excerpt', 'like', '%'.$request->q.'%')))
            ->when($request->filled('category'), fn ($q) => $q->whereHas('category', fn ($q) => $q->where('slug', $request->category)));

        return view('public.articles.index', ['items' => $query->latest('published_at')->paginate(9)->withQueryString(), 'categories' => ArticleCategory::query()->where('is_active', true)->orderBy('name')->get()]);
    }

    public function article(Article $article)
    {
        $this->ensureVisible($article);
        $article->load(['category', 'author']);
        $related = $this->visible(Article::query())->with('category')->where('article_category_id', $article->article_category_id)->whereKeyNot($article->id)->latest('published_at')->limit(3)->get();

        return view('public.articles.show', compact('article', 'related'));
    }

    public function search(Request $request)
    {
        $term = trim((string) $request->query('q'));
        $results = collect();
        if (mb_strlen($term) >= 2) {
            $results = Collection::make([
                ...$this->visible(TrainingProgram::query())->where(fn ($q) => $q->where('title', 'like', "%{$term}%")->orWhere('summary', 'like', "%{$term}%"))->limit(10)->get()->map(fn ($item) => ['type' => 'Pelatihan', 'title' => $item->title, 'summary' => $item->summary, 'url' => route('trainings.show', $item)]),
                ...$this->visible(CertificationScheme::query())->where(fn ($q) => $q->where('title', 'like', "%{$term}%")->orWhere('summary', 'like', "%{$term}%"))->limit(10)->get()->map(fn ($item) => ['type' => 'Sertifikasi', 'title' => $item->title, 'summary' => $item->summary, 'url' => route('certifications.show', $item)]),
                ...$this->visible(Article::query())->where(fn ($q) => $q->where('title', 'like', "%{$term}%")->orWhere('excerpt', 'like', "%{$term}%"))->limit(10)->get()->map(fn ($item) => ['type' => 'Artikel', 'title' => $item->title, 'summary' => $item->excerpt, 'url' => route('articles.show', $item)]),
            ]);
        }

        return view('public.search', compact('term', 'results'));
    }

    public function sitemap()
    {
        $urls = collect([
            route('home'), route('about'), route('trainings.index'), route('certifications.index'), route('services.index'),
            route('trainers.index'), route('partners'), route('portfolio'), route('apprenticeship'), route('consulting'), route('articles.index'), route('contact'),
        ])->map(fn (string $url) => ['loc' => $url, 'lastmod' => null])
            ->merge($this->sitemapEntries(TrainingProgram::query(), 'trainings.show'))
            ->merge($this->sitemapEntries(CertificationScheme::query(), 'certifications.show'))
            ->merge($this->sitemapEntries(Service::query(), 'services.show'))
            ->merge($this->sitemapEntries(Trainer::query()->where('is_active', true), 'trainers.show'))
            ->merge($this->sitemapEntries(Article::query(), 'articles.show'));

        return response()->view('public.sitemap', compact('urls'))->header('Content-Type', 'application/xml');
    }

    private function sitemapEntries(Builder $query, string $route): Collection
    {
        return $this->visible($query)
            ->get(['slug', 'updated_at'])
            ->map(fn ($item) => [
                'loc' => route($route, $item->slug),
                'lastmod' => $item->updated_at?->toAtomString(),
            ]);
    }
}
