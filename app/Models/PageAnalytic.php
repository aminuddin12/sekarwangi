<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'date',
        'views',
        'unique_visitors',
        'avg_time_spent',
    ];

    protected $casts = [
        'date' => 'date',
        'avg_time_spent' => 'decimal:2',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
