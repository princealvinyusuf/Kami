<?php

namespace App\Services;

use App\Models\CertificationScheme;
use App\Models\Service;
use App\Models\TrainingProgram;
use Illuminate\Database\Eloquent\Model;

class RegistrationTargetResolver
{
    public const TYPES = ['training', 'certification', 'consultation', 'internship'];

    public function resolve(string $type, ?string $slug): ?Model
    {
        abort_unless(in_array($type, self::TYPES, true), 404);

        if (in_array($type, ['training', 'certification'], true)) {
            abort_if(blank($slug), 404);
        }

        if (blank($slug)) {
            return null;
        }

        $query = match ($type) {
            'training' => TrainingProgram::query(),
            'certification' => CertificationScheme::query(),
            default => Service::query()->where('type', $type === 'consultation' ? 'konsultan' : 'pemagangan'),
        };

        return $query->published()->verified()->where('slug', $slug)->firstOrFail();
    }

    public function label(string $type): string
    {
        return match ($type) {
            'training' => 'Pelatihan',
            'certification' => 'Sertifikasi',
            'consultation' => 'Konsultasi',
            'internship' => 'Pemagangan',
            default => abort(404),
        };
    }

    public function title(?Model $target, string $type): string
    {
        return $target?->title ?? $target?->name ?? $this->label($type);
    }
}
