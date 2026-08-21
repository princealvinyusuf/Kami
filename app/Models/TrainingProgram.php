<?php

namespace App\Models;

use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasVerification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingProgram extends Model
{
    use HasPublishing,HasVerification,SoftDeletes;

    protected $fillable = ['training_category_id', 'title', 'slug', 'summary', 'description', 'cover_path', 'objectives', 'curriculum', 'benefits', 'requirements', 'duration', 'method', 'location', 'level', 'promo_label', 'quota', 'registration_deadline', 'certificate', 'price', 'status', 'published_at', 'verification_status', 'verification_notes'];

    protected function casts(): array
    {
        return ['objectives' => 'array', 'curriculum' => 'array', 'benefits' => 'array', 'requirements' => 'array', 'registration_deadline' => 'date', 'price' => 'decimal:2'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(TrainingCategory::class, 'training_category_id');
    }

    public function schedules()
    {
        return $this->hasMany(TrainingSchedule::class);
    }

    public function trainers()
    {
        return $this->belongsToMany(Trainer::class, 'training_program_trainer')->withPivot('role')->withTimestamps();
    }

    public function faqs()
    {
        return $this->morphMany(Faq::class, 'faqable');
    }
}
