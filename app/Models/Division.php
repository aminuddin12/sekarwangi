<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Division extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'image_url', 'parent_id'];

    // Auto Slug saat create/update
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($division) {
            if (empty($division->slug)) {
                $division->slug = Str::slug($division->name);
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Division::class, 'parent_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'division_user')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }
}
