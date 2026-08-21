<?php

namespace App\Models;

use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasVerification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    use HasPublishing,HasVerification,SoftDeletes;

    protected $fillable = ['name', 'slug', 'type', 'sort_order', 'website_url', 'logo_path', 'description', 'status', 'published_at', 'verification_status', 'verification_notes', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
