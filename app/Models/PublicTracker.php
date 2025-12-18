<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicTracker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'provider',
        'tracking_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
