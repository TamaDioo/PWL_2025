<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'LPTP', 'barang_nama' => 'Laptop', 'harga_beli' => 9000000, 'harga_jual' => 10000000],
            ['barang_id' => 2, 'kategori_id' => 1, 'barang_kode' => 'SMPH', 'barang_nama' => 'Smartphone', 'harga_beli' => 5000000, 'harga_jual' => 6000000],
            ['barang_id' => 3, 'kategori_id' => 2, 'barang_kode' => 'KMJA', 'barang_nama' => 'Kemeja', 'harga_beli' => 250000, 'harga_jual' => 300000],
            ['barang_id' => 4, 'kategori_id' => 2, 'barang_kode' => 'CLJN', 'barang_nama' => 'Celana Jeans', 'harga_beli' => 300000, 'harga_jual' => 350000],
            ['barang_id' => 5, 'kategori_id' => 3, 'barang_kode' => 'MINS', 'barang_nama' => 'Mie Instan', 'harga_beli' => 3000, 'harga_jual' => 5000],
            ['barang_id' => 6, 'kategori_id' => 3, 'barang_kode' => 'BSKT', 'barang_nama' => 'Biskuit', 'harga_beli' => 7000, 'harga_jual' => 8000],
            ['barang_id' => 7, 'kategori_id' => 4, 'barang_kode' => 'KOPI', 'barang_nama' => 'Kopi', 'harga_beli' => 4000, 'harga_jual' => 5000],
            ['barang_id' => 8, 'kategori_id' => 4, 'barang_kode' => 'THBT', 'barang_nama' => 'Teh Botol', 'harga_beli' => 5000, 'harga_jual' => 6000],
            ['barang_id' => 9, 'kategori_id' => 5, 'barang_kode' => 'BKGA3', 'barang_nama' => 'Buku Gambar A3', 'harga_beli' => 9000, 'harga_jual' => 10000],
            ['barang_id' => 10, 'kategori_id' => 5, 'barang_kode' => 'BKTL', 'barang_nama' => 'Buku Tulis', 'harga_beli' => 4000, 'harga_jual' => 5000],
        ];

        DB::table('m_barang')->insert($data);
    }
}
