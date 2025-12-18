<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryLoan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'loan_code',
        'inventory_item_id',
        'borrower_id',
        'quantity',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'return_condition',
        'fine_amount',
        'purpose',
        'approved_by',
        'admin_notes',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'fine_amount' => 'decimal:2',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
