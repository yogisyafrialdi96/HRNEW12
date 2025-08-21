<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_jabatan')->insert([
            [
                'department_id' => 2, // Pastikan ID 1 ada di master_departments
                'nama_jabatan' => 'Direktur Pendidikan',
                'kode_jabatan' => 'JBT-001',
                'jenis_jabatan' => 'struktural',
                'level_jabatan' => 'top_managerial',
                'tugas_pokok' => 'Mengelola seluruh kegiatan divisi Sumber Daya Manusia.',
                'requirements' => 'S1 Psikologi/Manajemen SDM, pengalaman min 5 tahun.',
                'min_salary' => 10000000,
                'max_salary' => 15000000,
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => 2,
                'nama_jabatan' => 'Kabid. Kurikulum',
                'kode_jabatan' => 'JBT-002',
                'jenis_jabatan' => 'struktural',
                'level_jabatan' => 'middle_manager',
                'tugas_pokok' => 'Melakukan pencatatan transaksi harian dan pelaporan.',
                'requirements' => 'Minimal D3 Akuntansi, teliti, menguasai Excel.',
                'min_salary' => 4000000,
                'max_salary' => 6000000,
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => 3,
                'nama_jabatan' => 'Direktur Muamalat',
                'kode_jabatan' => 'JBT-002',
                'jenis_jabatan' => 'struktural',
                'level_jabatan' => 'top_managerial',
                'tugas_pokok' => 'Melakukan pencatatan transaksi harian dan pelaporan.',
                'requirements' => 'Minimal D3 Akuntansi, teliti, menguasai Excel.',
                'min_salary' => 4000000,
                'max_salary' => 6000000,
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => 3,
                'nama_jabatan' => 'Manager BMT',
                'kode_jabatan' => 'JBT-004',
                'jenis_jabatan' => 'struktural',
                'level_jabatan' => 'middle_manager',
                'tugas_pokok' => 'Melakukan pencatatan transaksi harian dan pelaporan.',
                'requirements' => 'Minimal D3 Akuntansi, teliti, menguasai Excel.',
                'min_salary' => 4000000,
                'max_salary' => 6000000,
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => 4,
                'nama_jabatan' => 'Manager HR-GS',
                'kode_jabatan' => 'JBT-005',
                'jenis_jabatan' => 'struktural',
                'level_jabatan' => 'top_managerial',
                'tugas_pokok' => 'Melakukan pencatatan transaksi harian dan pelaporan.',
                'requirements' => 'Minimal D3 Akuntansi, teliti, menguasai Excel.',
                'min_salary' => 4500000,
                'max_salary' => 8000000,
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => 4,
                'nama_jabatan' => 'HR Spesialis Adm',
                'kode_jabatan' => 'JBT-006',
                'jenis_jabatan' => 'struktural',
                'level_jabatan' => 'staff',
                'tugas_pokok' => 'Melakukan pencatatan transaksi harian dan pelaporan.',
                'requirements' => 'Minimal D3 Akuntansi, teliti, menguasai Excel.',
                'min_salary' => 4500000,
                'max_salary' => 8000000,
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => 5,
                'nama_jabatan' => 'Imam Masjid',
                'kode_jabatan' => 'JBT-007',
                'jenis_jabatan' => 'struktural',
                'level_jabatan' => 'jabatan_khusus',
                'tugas_pokok' => 'Melakukan pencatatan transaksi harian dan pelaporan.',
                'requirements' => 'Minimal D3 Akuntansi, teliti, menguasai Excel.',
                'min_salary' => 5500000,
                'max_salary' => 10000000,
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
