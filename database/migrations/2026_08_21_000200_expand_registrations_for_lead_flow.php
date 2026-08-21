<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('registration_type', 30)->default('consultation')->after('id')->index();
            $table->nullableMorphs('schedule');
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('education')->nullable();
            $table->string('institution')->nullable();
            $table->string('occupation')->nullable();
            $table->string('preferred_schedule')->nullable();
            $table->string('nik', 30)->nullable();
            $table->string('gender', 30)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('document_path')->nullable();
            $table->string('document_name')->nullable();
            $table->string('document_mime', 120)->nullable();
            $table->unsignedBigInteger('document_size')->nullable();
            $table->string('status', 30)->default('new')->change();
            $table->index(['registration_type', 'status', 'created_at']);
        });

        DB::table('registrations')->where('status', 'pending')->update(['status' => 'new']);
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropIndex(['registration_type', 'status', 'created_at']);
            $table->dropMorphs('schedule');
            $table->dropColumn([
                'registration_type',
                'city',
                'province',
                'education',
                'institution',
                'occupation',
                'preferred_schedule',
                'nik',
                'gender',
                'birth_date',
                'document_path',
                'document_name',
                'document_mime',
                'document_size',
            ]);
            $table->string('status', 30)->default('pending')->change();
        });
    }
};
