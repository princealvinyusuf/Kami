<?php

namespace App\Models;

use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasVerification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trainer extends Model
{
    use HasPublishing,HasVerification,SoftDeletes;

    protected $fillable = ['name', 'slug', 'title', 'bio', 'photo_path', 'expertise', 'certifications', 'experience', 'linkedin_url', 'instagram_url', 'website_url', 'status', 'published_at', 'verification_status', 'verification_notes', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'expertise' => 'array', 'certifications' => 'array'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function programs()
    {
        return $this->belongsToMany(TrainingProgram::class, 'training_program_trainer')->withPivot('role')->withTimestamps();
    }
}
