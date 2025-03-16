<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // ['kategori_id' => 1, 'kategori_kode' => 'ELCT', 'kategori_nama' => 'Elektronik'],
            // ['kategori_id' => 2, 'kategori_kode' => 'FSHN', 'kategori_nama' => 'Pakaian'],
            // ['kategori_id' => 3, 'kategori_kode' => 'FOOD', 'kategori_nama' => 'Makanan'],
            // ['kategori_id' => 4, 'kategori_kode' => 'BVRG', 'kategori_nama' => 'Minuman'],
            // ['kategori_id' => 5, 'kategori_kode' => 'BOOK', 'kategori_nama' => 'Buku'],
            ['kategori_id' => 6, 'kategori_kode' => 'CML', 'kategori_nama' => 'Cemilan', 'created_at' => now()],
            ['kategori_id' => 7, 'kategori_kode' => 'MNR', 'kategori_nama' => 'Minuman Ringan', 'created_at' => now()],
        ];

        DB::table('m_kategori')->insert($data);
    }
}
