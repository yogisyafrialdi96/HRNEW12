<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_tahunajaran')->insert([
            [
                'periode'       => '2024/2025',
                'awal_periode'  => '2024-07-01',
                'akhir_periode' => '2025-06-30',
                'keterangan'    => 'Tahun ajaran 2024/2025',
                'is_active'     => true,
                'created_by'    => 1, // ganti dengan user_id yang sesuai
                'updated_by'    => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'periode'       => '2025/2026',
                'awal_periode'  => '2025-07-01',
                'akhir_periode' => '2026-06-30',
                'keterangan'    => 'Tahun ajaran 2025/2026',
                'is_active'     => false,
                'created_by'    => 1,
                'updated_by'    => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
