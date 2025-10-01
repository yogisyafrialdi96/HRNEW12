<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanDokumen extends Model
{
    use HasFactory;

    protected $table = 'karyawan_dokumen';

    protected $fillable = [
        'karyawan_id', 
        'nama_dokumen',
        'jenis_dokumen',
        'jenis_lainnya', 
        'status_dokumen', 
        'document_path', 
        'created_by',
        'edited_by'
    ];

    public const JENIS_DOKUMEN = [
        'foto' => 'Foto',
        'ktp' => 'KTP',
        'kk' => 'KK',
        'skck' => 'SKCK',
        'npwp' => 'NPWP',
        'ijazah' => 'Ijazah',
        'transkrip' => 'Transkrip',
        'sertifikat' => 'Sertifikat',
        'buku_tabungan' => 'Buku Tabungan',
        'lainnya' => 'Lainnya'
    ];

    public function getStatusBadgeAttribute()
    {
        $statusConfig = [
            'valid' => ['text' => 'Valid', 'class' => 'bg-green-100 text-green-800'],
            'invalid' => ['text' => 'Invalid', 'class' => 'bg-red-100 text-red-800'],
            'waiting' => ['text' => 'Waiting', 'class' => 'bg-yellow-100 text-yellow-800'],
        ];

        return $statusConfig[$this->status_dokumen] ?? ['text' => 'Unknown', 'class' => 'bg-gray-100 text-gray-800'];
    }

    public function getJenisBadgeAttribute()
    {
        $statusConfig = [
            'foto' => ['text' => 'Foto'],
            'ktp' => ['text' => 'KTP'],
            'kk' => ['text' => 'KK'],
            'skck' => ['text' => 'SKCK'],
            'npwp' => ['text' => 'NPWP'],
            'ijazah' => ['text' => 'Ijazah'],
            'transkrip' => ['text' => 'Transkrip'],
            'sertifikat' => ['text' => 'Sertifikat'],
            'buku_tabungan' => ['text' => 'Buku Tabungan'],
        ];

        return $statusConfig[$this->jenis_dokumen] ?? ['text' => 'Unknown'];
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
}
