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
                'kode_unit' => 'YYSN01',
                'deskripsi' => 'Ketua Umum Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Dewan Pembina & Penasehat',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'YYSN02',
                'deskripsi' => 'Dewan Pembina & Penasehat Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Dewan Pengawas',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'YYSN03',
                'deskripsi' => 'Dewan Pengawas Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Sekretaris Umum',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'YYSN04',
                'deskripsi' => 'Sekretaris Umum Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Keuangan',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'YYSN05',
                'deskripsi' => 'Keuangan Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Bidang Legal',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'YYSN06',
                'deskripsi' => 'Bidang Legal Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Adv Research & Development',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'YYSN07',
                'deskripsi' => 'Adv Research & Development Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Bidang Pendidikan',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'YYSN08',
                'deskripsi' => 'Bidang Pendidikan Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Bidang HR-GS',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'YYSN09',
                'deskripsi' => 'Bidang HR-GS Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Bidang Muamalat',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'YYSN10',
                'deskripsi' => 'Bidang Muamalat Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Bidang Sektor Riil',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'YYSN11',
                'deskripsi' => 'Bidang Sektor Riil Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Bidang Masjid & Dakwah',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'YYSN12',
                'deskripsi' => 'Bidang Masjid & Dakwah Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Bidang Project, Akuisisi Aset, Pendanaan, Sertifikasi Lahan',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'YYSN13',
                'deskripsi' => 'Bidang Project, Akuisisi Aset, Pendanaan, Sertifikasi Lahan Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'Bidang Infrastruktur & Pemeliharaan',
                'department_id' => 1, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'YYSN14',
                'deskripsi' => 'Bidang Infrastruktur & Pemeliharaan Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'TPA',
                'department_id' => 2, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'PNDK01',
                'deskripsi' => 'Tempat Penitipan Anak, Daycare, Preschool, Toodler',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'TKIT',
                'department_id' => 2, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'PNDK02',
                'deskripsi' => 'Taman Kanak-kanak Islam Terpadu',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'SDIT',
                'department_id' => 2, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'PNDK03',
                'deskripsi' => 'Sekolah Dasar Islam Terpadu',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'MTs',
                'department_id' => 2, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'PNDK04',
                'deskripsi' => 'Madrasah Tsanawiyah',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'SMPIT',
                'department_id' => 2, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'PNDK05',
                'deskripsi' => 'Sekolah Menengah Pertama Islam Terpadu',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'SMAIT',
                'department_id' => 2, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'PNDK06',
                'deskripsi' => 'Sekolah Menengah Atas Islam Terpadu',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'SMAIT',
                'department_id' => 2, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'PNDK06',
                'deskripsi' => 'Sekolah Menengah Atas Islam Terpadu',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'DIREKTORAT',
                'department_id' => 2, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'PNDK07',
                'deskripsi' => 'Direktorat Pendidikan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'KEUANGAN',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'MUAT01',
                'deskripsi' => 'Keuangan Yayasan',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'BMT PUSAT',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'MUAT02',
                'deskripsi' => 'BMT Pusat',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'BMT RUMBAI',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'MUAT03',
                'deskripsi' => 'BMT Cabang Rumbai',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'BMT PANAM',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'MUAT04',
                'deskripsi' => 'BMT Cabang Panam',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'BMT DURI',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'MUAT05',
                'deskripsi' => 'BMT Cabang Duri',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'BMT CIBUBUR',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'MUAT06',
                'deskripsi' => 'BMT Cabang Cibubur',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'DAPUR',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'MUAT07',
                'deskripsi' => 'Dapur',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'WARUNG 1',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'MUAT08',
                'deskripsi' => 'Warung Serba Ada 1',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'WARUNG 2',
                'department_id' => 3, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'MUAT09',
                'deskripsi' => 'Warung Serba Ada 2',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'HR',
                'department_id' => 4, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'HRGS01',
                'deskripsi' => 'Human Resources',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'GS',
                'department_id' => 4, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'HRGS02',
                'deskripsi' => 'General Services',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'MASJID',
                'department_id' => 5, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'BKMD01',
                'deskripsi' => 'Masjid & Dakwah',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit' => 'MAKAM',
                'department_id' => 5, // Sesuaikan dengan ID yang ada di master_departments
                'kode_unit' => 'BKMD02',
                'deskripsi' => 'Makam Yayasan & PHR',
                'kepala_unit' => 1, // ID user yang menjabat kepala unit
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
