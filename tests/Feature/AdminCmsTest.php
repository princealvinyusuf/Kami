<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Setting;
use App\Models\TrainingCategory;
use App\Models\TrainingProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_and_non_admins_are_forbidden(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs(User::factory()->create())->post(route('admin.resources.store', 'training-categories'), [
            'name' => 'Terlarang', 'slug' => 'terlarang',
        ])->assertForbidden();
    }

    public function test_admin_dashboard_is_query_backed(): void
    {
        $admin = User::factory()->admin()->create();
        TrainingCategory::create(['name' => 'Kategori', 'slug' => 'kategori', 'is_active' => true]);

        $this->actingAs($admin)->get(route('admin.dashboard'))
            ->assertOk()->assertSee('Program pelatihan')->assertSee('Pertanyaan baru');
    }

    public function test_admin_can_create_content_but_publication_requires_verification(): void
    {
        $admin = User::factory()->admin()->create();
        $category = TrainingCategory::create(['name' => 'Kategori', 'slug' => 'kategori', 'is_active' => true]);

        $this->actingAs($admin)->post(route('admin.resources.store', 'training-programs'), [
            'training_category_id' => $category->id,
            'title' => 'Program Aman',
            'slug' => '',
            'status' => 'published',
            'published_at' => now()->subMinute()->format('Y-m-d H:i:s'),
            'verification_status' => 'unverified',
        ])->assertRedirect(route('admin.resources.index', 'training-programs'));

        $program = TrainingProgram::firstOrFail();
        $this->assertSame('program-aman', $program->slug);
        $this->get(route('trainings.show', $program))->assertNotFound();

        $program->update(['verification_status' => 'verified']);
        $this->get(route('trainings.show', $program))->assertOk();
    }

    public function test_admin_can_update_settings(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->put(route('admin.settings.update'), [
            'branding__site_name' => 'KAMI Terverifikasi',
            'contact__email' => 'admin@example.com',
            'registration__document_max_kb' => 4096,
            'registration__collect_sensitive_fields' => 0,
            'registration__require_sensitive_fields' => 0,
        ])->assertSessionHasNoErrors()->assertSessionHas('success');

        $this->assertSame('KAMI Terverifikasi', Setting::where('key', 'branding.site_name')->value('value'));
    }

    public function test_opening_and_updating_contact_message_marks_it_read(): void
    {
        $admin = User::factory()->admin()->create();
        $message = ContactMessage::create([
            'name' => 'Pengirim', 'email' => 'sender@example.com', 'message' => 'Halo', 'status' => 'new',
        ]);

        $this->actingAs($admin)->get(route('admin.messages.show', $message))->assertOk();
        $this->assertNotNull($message->fresh()->read_at);

        $this->patch(route('admin.messages.update', $message), ['status' => 'replied'])
            ->assertSessionHas('success');
        $this->assertSame('replied', $message->fresh()->status);
    }

    public function test_admin_can_update_and_delete_a_representative_cms_resource(): void
    {
        $admin = User::factory()->admin()->create();
        $category = TrainingCategory::create([
            'name' => 'Nama Lama',
            'slug' => 'nama-lama',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->put(route('admin.resources.update', [
            'resource' => 'training-categories',
            'id' => $category->id,
        ]), [
            'name' => 'Nama Baru',
            'slug' => 'nama-baru',
            'sort_order' => 0,
            'is_active' => 1,
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('training_categories', [
            'id' => $category->id,
            'name' => 'Nama Baru',
        ]);

        $this->delete(route('admin.resources.destroy', [
            'resource' => 'training-categories',
            'id' => $category->id,
        ]))->assertSessionHas('success');

        $this->assertSoftDeleted($category);
    }
}
