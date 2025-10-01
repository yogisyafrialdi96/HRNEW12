<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;

class KaryawanPekerjaan extends Model
{
    protected $table = 'karyawan_pengalamankerja';

    protected $fillable = [
        'karyawan_id',
        'nama_instansi',
        'departemen',
        'jabatan',
        'lokasi_pekerjaan',
        'bidang_industri',
        'jenis_kontrak',
        'tgl_awal',
        'tgl_akhir',
        'status_kerja',
        'gaji_awal',
        'gaji_akhir',
        'mata_uang',
        'peran',
        'alasan_berhenti',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tgl_awal' => 'date',
        'tgl_akhir' => 'date',
        'gaji_awal' => 'decimal:2',
        'gaji_akhir' => 'decimal:2',
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
            'selesai' => ['text' => 'Selesai', 'class' => 'bg-red-100 text-red-800'],
            'resign' => ['text' => 'Resign', 'class' => 'bg-red-100 text-red-800'],
            'phk' => ['text' => 'PHK', 'class' => 'bg-red-100 text-red-800'],
            'mutasi' => ['text' => 'Mutasi', 'class' => 'bg-yellow-100 text-yellow-800'],
            'pensiun' => ['text' => 'Pensiun', 'class' => 'bg-gray-100 text-gray-800'],
        ];

        return $statusConfig[$this->status_kerja] ?? ['text' => 'Unknown', 'class' => 'bg-gray-100 text-gray-800'];
    }
}
