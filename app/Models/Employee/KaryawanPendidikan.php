<?php

namespace App\Models\Employee;

use App\Models\Master\EducationLevel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KaryawanPendidikan extends Model
{
    protected $table = 'karyawan_pendidikan';

    protected $fillable = [
        'karyawan_id',
        'education_level_id',
        'nama_institusi',
        'jenis_institusi',
        'fakultas',
        'jurusan',
        'spesialisasi',
        'gelar',
        'tahun_mulai',
        'tahun_selesai',
        'tanggal_ijazah',
        'nomor_ijazah',
        'ipk',
        'skala_ipk',
        'judul_skripsi',
        'negara',
        'kota',
        'akreditasi',
        'jenis_belajar',
        'sumber_dana',
        'nama_beasiswa',
        'is_current',
        'status',
        'document_path',
        'ket',
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function educationLevel()
    {
        return $this->belongsTo(\App\Models\Master\EducationLevel::class, 'education_level_id');
    }

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
            'completed' => ['text' => 'completed', 'class' => 'bg-green-100 text-green-800'],
            'ongoing' => ['text' => 'Ongoing', 'class' => 'bg-yellow-100 text-yellow-800'],
            'dropped_out' => ['text' => 'Droped Out', 'class' => 'bg-red-100 text-red-800'],
            'transferred' => ['text' => 'Transfered', 'class' => 'bg-blue-100 text-blue-800'],
        ];

        return $statusConfig[$this->status] ?? ['text' => 'Unknown', 'class' => 'bg-gray-100 text-gray-800'];
    }
}
