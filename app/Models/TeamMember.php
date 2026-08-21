<?php

namespace App\Models;

use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasVerification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamMember extends Model
{
    use HasPublishing,HasVerification,SoftDeletes;

    protected $fillable = ['name', 'slug', 'position', 'bio', 'photo_path', 'sort_order', 'is_active', 'status', 'published_at', 'verification_status', 'verification_notes'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
