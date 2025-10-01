<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanPelatihan extends Model
{
    use HasFactory;

    protected $table = 'karyawan_pelatihan';

    protected $fillable = [
        'karyawan_id',
        'nama_pelatihan',
        'penyelenggara',
        'lokasi',
        'tgl_mulai',
        'tgl_selesai',
        'jenis_pelatihan', //(e.g., 'internal','eksternal','online','offline','hybrid')
        'sertifikat_diperoleh',
        'document_path',
        'keterangan',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
        'sertifikat_diperoleh' => 'boolean',
    ];

    const JENIS_PELATIHAN = [
        'internal' => 'Internal',
        'eksternal' => 'Eksternal',
        'online' => 'Online',
        'offline' => 'Offline',
        'hybrid' => 'Hybrid'
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
