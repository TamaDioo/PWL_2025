<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['penjualan_id' => 1, 'user_id' => 3, 'pembeli' => 'Rini Soekamti', 'penjualan_kode' => 'PJL0001', 'penjualan_tanggal' => '2025-03-01 11:00:00'],
            ['penjualan_id' => 2, 'user_id' => 3, 'pembeli' => 'Budi Setiawan', 'penjualan_kode' => 'PJL0002', 'penjualan_tanggal' => '2025-03-02 10:00:00'],
            ['penjualan_id' => 3, 'user_id' => 3, 'pembeli' => 'Rohmat Sulis', 'penjualan_kode' => 'PJL0003', 'penjualan_tanggal' => '2025-03-03 09:00:00'],
            ['penjualan_id' => 4, 'user_id' => 3, 'pembeli' => 'Suparman', 'penjualan_kode' => 'PJL0004', 'penjualan_tanggal' => '2025-03-04 12:00:00'],
            ['penjualan_id' => 5, 'user_id' => 2, 'pembeli' => 'Dika Saputra', 'penjualan_kode' => 'PJL0005', 'penjualan_tanggal' => '2025-03-05 13:15:00'],
            ['penjualan_id' => 6, 'user_id' => 3, 'pembeli' => 'Benny Irawan', 'penjualan_kode' => 'PJL0006', 'penjualan_tanggal' => '2025-03-06 13:00:00'],
            ['penjualan_id' => 7, 'user_id' => 1, 'pembeli' => 'Syahrul Gunawan', 'penjualan_kode' => 'PJL0007', 'penjualan_tanggal' => '2025-03-02 14:00:00'],
            ['penjualan_id' => 8, 'user_id' => 2, 'pembeli' => 'Rizki Ali', 'penjualan_kode' => 'PJL0008', 'penjualan_tanggal' => '2025-03-01 15:00:00'],
            ['penjualan_id' => 9, 'user_id' => 3, 'pembeli' => 'Tama Dio', 'penjualan_kode' => 'PJL0009', 'penjualan_tanggal' => '2025-03-02 10:30:00'],
            ['penjualan_id' => 10, 'user_id' => 3, 'pembeli' => 'Siti Mahmudah', 'penjualan_kode' => 'PJL0010', 'penjualan_tanggal' => '2025-03-04 08:00:00'],
        ];

        DB::table('t_penjualan')->insert($data);
    }
}
