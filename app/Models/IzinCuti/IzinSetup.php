<?php

namespace App\Models\IzinCuti;

use Illuminate\Database\Eloquent\Model;

class IzinSetup extends Model
{
    protected $table = 'izin_setup';

    protected $fillable = [
        'h_min_izin_sakit',
        'max_izin_sakit_per_tahun',
        'sakit_perlu_surat_dokter',
        'hari_ke_berapa_perlu_dokter',
        'h_min_izin_penting',
        'max_izin_penting_per_tahun',
        'h_min_izin_ibadah',
        'max_hari_ibadah_per_tahun',
        'tidak_hitung_libnas',
        'tidak_hitung_libur_unit',
        'catatan',
        'updated_by',
    ];

    protected $casts = [
        'sakit_perlu_surat_dokter' => 'boolean',
        'tidak_hitung_libnas' => 'boolean',
        'tidak_hitung_libur_unit' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function updatedBy()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
}
