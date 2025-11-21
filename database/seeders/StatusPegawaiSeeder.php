<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusPegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_statuspegawai')->insert([
            [
                'nama_status' => 'Aktif', 
                'deskripsi' => 'Pegawai Aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_status' => 'Resign', 
                'deskripsi' => 'Pegawai Resign',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_status' => 'Pensiun', 
                'deskripsi' => 'Pegawai Pensiun',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_status' => 'Pensiun Dini', 
                'deskripsi' => 'Pegawai Pensiun Dini',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_status' => 'LWP', 
                'deskripsi' => 'Leave Without Payment',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_status' => 'Tugas Belajar', 
                'deskripsi' => 'Tugas Belajar Lagi',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_status' => 'Habis Kontrak', 
                'deskripsi' => 'Pegawai Habis Kontrak',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_status' => 'Meninggal Dunia', 
                'deskripsi' => 'Pegawai Meninggal',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
