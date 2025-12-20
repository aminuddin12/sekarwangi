<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UrlPublic extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'url_public';

    protected $fillable = [
        'group_id', 'parent_id', 'name', 'url', 'icon',
        'description', 'color', 'hint', 'target', 'order',
        'is_active', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(UrlGroup::class, 'group_id');
    }

    public function parent()
    {
        return $this->belongsTo(UrlPublic::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(UrlPublic::class, 'parent_id')->orderBy('order');
    }
}
