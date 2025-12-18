<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ApiPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'name',
        'logo',
        'merchant_id',
        'client_key',
        'server_key',
        'mode',
        'fee_flat',
        'fee_percent',
        'is_active',
    ];

    protected $casts = [
        'fee_flat' => 'decimal:2',
        'fee_percent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function setServerKeyAttribute($value)
    {
        $this->attributes['server_key'] = !empty($value) ? Crypt::encryptString($value) : null;
    }

    public function getServerKeyAttribute($value)
    {
        return !empty($value) ? Crypt::decryptString($value) : null;
    }
}
