<?php

namespace Database\Seeders;

use App\Models\FinanceCategory;
use Illuminate\Database\Seeder;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['code' => '1000', 'name' => 'ASET', 'type' => 'asset', 'description' => 'Aset Lancar dan Tidak Lancar'],
            ['code' => '1101', 'name' => 'Kas Tunai', 'type' => 'asset', 'description' => 'Uang tunai di kasir'],
            ['code' => '1102', 'name' => 'Bank BCA', 'type' => 'asset', 'description' => 'Rekening Operasional'],

            ['code' => '2000', 'name' => 'KEWAJIBAN', 'type' => 'liability', 'description' => 'Utang Jangka Pendek & Panjang'],

            ['code' => '3000', 'name' => 'EKUITAS', 'type' => 'equity', 'description' => 'Modal'],

            ['code' => '4000', 'name' => 'PENDAPATAN', 'type' => 'revenue', 'description' => 'Pemasukan Usaha'],
            ['code' => '4101', 'name' => 'Penjualan Merchandise', 'type' => 'revenue', 'description' => 'Hasil penjualan toko'],
            ['code' => '4102', 'name' => 'Jasa Sewa', 'type' => 'revenue', 'description' => 'Penyewaan Alat/Tempat'],

            ['code' => '5000', 'name' => 'BEBAN', 'type' => 'expense', 'description' => 'Pengeluaran Operasional'],
            ['code' => '5101', 'name' => 'Gaji Pegawai', 'type' => 'expense', 'description' => 'Payroll bulanan'],
            ['code' => '5102', 'name' => 'Listrik & Air', 'type' => 'expense', 'description' => 'Utility bills'],
        ];

        foreach ($categories as $cat) {
            FinanceCategory::firstOrCreate(
                ['code' => $cat['code']],
                $cat
            );
        }
    }
}
