<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingLinkClick extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'marketing_link_id',
        'ip_address',
        'user_agent',
        'referer',
        'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public function link()
    {
        return $this->belongsTo(MarketingLink::class, 'marketing_link_id');
    }
}
