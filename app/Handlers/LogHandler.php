<?php

namespace App\Handlers;

use App\Enums\LogSeverity;
use App\Models\SystemActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Throwable;

class LogHandler
{
    /**
     * Simpan Exception/Error ke Database
     */
    public static function capture(Throwable $e, string $context = 'system_error'): void
    {
        // Jangan log error validasi biasa (bikin penuh database)
        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return;
        }

        try {
            SystemActivityLog::create([
                'log_name' => $context,
                'description' => $e->getMessage(),
                'subject_type' => null,
                'subject_id' => null,
                'causer_type' => Auth::check() ? get_class(Auth::user()) : null,
                'causer_id' => Auth::id(),
                'properties' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => array_slice($e->getTrace(), 0, 5), // Simpan 5 trace teratas saja agar hemat
                    'input' => Request::except(['password', 'password_confirmation', 'credit_card']), // Sensor data sensitif
                ],
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'url' => Request::fullUrl(),
                'method' => Request::method(),
                'severity' => LogSeverity::CRITICAL, // Default error sistem dianggap critical
                'event' => 'exception_thrown',
            ]);
        } catch (\Exception $loggingError) {
            // Fallback jika database down, tulis ke file log laravel biasa
            \Illuminate\Support\Facades\Log::error('Failed to write to DB Log: ' . $loggingError->getMessage());
            \Illuminate\Support\Facades\Log::error($e);
        }
    }
}
