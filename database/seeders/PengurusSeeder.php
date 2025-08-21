<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PengurusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pengurus')->insert([
            [
                'user_id'        => 1, // pastikan user dengan id=1 ada
                'department_id'  => 1, // pastikan department dengan id=1 ada
                'jabatan_id'     => 1, // pastikan jabatan dengan id=1 ada
                'nama_pengurus'  => 'Ahmad Fauzi',
                'inisial'        => 'AFZ',
                'hp'             => '081234567890',
                'jenis_kelamin'  => 'laki-laki',
                'gelar_depan'    => 'Dr.',
                'gelar_belakang' => 'S.Pd',
                'tempat_lahir'   => 'Pekanbaru',
                'tanggal_lahir'  => '1985-05-20',
                'alamat'         => 'Jl. Merdeka No. 10, Pekanbaru',
                'foto'           => null, // bisa isi path file jika ada
                'ttd'            => null, // bisa isi path file jika ada
                'tanggal_masuk'  => '2020-01-15',
                'tanggal_keluar' => null,
                'is_active'      => true,
                'posisi'         => 'ketua',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'user_id'        => 2,
                'department_id'  => 1,
                'jabatan_id'     => 2,
                'nama_pengurus'  => 'Siti Rahmawati',
                'inisial'        => 'SRA',
                'hp'             => '081298765432',
                'jenis_kelamin'  => 'perempuan',
                'gelar_depan'    => null,
                'gelar_belakang' => 'M.M',
                'tempat_lahir'   => 'Dumai',
                'tanggal_lahir'  => '1990-08-10',
                'alamat'         => 'Jl. Sudirman No. 25, Dumai',
                'foto'           => null,
                'ttd'            => null,
                'tanggal_masuk'  => '2021-03-01',
                'tanggal_keluar' => null,
                'is_active'         => true,
                'posisi'         => 'anggota',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
        ]);
    }
}
