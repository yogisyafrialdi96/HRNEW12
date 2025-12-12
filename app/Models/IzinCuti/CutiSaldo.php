<?php

namespace App\Models\IzinCuti;

use Illuminate\Database\Eloquent\Model;

class CutiSaldo extends Model
{
    protected $table = 'cuti_saldo';

    protected $fillable = [
        'user_id',
        'tahun_ajaran_id',
        'cuti_tahunan_awal',
        'cuti_tahunan_terpakai',
        'cuti_tahunan_sisa',
        'cuti_melahirkan_awal',
        'cuti_melahirkan_terpakai',
        'cuti_melahirkan_sisa',
        'carry_over_tahunan',
        'carry_over_digunakan',
        'catatan',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo('App\Models\Master\TahunAjaran', 'tahun_ajaran_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function cutiPengajuan()
    {
        return $this->hasMany(CutiPengajuan::class, 'cuti_saldo_id');
    }
}
