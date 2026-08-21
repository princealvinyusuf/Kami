<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['trainers', 'team_members', 'partners'] as $table) {
            $addStatus = ! Schema::hasColumn($table, 'status');
            $addPublishedAt = ! Schema::hasColumn($table, 'published_at');

            if (! $addStatus && ! $addPublishedAt) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($addStatus, $addPublishedAt) {
                if ($addStatus) {
                    $blueprint->string('status', 30)->default('draft')->index();
                }
                if ($addPublishedAt) {
                    $blueprint->timestamp('published_at')->nullable()->index();
                }
            });
        }
    }

    public function down(): void
    {
        // These columns are part of the base domain schema. Keeping this
        // migration reversible as a no-op prevents rollback from removing
        // columns required by the corresponding models.
    }
};
