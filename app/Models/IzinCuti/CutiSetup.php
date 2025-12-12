<?php

namespace App\Models\IzinCuti;

use Illuminate\Database\Eloquent\Model;

class CutiSetup extends Model
{
    protected $table = 'cuti_setup';

    protected $fillable = [
        'h_min_cuti_tahunan',
        'max_cuti_tahunan_per_tahun',
        'max_carry_over',
        'hari_cuti_melahirkan',
        'h_min_cuti_melahirkan',
        'hari_kerja',
        'jam_kerja_per_hari',
        'catatan',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function updatedBy()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
}
