<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanSertifikasi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'karyawan_sertifikasi';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'karyawan_id',
        'nama_sertifikasi',
        'lembaga_penerbit',
        'nomor_sertifikat',
        'tgl_terbit',
        'tgl_kadaluwarsa',
        'biaya_sertifikasi',
        'metode_pembelajaran',
        'durasi_jam',
        'wajib_perpanjang',
        'jenis_sertifikasi',
        'tingkat',
        'document_path',
        'status_sertifikat',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tgl_terbit' => 'date',
        'tgl_kadaluwarsa' => 'date',
    ];

    /**
     * Konstanta untuk jenis sertifikasi
     */
    const JENIS_SERTIFIKASI = [
        'profesi' => 'Profesi',
        'keahlian' => 'Keahlian',
        'pelatihan' => 'Pelatihan',
        'kompetensi' => 'Kompetensi',
        'bahasa' => 'Bahasa',
        'teknologi' => 'Teknologi',
        'manajemen' => 'Manajemen',
        'keselamatan' => 'Keselamatan Kerja',
        'lainnya' => 'Lainnya'
    ];

    /**
     * Konstanta untuk tingkat sertifikasi
     */
    const TINGKAT_SERTIFIKASI = [
        'dasar' => 'Dasar',
        'menengah' => 'Menengah',
        'lanjut' => 'Lanjut',
        'ahli' => 'Ahli',
        'master' => 'Master'
    ];

    /**
     * Konstanta untuk status sertifikasi
     */
    const STATUS_SERTIFIKASI = [
        'aktif' => 'Aktif',
        'kadaluwarsa' => 'Kadaluwarsa',
        'dicabut' => 'Dicabut',
        'dalam_proses' => 'Dalam Proses'
    ];

    /**
     * Konstanta untuk lembaga penerbit umum
     */
    const LEMBAGA_POPULER = [
        'LSP' => 'LSP (Lembaga Sertifikasi Profesi)',
        'BNSP'=>'BNSP (Badan Nasional Sertifikasi Profesi)',
        'Microsoft'=>'Microsoft',
        'Oracle'=>'Oracle',
        'Cisco'=>'Cisco',
        'AWS'=>'AWS (Amazon Web Services)',
        'Google'=>'Google',
        'Adobe'=>'Adobe',
        'PMI'=>'PMI (Project Management Institute)',
        'ISO'=>'ISO (International Organization for Standardization)',
        'Kemendikbud'=>'Kemendikbud',
        'Kemenaker'=>'Kemenaker',
        'BPJSTK'=>'BPJS Ketenagakerjaan',
        'Lainnya'=>'Lainnya'
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

    public function getStatusBadgeAttribute()
    {
        $statusConfig = [
            'aktif' => ['text' => 'Aktif', 'class' => 'bg-green-100 text-green-800'],
            'kadaluwarsa' => ['text' => 'Kadaluwarsa', 'class' => 'bg-red-100 text-red-800'],
            'dicabut' => ['text' => 'Dicabut', 'class' => 'bg-red-100 text-red-800'],
            'dalam_proses' => ['text' => 'Prosesing', 'class' => 'bg-yellow-100 text-yellow-800'],
        ];

        return $statusConfig[$this->status_sertifikat] ?? ['text' => 'Unknown', 'class' => 'bg-gray-100 text-gray-800'];
    }
}
