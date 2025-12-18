<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = ['locale', 'group', 'key', 'value', 'is_json'];

    protected $casts = [
        'is_json' => 'boolean',
    ];

    // Helper untuk mengambil value (jika JSON, decode dulu)
    public function getValueAttribute($value)
    {
        if ($this->is_json) {
            return json_decode($value, true);
        }
        return $value;
    }
}
