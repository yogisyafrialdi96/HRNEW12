<?php

namespace App\Livewire\Admin\Izin;

use App\Models\IzinCuti\IzinPengajuan;
use App\Models\IzinCuti\IzinApproval;
use App\Models\IzinCuti\IzinApprovalHistory;
use App\Models\Atasan\AtasanUser;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class IzinApprovalIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterJenisIzin = '';
    public $filterStatus = '';

    public $showModal = false;
    public $selectedApproval = null;
    public $approvalComment = '';
    public $approvalAction = '';

    public function mount()
    {
        $this->authorize('izin.approve');
    }

    #[\Livewire\Attributes\Computed]
    public function pengajuans()
    {
        $userId = auth()->id();
        
        $query = IzinPengajuan::with([
            'karyawan:id,nama,nip',
            'approval.atasanUser:id,user_id',
            'approvalHistories:id,izin_pengajuan_id,level,status,approved_by,approval_comment,created_at'
        ])
        ->where('status', 'pending')
        ->whereHas('approval.atasanUser', function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->where('is_active', true);
        })
        ->whereHas('approval', function ($q) {
            $q->where('status', 'pending');
        });

        if ($this->search) {
            $query->whereHas('karyawan', function ($q) {
                $q->where('nama', 'like', "%{$this->search}%")
                  ->orWhere('nip', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterJenisIzin) {
            $query->where('jenis_izin', $this->filterJenisIzin);
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        return $query->paginate(10);
    }

    public function openApprovalModal($pengajuanId)
    {
        $pengajuan = IzinPengajuan::with('approval.atasanUser', 'approvalHistories')->findOrFail($pengajuanId);
        
        // Check if current user has pending approval for this pengajuan
        $hasApproval = $pengajuan->approval()
            ->whereHas('atasanUser', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->where('status', 'pending')
            ->exists();

        if (!$hasApproval) {
            session()->flash('error', 'Anda tidak memiliki wewenang untuk approval pengajuan ini.');
            return;
        }

        $this->selectedApproval = $pengajuan;
        $this->approvalComment = '';
        $this->approvalAction = '';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetModal();
    }

    public function resetModal()
    {
        $this->selectedApproval = null;
        $this->approvalComment = '';
        $this->approvalAction = '';
    }

    public function approve($pengajuanId)
    {
        $this->approvalAction = 'approved';
        $this->processApproval($pengajuanId);
    }

    public function reject($pengajuanId)
    {
        $this->approvalAction = 'rejected';
        $this->processApproval($pengajuanId);
    }

    private function processApproval($pengajuanId)
    {
        try {
            DB::beginTransaction();

            $pengajuan = IzinPengajuan::findOrFail($pengajuanId);
            
            // Validate rules
            $this->validate([
                'approvalComment' => 'required|string|max:500',
                'approvalAction' => 'required|in:approved,rejected'
            ], [
                'approvalComment.required' => 'Komentar approval harus diisi',
                'approvalComment.max' => 'Komentar tidak boleh lebih dari 500 karakter'
            ]);

            // Get current user's approval
            $currentApproval = $pengajuan->approval()
                ->whereHas('atasanUser', function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->where('status', 'pending')
                ->first();

            if (!$currentApproval) {
                throw new \Exception('Approval tidak ditemukan atau sudah diproses');
            }

            // Update approval status
            $currentApproval->update([
                'status' => $this->approvalAction,
                'komentar' => $this->approvalComment,
                'approved_at' => now()
            ]);

            // Record history
            IzinApprovalHistory::create([
                'izin_pengajuan_id' => $pengajuanId,
                'level' => $currentApproval->level,
                'status' => $this->approvalAction,
                'approved_by' => auth()->user()->id,
                'approval_comment' => $this->approvalComment
            ]);

            // Check if all approvals are done
            $allApprovals = $pengajuan->approval;
            $pendingCount = $allApprovals->where('status', 'pending')->count();
            $rejectedCount = $allApprovals->where('status', 'rejected')->count();

            if ($rejectedCount > 0) {
                // If any approval is rejected, mark pengajuan as rejected
                $pengajuan->update(['status' => 'rejected']);
                $message = 'Pengajuan izin ditolak';
            } elseif ($pendingCount === 0) {
                // If all approvals are done and none rejected, mark as approved
                $pengajuan->update(['status' => 'approved']);
                $message = 'Pengajuan izin disetujui';
            } else {
                $message = 'Pengajuan izin sedang dalam proses approval';
            }

            DB::commit();

            $this->dispatch('toast', message: $message, type: 'success');
            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('toast', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.admin.izin.izin-approval-index', [
            'pengajuans' => $this->pengajuans
        ]);
    }
}
