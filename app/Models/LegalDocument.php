<?php

namespace App\Models;

use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasVerification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LegalDocument extends Model
{
    use HasPublishing,HasVerification,SoftDeletes;

    protected $fillable = ['title', 'slug', 'type', 'body', 'file_path', 'version', 'effective_at', 'status', 'published_at', 'verification_status', 'verification_notes'];

    protected function casts(): array
    {
        return ['effective_at' => 'date'];
    }
}
