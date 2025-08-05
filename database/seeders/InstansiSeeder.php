<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstansiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_companies')->insert([
            [
                'nama_companies' => 'Yayasan Kesatuan Pendidikan Islam (YKPI) Al-Ittihad ',
                'kode' => 'YKPIC01',
                'singkatan' => 'YKPI AL-ITTIHAD',
                'jenis_instansi' => 'Yayasan Umat',
                'npwp' => '02.643.441.5-211.000',
                'alamat' => 'Komplek Masjid Al-Ittihad PT. PHR, Rumbai, Riau',
                'telepon' => '+62 821-7203-71838',
                'fax' => '-',
                'email' => 'info@ykpialittihad.or.id',
                'website' => 'https://www.ykpialittihad.or.id',
                'logo_path' => 'logo/yicb.png',
                'tax_id' => '02.643.441.5-211.000',
                'company_type' => 'Yayasan',
                'established_date' => Carbon::parse('1997-01-18'),
                'tgl_berdiri' => Carbon::parse('1960-01-18'),
                'status' => 'aktif',
                'keterangan' => 'Instansi utama Yayasan Al-Ittihad yang mengelola unit pendidikan SD-SMA.',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ]);
    }
}
