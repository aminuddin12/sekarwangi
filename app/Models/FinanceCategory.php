<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['code', 'name', 'type', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function records()
    {
        return $this->hasMany(FinanceRecord::class);
    }
}
