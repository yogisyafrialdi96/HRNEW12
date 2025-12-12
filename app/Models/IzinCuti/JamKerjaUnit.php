<?php

namespace App\Models\IzinCuti;

use Illuminate\Database\Eloquent\Model;

class JamKerjaUnit extends Model
{
    protected $table = 'jam_kerja_unit';

    protected $fillable = [
        'unit_id',
        'hari_ke',
        'jam_masuk',
        'jam_pulang',
        'jam_istirahat',
        'is_libur',
        'is_full_day',
        'keterangan',
    ];

    protected $casts = [
        'is_libur' => 'boolean',
        'is_full_day' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function unit()
    {
        return $this->belongsTo('App\Models\Master\Units', 'unit_id');
    }

    public function getNamaHariAttribute()
    {
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        return $hari[$this->hari_ke - 1] ?? 'Tidak diketahui';
    }
}
