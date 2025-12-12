<?php

namespace App\Models\IzinCuti;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IzinAlasan extends Model
{
    use SoftDeletes;

    protected $table = 'izin_alasan';

    protected $fillable = [
        'nama_alasan',
        'jenis_izin',
        'max_hari_setahun',
        'is_bayar_penuh',
        'perlu_surat_dokter',
        'keterangan',
        'is_active',
        'urutan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_bayar_penuh' => 'boolean',
        'perlu_surat_dokter' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Scope untuk mendapatkan alasan yang aktif saja
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk filter jenis izin
     */
    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_izin', $jenis);
    }

    /**
     * Scope untuk sorting
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('nama_alasan');
    }

    /**
     * Relationships
     */
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function izinPengajuan()
    {
        return $this->hasMany(IzinPengajuan::class, 'izin_alasan_id');
    }
}
