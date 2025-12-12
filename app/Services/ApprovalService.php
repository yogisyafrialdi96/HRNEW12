<?php

namespace App\Services;

use App\Models\IzinCuti\CutiPengajuan;
use App\Models\IzinCuti\CutiApproval;
use App\Models\IzinCuti\CutiApprovalHistory;
use App\Models\IzinCuti\CutiSaldo;
use App\Models\User;
use App\Models\Atasan\AtasanUser;
use App\Models\Master\TahunAjaran;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ApprovalService
{
    /**
     * Get approval hierarchy untuk user
     * 
     * @param int $userId
     * @return Collection|null
     */
    public static function getApprovalHierarchy($userId)
    {
        return AtasanUser::where('user_id', $userId)
            ->where('is_active', true)
            ->orderBy('level', 'asc')
            ->with(['atasan', 'atasan.karyawan'])
            ->get();
    }

    /**
     * Get next approver untuk cuti pengajuan
     * 
     * @param CutiPengajuan $cutiPengajuan
     * @return User|null
     */
    public static function getNextApprover(CutiPengajuan $cutiPengajuan)
    {
        $nextApproval = $cutiPengajuan->approval()
            ->where('status', 'pending')
            ->orderBy('urutan_approval', 'asc')
            ->first();

        return $nextApproval ? $nextApproval->atasanUser->atasan : null;
    }

    /**
     * Get current approval level dari cuti_approval records
     * 
     * @param CutiPengajuan $cutiPengajuan
     * @return int
     */
    public static function getCurrentApprovalLevel(CutiPengajuan $cutiPengajuan)
    {
        // Get last completed approval
        $lastApproval = $cutiPengajuan->approval()
            ->where('status', '!=', 'pending')
            ->orderBy('urutan_approval', 'desc')
            ->first();

        if (!$lastApproval) {
            return 0; // Belum ada approval
        }

        return $lastApproval->level;
    }

    /**
     * Get total approval levels required untuk user
     * 
     * @param int $userId
     * @return int
     */
    public static function getTotalApprovalLevels($userId)
    {
        return AtasanUser::where('user_id', $userId)
            ->where('is_active', true)
            ->max('level') ?? 0;
    }

    /**
     * Approve cuti pengajuan
     * Menciptakan/update CutiApproval record dan CutiApprovalHistory
     * Juga update CutiSaldo ketika semua level approved
     * 
     * @param CutiPengajuan $cutiPengajuan
     * @param User $approver
     * @param string $notes
     * @return bool
     */
    public static function approveCuti(CutiPengajuan $cutiPengajuan, User $approver, $notes = '')
    {
        return DB::transaction(function () use ($cutiPengajuan, $approver, $notes) {
            // Get pending approval untuk level saat ini
            $pendingApproval = $cutiPengajuan->approval()
                ->where('status', 'pending')
                ->orderBy('urutan_approval', 'asc')
                ->first();

            if (!$pendingApproval) {
                throw new \Exception('Tidak ada pending approval untuk cuti ini');
            }

            // Update CutiApproval record
            $pendingApproval->update([
                'status' => 'approved',
                'approved_by' => $approver->id,
                'approved_at' => now(),
                'komentar' => $notes,
            ]);

            // Create approval history record - gunakan field yang ada di migration
            CutiApprovalHistory::create([
                'cuti_pengajuan_id' => $cutiPengajuan->id,
                'action' => 'approved',
                'user_id' => $approver->id,
                'keterangan' => "Disetujui oleh {$approver->name} pada Level {$pendingApproval->level}",
            ]);

            // Refresh pengajuan untuk get updated approval status
            $cutiPengajuan->refresh();
            
            // Check apakah semua approvals sudah approved
            $totalApprovals = $cutiPengajuan->approval()->count();
            $approvedCount = $cutiPengajuan->approval()
                ->where('status', 'approved')
                ->count();

            if ($approvedCount == $totalApprovals) {
                // Semua approval level sudah approve - update status jadi approved
                $cutiPengajuan->update([
                    'status' => 'approved',
                    'updated_by' => $approver->id,
                ]);

                // Update CutiSaldo - kurangi sisa sesuai jenis cuti
                if ($cutiPengajuan->cutiSaldo) {
                    $cutiSaldo = $cutiPengajuan->cutiSaldo;
                    
                    if ($cutiPengajuan->jenis_cuti === 'tahunan') {
                        // Update cuti tahunan terpakai dan sisa
                        $cutiSaldo->increment('cuti_tahunan_terpakai', $cutiPengajuan->jumlah_hari);
                        $cutiSaldo->decrement('cuti_tahunan_sisa', $cutiPengajuan->jumlah_hari);
                    } elseif ($cutiPengajuan->jenis_cuti === 'melahirkan') {
                        // Update cuti melahirkan terpakai dan sisa
                        $cutiSaldo->increment('cuti_melahirkan_terpakai', $cutiPengajuan->jumlah_hari);
                        $cutiSaldo->decrement('cuti_melahirkan_sisa', $cutiPengajuan->jumlah_hari);
                    }
                }
            }

            return true;
        });
    }

    /**
     * Reject cuti pengajuan
     * 
     * @param CutiPengajuan $cutiPengajuan
     * @param User $approver
     * @param string $reason
     * @return bool
     */
    public static function rejectCuti(CutiPengajuan $cutiPengajuan, User $approver, $reason = '')
    {
        return DB::transaction(function () use ($cutiPengajuan, $approver, $reason) {
            // Get pending approval untuk level saat ini
            $pendingApproval = $cutiPengajuan->approval()
                ->where('status', 'pending')
                ->orderBy('urutan_approval', 'asc')
                ->first();

            if (!$pendingApproval) {
                throw new \Exception('Tidak ada pending approval untuk cuti ini');
            }

            // Update CutiApproval record
            $pendingApproval->update([
                'status' => 'rejected',
                'approved_by' => $approver->id,
                'approved_at' => now(),
                'komentar' => $reason,
            ]);

            // Create approval history record - gunakan field yang ada di migration
            CutiApprovalHistory::create([
                'cuti_pengajuan_id' => $cutiPengajuan->id,
                'action' => 'rejected',
                'user_id' => $approver->id,
                'keterangan' => "Ditolak oleh {$approver->name} pada Level {$pendingApproval->level}",
            ]);

            // Update cuti status jadi rejected
            $cutiPengajuan->update([
                'status' => 'rejected',
                'catatan_reject' => $reason,
                'updated_by' => $approver->id,
            ]);

            return true;
        });
    }

    /**
     * Get pending approvals untuk user (sebagai approver)
     * Menggunakan relasi cuti_approval dengan status pending
     * 
     * @param int $userId
     * @param int $level
     * @return Collection
     */
    public static function getPendingApprovalsForUser($userId, $level = null)
    {
        // Get pending cuti approvals dimana approver adalah user ini
        $query = CutiApproval::where('atasan_user_id', $userId)
            ->where('status', 'pending')
            ->with(['cutiPengajuan' => function ($q) {
                $q->with(['user', 'user.karyawan']);
            }])
            ->when($level, fn($q) => $q->where('level', $level))
            ->orderBy('created_at', 'desc');

        return $query->get()->map(fn($approval) => $approval->cutiPengajuan);
    }

    /**
     * Check apakah user bisa approve cuti pengajuan pada level tertentu
     * 
     * @param CutiPengajuan $cutiPengajuan
     * @param User $approver
     * @return bool
     */
    public static function canApprove(CutiPengajuan $cutiPengajuan, User $approver)
    {
        $atasanUser = AtasanUser::where('user_id', $cutiPengajuan->user_id)
            ->where('atasan_id', $approver->id)
            ->where('is_active', true)
            ->first();

        if (!$atasanUser) {
            return false;
        }

        // Check apakah ada pending approval untuk level ini dan approver ini
        $pendingApproval = $cutiPengajuan->approval()
            ->where('atasan_user_id', $atasanUser->id)
            ->where('status', 'pending')
            ->first();

        return $pendingApproval !== null;
    }

    /**
     * Get approval history dari cuti_approval_history
     * 
     * @param CutiPengajuan $cutiPengajuan
     * @return Collection
     */
    public static function getApprovalHistory(CutiPengajuan $cutiPengajuan)
    {
        return $cutiPengajuan->history()
            ->orderBy('created_at', 'asc')
            ->with(['user', 'approver'])
            ->get();
    }

    /**
     * Get approval status badge config untuk display
     * 
     * @param string $status
     * @return array
     */
    public static function getStatusBadgeConfig($status)
    {
        $config = [
            'pending' => [
                'label' => 'Menunggu',
                'color' => 'bg-yellow-100 text-yellow-800',
                'icon' => 'clock',
            ],
            'approved' => [
                'label' => 'Disetujui',
                'color' => 'bg-green-100 text-green-800',
                'icon' => 'check',
            ],
            'rejected' => [
                'label' => 'Ditolak',
                'color' => 'bg-red-100 text-red-800',
                'icon' => 'x-mark',
            ],
        ];

        return $config[$status] ?? [
            'label' => ucfirst($status),
            'color' => 'bg-gray-100 text-gray-800',
            'icon' => 'question-mark',
        ];
    }

    /**
     * Validate apakah cuti request dapat disetujui (cek saldo)
     * 
     * @param CutiPengajuan $cutiPengajuan
     * @return array ['valid' => bool, 'message' => string]
     */
    public static function validateCutiBalance(CutiPengajuan $cutiPengajuan): array
    {
        if (!$cutiPengajuan->cutiSaldo) {
            return [
                'valid' => false,
                'message' => 'Saldo cuti tidak ditemukan untuk tahun ajaran ini'
            ];
        }

        $cutiSaldo = $cutiPengajuan->cutiSaldo;
        $jumlahHari = $cutiPengajuan->jumlah_hari;

        if ($cutiPengajuan->jenis_cuti === 'tahunan') {
            $sisa = $cutiSaldo->cuti_tahunan_sisa ?? 0;
            if ($jumlahHari > $sisa) {
                return [
                    'valid' => false,
                    'message' => "Sisa cuti tahunan tidak cukup. Tersedia: {$sisa} hari, diminta: {$jumlahHari} hari"
                ];
            }
        } elseif ($cutiPengajuan->jenis_cuti === 'melahirkan') {
            $sisa = $cutiSaldo->cuti_melahirkan_sisa ?? 0;
            if ($jumlahHari > $sisa) {
                return [
                    'valid' => false,
                    'message' => "Sisa cuti melahirkan tidak cukup. Tersedia: {$sisa} hari, diminta: {$jumlahHari} hari"
                ];
            }
        }

        return [
            'valid' => true,
            'message' => 'Saldo cuti cukup'
        ];
    }

    /**
     * Get available saldo untuk user tertentu di tahun tertentu
     * 
     * @param User $user
     * @param TahunAjaran $tahunAjaran
     * @return CutiSaldo|null
     */
    public static function getUserCutiSaldo(User $user, TahunAjaran $tahunAjaran): ?CutiSaldo
    {
        return CutiSaldo::where('user_id', $user->id)
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->first();
    }
}
