<?php

namespace App\Helpers;

use App\Enums\LogSeverity;
use App\Models\SystemActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Catat Log Aktivitas
     */
    public static function log(
        string $description,
        string $logName = 'default',
        ?Model $subject = null,
        array $properties = [],
        LogSeverity $severity = LogSeverity::INFO
    ): void
    {
        SystemActivityLog::create([
            'log_name' => $logName,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
            'causer_type' => Auth::check() ? get_class(Auth::user()) : null,
            'causer_id' => Auth::id(),
            'properties' => $properties, // Otomatis dicast ke JSON oleh Model
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'severity' => $severity,
        ]);
    }
}
