<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MapelSeeder extends Seeder
{
        public function run()
    {
        $mapel = [
            // Kelompok A - Mata Pelajaran Umum
            [
                'nama_mapel' => 'Pendidikan Agama Islam',
                'kode_mapel' => 'PAI',
                'tugas_pokok' => 'Mengajarkan nilai-nilai keislaman, Al-Quran, hadits, akidah akhlak, fikih, dan sejarah kebudayaan Islam',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Pendidikan Pancasila dan Kewarganegaraan',
                'kode_mapel' => 'PPKN',
                'tugas_pokok' => 'Mengajarkan nilai-nilai Pancasila, UUD 1945, Bhinneka Tunggal Ika, NKRI, dan pembentukan karakter warga negara yang baik',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Bahasa Indonesia',
                'kode_mapel' => 'BIND',
                'tugas_pokok' => 'Mengajarkan kemampuan berbahasa Indonesia yang baik dan benar, sastra Indonesia, serta literasi',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Matematika',
                'kode_mapel' => 'MAT',
                'tugas_pokok' => 'Mengajarkan konsep matematika, logika, pemecahan masalah, dan penalaran matematis',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Sejarah Indonesia',
                'kode_mapel' => 'SEJIND',
                'tugas_pokok' => 'Mengajarkan sejarah perjuangan bangsa Indonesia dan pembentukan karakter nasionalisme',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Bahasa Inggris',
                'kode_mapel' => 'BING',
                'tugas_pokok' => 'Mengajarkan kemampuan berbahasa Inggris lisan dan tulisan',
                'is_active' => true,
            ],

            // Kelompok B - Mata Pelajaran Umum (Lanjutan)
            [
                'nama_mapel' => 'Seni Budaya',
                'kode_mapel' => 'SBDP',
                'tugas_pokok' => 'Mengajarkan apresiasi dan kreativitas seni (seni rupa, musik, tari, teater)',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Pendidikan Jasmani, Olahraga dan Kesehatan',
                'kode_mapel' => 'PJOK',
                'tugas_pokok' => 'Mengajarkan pendidikan jasmani, olahraga, dan kesehatan untuk kebugaran fisik',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Prakarya dan Kewirausahaan',
                'kode_mapel' => 'PKWU',
                'tugas_pokok' => 'Mengajarkan keterampilan prakarya dan jiwa kewirausahaan',
                'is_active' => true,
            ],

            // Kelompok C - Mata Pelajaran Peminatan (MIPA)
            [
                'nama_mapel' => 'Matematika Peminatan',
                'kode_mapel' => 'MATMIN',
                'tugas_pokok' => 'Mengajarkan matematika tingkat lanjut untuk peminatan MIPA',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Fisika',
                'kode_mapel' => 'FIS',
                'tugas_pokok' => 'Mengajarkan konsep fisika, hukum alam, dan fenomena fisika',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Kimia',
                'kode_mapel' => 'KIM',
                'tugas_pokok' => 'Mengajarkan konsep kimia, reaksi kimia, dan aplikasi kimia dalam kehidupan',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Biologi',
                'kode_mapel' => 'BIO',
                'tugas_pokok' => 'Mengajarkan ilmu tentang makhluk hidup, ekosistem, dan biologi molekuler',
                'is_active' => true,
            ],

            // Kelompok C - Mata Pelajaran Peminatan (IPS)
            [
                'nama_mapel' => 'Geografi',
                'kode_mapel' => 'GEO',
                'tugas_pokok' => 'Mengajarkan ilmu tentang bumi, lingkungan, dan interaksi manusia dengan lingkungan',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Sejarah',
                'kode_mapel' => 'SEJ',
                'tugas_pokok' => 'Mengajarkan sejarah dunia, Asia, dan Indonesia secara mendalam',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Sosiologi',
                'kode_mapel' => 'SOS',
                'tugas_pokok' => 'Mengajarkan ilmu tentang masyarakat, interaksi sosial, dan struktur sosial',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Ekonomi',
                'kode_mapel' => 'EKO',
                'tugas_pokok' => 'Mengajarkan konsep ekonomi, manajemen, akuntansi, dan keuangan',
                'is_active' => true,
            ],

            // Kelompok C - Mata Pelajaran Peminatan (Bahasa)
            [
                'nama_mapel' => 'Bahasa dan Sastra Indonesia',
                'kode_mapel' => 'BSIND',
                'tugas_pokok' => 'Mengajarkan bahasa Indonesia tingkat lanjut dan sastra Indonesia',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Bahasa dan Sastra Inggris',
                'kode_mapel' => 'BSING',
                'tugas_pokok' => 'Mengajarkan bahasa Inggris tingkat lanjut dan sastra Inggris',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Bahasa Arab',
                'kode_mapel' => 'BARB',
                'tugas_pokok' => 'Mengajarkan bahasa Arab dan sastra Arab',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Bahasa Mandarin',
                'kode_mapel' => 'BMAND',
                'tugas_pokok' => 'Mengajarkan bahasa Mandarin dan budaya China',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Bahasa Jepang',
                'kode_mapel' => 'BJPN',
                'tugas_pokok' => 'Mengajarkan bahasa Jepang dan budaya Jepang',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Bahasa Korea',
                'kode_mapel' => 'BKOR',
                'tugas_pokok' => 'Mengajarkan bahasa Korea dan budaya Korea',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Bahasa Jerman',
                'kode_mapel' => 'BJRM',
                'tugas_pokok' => 'Mengajarkan bahasa Jerman dan budaya Jerman',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Bahasa Perancis',
                'kode_mapel' => 'BPRC',
                'tugas_pokok' => 'Mengajarkan bahasa Perancis dan budaya Perancis',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Antropologi',
                'kode_mapel' => 'ANTR',
                'tugas_pokok' => 'Mengajarkan ilmu tentang manusia, budaya, dan keberagaman',
                'is_active' => true,
            ],

            // Mata Pelajaran Muatan Lokal
            [
                'nama_mapel' => 'Bahasa Daerah',
                'kode_mapel' => 'BDAE',
                'tugas_pokok' => 'Mengajarkan bahasa daerah setempat untuk melestarikan budaya lokal',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Seni Budaya Daerah',
                'kode_mapel' => 'SBDAE',
                'tugas_pokok' => 'Mengajarkan seni dan budaya daerah setempat',
                'is_active' => true,
            ],

            // Mata Pelajaran Khusus Madrasah
            [
                'nama_mapel' => 'Al-Quran Hadits',
                'kode_mapel' => 'QH',
                'tugas_pokok' => 'Mengajarkan bacaan, pemahaman, dan pengamalan Al-Quran dan Hadits',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Akidah Akhlak',
                'kode_mapel' => 'AA',
                'tugas_pokok' => 'Mengajarkan akidah Islam dan pembentukan akhlak mulia',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Fikih',
                'kode_mapel' => 'FQH',
                'tugas_pokok' => 'Mengajarkan hukum Islam dan praktik ibadah',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Sejarah Kebudayaan Islam',
                'kode_mapel' => 'SKI',
                'tugas_pokok' => 'Mengajarkan sejarah peradaban dan kebudayaan Islam',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Bahasa Arab Madrasah',
                'kode_mapel' => 'BARBMTS',
                'tugas_pokok' => 'Mengajarkan bahasa Arab untuk keperluan ibadah dan komunikasi',
                'is_active' => true,
            ],

            // Mata Pelajaran SD
            [
                'nama_mapel' => 'Ilmu Pengetahuan Alam',
                'kode_mapel' => 'IPA',
                'tugas_pokok' => 'Mengajarkan ilmu pengetahuan alam untuk jenjang SD/MI',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Ilmu Pengetahuan Sosial',
                'kode_mapel' => 'IPS',
                'tugas_pokok' => 'Mengajarkan ilmu pengetahuan sosial untuk jenjang SD/MI',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Tematik',
                'kode_mapel' => 'TEMATIK',
                'tugas_pokok' => 'Mengajarkan pembelajaran tematik terpadu untuk SD kelas rendah',
                'is_active' => true,
            ],

            // Mata Pelajaran Tambahan
            [
                'nama_mapel' => 'Bimbingan Konseling',
                'kode_mapel' => 'BK',
                'tugas_pokok' => 'Memberikan bimbingan dan konseling untuk pengembangan diri siswa',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Teknologi Informasi dan Komunikasi',
                'kode_mapel' => 'TIK',
                'tugas_pokok' => 'Mengajarkan penggunaan teknologi informasi dan komunikasi',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Tahfidz Quran',
                'kode_mapel' => 'TAHFIDZ',
                'tugas_pokok' => 'Membimbing siswa dalam menghafal Al-Quran',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Literasi dan Numerasi',
                'kode_mapel' => 'LITNUM',
                'tugas_pokok' => 'Mengajarkan kemampuan literasi membaca dan numerasi dasar',
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Pendidikan Karakter',
                'kode_mapel' => 'PENKAR',
                'tugas_pokok' => 'Membentuk karakter dan kepribadian siswa yang berakhlak mulia',
                'is_active' => true,
            ],
        ];

        foreach ($mapel as $item) {
            DB::table('master_mapel')->insert([
                'nama_mapel' => $item['nama_mapel'],
                'kode_mapel' => $item['kode_mapel'],
                'tugas_pokok' => $item['tugas_pokok'],
                'is_active' => $item['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}