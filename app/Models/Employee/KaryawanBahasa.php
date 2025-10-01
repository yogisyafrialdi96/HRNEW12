<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanBahasa extends Model
{
    use HasFactory;

    protected $table = 'karyawan_bahasa';

    protected $fillable = [
        'karyawan_id',
        'nama_bahasa',
        'level_bahasa',
        'jenis_test',
        'jenistest_lain',
        'lembaga_sertifikasi',
        'skor_numerik',
        'is_active',
        'tgl_expired_sertifikasi',
        'tgl_sertifikasi',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    /*** The attributes that should be cast.*/
    protected $casts = [
        'tgl_sertifikasi' => 'date',
    ];

    /*** Konstanta untuk level bahasa*/
    const LEVEL_BAHASA = [
        'pemula' => 'Pemula',
        'dasar' => 'Dasar',
        'menengah' => 'Menengah',
        'mahir' => 'Mahir',
        'fasih' => 'Fasih',
        'native' => 'Native Speaker'
    ];

    /*** Konstanta untuk level bahasa*/
    const JENIS_TEST = [
        'IELTS' => 'IELTS',
        'TOEFL' => 'TOEFL',
        'CAE' => 'CAE',
        'TOEIC' => 'TOEIC',
        'HSK' => 'HSK',
        'JLPT' => 'JLPT',
        'DELF' => 'DELF',
        'TestDaF' => 'TestDaF',
        'Lainnya' => 'Lainnya',
    ];

    /*** Konstanta untuk bahasa umum*/
    const BAHASA_UMUM = [
        'indonesia' => 'Bahasa Indonesia',
        'inggris' => 'Bahasa Inggris',
        'mandarin' => 'Bahasa Mandarin',
        'hindi' => 'Bahasa Hindi',
        'arab' => 'Bahasa Arab',
        'jepang' => 'Bahasa Jepang',
        'korea' => 'Bahasa Korea',
        'jerman' => 'Bahasa Jerman',
        'perancis' => 'Bahasa Perancis',
        'spanyol' => 'Bahasa Spanyol',
        'rusia' => 'Bahasa Rusia',
    ];

    public function karyawan()
    {
        return $this->belongsTo(\App\Models\Employee\Karyawan::class, 'karyawan_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}
