<?php

namespace App\Models\IzinCuti;

use Illuminate\Database\Eloquent\Model;

class LiburNasional extends Model
{
    protected $table = 'libur_nasional';

    protected $fillable = [
        'nama_libur',
        'tanggal_libur',
        'tanggal_libur_akhir',
        'tipe',
        'provinsi_id',
        'is_active',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_libur' => 'date',
        'tanggal_libur_akhir' => 'date',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function provinsi()
    {
        return $this->belongsTo('App\Models\Wilayah\Provinsi', 'provinsi_id');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }
}
