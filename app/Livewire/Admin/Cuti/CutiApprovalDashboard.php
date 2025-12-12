<?php

namespace App\Livewire\Admin\Cuti;

use App\Models\IzinCuti\CutiPengajuan;
use App\Services\ApprovalService;
use Livewire\Component;
use Livewire\WithPagination;

class CutiApprovalDashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $filterLevel = '';
    public $showHistory = false; // Toggle untuk menampilkan history
    public $showApprovalModal = false;
    public $selectedCuti = null;
    public $approvalNotes = '';
    public $approvalReason = '';
    public $approvalAction = ''; // approve or reject

    protected $rules = [
        'approvalNotes' => 'nullable|string|max:500',
        'approvalReason' => 'required_if:approvalAction,reject|string|min:3|max:500',
    ];

    /**
     * Get pending/history approvals untuk current user as approver
     */
    public function getPendingApprovalsProperty()
    {
        $userId = auth()->id();
        
        // Get all users yang current user adalah atasan-nya
        $subordinateIds = \App\Models\Atasan\AtasanUser::where('atasan_id', $userId)
            ->where('is_active', true)
            ->pluck('user_id')
            ->toArray();

        if (empty($subordinateIds)) {
            return collect([]);
        }

        // Get cuti dari subordinates
        if ($this->showHistory) {
            // Show APPROVED/REJECTED history
            $query = CutiPengajuan::whereIn('user_id', $subordinateIds)
                ->whereIn('status', ['approved', 'rejected'])
                ->with([
                    'user', 
                    'user.karyawan',
                    'approval' => function ($q) {
                        $q->with(['approvedBy'])->orderBy('urutan_approval', 'asc');
                    }
                ])
                ->orderBy('created_at', 'desc');
        } else {
            // Show PENDING/DRAFT (original logic)
            $query = CutiPengajuan::whereIn('user_id', $subordinateIds)
                ->whereIn('status', ['pending', 'draft'])
                ->with([
                    'user', 
                    'user.karyawan',
                    'approval' => function ($q) {
                        $q->with(['approvedBy'])->orderBy('urutan_approval', 'asc');
                    }
                ])
                ->orderBy('created_at', 'desc');
        }

        // Apply filters
        if ($this->search) {
            $query->whereHas('user.karyawan', function ($q) {
                $q->where('full_name', 'like', '%' . $this->search . '%');
            })->orWhere('nomor_cuti', 'like', '%' . $this->search . '%');
        }

        return $query->get();
    }

    /**
     * Get paginated list
     */
    public function getPagedApprovalsProperty()
    {
        $perPage = 10;
        $page = $this->getPage();
        $approvals = $this->pendingApprovals;

        return $approvals->slice(($page - 1) * $perPage, $perPage)->values();
    }

    /**
     * Open approval modal
     */
    public function openApprovalModal($cutiId)
    {
        $cuti = CutiPengajuan::with('user.karyawan')->find($cutiId);
        
        if (!$cuti) {
            $this->dispatch('notify', 'Pengajuan tidak ditemukan');
            return;
        }

        if (!ApprovalService::canApprove($cuti, auth()->user())) {
            $this->dispatch('notify', 'Anda tidak memiliki akses untuk approve pengajuan ini');
            return;
        }

        $this->selectedCuti = $cuti;
        $this->approvalNotes = '';
        $this->approvalReason = '';
        $this->approvalAction = '';
        $this->showApprovalModal = true;
    }

    /**
     * Close modal
     */
    public function closeApprovalModal()
    {
        $this->showApprovalModal = false;
        $this->selectedCuti = null;
        $this->approvalNotes = '';
        $this->approvalReason = '';
        $this->approvalAction = '';
    }

    /**
     * Approve cuti
     */
    public function approve()
    {
        \Illuminate\Support\Facades\Log::info('✅ APPROVE METHOD CALLED', ['selectedCuti' => $this->selectedCuti?->id]);
        
        if (!$this->selectedCuti) {
            \Illuminate\Support\Facades\Log::error('❌ No selectedCuti');
            return;
        }

        try {
            \Illuminate\Support\Facades\Log::info('Processing approval for cuti ID: ' . $this->selectedCuti->id);
            ApprovalService::approveCuti($this->selectedCuti, auth()->user(), $this->approvalNotes);
            
            \Illuminate\Support\Facades\Log::info('✅ Approval succeeded');
            $this->dispatch('toast', type: 'success', message: 'Pengajuan cuti berhasil disetujui');
            $this->closeApprovalModal();
            $this->resetPage();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('❌ Approval error: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject cuti
     */
    public function reject()
    {
        \Illuminate\Support\Facades\Log::info('❌ REJECT METHOD CALLED', ['selectedCuti' => $this->selectedCuti?->id]);
        
        if (!$this->selectedCuti || !$this->approvalReason) {
            \Illuminate\Support\Facades\Log::error('❌ Missing selectedCuti or approvalReason');
            $this->dispatch('toast', type: 'error', message: 'Alasan penolakan diperlukan');
            return;
        }

        try {
            \Illuminate\Support\Facades\Log::info('Processing rejection for cuti ID: ' . $this->selectedCuti->id);
            ApprovalService::rejectCuti($this->selectedCuti, auth()->user(), $this->approvalReason);
            
            \Illuminate\Support\Facades\Log::info('✅ Rejection succeeded');
            $this->dispatch('toast', type: 'success', message: 'Pengajuan cuti berhasil ditolak');
            $this->closeApprovalModal();
            $this->resetPage();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('❌ Rejection error: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Clear filters
     */
    public function clearFilters()
    {
        $this->search = '';
        $this->filterLevel = '';
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.cuti.cuti-approval-dashboard', [
            'pendingApprovals' => $this->pagedApprovals,
            'totalPending' => $this->pendingApprovals->count(),
        ]);
    }
}
