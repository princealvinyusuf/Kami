<?php

namespace App\Models;

use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasVerification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificationScheme extends Model
{
    use HasPublishing,HasVerification,SoftDeletes;

    protected $fillable = ['certification_category_id', 'title', 'slug', 'summary', 'description', 'cover_path', 'partner_lsp', 'requirements', 'process', 'assessment_method', 'validity_period', 'location', 'fee', 'intended_participants', 'status', 'published_at', 'verification_status', 'verification_notes'];

    protected function casts(): array
    {
        return ['requirements' => 'array', 'process' => 'array', 'intended_participants' => 'array', 'fee' => 'decimal:2'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(CertificationCategory::class, 'certification_category_id');
    }

    public function schedules()
    {
        return $this->hasMany(CertificationSchedule::class);
    }

    public function competencyUnits()
    {
        return $this->hasMany(CompetencyUnit::class);
    }

    public function faqs()
    {
        return $this->morphMany(Faq::class, 'faqable');
    }
}
