<?php

namespace App\Models;

use App\Enums\FinanceTransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference_number',
        'title',
        'description',
        'transaction_type',
        'amount',
        'transaction_date',
        'finance_category_id',
        'referenceable_type',
        'referenceable_id',
        'payer_payee_name',
        'payment_method',
        'receipt_image',
        'attachment_file',
        'tax_amount',
        'status',
        'recorded_by',
        'verified_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'transaction_type' => FinanceTransactionType::class,
    ];

    // Relasi ke User Perekam
    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Relasi ke Auditor/Verifier
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Relasi ke Kategori Akun
    public function category()
    {
        return $this->belongsTo(FinanceCategory::class, 'finance_category_id');
    }

    // Relasi Polimorfik (Bisa terhubung ke Project, Event/Jadwal, atau Order)
    public function referenceable()
    {
        return $this->morphTo();
    }
}
