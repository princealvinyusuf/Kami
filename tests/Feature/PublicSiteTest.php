<?php

namespace Tests\Feature;

use App\Models\CertificationCategory;
use App\Models\CertificationScheme;
use App\Models\ContactMessage;
use App\Models\LegalDocument;
use App\Models\TrainingCategory;
use App\Models\TrainingProgram;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_has_canonical_social_metadata_and_no_unconfigured_organization_claim(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('property="og:title"', false)
            ->assertSee('name="twitter:card"', false)
            ->assertDontSee('"EducationalOrganization"', false);
    }

    public function test_homepage_shows_upcoming_training_and_certification_schedules(): void
    {
        $trainingCategory = TrainingCategory::create([
            'name' => 'Digital',
            'slug' => 'digital',
            'is_active' => true,
        ]);
        $training = TrainingProgram::create([
            'training_category_id' => $trainingCategory->id,
            'title' => 'Pelatihan Data',
            'slug' => 'pelatihan-data',
            'status' => 'published',
            'verification_status' => 'verified',
        ]);
        $training->schedules()->create([
            'starts_at' => now()->addWeek(),
            'location' => 'Bandung',
            'status' => 'scheduled',
        ]);

        $certificationCategory = CertificationCategory::create([
            'name' => 'Teknologi',
            'slug' => 'teknologi',
            'is_active' => true,
        ]);
        $certification = CertificationScheme::create([
            'certification_category_id' => $certificationCategory->id,
            'title' => 'Sertifikasi Analis Data',
            'slug' => 'sertifikasi-analis-data',
            'status' => 'published',
            'verification_status' => 'verified',
        ]);
        $certification->schedules()->create([
            'starts_at' => now()->addWeeks(2),
            'location' => 'Jakarta',
            'status' => 'scheduled',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Jadwal Pelatihan')
            ->assertSee('Pelatihan Data')
            ->assertSee('Bandung')
            ->assertSee('Jadwal Sertifikasi')
            ->assertSee('Sertifikasi Analis Data')
            ->assertSee('Jakarta');
    }

    public function test_homepage_shows_verified_legal_documents_and_serves_their_images(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('legal/cover.jpg', 'image-content');

        $visible = LegalDocument::create([
            'title' => 'Dasar Hukum KAMI',
            'slug' => 'dasar-hukum-kami',
            'type' => 'regulasi',
            'body' => 'Ringkasan dasar hukum yang telah diverifikasi.',
            'file_path' => 'legal/cover.jpg',
            'status' => 'published',
            'published_at' => now()->subMinute(),
            'verification_status' => 'verified',
        ]);
        LegalDocument::create([
            'title' => 'Dokumen Belum Terverifikasi',
            'slug' => 'dokumen-belum-terverifikasi',
            'type' => 'kebijakan',
            'body' => 'Dokumen ini belum boleh tampil.',
            'status' => 'published',
            'published_at' => now()->subMinute(),
            'verification_status' => 'unverified',
        ]);

        $attachmentUrl = route('legal-documents.attachment', ['legalDocument' => $visible->slug]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Dokumen legal terverifikasi')
            ->assertSee('Dasar Hukum KAMI')
            ->assertSee($attachmentUrl, false)
            ->assertDontSee('Dokumen Belum Terverifikasi');

        $this->get($attachmentUrl)->assertOk();
        $this->get(route('legal-documents.attachment', ['legalDocument' => 'dokumen-belum-terverifikasi']))
            ->assertNotFound();
    }

    public function test_catalog_and_detail_only_show_published_verified_content(): void
    {
        $category = TrainingCategory::create([
            'name' => 'Digital',
            'slug' => 'digital',
            'is_active' => true,
        ]);
        $visible = TrainingProgram::create([
            'training_category_id' => $category->id,
            'title' => 'Program Terverifikasi',
            'slug' => 'program-terverifikasi',
            'status' => 'published',
            'published_at' => now()->subMinute(),
            'verification_status' => 'verified',
        ]);
        $draft = TrainingProgram::create([
            'training_category_id' => $category->id,
            'title' => 'Program Draf',
            'slug' => 'program-draf',
            'status' => 'draft',
            'verification_status' => 'verified',
        ]);

        $this->get(route('trainings.index'))
            ->assertOk()
            ->assertSee($visible->title)
            ->assertDontSee($draft->title);
        $this->get(route('trainings.show', $visible))
            ->assertOk()
            ->assertSee('"Course"', false)
            ->assertDontSee('"Event"', false);
        $visible->schedules()->create([
            'starts_at' => now()->addWeek(),
            'location' => 'Bandung',
            'status' => 'scheduled',
        ]);
        $this->get(route('trainings.show', $visible))
            ->assertOk()
            ->assertSee('"Event"', false);
        $this->get(route('trainings.show', $draft))->assertNotFound();
    }

    public function test_contact_validation_and_persistence(): void
    {
        Mail::fake();

        $this->post(route('contact.store'), [])
            ->assertSessionHasErrors(['name', 'email', 'message']);

        $this->post(route('contact.store'), [
            'name' => '  Pengirim Uji  ',
            'email' => 'PENGIRIM@example.com',
            'phone' => '0812-3456-7890',
            'subject' => 'Kebutuhan pelatihan',
            'message' => 'Mohon informasi lebih lanjut.',
            'website' => '',
        ])->assertSessionHasNoErrors()->assertSessionHas('success');

        $message = ContactMessage::firstOrFail();
        $this->assertSame('Pengirim Uji', $message->name);
        $this->assertSame('pengirim@example.com', $message->email);
        $this->assertSame('+6281234567890', $message->phone);
    }
}
