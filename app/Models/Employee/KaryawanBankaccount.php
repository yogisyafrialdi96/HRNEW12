<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanBankaccount extends Model
{
    use HasFactory;

    protected $table = 'karyawan_akunbank';

    protected $fillable = [
        'karyawan_id',
        'nama_bank',
        'kode_bank',
        'nomor_rekening',
        'nama_pemilik',
        'jenis_rekening',
        'tujuan',
        'cabang',
        'is_primary',
        'status',
        'tanggal_buka',
        'keterangan',
        'document_path',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'tanggal_buka' => 'date',
    ];

    const JENIS_REKENING = [
        'tabungan' => 'Tabungan',
        'giro' => 'Giro',
        'deposito' => 'Deposito',
        'kredit' => 'Kredit',
    ];

    const TUJUAN_REKENING = [
        'gaji' => 'Gaji',
        'bonus' => 'Bonus',
        'tunjangan' => 'Tunjangan',
        'reimburse' => 'Reimburse',
        'pribadi' => 'Pribadi',
    ];

    const STATUS_REKENING = [
        'aktif' => 'Aktif',
        'nonaktif' => 'Nonaktif',
        'blocked' => 'Blocked',
        'closed' => 'Closed',
    ];

    public function getStatusBadgeAttribute()
    {
        $statusConfig = [
            'aktif' => ['text' => 'Aktif', 'class' => 'bg-green-100 text-green-800'],
            'nonaktif' => ['text' => 'Nonaktif', 'class' => 'bg-red-100 text-red-800'],
            'blocked' => ['text' => 'Blocked', 'class' => 'bg-gray-100 text-gray-800'],
            'closed' => ['text' => 'Closed', 'class' => 'bg-red-100 text-red-800'],
        ];

        return $statusConfig[$this->status] ?? ['text' => 'Unknown', 'class' => 'bg-gray-100 text-gray-800'];
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
