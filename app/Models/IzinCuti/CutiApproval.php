<?php

namespace App\Models\IzinCuti;

use Illuminate\Database\Eloquent\Model;

class CutiApproval extends Model
{
    protected $table = 'cuti_approval';

    protected $fillable = [
        'cuti_pengajuan_id',
        'atasan_user_id',
        'level',
        'status',
        'komentar',
        'approved_by',
        'approved_at',
        'urutan_approval',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function cutiPengajuan()
    {
        return $this->belongsTo(CutiPengajuan::class, 'cuti_pengajuan_id');
    }

    public function atasanUser()
    {
        return $this->belongsTo('App\Models\Atasan\AtasanUser', 'atasan_user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo('App\Models\User', 'approved_by');
    }

    public function approver()
    {
        return $this->belongsTo('App\Models\User', 'approved_by');
    }
}
