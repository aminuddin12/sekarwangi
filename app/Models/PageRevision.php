<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'user_id',
        'title',
        'content',
        'content_structure',
        'revision_note',
    ];

    protected $casts = [
        'content_structure' => 'array',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
