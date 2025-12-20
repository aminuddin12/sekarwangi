<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UrlGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'section', 'description',
        'is_active', 'order', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        // Auto-fill audit columns
        static::creating(function ($model) {
            $model->created_by = Auth::id();
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    public function apis()
    {
        return $this->hasMany(UrlApi::class, 'group_id');
    }

    public function authenticatedMenus()
    {
        return $this->hasMany(UrlAuthenticated::class, 'group_id');
    }

    public function publicLinks()
    {
        return $this->hasMany(UrlPublic::class, 'group_id');
    }
}
