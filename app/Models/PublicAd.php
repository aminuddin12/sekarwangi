<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublicAd extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ad_placement_id',
        'name',
        'type',
        'content',
        'image_path',
        'target_url',
        'start_at',
        'end_at',
        'impression_count',
        'click_count',
        'is_active',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function placement()
    {
        return $this->belongsTo(AdPlacement::class, 'ad_placement_id');
    }

    // Scope untuk iklan yang sedang tayang
    public function scopeActive($query)
    {
        $now = now();
        return $query->where('is_active', true)
                     ->where(function($q) use ($now) {
                         $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
                     })
                     ->where(function($q) use ($now) {
                         $q->whereNull('end_at')->orWhere('end_at', '>=', $now);
                     });
    }
}
