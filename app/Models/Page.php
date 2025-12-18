<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'page_type_id',
        'page_template_id',
        'content',
        'content_structure',
        'status',
        'published_at',
        'expired_at',
        'password',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'is_indexable',
        'featured_image',
        'custom_css',
        'custom_js',
        'author_id',
        'last_editor_id',
    ];

    protected $casts = [
        'content_structure' => 'array',
        'custom_css' => 'array',
        'custom_js' => 'array',
        'published_at' => 'datetime',
        'expired_at' => 'datetime',
        'is_indexable' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    // Relasi
    public function type()
    {
        return $this->belongsTo(PageType::class, 'page_type_id');
    }

    public function template()
    {
        return $this->belongsTo(PageTemplate::class, 'page_template_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'last_editor_id');
    }

    public function sections()
    {
        return $this->hasMany(PageSection::class)->orderBy('order');
    }

    public function revisions()
    {
        return $this->hasMany(PageRevision::class)->latest();
    }

    public function analytics()
    {
        return $this->hasMany(PageAnalytic::class);
    }
}
