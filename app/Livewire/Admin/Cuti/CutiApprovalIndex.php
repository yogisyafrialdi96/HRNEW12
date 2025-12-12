<?php

namespace App\Livewire\Admin\Cuti;

use App\Models\IzinCuti\CutiPengajuan;
use App\Models\IzinCuti\CutiApproval;
use App\Models\IzinCuti\CutiApprovalHistory;
use App\Models\Atasan\AtasanUser;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CutiApprovalIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterJenisCuti = '';
    public $filterStatus = '';
    public $showHistory = false; // Toggle untuk menampilkan history

    public $showModal = false;
    public $selectedApproval = null;
    public $approvalComment = '';
    public $approvalAction = '';

    #[\Livewire\Attributes\On('update:showHistory')]
    public function toggleHistory()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->authorize('cuti.approve');
        
        // Log user's atasan info
        $userId = auth()->id();
        $userAtasan = AtasanUser::where('user_id', $userId)
            ->where('is_active', true)
            ->get();
        
        Log::info('User Atasan Info', [
            'user_id' => $userId,
            'atasan_count' => $userAtasan->count(),
            'levels' => $userAtasan->pluck('level')->toArray()
        ]);
    }

    #[\Livewire\Attributes\Computed]
    public function pengajuans()
    {
        $userId = auth()->id();

        // Get current user's atasan_user info
        $currentUserAtasanLevels = AtasanUser::where('user_id', $userId)
            ->where('is_active', true)
            ->pluck('level')
            ->toArray();

        if ($this->showHistory) {
            // Show APPROVED/REJECTED history
            $query = CutiPengajuan::with([
                'user',
                'user.karyawan',
                'approval' => function ($q) use ($userId) {
                    $q->whereIn('status', ['approved', 'rejected'])
                      ->whereHas('atasanUser', function ($innerQ) use ($userId) {
                          $innerQ->where('user_id', $userId)->where('is_active', true);
                      })
                      ->with(['atasanUser', 'approvedBy']);
                },
                'approvalHistories' => function ($q) {
                    $q->orderBy('level');
                }
            ])
            ->whereHas('approval', function ($q) use ($userId) {
                $q->whereIn('status', ['approved', 'rejected'])
                  ->whereHas('atasanUser', function ($innerQ) use ($userId) {
                      $innerQ->where('user_id', $userId)->where('is_active', true);
                  });
            });
        } else {
            // Show PENDING (original logic)
            $query = CutiPengajuan::with([
                'user',
                'user.karyawan',
                'approval' => function ($q) use ($userId) {
                    $q->where('status', 'pending')
                      ->whereHas('atasanUser', function ($innerQ) use ($userId) {
                          $innerQ->where('user_id', $userId)->where('is_active', true);
                      })
                      ->with(['atasanUser', 'approvedBy']);
                },
                'approvalHistories' => function ($q) {
                    $q->orderBy('level');
                }
            ])
            ->whereHas('approval', function ($q) use ($userId) {
                $q->where('status', 'pending')
                  ->whereHas('atasanUser', function ($innerQ) use ($userId) {
                      $innerQ->where('user_id', $userId)->where('is_active', true);
                  });
            });
        }

        if ($this->search) {
            $query->whereHas('user.karyawan', function ($q) {
                $q->where('full_name', 'like', "%{$this->search}%")
                  ->orWhere('nip', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterJenisCuti) {
            $query->where('jenis_cuti', $this->filterJenisCuti);
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function openApprovalModal($pengajuanId)
    {
        try {
            $pengajuan = CutiPengajuan::with([
                'user.karyawan',
                'approval.atasanUser.user',
                'approvalHistories.approvedBy'
            ])->findOrFail($pengajuanId);
            
            // Check if current user has ANY pending approval for this pengajuan
            $userId = auth()->id();
            $userApprovals = $pengajuan->approval->filter(function ($approval) use ($userId) {
                return $approval->atasanUser->user_id === $userId 
                    && $approval->atasanUser->is_active;
            });

            if ($userApprovals->isEmpty()) {
                $this->dispatch('toast', message: 'Anda tidak memiliki wewenang untuk approval pengajuan ini.', type: 'warning');
                return;
            }

            $this->selectedApproval = $pengajuan;
            $this->approvalComment = '';
            $this->showModal = true;
        } catch (\Exception $e) {
            Log::error('Error opening approval modal: ' . $e->getMessage());
            $this->dispatch('toast', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
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
        Log::info('✓ APPROVE METHOD CALLED', ['pengajuan_id' => $pengajuanId]);
        try {
            $this->processApproval($pengajuanId, 'approved');
        } catch (\Exception $e) {
            Log::error('❌ ERROR in approve()', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function reject($pengajuanId)
    {
        Log::info('✗ REJECT METHOD CALLED', ['pengajuan_id' => $pengajuanId]);
        try {
            $this->processApproval($pengajuanId, 'rejected');
        } catch (\Exception $e) {
            Log::error('❌ ERROR in reject()', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    private function processApproval($pengajuanId, $action)
    {
        try {
            Log::warning('🟦 [STEP 1] Starting processApproval');
            Log::warning('   - Pengajuan ID: ' . $pengajuanId);
            Log::warning('   - Action: ' . $action);

            DB::beginTransaction();
            Log::warning('🟦 [STEP 2] Transaction started');

            $userId = Auth::id();
            
            // Fetch pengajuan dengan relasi approval
            $pengajuan = CutiPengajuan::with('approval.atasanUser')->findOrFail($pengajuanId);
            Log::warning('🟦 [STEP 3] Pengajuan loaded');
            Log::warning('   - Pengajuan Status: ' . $pengajuan->status);
            Log::warning('   - Total approvals: ' . $pengajuan->approval->count());

            // Cari approval user yang pending (bisa dari level berapa saja)
            $userPendingApprovals = $pengajuan->approval->filter(function ($approval) use ($userId) {
                $isMatch = $approval->atasanUser->user_id === $userId 
                    && $approval->atasanUser->is_active 
                    && $approval->status === 'pending';
                
                Log::warning('   - Checking Approval ID: ' . $approval->id . 
                             ' | AtasanUser: ' . $approval->atasanUser->user_id .
                             ' | Active: ' . ($approval->atasanUser->is_active ? 'YES' : 'NO') .
                             ' | Status: ' . $approval->status .
                             ' | Match: ' . ($isMatch ? 'YES' : 'NO'));
                
                return $isMatch;
            });

            Log::warning('🟦 [STEP 4] Filtered pending approvals');
            Log::warning('   - Found: ' . $userPendingApprovals->count());

            if ($userPendingApprovals->isEmpty()) {
                DB::rollBack();
                Log::error('❌ [STEP 5] No pending approval found');
                throw new \Exception('Anda tidak memiliki approval yang pending untuk pengajuan ini');
            }

            // Validate action
            if (!in_array($action, ['approved', 'rejected'])) {
                DB::rollBack();
                Log::error('❌ [STEP 5] Invalid action: ' . $action);
                throw new \Exception('Action harus approved atau rejected');
            }

            Log::warning('🟦 [STEP 5] Action validated: ' . $action);

            $message = '';
            $historyCount = 0;

            // Update semua pending approvals dari user ini
            foreach ($userPendingApprovals as $approval) {
                Log::warning('🟦 [STEP 6] Processing approval ID: ' . $approval->id);
                
                $updateData = [
                    'status' => $action,
                    'komentar' => $this->approvalComment ?: ($action === 'approved' ? 'Disetujui' : 'Ditolak'),
                    'approved_by' => $userId,
                    'approved_at' => now()
                ];
                
                Log::warning('   - Update data: ' . json_encode($updateData));
                
                $approval->update($updateData);
                
                Log::warning('   - Approval updated successfully');

                // Record history - gunakan field yang sesuai migration
                try {
                    $historyData = [
                        'cuti_pengajuan_id' => $pengajuanId,
                        'action' => $action === 'approved' ? 'approved' : 'rejected',
                        'user_id' => $userId,
                        'keterangan' => $this->approvalComment ?: ($action === 'approved' ? 'Disetujui' : 'Ditolak'),
                        // Field tambahan untuk compatibility
                        'level' => $approval->level,
                        'status' => $action,
                        'approved_by' => $userId,
                        'approval_comment' => $this->approvalComment ?: ($action === 'approved' ? 'Disetujui' : 'Ditolak'),
                    ];
                    
                    Log::warning('   - Creating history with data: ' . json_encode($historyData));
                    
                    $history = CutiApprovalHistory::create($historyData);
                    
                    Log::warning('   - History created with ID: ' . $history->id);
                    $historyCount++;
                    
                } catch (\Exception $historyEx) {
                    Log::error('❌ Error creating history: ' . $historyEx->getMessage());
                    Log::error('   - Trace: ' . $historyEx->getTraceAsString());
                    // Jangan throw, hanya log - lanjutkan proses
                }
            }

            Log::warning('🟦 [STEP 7] All approvals processed. History count: ' . $historyCount);

            // Refresh dan check status keseluruhan approval
            $pengajuan->refresh();
            $allApprovals = $pengajuan->approval;
            
            Log::warning('🟦 [STEP 8] Pengajuan refreshed');
            Log::warning('   - Total approvals: ' . $allApprovals->count());
            Log::warning('   - Statuses: ' . json_encode($allApprovals->pluck('status')->toArray()));

            // Tentukan status pengajuan berdasarkan semua approval
            $rejectedCount = $allApprovals->where('status', 'rejected')->count();
            $pendingCount = $allApprovals->where('status', 'pending')->count();
            $approvedCount = $allApprovals->where('status', 'approved')->count();

            Log::warning('🟦 [STEP 9] Status summary:');
            Log::warning('   - Approved: ' . $approvedCount);
            Log::warning('   - Rejected: ' . $rejectedCount);
            Log::warning('   - Pending: ' . $pendingCount);

            if ($rejectedCount > 0) {
                // Jika ada yang ditolak, pengajuan rejected
                $pengajuan->update(['status' => 'rejected']);
                $message = 'Pengajuan cuti ditolak';
                Log::warning('   - Setting pengajuan status to: REJECTED');
            } elseif ($pendingCount === 0) {
                // Semua approval selesai dan tidak ada yang rejected = approved
                $pengajuan->update(['status' => 'approved']);
                $message = 'Pengajuan cuti disetujui';
                Log::warning('   - Setting pengajuan status to: APPROVED');
            } else {
                // Masih ada pending approval
                $message = 'Approval Anda disimpan. Menunggu approval dari level lain';
                Log::warning('   - Pengajuan status remains: PENDING (waiting for other approvals)');
            }

            DB::commit();
            Log::warning('🟦 [STEP 10] Transaction committed successfully');

            $this->closeModal();
            Log::warning('🟦 [STEP 11] Modal closed');
            
            Log::warning('✅ SUCCESS: ' . $message);
            $this->dispatch('toast', message: $message, type: 'success');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ ==========================================');
            Log::error('❌ ERROR in processApproval');
            Log::error('❌ Message: ' . $e->getMessage());
            Log::error('❌ Code: ' . $e->getCode());
            Log::error('❌ File: ' . $e->getFile());
            Log::error('❌ Line: ' . $e->getLine());
            Log::error('❌ Trace: ' . $e->getTraceAsString());
            Log::error('❌ ==========================================');
            
            $this->dispatch('toast', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.admin.cuti.cuti-approval-index', [
            'pengajuans' => $this->pengajuans
        ]);
    }
}
