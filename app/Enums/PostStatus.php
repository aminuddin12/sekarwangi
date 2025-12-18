<?php

namespace App\Enums;

enum PostStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case PUBLISHED = 'published';
    case SCHEDULED = 'scheduled';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Konsep',
            self::PENDING => 'Menunggu Review',
            self::PUBLISHED => 'Terbit',
            self::SCHEDULED => 'Terjadwal',
            self::ARCHIVED => 'Diarsipkan',
        };
    }
}
