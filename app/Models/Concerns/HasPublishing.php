<?php

namespace App\Models\Concerns;

use App\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Builder;

trait HasPublishing
{
    protected function initializeHasPublishing(): void
    {
        $this->mergeCasts(['status' => ContentStatus::class, 'published_at' => 'datetime']);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ContentStatus::Published)->where('published_at', '<=', now());
    }
}
