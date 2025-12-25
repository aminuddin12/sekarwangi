<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission;

class UrlAuthenticated extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'url_authenticated';

    protected $fillable = [
        'group_id', 'parent_id', 'name', 'url','route', 'icon',
        'badge', 'badge_color', 'hint', 'order', 'permission_id',
        'is_active', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(UrlGroup::class, 'group_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    // Hierarki
    public function parent()
    {
        return $this->belongsTo(UrlAuthenticated::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(UrlAuthenticated::class, 'parent_id')->orderBy('order');
    }
}
