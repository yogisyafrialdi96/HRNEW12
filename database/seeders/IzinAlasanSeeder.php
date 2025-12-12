<?php

namespace Database\Seeders;

use App\Models\IzinCuti\IzinAlasan;
use Illuminate\Database\Seeder;

class IzinAlasanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alasanData = [
            [
                'nama_alasan' => 'Sakit',
                'jenis_izin' => 'hari',
                'max_hari_setahun' => 10,
                'is_bayar_penuh' => true,
                'perlu_surat_dokter' => true,
                'keterangan' => 'Izin sakit dengan surat dokter untuk hari ke-3 dan seterusnya',
                'is_active' => true,
                'urutan' => 1,
            ],
            [
                'nama_alasan' => 'Pernikahan Pegawai',
                'jenis_izin' => 'hari',
                'max_hari_setahun' => 3,
                'is_bayar_penuh' => true,
                'perlu_surat_dokter' => false,
                'keterangan' => 'Izin pernikahan pegawai',
                'is_active' => true,
                'urutan' => 2,
            ],
            [
                'nama_alasan' => 'Istri Pegawai Melahirkan / Keguguran Kandungan',
                'jenis_izin' => 'hari',
                'max_hari_setahun' => 3,
                'is_bayar_penuh' => true,
                'perlu_surat_dokter' => false,
                'keterangan' => 'Izin untuk istri pegawai melahirkan atau keguguran kandungan',
                'is_active' => true,
                'urutan' => 3,
            ],
            [
                'nama_alasan' => 'Istri/Suami/Anak Pegawai Meninggal Dunia',
                'jenis_izin' => 'hari',
                'max_hari_setahun' => 3,
                'is_bayar_penuh' => true,
                'perlu_surat_dokter' => false,
                'keterangan' => 'Izin untuk istri/suami/anak pegawai meninggal dunia',
                'is_active' => true,
                'urutan' => 4,
            ],
            [
                'nama_alasan' => 'Orang Tua/Mertua Pegawai Meninggal Dunia',
                'jenis_izin' => 'hari',
                'max_hari_setahun' => 3,
                'is_bayar_penuh' => true,
                'perlu_surat_dokter' => false,
                'keterangan' => 'Izin untuk orang tua/mertua pegawai meninggal dunia',
                'is_active' => true,
                'urutan' => 5,
            ],
            [
                'nama_alasan' => 'Saudara Kandung/Ipar Pegawai Meninggal Dunia',
                'jenis_izin' => 'hari',
                'max_hari_setahun' => 2,
                'is_bayar_penuh' => true,
                'perlu_surat_dokter' => false,
                'keterangan' => 'Izin untuk saudara kandung/ipar pegawai meninggal dunia',
                'is_active' => true,
                'urutan' => 6,
            ],
            [
                'nama_alasan' => 'Orang yang Tinggal Serumah Meninggal Dunia',
                'jenis_izin' => 'hari',
                'max_hari_setahun' => 1,
                'is_bayar_penuh' => true,
                'perlu_surat_dokter' => false,
                'keterangan' => 'Izin untuk orang yang tinggal serumah meninggal dunia',
                'is_active' => true,
                'urutan' => 7,
            ],
            [
                'nama_alasan' => 'Pernikahan Saudara Kandung/Ipar Pegawai',
                'jenis_izin' => 'hari',
                'max_hari_setahun' => 2,
                'is_bayar_penuh' => true,
                'perlu_surat_dokter' => false,
                'keterangan' => 'Izin untuk pernikahan saudara kandung/ipar pegawai',
                'is_active' => true,
                'urutan' => 8,
            ],
            [
                'nama_alasan' => 'Pernikahan Anak Pegawai',
                'jenis_izin' => 'hari',
                'max_hari_setahun' => 3,
                'is_bayar_penuh' => true,
                'perlu_surat_dokter' => false,
                'keterangan' => 'Izin untuk pernikahan anak pegawai',
                'is_active' => true,
                'urutan' => 9,
            ],
            [
                'nama_alasan' => 'Khitanan Anak Pegawai',
                'jenis_izin' => 'hari',
                'max_hari_setahun' => 2,
                'is_bayar_penuh' => true,
                'perlu_surat_dokter' => false,
                'keterangan' => 'Izin untuk khitanan anak pegawai',
                'is_active' => true,
                'urutan' => 10,
            ],
            [
                'nama_alasan' => 'Suami/Istri/Anak/Orang Tua/Mertua Dirawat di Rumah Sakit',
                'jenis_izin' => 'hari',
                'max_hari_setahun' => 3,
                'is_bayar_penuh' => true,
                'perlu_surat_dokter' => true,
                'keterangan' => 'Izin untuk suami/istri/anak/orang tua/mertua dirawat di rumah sakit',
                'is_active' => true,
                'urutan' => 11,
            ],
        ];

        foreach ($alasanData as $data) {
            IzinAlasan::updateOrCreate(
                ['nama_alasan' => $data['nama_alasan']],
                $data
            );
        }
    }
}
