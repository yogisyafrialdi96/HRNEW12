<?php

namespace App\Models\IzinCuti;

use Illuminate\Database\Eloquent\Model;

class CutiApprovalHistory extends Model
{
    protected $table = 'cuti_approval_history';

    protected $fillable = [
        'cuti_pengajuan_id',
        'action',
        'user_id',
        'old_data',
        'new_data',
        'keterangan',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function cutiPengajuan()
    {
        return $this->belongsTo(CutiPengajuan::class, 'cuti_pengajuan_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
