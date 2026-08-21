<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->longText('value')->nullable();
            $t->string('type', 30)->default('string');
            $t->string('group')->default('general')->index();
            $t->boolean('is_public')->default(false);
            $t->timestamps();
        });
        Schema::create('training_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('description')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true)->index();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('training_programs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('training_category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('summary')->nullable();
            $t->longText('description')->nullable();
            $t->string('cover_path')->nullable();
            $t->json('objectives')->nullable();
            $t->json('curriculum')->nullable();
            $t->json('benefits')->nullable();
            $t->json('requirements')->nullable();
            $t->string('duration')->nullable();
            $t->string('method')->nullable();
            $t->string('location')->nullable();
            $t->string('level')->nullable();
            $t->string('promo_label')->nullable();
            $t->unsignedInteger('quota')->nullable();
            $t->date('registration_deadline')->nullable();
            $t->string('certificate')->nullable();
            $t->decimal('price', 14, 2)->nullable();
            $t->string('status', 30)->default('draft')->index();
            $t->timestamp('published_at')->nullable()->index();
            $t->string('verification_status', 30)->default('unverified')->index();
            $t->text('verification_notes')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('training_schedules', function (Blueprint $t) {
            $t->id();
            $t->foreignId('training_program_id')->constrained()->cascadeOnDelete();
            $t->dateTime('starts_at')->index();
            $t->dateTime('ends_at')->nullable();
            $t->string('location')->nullable();
            $t->unsignedInteger('capacity')->nullable();
            $t->decimal('price', 14, 2)->nullable();
            $t->string('status', 30)->default('scheduled')->index();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('trainers', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('title')->nullable();
            $t->text('bio')->nullable();
            $t->string('photo_path')->nullable();
            $t->json('expertise')->nullable();
            $t->json('certifications')->nullable();
            $t->text('experience')->nullable();
            $t->string('linkedin_url')->nullable();
            $t->string('instagram_url')->nullable();
            $t->string('website_url')->nullable();
            $t->string('status', 30)->default('draft')->index();
            $t->timestamp('published_at')->nullable()->index();
            $t->string('verification_status', 30)->default('unverified')->index();
            $t->text('verification_notes')->nullable();
            $t->boolean('is_active')->default(true)->index();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('training_program_trainer', function (Blueprint $t) {
            $t->foreignId('training_program_id')->constrained()->cascadeOnDelete();
            $t->foreignId('trainer_id')->constrained()->cascadeOnDelete();
            $t->string('role')->nullable();
            $t->timestamps();
            $t->primary(['training_program_id', 'trainer_id']);
        });
        Schema::create('certification_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('description')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true)->index();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('certification_schemes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('certification_category_id')->constrained()->restrictOnDelete();
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('summary')->nullable();
            $t->longText('description')->nullable();
            $t->string('cover_path')->nullable();
            $t->string('partner_lsp')->nullable();
            $t->json('requirements')->nullable();
            $t->json('process')->nullable();
            $t->string('assessment_method')->nullable();
            $t->string('validity_period')->nullable();
            $t->string('location')->nullable();
            $t->decimal('fee', 14, 2)->nullable();
            $t->json('intended_participants')->nullable();
            $t->string('status', 30)->default('draft')->index();
            $t->timestamp('published_at')->nullable()->index();
            $t->string('verification_status', 30)->default('unverified')->index();
            $t->text('verification_notes')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('certification_schedules', function (Blueprint $t) {
            $t->id();
            $t->foreignId('certification_scheme_id')->constrained()->cascadeOnDelete();
            $t->dateTime('starts_at')->index();
            $t->dateTime('ends_at')->nullable();
            $t->string('location')->nullable();
            $t->unsignedInteger('capacity')->nullable();
            $t->decimal('fee', 14, 2)->nullable();
            $t->string('status', 30)->default('scheduled')->index();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('competency_units', function (Blueprint $t) {
            $t->id();
            $t->foreignId('certification_scheme_id')->constrained()->cascadeOnDelete();
            $t->string('code')->nullable();
            $t->string('title');
            $t->text('description')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
            $t->softDeletes();
            $t->unique(['certification_scheme_id', 'code']);
        });
        Schema::create('team_members', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('position')->nullable();
            $t->text('bio')->nullable();
            $t->string('photo_path')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true)->index();
            $t->string('status', 30)->default('draft')->index();
            $t->timestamp('published_at')->nullable()->index();
            $t->string('verification_status', 30)->default('unverified')->index();
            $t->text('verification_notes')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('services', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('type')->nullable()->index();
            $t->string('icon')->nullable();
            $t->string('cover_path')->nullable();
            $t->text('summary')->nullable();
            $t->longText('description')->nullable();
            $t->string('status', 30)->default('draft')->index();
            $t->timestamp('published_at')->nullable()->index();
            $t->string('verification_status', 30)->default('unverified')->index();
            $t->text('verification_notes')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('partners', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('type')->nullable()->index();
            $t->unsignedInteger('sort_order')->default(0);
            $t->string('website_url')->nullable();
            $t->string('logo_path')->nullable();
            $t->text('description')->nullable();
            $t->string('status', 30)->default('draft')->index();
            $t->timestamp('published_at')->nullable()->index();
            $t->string('verification_status', 30)->default('unverified')->index();
            $t->text('verification_notes')->nullable();
            $t->boolean('is_active')->default(true)->index();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('portfolios', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('client_name')->nullable();
            $t->string('category')->nullable()->index();
            $t->string('location')->nullable();
            $t->date('event_date')->nullable();
            $t->text('summary')->nullable();
            $t->longText('description')->nullable();
            $t->date('completed_at')->nullable();
            $t->string('status', 30)->default('draft')->index();
            $t->timestamp('published_at')->nullable()->index();
            $t->string('verification_status', 30)->default('unverified')->index();
            $t->text('verification_notes')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('portfolio_images', function (Blueprint $t) {
            $t->id();
            $t->foreignId('portfolio_id')->constrained()->cascadeOnDelete();
            $t->string('path');
            $t->string('alt_text')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });
        Schema::create('testimonials', function (Blueprint $t) {
            $t->id();
            $t->string('author_name');
            $t->string('author_title')->nullable();
            $t->string('institution')->nullable();
            $t->string('program')->nullable();
            $t->unsignedTinyInteger('rating')->nullable();
            $t->string('photo_path')->nullable();
            $t->string('video_url')->nullable();
            $t->text('content');
            $t->string('status', 30)->default('draft')->index();
            $t->timestamp('published_at')->nullable()->index();
            $t->string('verification_status', 30)->default('unverified')->index();
            $t->text('verification_notes')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('registrations', function (Blueprint $t) {
            $t->id();
            $t->nullableMorphs('registrable');
            $t->string('name');
            $t->string('email')->index();
            $t->string('phone')->nullable();
            $t->string('organization')->nullable();
            $t->text('notes')->nullable();
            $t->string('status', 30)->default('pending')->index();
            $t->json('metadata')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('contact_messages', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('email')->index();
            $t->string('phone')->nullable();
            $t->string('subject')->nullable();
            $t->text('message');
            $t->string('status', 30)->default('new')->index();
            $t->timestamp('read_at')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('article_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('description')->nullable();
            $t->boolean('is_active')->default(true)->index();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('articles', function (Blueprint $t) {
            $t->id();
            $t->foreignId('article_category_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('excerpt')->nullable();
            $t->longText('body');
            $t->string('featured_image_path')->nullable();
            $t->string('meta_title')->nullable();
            $t->text('meta_description')->nullable();
            $t->string('canonical_url')->nullable();
            $t->string('status', 30)->default('draft')->index();
            $t->timestamp('published_at')->nullable()->index();
            $t->string('verification_status', 30)->default('unverified')->index();
            $t->text('verification_notes')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('faqs', function (Blueprint $t) {
            $t->id();
            $t->nullableMorphs('faqable');
            $t->string('question');
            $t->text('answer');
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true)->index();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('legal_documents', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('type')->index();
            $t->longText('body');
            $t->string('version')->nullable();
            $t->date('effective_at')->nullable();
            $t->string('status', 30)->default('draft')->index();
            $t->timestamp('published_at')->nullable()->index();
            $t->string('verification_status', 30)->default('unverified')->index();
            $t->text('verification_notes')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
    }

    public function down(): void
    {
        foreach (['legal_documents', 'faqs', 'articles', 'article_categories', 'contact_messages', 'registrations', 'testimonials', 'portfolio_images', 'portfolios', 'partners', 'services', 'team_members', 'competency_units', 'certification_schedules', 'certification_schemes', 'certification_categories', 'training_program_trainer', 'trainers', 'training_schedules', 'training_programs', 'training_categories', 'settings'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
