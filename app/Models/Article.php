<?php

namespace App\Models;

use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasVerification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasPublishing,HasVerification,SoftDeletes;

    protected $fillable = ['article_category_id', 'author_id', 'title', 'slug', 'excerpt', 'body', 'featured_image_path', 'meta_title', 'meta_description', 'canonical_url', 'status', 'published_at', 'verification_status', 'verification_notes'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
