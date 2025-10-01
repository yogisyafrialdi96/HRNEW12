<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;

class KaryawanOrganisasi extends Model
{
    protected $table = 'karyawan_organisasi';

    protected $fillable = [
        'karyawan_id',
        'organisasi',
        'level',
        'jabatan',
        'tgl_awal',
        'tgl_akhir',
        'status_organisasi',
        'peran',
        'jenis_organisasi',
        'jenisorg_lain',
        'alamat_organisasi',
        'website',
        'email_organisasi',
        'document_path',
        'catatan',
        'created_by',
        'updated_by',
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
            'alumni' => ['text' => 'Alumni', 'class' => 'bg-yellow-100 text-yellow-800'],
            'tidak_aktif' => ['text' => 'Nonaktif', 'class' => 'bg-red-100 text-red-800'],
            'pensiun' => ['text' => 'Pensiun', 'class' => 'bg-blue-100 text-blue-800'],
        ];

        return $statusConfig[$this->status_organisasi] ?? ['text' => 'Unknown', 'class' => 'bg-gray-100 text-gray-800'];
    }
}