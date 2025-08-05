<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mapels')->insert([
            [
                'nama_mapel' => 'Matematika',
                'kode_mapel' => 'MATH01',
                'requirements' => 'S1 Pendidikan Matematika, mampu mengajar secara interaktif.',
                'tugas_pokok' => 'Mengajar materi matematika sesuai kurikulum dan membuat evaluasi pembelajaran.',
                'status' => 'aktif',
                'created_by' => 1, // pastikan user ID 1 ada
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_mapel' => 'Bahasa Indonesia',
                'kode_mapel' => 'BIN01',
                'requirements' => 'S1 Pendidikan Bahasa Indonesia, memiliki kemampuan komunikasi yang baik.',
                'tugas_pokok' => 'Mengajarkan tata bahasa, membaca, menulis, dan sastra Indonesia.',
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_mapel' => 'Bahasa Inggris',
                'kode_mapel' => 'ENG01',
                'requirements' => 'S1 Pendidikan Bahasa Inggris, TOEFL minimal 500.',
                'tugas_pokok' => 'Mengajar grammar, speaking, listening, reading dan writing.',
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_mapel' => 'Pendidikan Agama Islam',
                'kode_mapel' => 'PAI01',
                'requirements' => 'S1 PAI, memiliki akhlak dan komunikasi yang baik.',
                'tugas_pokok' => 'Mengajarkan Al-Quran, Hadits, Akidah Akhlak, dan Fikih.',
                'status' => 'aktif',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
