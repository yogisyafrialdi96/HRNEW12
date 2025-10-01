<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanKeluarga extends Model
{
    use HasFactory;

    protected $table = 'karyawan_anggotakeluarga';

    protected $fillable = [
        'karyawan_id',
        'nama_anggota',
        'hubungan',
        'hubungan_lain',
        'jenis_kelamin',
        'tempat_lahir',
        'tgl_lahir',
        'pekerjaan',
        'status_hidup',
        'ditanggung',
        'alamat',
        'no_hp',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'ditanggung' => 'boolean',
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
            'Hidup' => ['text' => 'Hidup', 'class' => 'bg-green-100 text-green-800'],
            'Meninggal' => ['text' => 'Meninggal', 'class' => 'bg-red-100 text-red-800'],
        ];

        return $statusConfig[$this->status_hidup] ?? ['text' => 'Unknown', 'class' => 'bg-gray-100 text-gray-800'];
    }
}
