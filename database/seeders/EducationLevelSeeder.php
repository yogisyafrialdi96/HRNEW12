<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $educationLevels = [
            [
                'level_code' => 'SD',
                'level_name' => 'Sekolah Dasar',
                'level_order' => 1,
                'is_formal' => true,
                'minimum_years' => 6,
                'description' => 'Pendidikan dasar tingkat pertama untuk anak usia 6-12 tahun',
                'is_active' => true,
            ],
            [
                'level_code' => 'SMP',
                'level_name' => 'Sekolah Menengah Pertama',
                'level_order' => 2,
                'is_formal' => true,
                'minimum_years' => 3,
                'description' => 'Pendidikan menengah tingkat pertama untuk anak usia 12-15 tahun',
                'is_active' => true,
            ],
            [
                'level_code' => 'SMA',
                'level_name' => 'Sekolah Menengah Atas',
                'level_order' => 3,
                'is_formal' => true,
                'minimum_years' => 3,
                'description' => 'Pendidikan menengah atas jalur akademik',
                'is_active' => true,
            ],
            [
                'level_code' => 'SMK',
                'level_name' => 'Sekolah Menengah Kejuruan',
                'level_order' => 3,
                'is_formal' => true,
                'minimum_years' => 3,
                'description' => 'Pendidikan menengah atas jalur kejuruan/vokasi',
                'is_active' => true,
            ],
            [
                'level_code' => 'MA',
                'level_name' => 'Madrasah Aliyah',
                'level_order' => 3,
                'is_formal' => true,
                'minimum_years' => 3,
                'description' => 'Pendidikan menengah atas berbasis agama Islam',
                'is_active' => true,
            ],
            [
                'level_code' => 'D1',
                'level_name' => 'Diploma I',
                'level_order' => 4,
                'is_formal' => true,
                'minimum_years' => 1,
                'description' => 'Program diploma tingkat I (satu tahun)',
                'is_active' => true,
            ],
            [
                'level_code' => 'D2',
                'level_name' => 'Diploma II',
                'level_order' => 5,
                'is_formal' => true,
                'minimum_years' => 2,
                'description' => 'Program diploma tingkat II (dua tahun)',
                'is_active' => true,
            ],
            [
                'level_code' => 'D3',
                'level_name' => 'Diploma III',
                'level_order' => 6,
                'is_formal' => true,
                'minimum_years' => 3,
                'description' => 'Program diploma tingkat III (tiga tahun)',
                'is_active' => true,
            ],
            [
                'level_code' => 'D4',
                'level_name' => 'Diploma IV / Sarjana Terapan',
                'level_order' => 7,
                'is_formal' => true,
                'minimum_years' => 4,
                'description' => 'Program diploma tingkat IV atau Sarjana Terapan',
                'is_active' => true,
            ],
            [
                'level_code' => 'S1',
                'level_name' => 'Sarjana (S1)',
                'level_order' => 8,
                'is_formal' => true,
                'minimum_years' => 4,
                'description' => 'Program Sarjana Strata 1',
                'is_active' => true,
            ],
            [
                'level_code' => 'PROF',
                'level_name' => 'Profesi',
                'level_order' => 9,
                'is_formal' => true,
                'minimum_years' => 1,
                'description' => 'Program Pendidikan Profesi (setelah S1)',
                'is_active' => true,
            ],
            [
                'level_code' => 'S2',
                'level_name' => 'Magister (S2)',
                'level_order' => 10,
                'is_formal' => true,
                'minimum_years' => 2,
                'description' => 'Program Magister Strata 2',
                'is_active' => true,
            ],
            [
                'level_code' => 'SP1',
                'level_name' => 'Spesialis I',
                'level_order' => 11,
                'is_formal' => true,
                'minimum_years' => 2,
                'description' => 'Program Spesialis tingkat I (setelah S2)',
                'is_active' => true,
            ],
            [
                'level_code' => 'S3',
                'level_name' => 'Doktor (S3)',
                'level_order' => 12,
                'is_formal' => true,
                'minimum_years' => 3,
                'description' => 'Program Doktor Strata 3',
                'is_active' => true,
            ],
            [
                'level_code' => 'SP2',
                'level_name' => 'Spesialis II',
                'level_order' => 13,
                'is_formal' => true,
                'minimum_years' => 2,
                'description' => 'Program Spesialis tingkat II (setelah S3)',
                'is_active' => true,
            ],
            // Non-formal education
            [
                'level_code' => 'CERT',
                'level_name' => 'Sertifikasi',
                'level_order' => 99,
                'is_formal' => false,
                'minimum_years' => null,
                'description' => 'Sertifikasi profesional atau keterampilan',
                'is_active' => true,
            ],
            [
                'level_code' => 'COURSE',
                'level_name' => 'Kursus',
                'level_order' => 98,
                'is_formal' => false,
                'minimum_years' => null,
                'description' => 'Kursus atau pelatihan singkat',
                'is_active' => true,
            ],
            [
                'level_code' => 'WORKSHOP',
                'level_name' => 'Workshop',
                'level_order' => 97,
                'is_formal' => false,
                'minimum_years' => null,
                'description' => 'Workshop atau seminar pelatihan',
                'is_active' => true,
            ],
            [
                'level_code' => 'BOOTCAMP',
                'level_name' => 'Bootcamp',
                'level_order' => 96,
                'is_formal' => false,
                'minimum_years' => null,
                'description' => 'Program pelatihan intensif (bootcamp)',
                'is_active' => true,
            ],
        ];

        // Add timestamps to each record
        $now = Carbon::now();
        foreach ($educationLevels as &$level) {
            $level['created_at'] = $now;
            $level['updated_at'] = $now;
        }

        // Insert data
        DB::table('master_educationlevel')->insert($educationLevels);
    }

}
