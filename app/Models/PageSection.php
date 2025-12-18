<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'section_name',
        'component_type',
        'order',
        'data',
        'style_config',
        'is_visible',
    ];

    protected $casts = [
        'data' => 'array',
        'style_config' => 'array',
        'is_visible' => 'boolean',
        'order' => 'integer',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
