<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id_penjualan'=> 1,'user_id'=> 1,'pembeli'=> 'Andi','penjualan_kode'=> 'TRX001','penjualan_tanggal'=> now()],
            ['id_penjualan'=> 2,'user_id'=> 2,'pembeli'=> 'Budi','penjualan_kode'=> 'TRX002','penjualan_tanggal'=> now()],
            ['id_penjualan'=> 3,'user_id'=> 3,'pembeli'=> 'Siti','penjualan_kode'=> 'TRX003','penjualan_tanggal'=> now()],
            ['id_penjualan'=> 4,'user_id'=> 1,'pembeli'=> 'Rina','penjualan_kode'=> 'TRX004','penjualan_tanggal'=> now()],
            ['id_penjualan'=> 5,'user_id'=> 2,'pembeli'=> 'Dani','penjualan_kode'=> 'TRX005','penjualan_tanggal'=> now()],
            ['id_penjualan'=> 6,'user_id'=> 3,'pembeli'=> 'Ahmad','penjualan_kode'=> 'TRX006','penjualan_tanggal'=> now()],
            ['id_penjualan'=> 7,'user_id'=> 1,'pembeli'=> 'Eko','penjualan_kode'=> 'TRX007','penjualan_tanggal'=> now()],
            ['id_penjualan'=> 8,'user_id'=> 2,'pembeli'=> 'Nia','penjualan_kode'=> 'TRX008','penjualan_tanggal'=> now()],
            ['id_penjualan'=> 9,'user_id'=> 3,'pembeli'=> 'Rudi','penjualan_kode'=> 'TRX009','penjualan_tanggal'=> now()],
            ['id_penjualan'=> 10,'user_id'=> 1,'pembeli'=> 'Lina','penjualan_kode'=> 'TRX010','penjualan_tanggal'=> now()],   
        ];

        DB::table('t_penjualan')->insert($data);
    }
}
