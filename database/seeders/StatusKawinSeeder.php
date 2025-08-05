<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusKawinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_statuskawin')->insert([
            [
                'nama' => 'TK/0',
                'tarif_pkp' => '54000000',
                'keterangan' => 'Tidak Kawin, tanpa tanggungan',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'TK/1',
                'tarif_pkp' => '58500000',
                'keterangan' => 'Tidak Kawin, 1 tanggungan',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'TK/2',
                'tarif_pkp' => '63000000',
                'keterangan' => 'Tidak Kawin, 2 tanggungan',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'TK/3',
                'tarif_pkp' => '67500000',
                'keterangan' => 'Tidak Kawin, 3 tanggungan',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'K/0',
                'tarif_pkp' => '58500000',
                'keterangan' => 'Kawin, tanpa tanggungan',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'K/1',
                'tarif_pkp' => '63000000',
                'keterangan' => 'Kawin, 1 tanggungan',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'K/2',
                'tarif_pkp' => '67500000',
                'keterangan' => 'Kawin, 2 tanggungan',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'K/3',
                'tarif_pkp' => '72000000',
                'keterangan' => 'Kawin, 3 tanggungan',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'K/I/0',
                'tarif_pkp' => '112500000',
                'keterangan' => 'Kawin Penghasilan Suami Istri, 0 tanggungan',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'K/I/1',
                'tarif_pkp' => '117000000',
                'keterangan' => 'Kawin Penghasilan Suami Istri, 1 tanggungan',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'K/I/2',
                'tarif_pkp' => '121500000',
                'keterangan' => 'Kawin Penghasilan Suami Istri, 2 tanggungan',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'K/I/3',
                'tarif_pkp' => '126000000',
                'keterangan' => 'Kawin Penghasilan Suami Istri, 3 tanggungan',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
