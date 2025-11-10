<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KaryawanKontrak extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'karyawan_kontrak';

    protected $fillable = [
        'nomor_kontrak',
        'karyawan_id',
        'kontrak_id',
        'golongan_id',
        'unit_id',
        'jabatan_id',
        'mapel_id',
        'gaji_paket',
        'gaji_pokok',
        'transport',
        'tglmulai_kontrak',
        'tglselesai_kontrak',
        'status',
        'catatan',
        'deskripsi',
        'created_by',
        'updated_by',
        'approved_1',
        'approved_2',
    ];

    // Relationships
    public function karyawan()
    {
        return $this->belongsTo(\App\Models\Employee\Karyawan::class, 'karyawan_id');
    }

    public function kontrak()
    {
        return $this->belongsTo(\App\Models\Master\Kontrak::class, 'kontrak_id');
    }

    public function golongan()
    {
        return $this->belongsTo(\App\Models\Master\Golongan::class, 'golongan_id');
    }

    public function unit()
    {
        return $this->belongsTo(\App\Models\Master\Units::class, 'unit_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(\App\Models\Master\Jabatans::class, 'jabatan_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function mapel()
    {
        return $this->belongsTo(\App\Models\Master\Mapel::class, 'id');
    }

    public function approver1()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_1');
    }

    public function approver2()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_2');
    }
}
