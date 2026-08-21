<?php

namespace App\Support;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\CertificationCategory;
use App\Models\CertificationSchedule;
use App\Models\CertificationScheme;
use App\Models\CompetencyUnit;
use App\Models\Faq;
use App\Models\LegalDocument;
use App\Models\Partner;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\Trainer;
use App\Models\TrainingCategory;
use App\Models\TrainingProgram;
use App\Models\TrainingSchedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class AdminResourceRegistry
{
    public const CONTENT = ['training-programs', 'certification-schemes', 'services', 'portfolios', 'testimonials', 'articles', 'legal-documents'];

    public function all(): array
    {
        return [
            'training-categories' => $this->simple(TrainingCategory::class, 'Kategori Pelatihan', ['name', 'slug', 'description', 'sort_order', 'is_active']),
            'training-programs' => [
                ...$this->content(TrainingProgram::class, 'Program Pelatihan', 'title', 'cover_path'),
                'search' => ['title', 'slug', 'summary', 'location'],
                'with' => ['category', 'trainers'],
                'fields' => [
                    'training_category_id' => $this->relation('Kategori', TrainingCategory::class, 'name'),
                    'title' => $this->text('Judul', true),
                    'slug' => $this->slug(),
                    'summary' => $this->area('Ringkasan'),
                    'description' => $this->area('Deskripsi', false, 10),
                    'cover_path' => $this->image('Sampul', 'training'),
                    'objectives' => $this->list('Tujuan (satu per baris)'),
                    'curriculum' => $this->list('Kurikulum (satu per baris)'),
                    'benefits' => $this->list('Manfaat (satu per baris)'),
                    'requirements' => $this->list('Persyaratan (satu per baris)'),
                    'duration' => $this->text('Durasi'),
                    'method' => $this->text('Metode'),
                    'location' => $this->text('Lokasi'),
                    'level' => $this->text('Tingkat'),
                    'promo_label' => $this->text('Label promo'),
                    'quota' => $this->number('Kuota', false, 0),
                    'registration_deadline' => $this->date('Batas pendaftaran'),
                    'certificate' => $this->text('Sertifikat'),
                    'price' => $this->number('Harga', false, 0),
                    'trainer_ids' => $this->multi('Pelatih', Trainer::class, 'name'),
                    ...$this->publication(),
                ],
            ],
            'training-schedules' => $this->schedule(TrainingSchedule::class, 'Jadwal Pelatihan', 'training_program_id', TrainingProgram::class, 'title', 'price'),
            'certification-categories' => $this->simple(CertificationCategory::class, 'Kategori Sertifikasi', ['name', 'slug', 'description', 'sort_order', 'is_active']),
            'certification-schemes' => [
                ...$this->content(CertificationScheme::class, 'Skema Sertifikasi', 'title', 'cover_path'),
                'search' => ['title', 'slug', 'summary', 'partner_lsp'],
                'with' => ['category'],
                'fields' => [
                    'certification_category_id' => $this->relation('Kategori', CertificationCategory::class, 'name'),
                    'title' => $this->text('Judul', true),
                    'slug' => $this->slug(),
                    'summary' => $this->area('Ringkasan'),
                    'description' => $this->area('Deskripsi', false, 10),
                    'cover_path' => $this->image('Sampul', 'certifications'),
                    'partner_lsp' => $this->text('LSP mitra (wajib diverifikasi)'),
                    'requirements' => $this->list('Persyaratan (satu per baris)'),
                    'process' => $this->list('Proses (satu per baris)'),
                    'assessment_method' => $this->text('Metode asesmen'),
                    'validity_period' => $this->text('Masa berlaku'),
                    'location' => $this->text('Lokasi'),
                    'fee' => $this->number('Biaya', false, 0),
                    'intended_participants' => $this->list('Peserta yang dituju'),
                    ...$this->publication(),
                ],
            ],
            'certification-schedules' => $this->schedule(CertificationSchedule::class, 'Jadwal Sertifikasi', 'certification_scheme_id', CertificationScheme::class, 'title', 'fee'),
            'competency-units' => [
                ...$this->base(CompetencyUnit::class, 'Unit Kompetensi', 'title'),
                'search' => ['code', 'title', 'description'],
                'fields' => [
                    'certification_scheme_id' => $this->relation('Skema', CertificationScheme::class, 'title'),
                    'code' => $this->text('Kode'),
                    'title' => $this->text('Judul', true),
                    'description' => $this->area('Deskripsi'),
                    'sort_order' => $this->number('Urutan', true, 0),
                ],
            ],
            'trainers' => $this->verifiedPerson(Trainer::class, 'Pelatih', 'trainer', ['title' => 'Gelar/jabatan', 'bio' => 'Biografi', 'experience' => 'Pengalaman'], true),
            'team' => $this->verifiedPerson(TeamMember::class, 'Tim', 'team', ['position' => 'Jabatan', 'bio' => 'Biografi']),
            'partners' => [
                ...$this->content(Partner::class, 'Mitra', 'name', 'logo_path'),
                'search' => ['name', 'slug', 'type'],
                'fields' => [
                    'name' => $this->text('Nama', true), 'slug' => $this->slug(), 'type' => $this->text('Jenis'),
                    'website_url' => $this->url('Situs web'), 'logo_path' => $this->image('Logo', 'partners'),
                    'description' => $this->area('Deskripsi'), 'sort_order' => $this->number('Urutan', true, 0),
                    ...$this->publication(), 'is_active' => $this->check('Aktif'),
                ],
            ],
            'portfolios' => [
                ...$this->content(Portfolio::class, 'Portofolio', 'title'),
                'search' => ['title', 'slug', 'client_name', 'category', 'location'],
                'with' => ['images'],
                'fields' => [
                    'title' => $this->text('Judul', true), 'slug' => $this->slug(),
                    'client_name' => $this->text('Klien (wajib diverifikasi)'), 'category' => $this->text('Kategori'),
                    'location' => $this->text('Lokasi'), 'event_date' => $this->date('Tanggal kegiatan'),
                    'completed_at' => $this->date('Tanggal selesai'), 'summary' => $this->area('Ringkasan'),
                    'description' => $this->area('Deskripsi', false, 10),
                    'portfolio_images' => ['type' => 'images', 'label' => 'Galeri gambar', 'directory' => 'portfolio', 'rules' => ['nullable', 'array', 'max:12']],
                    ...$this->publication(),
                ],
            ],
            'services' => [
                ...$this->content(Service::class, 'Layanan', 'name', 'cover_path'),
                'search' => ['name', 'slug', 'type', 'summary'],
                'fields' => [
                    'name' => $this->text('Nama', true), 'slug' => $this->slug(), 'type' => $this->text('Jenis'),
                    'icon' => $this->text('Ikon'), 'cover_path' => $this->image('Sampul', 'services'),
                    'summary' => $this->area('Ringkasan'), 'description' => $this->area('Deskripsi', false, 10),
                    'sort_order' => $this->number('Urutan', true, 0), ...$this->publication(),
                ],
            ],
            'testimonials' => [
                ...$this->content(Testimonial::class, 'Testimoni', 'author_name', 'photo_path'),
                'search' => ['author_name', 'institution', 'program', 'content'],
                'fields' => [
                    'author_name' => $this->text('Nama pemberi testimoni', true), 'author_title' => $this->text('Jabatan'),
                    'institution' => $this->text('Institusi'), 'program' => $this->text('Program'),
                    'rating' => $this->number('Penilaian (1–5)', false, 1, 5), 'photo_path' => $this->image('Foto', 'testimonials'),
                    'video_url' => $this->url('URL video'), 'content' => $this->area('Isi testimoni', true, 6),
                    ...$this->publication(),
                ],
            ],
            'article-categories' => $this->simple(ArticleCategory::class, 'Kategori Artikel', ['name', 'slug', 'description', 'is_active']),
            'articles' => [
                ...$this->content(Article::class, 'Artikel', 'title', 'featured_image_path'),
                'search' => ['title', 'slug', 'excerpt', 'body'],
                'with' => ['category'],
                'fields' => [
                    'article_category_id' => $this->relation('Kategori', ArticleCategory::class, 'name', false),
                    'title' => $this->text('Judul', true), 'slug' => $this->slug(),
                    'excerpt' => $this->area('Ringkasan'), 'body' => $this->area('Isi artikel', true, 16),
                    'featured_image_path' => $this->image('Gambar utama', 'articles'),
                    'meta_title' => $this->text('Judul SEO'), 'meta_description' => $this->area('Deskripsi SEO'),
                    'canonical_url' => $this->url('URL kanonis'), ...$this->publication(),
                ],
            ],
            'faqs' => [
                ...$this->base(Faq::class, 'FAQ', 'question'),
                'search' => ['question', 'answer'],
                'fields' => [
                    'faqable_type' => ['type' => 'select', 'label' => 'Terkait jenis', 'rules' => ['nullable', Rule::in(['training', 'certification', 'service'])], 'options' => ['' => 'Umum', 'training' => 'Pelatihan', 'certification' => 'Sertifikasi', 'service' => 'Layanan']],
                    'faqable_id' => $this->number('ID konten terkait', false, 1),
                    'question' => $this->text('Pertanyaan', true), 'answer' => $this->area('Jawaban', true, 6),
                    'sort_order' => $this->number('Urutan', true, 0), 'is_active' => $this->check('Aktif'),
                ],
            ],
            'legal-documents' => [
                ...$this->content(LegalDocument::class, 'Dokumen Legal', 'title', 'file_path'),
                'search' => ['title', 'slug', 'type', 'version', 'body'],
                'fields' => [
                    'title' => $this->text('Judul', true), 'slug' => $this->slug(), 'type' => $this->text('Jenis', true),
                    'body' => $this->area('Isi dokumen', true, 14), 'version' => $this->text('Versi'),
                    'effective_at' => $this->date('Tanggal berlaku'),
                    'file_path' => ['type' => 'file', 'label' => 'Berkas pendukung privat', 'disk' => 'local', 'directory' => 'legal', 'rules' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240']],
                    ...$this->publication(),
                ],
            ],
        ];
    }

    public function get(string $key): array
    {
        return $this->all()[$key] ?? abort(404);
    }

    public function model(string $key, int|string|null $id = null): Model
    {
        $class = $this->get($key)['model'];

        return $id === null ? new $class : $class::query()->findOrFail($id);
    }

    private function base(string $model, string $label, string $title): array
    {
        return ['model' => $model, 'label' => $label, 'title' => $title, 'search' => [$title], 'with' => [], 'filters' => []];
    }

    private function content(string $model, string $label, string $title, ?string $upload = null): array
    {
        return [...$this->base($model, $label, $title), 'publication' => true, 'upload' => $upload];
    }

    private function simple(string $model, string $label, array $names): array
    {
        $fields = [];
        foreach ($names as $name) {
            $fields[$name] = match ($name) {
                'name' => $this->text('Nama', true),
                'slug' => $this->slug(),
                'description' => $this->area('Deskripsi'),
                'sort_order' => $this->number('Urutan', true, 0),
                'is_active' => $this->check('Aktif'),
            };
        }

        return [...$this->base($model, $label, 'name'), 'search' => ['name', 'slug', 'description'], 'fields' => $fields];
    }

    private function schedule(string $model, string $label, string $foreign, string $parent, string $parentLabel, string $money): array
    {
        return [
            ...$this->base($model, $label, 'starts_at'),
            'search' => ['location', 'status'],
            'fields' => [
                $foreign => $this->relation('Program/skema', $parent, $parentLabel),
                'starts_at' => $this->datetime('Mulai', true), 'ends_at' => ['type' => 'datetime-local', 'label' => 'Selesai', 'rules' => ['nullable', 'date', 'after:starts_at']],
                'location' => $this->text('Lokasi'), 'capacity' => $this->number('Kapasitas', false, 0),
                $money => $this->number($money === 'price' ? 'Harga' : 'Biaya', false, 0),
                'status' => ['type' => 'select', 'label' => 'Status', 'rules' => ['required', Rule::in(['scheduled', 'cancelled', 'completed'])], 'options' => ['scheduled' => 'Terjadwal', 'cancelled' => 'Dibatalkan', 'completed' => 'Selesai']],
            ],
        ];
    }

    private function verifiedPerson(string $model, string $label, string $directory, array $extra, bool $trainer = false): array
    {
        $fields = ['name' => $this->text('Nama', true), 'slug' => $this->slug()];
        foreach ($extra as $name => $caption) {
            $fields[$name] = $name === 'bio' || $name === 'experience' ? $this->area($caption, false, 7) : $this->text($caption);
        }
        $fields['photo_path'] = $this->image('Foto', $directory);
        if ($trainer) {
            $fields['expertise'] = $this->list('Keahlian (satu per baris)');
            $fields['certifications'] = $this->list('Sertifikasi (satu per baris)');
            $fields['linkedin_url'] = $this->url('LinkedIn');
            $fields['instagram_url'] = $this->url('Instagram');
            $fields['website_url'] = $this->url('Situs web');
        } else {
            $fields['sort_order'] = $this->number('Urutan', true, 0);
        }
        $fields += $this->publication();
        $fields['is_active'] = $this->check('Aktif');

        return [...$this->content($model, $label, 'name', 'photo_path'), 'search' => ['name', 'slug', ...array_keys($extra)], 'fields' => $fields];
    }

    private function publication(): array
    {
        return [
            'status' => ['type' => 'select', 'label' => 'Status publikasi', 'rules' => ['required', Rule::in(['draft', 'published', 'archived'])], 'options' => ['draft' => 'Draf', 'published' => 'Terbit', 'archived' => 'Arsip']],
            'published_at' => $this->datetime('Waktu terbit'),
            ...$this->verification(),
        ];
    }

    private function verification(): array
    {
        return [
            'verification_status' => ['type' => 'select', 'label' => 'Status verifikasi', 'rules' => ['required', Rule::in(['unverified', 'pending', 'verified', 'rejected'])], 'options' => ['unverified' => 'Belum diverifikasi', 'pending' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak']],
            'verification_notes' => $this->area('Catatan verifikasi'),
        ];
    }

    private function text(string $label, bool $required = false): array
    {
        return ['type' => 'text', 'label' => $label, 'rules' => [$required ? 'required' : 'nullable', 'string', 'max:255']];
    }

    private function slug(): array
    {
        return ['type' => 'slug', 'label' => 'Slug', 'rules' => ['required', 'string', 'max:255', 'alpha_dash']];
    }

    private function area(string $label, bool $required = false, int $rows = 4): array
    {
        return ['type' => 'textarea', 'label' => $label, 'rows' => $rows, 'rules' => [$required ? 'required' : 'nullable', 'string', 'max:65000']];
    }

    private function list(string $label): array
    {
        return ['type' => 'list', 'label' => $label, 'rules' => ['nullable', 'string', 'max:30000']];
    }

    private function url(string $label): array
    {
        return ['type' => 'url', 'label' => $label, 'rules' => ['nullable', 'url:http,https', 'max:2048']];
    }

    private function date(string $label): array
    {
        return ['type' => 'date', 'label' => $label, 'rules' => ['nullable', 'date']];
    }

    private function datetime(string $label, bool $required = false): array
    {
        return ['type' => 'datetime-local', 'label' => $label, 'rules' => [$required ? 'required' : 'nullable', 'date']];
    }

    private function number(string $label, bool $required = false, int $min = 0, ?int $max = null): array
    {
        return ['type' => 'number', 'label' => $label, 'rules' => array_filter([$required ? 'required' : 'nullable', 'numeric', "min:$min", $max ? "max:$max" : null])];
    }

    private function check(string $label): array
    {
        return ['type' => 'checkbox', 'label' => $label, 'rules' => ['boolean']];
    }

    private function image(string $label, string $directory): array
    {
        return ['type' => 'image', 'label' => $label, 'disk' => 'public', 'directory' => $directory, 'rules' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096']];
    }

    private function relation(string $label, string $model, string $display, bool $required = true): array
    {
        return ['type' => 'relation', 'label' => $label, 'model' => $model, 'display' => $display, 'rules' => [$required ? 'required' : 'nullable', 'integer', Rule::exists((new $model)->getTable(), 'id')]];
    }

    private function multi(string $label, string $model, string $display): array
    {
        return ['type' => 'multi', 'label' => $label, 'model' => $model, 'display' => $display, 'rules' => ['nullable', 'array'], 'item_rules' => ['integer', Rule::exists((new $model)->getTable(), 'id')]];
    }
}
