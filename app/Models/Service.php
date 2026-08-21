<?php

namespace App\Models;

use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasVerification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasPublishing,HasVerification,SoftDeletes;

    protected $fillable = ['name', 'slug', 'type', 'icon', 'cover_path', 'summary', 'description', 'status', 'published_at', 'verification_status', 'verification_notes', 'sort_order'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function faqs()
    {
        return $this->morphMany(Faq::class, 'faqable');
    }
}
