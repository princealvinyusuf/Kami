<?php

namespace App\Models;

use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasVerification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portfolio extends Model
{
    use HasPublishing,HasVerification,SoftDeletes;

    protected $fillable = ['title', 'slug', 'client_name', 'category', 'location', 'event_date', 'summary', 'description', 'completed_at', 'status', 'published_at', 'verification_status', 'verification_notes'];

    protected function casts(): array
    {
        return ['completed_at' => 'date', 'event_date' => 'date'];
    }

    public function images()
    {
        return $this->hasMany(PortfolioImage::class)->orderBy('sort_order');
    }
}
