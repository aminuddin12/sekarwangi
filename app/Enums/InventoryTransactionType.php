<?php

namespace App\Enums;

enum InventoryTransactionType: string
{
    case IN = 'in'; // Masuk (Beli/Hibah)
    case OUT = 'out'; // Keluar (Dipakai)
    case SALE = 'sale'; // Terjual
    case ADJUSTMENT = 'adjustment'; // Koreksi Stok (Opname)
    case WRITE_OFF = 'write_off'; // Penghapusan (Rusak/Hilang)

    public function label(): string
    {
        return match($this) {
            self::IN => 'Barang Masuk',
            self::OUT => 'Barang Keluar',
            self::SALE => 'Penjualan',
            self::ADJUSTMENT => 'Penyesuaian Stok',
            self::WRITE_OFF => 'Penghapusan Aset',
        };
    }
}
