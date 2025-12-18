<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class ApiAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'provider',
        'name',
        'api_key',
        'api_secret',
        'app_id',
        'endpoint_url',
        'webhook_secret',
        'access_token',
        'refresh_token',
        'expires_at',
        'additional_config',
        'is_active',
        'environment',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'additional_config' => 'array',
        'is_active' => 'boolean',
    ];

    // Encrypt API Secret
    public function setApiSecretAttribute($value)
    {
        $this->attributes['api_secret'] = !empty($value) ? Crypt::encryptString($value) : null;
    }

    public function getApiSecretAttribute($value)
    {
        return !empty($value) ? Crypt::decryptString($value) : null;
    }
}
