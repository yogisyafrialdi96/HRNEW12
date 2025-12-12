<?php

namespace App\Livewire\Cuti;

use App\Models\IzinCuti\CutiPengajuan;
use App\Models\IzinCuti\CutiSaldo;
use App\Models\IzinCuti\CutiSetup;
use App\Models\Master\TahunAjaran;
use App\Models\User;
use App\Services\CutiCalculationService;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CutiPengajuanIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';
    public ?string $filterStatus = '';
    public ?string $filterJenisCuti = '';
    public array $selectedIds = [];
    public bool $selectAll = false;
    public bool $showModal = false;
    public bool $isEdit = false;
    public ?CutiPengajuan $editingModel = null;
    public bool $confirmingDelete = false;
    public ?CutiPengajuan $modelToDelete = null;
    
    // Modal detail approval
    public bool $showDetailModal = false;
    public ?CutiPengajuan $detailModel = null;

    // Form fields
    public ?string $jenis_cuti = null;
    public ?string $tanggal_mulai = null;
    public ?string $tanggal_selesai = null;
    public ?int $jumlah_hari = null;
    public ?string $alasan = null;
    public ?string $contact_address = null;
    public ?string $phone = null;
    public ?string $tanggal_estimasi_lahir = null;
    public ?string $tanggal_surat_dokter = null;
    public ?string $nama_dokter = null;

    // Cuti info
    public ?int $cuti_sisa = null;
    public ?int $cuti_maksimal = null;
    public ?int $cuti_terpakai = null;
    public ?int $h_min_cuti = null;
    public ?string $tanggal_mulai_allowed = null;
    public ?int $cuti_sisa_estimasi = null;
    public ?int $cuti_terpakai_estimasi = null;
    
    // Service
    private ?CutiCalculationService $cutiService = null;
    
    /**
     * Get or create CutiCalculationService instance
     */
    private function getCutiService(): CutiCalculationService
    {
        return $this->cutiService ??= new CutiCalculationService();
    }

    public function mount()
    {
        $this->cutiService = new CutiCalculationService();
    }

    public function rules()
    {
        return [
            'jenis_cuti' => 'required|in:tahunan,melahirkan',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jumlah_hari' => 'required|integer|min:1|max:60',
            'alasan' => 'nullable|string|max:500',
            'contact_address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'tanggal_estimasi_lahir' => 'nullable|date|required_if:jenis_cuti,melahirkan',
            'tanggal_surat_dokter' => 'nullable|date',
            'nama_dokter' => 'nullable|string|max:100',
        ];
    }

    /**
     * Validate that the requested dates don't overlap with existing approved/pending cuti requests
     */
    private function validateDatesNotAlreadyUsed($validated)
    {
        $tanggal_mulai = \Carbon\Carbon::parse($validated['tanggal_mulai']);
        $tanggal_selesai = \Carbon\Carbon::parse($validated['tanggal_selesai']);
        
        // Query untuk check apakah ada pengajuan yang overlap dengan tanggal ini
        $query = CutiPengajuan::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($q) use ($tanggal_mulai, $tanggal_selesai) {
                $q->whereBetween('tanggal_mulai', [$tanggal_mulai, $tanggal_selesai])
                    ->orWhereBetween('tanggal_selesai', [$tanggal_mulai, $tanggal_selesai])
                    ->orWhere(function ($subQuery) use ($tanggal_mulai, $tanggal_selesai) {
                        $subQuery->where('tanggal_mulai', '<=', $tanggal_mulai)
                            ->where('tanggal_selesai', '>=', $tanggal_selesai);
                    });
            });

        // Jika edit, exclude pengajuan yang sedang di-edit
        if ($this->isEdit && $this->editingModel) {
            $query = $query->where('id', '!=', $this->editingModel->id);
        }

        if ($query->exists()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'tanggal_mulai' => 'Tanggal pengajuan sudah digunakan dalam pengajuan lain yang masih pending/approved',
            ]);
        }
    }

    #[\Livewire\Attributes\Computed]
    public function cutiPengajuan()
    {
        // STAFF VIEW: Only show own cuti requests
        return CutiPengajuan::with([
            'user', 
            'tahunAjaran',
            'approval' => function ($q) {
                $q->with(['approvedBy', 'atasanUser.user']);
            }
        ])
            ->where('user_id', auth()->id())  // ✅ HANYA data milik user login
            ->when($this->search, fn($q) => $q->where('alasan', 'like', "%{$this->search}%"))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterJenisCuti, fn($q) => $q->where('jenis_cuti', $this->filterJenisCuti))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);
    }

    /**
     * Get list of reserved dates (dates already used in approved/pending requests)
     */
    #[\Livewire\Attributes\Computed]
    public function reservedDates()
    {
        $dates = [];
        $pengajuans = CutiPengajuan::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'approved'])
            ->get();
        
        foreach ($pengajuans as $pengajuan) {
            $current = \Carbon\Carbon::parse($pengajuan->tanggal_mulai);
            $selesai = \Carbon\Carbon::parse($pengajuan->tanggal_selesai);
            
            while ($current <= $selesai) {
                $dates[] = $current->format('Y-m-d');
                $current->addDay();
            }
        }
        
        return array_unique($dates);
    }

    #[\Livewire\Attributes\Computed]
    public function tahunAjaranList()
    {
        return TahunAjaran::where('is_active', true)->get();
    }

    public function loadCutiInfo()
    {
        try {
            if (empty($this->jenis_cuti)) {
                $this->jenis_cuti = 'tahunan';
            }
            
            $tahunAjaran = TahunAjaran::where('is_active', true)->first();
            if (!$tahunAjaran) {
                $this->cuti_sisa = 12;
                $this->cuti_maksimal = 12;
                $this->cuti_terpakai = 0;
                $this->tanggal_mulai_allowed = Carbon::now()->format('Y-m-d');
                return;
            }

            $cutiSaldo = CutiSaldo::where('user_id', auth()->id())
                ->where('tahun_ajaran_id', $tahunAjaran->id)
                ->first();
            
            if (!$cutiSaldo) {
                $cutiSaldo = CutiSaldo::create([
                    'user_id' => auth()->id(),
                    'tahun_ajaran_id' => $tahunAjaran->id,
                    'cuti_tahunan_awal' => 12,
                    'cuti_tahunan_terpakai' => 0,
                    'cuti_tahunan_sisa' => 12,
                    'cuti_melahirkan_awal' => 0,
                    'cuti_melahirkan_terpakai' => 0,
                    'cuti_melahirkan_sisa' => 0,
                ]);
            }

            $cutiSetup = CutiSetup::first();
            
            if ($this->jenis_cuti === 'tahunan') {
                $this->cuti_sisa = $cutiSaldo->cuti_tahunan_sisa ?? 0;
                $this->cuti_maksimal = $cutiSaldo->cuti_tahunan_awal ?? 12;
                $this->cuti_terpakai = $cutiSaldo->cuti_tahunan_terpakai ?? 0;
                $this->h_min_cuti = $cutiSetup?->h_min_cuti_tahunan ?? 0;
            } else {
                $this->cuti_sisa = $cutiSaldo->cuti_melahirkan_sisa ?? 0;
                $this->cuti_maksimal = $cutiSetup?->hari_cuti_melahirkan ?? 90;
                $this->cuti_terpakai = $cutiSaldo->cuti_melahirkan_terpakai ?? 0;
                $this->h_min_cuti = $cutiSetup?->h_min_cuti_melahirkan ?? 0;
            }

            try {
                if ($this->h_min_cuti && $this->h_min_cuti > 0) {
                    $user = auth()->user();
                    $unitId = null;
                    
                    if ($user && $user->karyawan) {
                        try {
                            $jabatanAktif = $user->karyawan->jabatanAktif();
                            if ($jabatanAktif) {
                                $unitId = $jabatanAktif->unit_id;
                            }
                        } catch (\Exception $jabatanError) {
                            Log::debug('Could not get jabatan aktif: ' . $jabatanError->getMessage());
                        }
                    }
                    
                    $minDate = $this->getCutiService()->calculateMinimumStartDate(
                        $this->h_min_cuti,
                        unitId: $unitId
                    );
                    
                    $this->tanggal_mulai_allowed = $minDate->format('Y-m-d');
                } else {
                    $this->tanggal_mulai_allowed = Carbon::now()->format('Y-m-d');
                }
            } catch (\Exception $dateCalcError) {
                Log::debug('Error calculating min start date: ' . $dateCalcError->getMessage());
                $this->tanggal_mulai_allowed = Carbon::now()->format('Y-m-d');
            }
        } catch (\Exception $e) {
            Log::error('Error loading cuti info: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'jenis_cuti' => $this->jenis_cuti,
                'exception' => get_class($e),
            ]);
            
            if ($this->cuti_sisa === null) {
                $this->cuti_sisa = 12;
                $this->cuti_maksimal = 12;
                $this->cuti_terpakai = 0;
            }
            $this->tanggal_mulai_allowed = Carbon::now()->format('Y-m-d');
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->filterJenisCuti = '';
        $this->resetPage();
    }

    public function calculateJumlahHari()
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) {
            $this->jumlah_hari = null;
            return;
        }

        try {
            $user = auth()->user();
            $unitId = null;
            $provinsiId = null;
            
            if ($user && $user->karyawan) {
                $jabatanAktif = $user->karyawan->jabatanAktif();
                if ($jabatanAktif) {
                    $unitId = $jabatanAktif->unit_id;
                }
            }
            
            $this->jumlah_hari = $this->getCutiService()->calculateWorkingDays(
                $this->tanggal_mulai,
                $this->tanggal_selesai,
                unitId: $unitId,
                provinsiId: $provinsiId
            );
            
            if ($this->cuti_sisa !== null && $this->jumlah_hari) {
                $this->cuti_sisa_estimasi = max(0, $this->cuti_sisa - $this->jumlah_hari);
                $this->cuti_terpakai_estimasi = $this->cuti_terpakai + $this->jumlah_hari;
            }
        } catch (\Exception $e) {
            Log::error('Error calculating jumlah hari: ' . $e->getMessage());
            
            try {
                $this->jumlah_hari = $this->getCutiService()->calculateWorkingDays(
                    $this->tanggal_mulai,
                    $this->tanggal_selesai,
                    unitId: null,
                    provinsiId: null
                );
                
                if ($this->cuti_sisa !== null && $this->jumlah_hari) {
                    $this->cuti_sisa_estimasi = max(0, $this->cuti_sisa - $this->jumlah_hari);
                    $this->cuti_terpakai_estimasi = $this->cuti_terpakai + $this->jumlah_hari;
                }
            } catch (\Exception $e2) {
                try {
                    $mulai = \Carbon\Carbon::parse($this->tanggal_mulai);
                    $selesai = \Carbon\Carbon::parse($this->tanggal_selesai);
                    $this->jumlah_hari = max(1, $mulai->diffInDays($selesai) + 1);
                    
                    if ($this->cuti_sisa !== null && $this->jumlah_hari) {
                        $this->cuti_sisa_estimasi = max(0, $this->cuti_sisa - $this->jumlah_hari);
                        $this->cuti_terpakai_estimasi = $this->cuti_terpakai + $this->jumlah_hari;
                    }
                } catch (\Exception $e3) {
                    $this->jumlah_hari = null;
                }
            }
        }
    }

    public function updated($name, $value)
    {
        if ($name === 'jenis_cuti') {
            $this->loadCutiInfo();
        }

        if (in_array($name, ['tanggal_mulai', 'tanggal_selesai'])) {
            $this->calculateJumlahHari();
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->editingModel = null;
        $this->jenis_cuti = 'tahunan';
        $this->loadCutiInfo();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $model = CutiPengajuan::findOrFail($id);
        
        // Authorization: hanya bisa edit milik sendiri dan status draft
        if ($model->user_id !== auth()->id()) {
            $this->dispatch('toast', type: 'error', message: 'Anda tidak memiliki izin untuk mengedit pengajuan ini');
            return;
        }
        
        if ($model->status !== 'draft') {
            $this->dispatch('toast', type: 'error', message: 'Hanya pengajuan draft yang bisa diedit');
            return;
        }
        
        $this->editingModel = $model;
        $this->isEdit = true;
        $this->jenis_cuti = $model->jenis_cuti;
        $this->tanggal_mulai = $model->tanggal_mulai->format('Y-m-d');
        $this->tanggal_selesai = $model->tanggal_selesai->format('Y-m-d');
        $this->jumlah_hari = $model->jumlah_hari;
        $this->alasan = $model->alasan;
        $this->contact_address = $model->contact_address;
        $this->phone = $model->phone;
        $this->tanggal_estimasi_lahir = $model->tanggal_estimasi_lahir?->format('Y-m-d');
        $this->tanggal_surat_dokter = $model->tanggal_surat_dokter?->format('Y-m-d');
        $this->nama_dokter = $model->nama_dokter;
        $this->loadCutiInfo();
        $this->showModal = true;
    }

    public function save()
    {
        try {
            $validated = $this->validate();
            $this->validateDatesNotAlreadyUsed($validated);
            
            \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
                $tahunAjaran = TahunAjaran::where('is_active', true)->first();
                if (!$tahunAjaran) {
                    throw new \Exception('Tahun ajaran aktif tidak ditemukan');
                }

                $cutiSaldo = CutiSaldo::firstOrCreate(
                    ['user_id' => auth()->id(), 'tahun_ajaran_id' => $tahunAjaran->id],
                    [
                        'cuti_tahunan_awal' => 12,
                        'cuti_tahunan_sisa' => 12,
                        'cuti_melahirkan_awal' => 0,
                        'cuti_melahirkan_sisa' => 0,
                    ]
                );

                $validated['created_by'] = auth()->id();
                $validated['user_id'] = auth()->id();
                $validated['cuti_saldo_id'] = $cutiSaldo->id;
                $validated['tahun_ajaran_id'] = $tahunAjaran->id;

                if ($this->isEdit && $this->editingModel) {
                    $this->editingModel->update($validated);
                    $this->dispatch('toast', type: 'success', message: 'Pengajuan cuti berhasil diperbarui');
                    $this->closeModal();
                } else {
                    $validated['status'] = 'pending';

                    $cutiPengajuan = new CutiPengajuan($validated);
                    $validation = \App\Services\ApprovalService::validateCutiBalance($cutiPengajuan);
                    if (!$validation['valid']) {
                        throw new \Exception($validation['message']);
                    }

                    $userId = auth()->id();
                    $atasan = \App\Models\Atasan\AtasanUser::where('user_id', $userId)
                        ->where('is_active', true)
                        ->orderBy('level')
                        ->get();

                    if ($atasan->count() === 0) {
                        throw new \Exception('Anda belum memiliki atasan yang terdaftar. Hubungi admin untuk mendaftarkan atasan Anda.');
                    }

                    $pengajuan = CutiPengajuan::create($validated);

                    foreach ($atasan as $atasanRecord) {
                        \App\Models\IzinCuti\CutiApproval::create([
                            'cuti_pengajuan_id' => $pengajuan->id,
                            'atasan_user_id' => $atasanRecord->id,
                            'level' => $atasanRecord->level,
                            'status' => 'pending',
                            'urutan_approval' => $atasanRecord->level,
                        ]);
                    }

                    $this->dispatch('toast', type: 'success', message: 'Pengajuan cuti berhasil dibuat dan disubmit untuk approval');
                    $this->closeModal();
                }
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Error: ' . $e->getMessage());
            Log::error('Error saving cuti: ' . $e->getMessage(), ['user_id' => auth()->id()]);
        }
    }

    public function cancel($id)
    {
        $model = CutiPengajuan::findOrFail($id);
        
        // Authorization: hanya bisa cancel milik sendiri
        if ($model->user_id !== auth()->id()) {
            $this->dispatch('toast', type: 'error', message: 'Anda tidak memiliki izin untuk membatalkan pengajuan ini');
            return;
        }

        try {
            if (!in_array($model->status, ['draft', 'pending'])) {
                $this->dispatch('toast', type: 'error', message: 'Tidak bisa membatalkan pengajuan yang sudah diapprove/ditolak');
                return;
            }

            $model->update(['status' => 'cancelled']);
            $this->dispatch('toast', type: 'success', message: 'Pengajuan cuti berhasil dibatalkan');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $model = CutiPengajuan::findOrFail($id);
        
        // Authorization: hanya bisa delete milik sendiri dan status draft
        if ($model->user_id !== auth()->id()) {
            $this->dispatch('toast', type: 'error', message: 'Anda tidak memiliki izin untuk menghapus pengajuan ini');
            return;
        }
        
        if ($model->status !== 'draft') {
            $this->dispatch('toast', type: 'error', message: 'Hanya pengajuan draft yang bisa dihapus');
            return;
        }
        
        $this->modelToDelete = $model;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        try {
            if ($this->modelToDelete) {
                $this->modelToDelete->delete();
                $this->dispatch('toast', type: 'success', message: 'Pengajuan cuti berhasil dihapus');
                $this->confirmingDelete = false;
                $this->modelToDelete = null;
                $this->resetPage();
            }
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showDetailModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->jenis_cuti = null;
        $this->tanggal_mulai = null;
        $this->tanggal_selesai = null;
        $this->jumlah_hari = null;
        $this->alasan = null;
        $this->contact_address = null;
        $this->phone = null;
        $this->tanggal_estimasi_lahir = null;
        $this->tanggal_surat_dokter = null;
        $this->nama_dokter = null;
        $this->cuti_sisa = null;
        $this->cuti_maksimal = null;
        $this->cuti_terpakai = null;
        $this->h_min_cuti = null;
        $this->tanggal_mulai_allowed = null;
        $this->cuti_sisa_estimasi = null;
        $this->cuti_terpakai_estimasi = null;
    }

    public function showDetail($id)
    {
        \Log::info('showDetail called', ['id' => $id, 'user_id' => auth()->id()]);
        
        try {
            $model = CutiPengajuan::with(['approval.approvedBy', 'approvalHistories.user', 'user'])->findOrFail($id);
            
            // Authorization: hanya bisa lihat detail milik sendiri
            if ($model->user_id !== auth()->id()) {
                $this->dispatch('toast', type: 'error', message: 'Anda tidak memiliki izin untuk melihat detail pengajuan ini');
                return;
            }
            
            $this->detailModel = $model;
            $this->showDetailModal = true;
            
            \Log::info('Detail modal opened successfully', [
                'cuti_id' => $this->detailModel->id,
                'nomor_cuti' => $this->detailModel->nomor_cuti,
                'showDetailModal' => $this->showDetailModal,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in showDetail', ['id' => $id, 'error' => $e->getMessage()]);
            $this->dispatch('toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function closeDetailModal()
    {
        \Log::info('closeDetailModal called');
        $this->showDetailModal = false;
        $this->detailModel = null;
    }

    public function render()
    {
        $reservedDatesArray = $this->reservedDates;
        
        return view('livewire.cuti.cuti-pengajuan-index', [
            'reservedDatesArray' => $reservedDatesArray,
            'showDetailModal' => $this->showDetailModal,
            'detailModel' => $this->detailModel,
        ]);
    }
}
