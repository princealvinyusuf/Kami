<?php

namespace App\Models\Concerns;

use App\Enums\VerificationStatus;
use Illuminate\Database\Eloquent\Builder;

trait HasVerification
{
    protected function initializeHasVerification(): void
    {
        $this->mergeCasts(['verification_status' => VerificationStatus::class]);
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('verification_status', VerificationStatus::Verified);
    }
}
