<?php

namespace Tests\Feature;

use App\Models\Registration;
use App\Models\TrainingCategory;
use App\Models\TrainingProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_requires_shared_fields(): void
    {
        $program = $this->publishedTraining();

        $this->post(route('registrations.store', ['type' => 'training', 'slug' => $program->slug]))
            ->assertSessionHasErrors(['name', 'email', 'phone', 'city', 'province', 'education', 'institution', 'occupation']);
    }

    public function test_a_valid_registration_is_stored_with_private_document(): void
    {
        Storage::fake('local');
        Mail::fake();
        $program = $this->publishedTraining();

        $response = $this->post(route('registrations.store', ['type' => 'training', 'slug' => $program->slug]), [
            'name' => 'Peserta Uji',
            'email' => 'PESERTA@example.com',
            'phone' => '0812-3456-7890',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'education' => 'S1',
            'institution' => 'PT Contoh',
            'occupation' => 'Analis',
            'preferred_schedule' => 'Hari kerja',
            'notes' => 'Mohon informasi lanjutan.',
            'document' => UploadedFile::fake()->create('dukungan.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHas('success', 'Pendaftaran berhasil dikirim. Tim kami akan menghubungi Anda melalui WhatsApp atau email untuk proses selanjutnya.');
        $registration = Registration::query()->firstOrFail();
        $this->assertSame('+6281234567890', $registration->phone);
        $this->assertSame('new', $registration->status->value);
        $this->assertSame($program->id, $registration->registrable_id);
        Storage::disk('local')->assertExists($registration->document_path);
    }

    public function test_non_admin_cannot_access_registration_admin_page(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.registrations.index'))
            ->assertForbidden();
    }

    public function test_csv_export_respects_type_filter(): void
    {
        $admin = User::factory()->admin()->create();
        Registration::factory()->create(['registration_type' => 'training', 'email' => 'training@example.com']);
        Registration::factory()->create(['registration_type' => 'consultation', 'email' => 'consultation@example.com']);

        $content = $this->actingAs($admin)
            ->get(route('admin.registrations.export', ['type' => 'training']))
            ->assertOk()
            ->streamedContent();

        $this->assertStringContainsString('training@example.com', $content);
        $this->assertStringNotContainsString('consultation@example.com', $content);
    }

    public function test_registration_rejects_schedule_from_another_program(): void
    {
        $program = $this->publishedTraining();
        $other = $this->publishedTraining('pelatihan-lain');
        $schedule = $other->schedules()->create([
            'starts_at' => now()->addWeek(),
            'status' => 'scheduled',
        ]);

        $this->post(route('registrations.store', ['type' => 'training', 'slug' => $program->slug]), [
            ...$this->validRegistrationData(),
            'schedule_id' => $schedule->id,
        ])->assertSessionHasErrors('schedule_id');

        $this->assertDatabaseCount('registrations', 0);
    }

    public function test_csv_export_neutralizes_formula_cells_after_leading_whitespace(): void
    {
        $admin = User::factory()->admin()->create();
        Registration::factory()->create([
            'name' => "\t=HYPERLINK(\"https://example.invalid\")",
            'email' => 'safe@example.com',
        ]);

        $content = $this->actingAs($admin)
            ->get(route('admin.registrations.export'))
            ->assertOk()
            ->streamedContent();

        $this->assertStringContainsString("'\t=HYPERLINK", $content);
    }

    private function publishedTraining(string $slug = 'pelatihan-uji'): TrainingProgram
    {
        $category = TrainingCategory::firstOrCreate(
            ['slug' => 'kategori'],
            ['name' => 'Kategori', 'is_active' => true],
        );

        return TrainingProgram::create([
            'training_category_id' => $category->id,
            'title' => 'Pelatihan '.str($slug)->headline(),
            'slug' => $slug,
            'status' => 'published',
            'published_at' => now()->subMinute(),
            'verification_status' => 'verified',
        ]);
    }

    private function validRegistrationData(): array
    {
        return [
            'name' => 'Peserta Uji',
            'email' => 'peserta@example.com',
            'phone' => '081234567890',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'education' => 'S1',
            'institution' => 'PT Contoh',
            'occupation' => 'Analis',
        ];
    }
}
