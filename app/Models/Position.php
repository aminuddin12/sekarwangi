<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'level', 'is_executive'];

    protected $casts = [
        'is_executive' => 'boolean',
        'level' => 'integer',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(UserDetail::class);
    }
}
