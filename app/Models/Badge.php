<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Badge extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'icon', 'color', 'description', 'type'
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($badge) {
            if (empty($badge->slug)) {
                $badge->slug = Str::slug($badge->name);
            }
        });
    }

    // Polymorphic Relation: Get all users that have this badge
    public function users()
    {
        return $this->morphedByMany(User::class, 'model', 'model_has_badges');
    }

    public function products()
    {
        return $this->morphedByMany(Product::class, 'model', 'model_has_badges');
    }
}
