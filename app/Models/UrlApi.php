<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission;

class UrlApi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'group_id', 'name', 'method', 'url', 'permission_id',
        'is_active', 'is_public', 'rate_limit', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_public' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(UrlGroup::class, 'group_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
