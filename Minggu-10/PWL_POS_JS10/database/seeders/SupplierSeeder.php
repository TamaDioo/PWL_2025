<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_supplier')->insert([
            [
                'supplier_id' => 1,
                'supplier_kode' => 'SUP001',
                'supplier_nama' => 'PT Sumber Rezeki',
                'supplier_alamat' => 'Jl. Merdeka No. 123, Surabaya',
            ],
            [
                'supplier_id' => 2,
                'supplier_kode' => 'SUP002',
                'supplier_nama' => 'CV Maju Jaya',
                'supplier_alamat' => 'Jl. Diponegoro No. 45, Blitar',
            ],
            [
                'supplier_id' => 3,
                'supplier_kode' => 'SUP003',
                'supplier_nama' => 'UD Berkah Sentosa',
                'supplier_alamat' => 'Jl. Soekarno Hatta No. 78, Malang',
            ]
        ]);
    }
}
