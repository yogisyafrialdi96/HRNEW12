<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KontrakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_kontrak')->insert([
            [
                'nama_kontrak' => 'TETAP', 
                'deskripsi' => 'Pekerja Tetap Yayasan',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kontrak' => 'PKWT', 
                'deskripsi' => 'Pekerja Kontrak Waktu Tertentu',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kontrak' => 'PHL PERJAM', 
                'deskripsi' => 'Pekerja Harian Lepas/Jam Ngajar',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kontrak' => 'PHL Honor Paket', 
                'deskripsi' => 'Pekerja Harian Lepas Honor Paket Pengganti Guru',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kontrak' => 'PHL Operator BOS', 
                'deskripsi' => 'Pekerja Harian Lepas Operator BOS',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kontrak' => 'PHL 40 JAM', 
                'deskripsi' => 'Pekerja Harian Lepas Paket 40 jam seminggu',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kontrak' => 'PHL PENGASUH TPA', 
                'deskripsi' => 'Pekerja Harian Lepas Pengasuh TPA',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kontrak' => 'PHL PETUGAS TAMAN', 
                'deskripsi' => 'Pekerja Harian Lepas Petugas Taman',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
