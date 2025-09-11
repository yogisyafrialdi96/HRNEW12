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
        'mapel',
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
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }
}
