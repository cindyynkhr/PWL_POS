<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id_kategori' => 1, 'kategori_kode' => 'ELC', 'kategori_nama' => 'Elektronik'],
            ['id_kategori' => 2, 'kategori_kode' => 'FSH', 'kategori_nama' => 'Fashion'],
            ['id_kategori' => 3, 'kategori_kode' => 'KSN', 'kategori_nama' => 'Kesehatan'],
            ['id_kategori' => 4, 'kategori_kode' => 'MKN', 'kategori_nama' => 'Makanan dan Minuman'],
            ['id_kategori' => 5, 'kategori_kode' => 'ATK', 'kategori_nama' => 'Alat Tulis Kantor'],
        ];
        DB::table('m_kategori')->insert($data);
    }
}
