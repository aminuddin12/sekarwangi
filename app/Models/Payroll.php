<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'slip_number',
        'period',
        'basic_salary',
        'total_allowances',
        'total_deductions',
        'overtime_pay',
        'bonus',
        'tax_amount',
        'reimbursement',
        'net_salary',
        'allowance_details',
        'deduction_details',
        'status',
        'payment_method',
        'bank_name',
        'account_number',
        'payment_date',
        'approved_by',
        'notes',
    ];

    protected $casts = [
        'allowance_details' => 'array',
        'deduction_details' => 'array',
        'payment_date' => 'date',
        'basic_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
