<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'warehouse_id',
        'supplier_id',
        'name',
        'sku',
        'barcode',
        'brand',
        'model',
        'description',
        'quantity',
        'minimum_stock',
        'unit',
        'purchase_price',
        'selling_price',
        'purchase_date',
        'condition',
        'status',
        'is_lendable',
        'is_saleable',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'is_lendable' => 'boolean',
        'is_saleable' => 'boolean',
    ];

    // Relasi
    public function category()
    {
        return $this->belongsTo(InventoryCategory::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(InventoryWarehouse::class);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function images()
    {
        return $this->hasMany(InventoryItemImage::class)->orderBy('sort_order');
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function loans()
    {
        return $this->hasMany(InventoryLoan::class);
    }

    // Relasi Log Aktivitas Barang - YANG TERLEWAT SEBELUMNYA
    public function logs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    // Helper: Cek Stok Kritis
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->minimum_stock;
    }
}
