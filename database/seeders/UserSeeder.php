<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_user' => 1,
                'level_id' => 1,
                'user_kode' => 'admin',
                'nama' => 'Administrator',
                'password' => Hash::make('12345'), //class untuk mengenkripsi/hash password
            ],
            [
                'id_user' => 2,
                'level_id' => 2,
                'user_kode' => 'manager',
                'nama' => 'Manager',
                'password' => Hash::make('12345'),
            ],
            [
                'id_user' => 3,
                'level_id' => 3,
                'user_kode' => 'staff',
                'nama' => 'staff/Kasir',
                'password' => Hash::make('12345'),
            ],
        ];
        DB::table('m_user')->insert($data);
    }
}
