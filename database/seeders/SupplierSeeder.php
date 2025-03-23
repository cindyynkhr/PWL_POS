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
        $data = [
            [
                'supplier_id' => 1,
                'supplier_kode' => 'SUP001',
                'nama' => 'Budi Santoso',
                'nama_pt' => 'PT Maju Sejahtera',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta',
            ],
            [
                'supplier_id' => 2,
                'supplier_kode' => 'SUP002',
                'nama' => 'Siti Aminah',
                'nama_pt' => 'PT Sukses Bersama',
                'alamat' => 'Jl. Raya Darmo No. 15, Surabaya',
            ],
            [
                'supplier_id' => 3,
                'supplier_kode' => 'SUP003',
                'nama' => 'Ahmad Fauzi',
                'nama_pt' => 'PT Cahaya Abadi',
                'alamat' => 'Jl. Asia Afrika No. 22, Bandung',
            ],
        ];
        DB::table('m_supplier')->insert($data);
    }
}
