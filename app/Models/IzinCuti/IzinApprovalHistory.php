<?php

namespace App\Models\IzinCuti;

use Illuminate\Database\Eloquent\Model;

class IzinApprovalHistory extends Model
{
    protected $table = 'izin_approval_history';

    protected $fillable = [
        'izin_pengajuan_id',
        'level',
        'status',
        'approved_by',
        'approval_comment',
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

    public function izinPengajuan()
    {
        return $this->belongsTo(IzinPengajuan::class, 'izin_pengajuan_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\Models\User', 'approved_by');
    }
}
