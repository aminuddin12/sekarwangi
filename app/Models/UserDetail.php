<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'id_number',
        'identity_card_number',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'marital_status',
        'address',
        'city',
        'province',
        'postal_code',
        'country',
        'position_id',
        'join_date',
        'resign_date',
        'join_approved_by',
        'bio',
        'social_links',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'join_date' => 'date',
        'resign_date' => 'date',
        'social_links' => 'array', // Otomatis convert JSON ke Array
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'join_approved_by');
    }

    // Accessor untuk Alamat Lengkap
    public function getFullAddressAttribute(): string
    {
        return "{$this->address}, {$this->city}, {$this->province} {$this->postal_code}";
    }
}
