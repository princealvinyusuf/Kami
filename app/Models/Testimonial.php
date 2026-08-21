<?php

namespace App\Models;

use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasVerification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasPublishing,HasVerification,SoftDeletes;

    protected $fillable = ['author_name', 'author_title', 'institution', 'program', 'rating', 'photo_path', 'video_url', 'content', 'status', 'published_at', 'verification_status', 'verification_notes'];

    protected function casts(): array
    {
        return ['rating' => 'integer'];
    }
}
