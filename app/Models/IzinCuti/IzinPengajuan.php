<?php

namespace App\Models\IzinCuti;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IzinPengajuan extends Model
{
    use SoftDeletes;

    protected $table = 'izin_pengajuan';

    protected $fillable = [
        'user_id',
        'tahun_ajaran_id',
        'izin_alasan_id',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_jam',
        'alasan',
        'file_surat_dokter',
        'tanggal_surat_dokter',
        'created_by',
        'updated_by',
        'catatan_reject',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_surat_dokter' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo('App\Models\Master\TahunAjaran', 'tahun_ajaran_id');
    }

    public function izinAlasan()
    {
        return $this->belongsTo(IzinAlasan::class, 'izin_alasan_id');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function approval()
    {
        return $this->hasMany(IzinApproval::class, 'izin_pengajuan_id');
    }

    public function history()
    {
        return $this->hasMany(IzinApprovalHistory::class, 'izin_pengajuan_id');
    }

    public function approvalHistories()
    {
        return $this->hasMany(IzinApprovalHistory::class, 'izin_pengajuan_id');
    }

    public function karyawan()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Get current approval status berdasarkan relasi izin_approval
     * Status diambil dari approval record dengan urutan_approval tertinggi
     * 
     * @return string|null
     */
    public function getCurrentApprovalStatus()
    {
        // Jika status sudah approved atau rejected di level final, gunakan status izin_pengajuan
        if ($this->status === 'approved') {
            return 'approved';
        }
        
        if ($this->status === 'rejected') {
            return 'rejected';
        }

        // Ambil approval dengan urutan tertinggi yang sudah diproses
        $lastApproval = $this->approval()
            ->orderBy('urutan_approval', 'desc')
            ->first();

        if (!$lastApproval) {
            // Jika belum ada approval sama sekali, maka pending di level 1
            return 'pending_level_1';
        }

        // Tentukan status berdasarkan level dan status approval
        if ($lastApproval->status === 'approved') {
            // Cek apakah ini approval terakhir
            $nextApproval = $this->approval()
                ->where('urutan_approval', '>', $lastApproval->urutan_approval)
                ->first();
            
            if ($nextApproval) {
                return "approved_level_{$lastApproval->level}_pending_level_{$nextApproval->level}";
            }
            
            return 'approved';
        }

        if ($lastApproval->status === 'rejected') {
            return "rejected_level_{$lastApproval->level}";
        }

        return "pending_level_{$lastApproval->level}";
    }

    /**
     * Get last approval record
     */
    public function getLastApproval()
    {
        return $this->approval()
            ->orderBy('urutan_approval', 'desc')
            ->first();
    }

    /**
     * Get last approval history record
     */
    public function getLastApprovalHistory()
    {
        return $this->history()
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Get current approver yang harus approve
     */
    public function getCurrentApprover()
    {
        $pendingApproval = $this->approval()
            ->where('status', 'pending')
            ->orderBy('urutan_approval', 'asc')
            ->first();

        if ($pendingApproval) {
            return $pendingApproval->atasanUser;
        }

        return null;
    }
}
