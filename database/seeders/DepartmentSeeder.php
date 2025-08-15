<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_department')->insert([
            [
                'company_id' => 1, // Pastikan ID 1 sudah ada di tabel master_companies
                'department' => 'YAYASAN',
                'kode_department' => 'YYSN',
                'deskripsi' => 'Pengurus Yayasan Kesatuan Pendidikan Islam Al-Ittihad',
                'kepala_department' => 1, // User ID
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'department' => 'PENDIDIKAN',
                'kode_department' => 'PNDK',
                'deskripsi' => 'Departemen Pendidikan, TPA, TKIT, SDIT, MTS, SMPIT, SMAIT',
                'kepala_department' => 1,
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'department' => 'MUAMALAT',
                'kode_department' => 'MUAT',
                'deskripsi' => 'Departemen Keuangan atau Ekonomi',
                'kepala_department' => 1,
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'department' => 'HR-GS',
                'kode_department' => 'HRGS',
                'deskripsi' => 'Departemen Human Resources and General Services',
                'kepala_department' => 1,
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'department' => 'BKMD',
                'kode_department' => 'BKMD',
                'deskripsi' => 'Departemen Keagamaan Bidang Kemakmuran Masjid dan Dakwah',
                'kepala_department' => 1,
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
