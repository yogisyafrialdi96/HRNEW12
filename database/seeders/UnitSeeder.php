<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_unit')->insert([
            [
                'unit' => 'Ketua Umum',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-001',
                'deskripsi' => 'Ketua Umum Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Dewan Pembina & Penasehat',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-002',
                'deskripsi' => 'Dewan Pembina & Penasehat Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Dewan Pengawas',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-003',
                'deskripsi' => 'Dewan Pengawas Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Sekretaris Umum',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-004',
                'deskripsi' => 'Sekretaris Umum Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Keuangan',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-005',
                'deskripsi' => 'Keuangan Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Bidang Legal',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-006',
                'deskripsi' => 'Bidang Legal Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Adv Research & Development',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-007',
                'deskripsi' => 'Adv Research & Development Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Bidang Pendidikan',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-008',
                'deskripsi' => 'Bidang Pendidikan Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Bidang HR-GS',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-009',
                'deskripsi' => 'Bidang HR-GS Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Bidang Muamalat',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-010',
                'deskripsi' => 'Bidang Muamalat Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Bidang Sektor Riil',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-011',
                'deskripsi' => 'Bidang Sektor Riil Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Bidang Masjid & Dakwah',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-012',
                'deskripsi' => 'Bidang Masjid & Dakwah Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Bidang Project, Akuisisi Aset, Pendanaan, Sertifikasi Lahan',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-013',
                'deskripsi' => 'Bidang Project, Akuisisi Aset, Pendanaan, Sertifikasi Lahan Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Bidang Infrastruktur & Pemeliharaan',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-014',
                'deskripsi' => 'Bidang Infrastruktur & Pemeliharaan Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'TPA',
                'department_id' => 2, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-015',
                'deskripsi' => 'Tempat Penitipan Anak, Daycare, Preschool, Toodler',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'TKIT',
                'department_id' => 2, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-016',
                'deskripsi' => 'Taman Kanak-kanak Islam Terpadu',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'SDIT',
                'department_id' => 2, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-017',
                'deskripsi' => 'Sekolah Dasar Islam Terpadu',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'MTs',
                'department_id' => 2, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-018',
                'deskripsi' => 'Madrasah Tsanawiyah',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'SMPIT',
                'department_id' => 2, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-019',
                'deskripsi' => 'Sekolah Menengah Pertama Islam Terpadu',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'SMAIT',
                'department_id' => 2, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-020',
                'deskripsi' => 'Sekolah Menengah Atas Islam Terpadu',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'SMAIT',
                'department_id' => 2, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-021',
                'deskripsi' => 'Sekolah Menengah Atas Islam Terpadu',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'DIREKTORAT',
                'department_id' => 2, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-022',
                'deskripsi' => 'Direktorat Pendidikan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'KEUANGAN',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-023',
                'deskripsi' => 'Keuangan Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'BMT PUSAT',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-024',
                'deskripsi' => 'BMT Pusat',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'BMT RUMBAI',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-025',
                'deskripsi' => 'BMT Cabang Rumbai',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'BMT PANAM',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-026',
                'deskripsi' => 'BMT Cabang Panam',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'BMT DURI',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-027',
                'deskripsi' => 'BMT Cabang Duri',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'BMT CIBUBUR',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-028',
                'deskripsi' => 'BMT Cabang Cibubur',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'DAPUR',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-029',
                'deskripsi' => 'Dapur',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'WARUNG 1',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-030',
                'deskripsi' => 'Warung Serba Ada 1',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'WARUNG 2',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-031',
                'deskripsi' => 'Warung Serba Ada 2',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'HR',
                'department_id' => 4, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-032',
                'deskripsi' => 'Human Resources',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'GS',
                'department_id' => 4, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-033',
                'deskripsi' => 'General Services',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'MASJID',
                'department_id' => 5, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-034',
                'deskripsi' => 'Masjid & Dakwah',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'MAKAM',
                'department_id' => 5, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'UNT-035',
                'deskripsi' => 'Makam Yayasan & PHR',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
