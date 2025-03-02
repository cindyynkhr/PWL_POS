<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data =[
            ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'ELK001', 'barang_nama' => 'Smartphonre', 'harga_beli' => 3000000, 'harga_jual' => 3500000],
            ['barang_id' => 2, 'kategori_id' => 1, 'barang_kode' => 'ELK002', 'barang_nama' => 'Laptop', 'harga_beli' => 8000000, 'harga_jual' => 9500000],
            ['barang_id' => 3, 'kategori_id' => 2, 'barang_kode' => 'FAS001', 'barang_nama' => 'Kaos Polos', 'harga_beli' => 50000, 'harga_jual' => 75000],
            ['barang_id' => 4, 'kategori_id' => 2, 'barang_kode' => 'FAS002', 'barang_nama' => 'Celana Jeans', 'harga_beli' => 150000, 'harga_jual' => 200000],
            ['barang_id' => 5, 'kategori_id' => 3, 'barang_kode' => 'KSN001', 'barang_nama' => 'Masker Medis', 'harga_beli' => 20000, 'harga_jual' => 30000],
            ['barang_id' => 6, 'kategori_id' => 3, 'barang_kode' => 'KSN002', 'barang_nama' => 'Hand Zanitizer', 'harga_beli' => 25000, 'harga_jual' => 40000],
            ['barang_id' => 7, 'kategori_id' => 4, 'barang_kode' => 'MKN001', 'barang_nama' => 'Mie Instan', 'harga_beli' => 3000, 'harga_jual' => 5000],
            ['barang_id' => 8, 'kategori_id' => 4, 'barang_kode' => 'MKN002', 'barang_nama' => 'Minuman Kaleng', 'harga_beli' => 6000, 'harga_jual' => 9000],
            ['barang_id' => 9, 'kategori_id' => 5, 'barang_kode' => 'ATK001', 'barang_nama' => 'Bulpoin', 'harga_beli' => 2000, 'harga_jual' => 4000],
            ['barang_id' => 10, 'kategori_id' => 5, 'barang_kode' => 'ATK002', 'barang_nama' => 'Buku Tulis', 'harga_beli' => 5000, 'harga_jual' => 10000]
        ];
        DB::table('m_barang')->insert($data);
    }
}
