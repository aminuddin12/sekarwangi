<?php

namespace App\Models;

use App\Enums\LogSeverity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SystemActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'severity',
        'event',
        'batch_uuid',
    ];

    protected $casts = [
        'properties' => 'array', // Penting: Otomatis convert JSON properties ke Array
        'severity' => LogSeverity::class,
    ];

    // Objek yang dimanipulasi (Misal: Product A yang diedit)
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    // Pelaku (User / System)
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }
}
