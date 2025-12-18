<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'subtitle',
        'excerpt',
        'content',
        'status',
        'visibility',
        'password',
        'published_at',
        'expired_at',
        'thumbnail',
        'banner_image',
        'banner_caption',
        'is_featured',
        'is_pinned',
        'allow_comments',
        'view_count',
        'share_count',
        'like_count',
        'reading_time',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'is_indexable',
        'og_title',
        'og_description',
        'og_image',
    ];

    protected $casts = [
        'status' => PostStatus::class,
        'published_at' => 'datetime',
        'expired_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_pinned' => 'boolean',
        'allow_comments' => 'boolean',
        'is_indexable' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            // Auto calculate reading time (avg 200 words per min)
            if (!empty($post->content)) {
                $wordCount = str_word_count(strip_tags($post->content));
                $post->reading_time = ceil($wordCount / 200);
            }
        });
    }

    // Relasi
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function categories()
    {
        return $this->belongsToMany(PostCategory::class, 'category_post')->withPivot('is_primary');
    }

    public function tags()
    {
        return $this->belongsToMany(PostTag::class, 'post_tag');
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function media()
    {
        return $this->hasMany(PostMedia::class);
    }

    // Scope Helpers
    public function scopePublished($query)
    {
        return $query->where('status', PostStatus::PUBLISHED)
                     ->where('published_at', '<=', now());
    }
}
