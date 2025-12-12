<?php

namespace App\Livewire\Izin;

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

    #[\Livewire\Attributes\Computed]
    public function pengajuans()
    {
        $userId = auth()->id();
        
        $query = IzinPengajuan::with([
            'karyawan:id,nama,nip',
            'approval.atasanUser:id,user_id',
            'approvalHistories:id,izin_pengajuan_id,user_id,action,keterangan,created_at'
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
        $pengajuan = IzinPengajuan::with('approval.atasanUser', 'approvalHistories.user')->findOrFail($pengajuanId);
        
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
        $this->resetErrorBag();
    }

    public function approve($pengajuanId)
    {
        try {
            $pengajuan = IzinPengajuan::findOrFail($pengajuanId);
            
            // Validate comment
            $this->validate([
                'approvalComment' => 'nullable|string|max:500',
            ]);

            $approval = $pengajuan->approval()
                ->whereHas('atasanUser', function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->where('status', 'pending')
                ->firstOrFail();

            DB::transaction(function () use ($approval, $pengajuan) {
                // Update approval
                $approval->update([
                    'status' => 'approved',
                    'komentar' => $this->approvalComment,
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);

                // Create history
                IzinApprovalHistory::create([
                    'izin_pengajuan_id' => $pengajuan->id,
                    'user_id' => auth()->id(),
                    'action' => 'approved',
                    'keterangan' => $this->approvalComment,
                ]);

                // Check if all approvals are done
                $pendingApprovals = $pengajuan->approval()->where('status', 'pending')->count();
                
                if ($pendingApprovals === 0) {
                    // All approvals done, update pengajuan status
                    $pengajuan->update(['status' => 'approved']);
                }
            });

            $this->closeModal();
            session()->flash('success', 'Pengajuan izin berhasil disetujui');
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function reject($pengajuanId)
    {
        try {
            $pengajuan = IzinPengajuan::findOrFail($pengajuanId);
            
            $this->validate([
                'approvalComment' => 'required|string|max:500',
            ]);

            $approval = $pengajuan->approval()
                ->whereHas('atasanUser', function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->where('status', 'pending')
                ->firstOrFail();

            DB::transaction(function () use ($approval, $pengajuan) {
                // Update approval
                $approval->update([
                    'status' => 'rejected',
                    'komentar' => $this->approvalComment,
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);

                // Create history
                IzinApprovalHistory::create([
                    'izin_pengajuan_id' => $pengajuan->id,
                    'user_id' => auth()->id(),
                    'action' => 'rejected',
                    'keterangan' => $this->approvalComment,
                ]);

                // Update pengajuan status to rejected
                $pengajuan->update(['status' => 'rejected']);
            });

            $this->closeModal();
            session()->flash('success', 'Pengajuan izin berhasil ditolak');
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.izin.izin-approval-index');
    }
}
