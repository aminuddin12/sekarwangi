<?php

namespace App\Enums;

enum LogSeverity: string
{
    case INFO = 'info';
    case WARNING = 'warning';
    case DANGER = 'danger';
    case CRITICAL = 'critical';

    public function color(): string
    {
        return match($this) {
            self::INFO => 'blue',
            self::WARNING => 'yellow',
            self::DANGER => 'orange',
            self::CRITICAL => 'red',
        };
    }
}
