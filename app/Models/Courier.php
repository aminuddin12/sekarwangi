<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code', // jne, jnt, sicepat
        'logo',
        'website',
        'description',
        'service_types', // ["REG", "YES"]
        'tracking_url_template',
        'is_active',
    ];

    protected $casts = [
        'service_types' => 'array',
        'is_active' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
