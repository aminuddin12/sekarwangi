<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'specification',
        'price_idr',
        'price_usd',
        'stock',
        'is_digital',
        'weight_grams',
        'thumbnail',
    ];

    protected $casts = [
        'is_digital' => 'boolean',
        'price_idr' => 'decimal:2',
        'price_usd' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // --- RELATIONS ---

    // Kategori
    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_product_category');
    }

    // Varian
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Media (Gambar/Video)
    public function media()
    {
        return $this->hasMany(ProductMedia::class)->orderBy('sort_order');
    }

    // Review User
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    // Link Eksternal (Shopee/Tokped)
    public function externalLinks()
    {
        return $this->hasMany(ProductExternalLink::class);
    }

    // Badge Polymorphic
    public function badges()
    {
        return $this->morphToMany(Badge::class, 'model', 'model_has_badges');
    }
}
