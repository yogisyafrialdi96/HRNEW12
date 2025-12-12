<?php

namespace App\Livewire\Admin\Izin;

use App\Models\IzinCuti\IzinPengajuan;
use App\Models\IzinCuti\IzinAlasan;
use App\Models\Master\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class IzinPengajuanIndex extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';
    public ?int $filterStatus = null;
    public ?string $filterJenisIzin = null;
    public array $selectedIds = [];
    public bool $selectAll = false;
    public bool $showModal = false;
    public bool $isEdit = false;
    public ?IzinPengajuan $editingModel = null;
    public bool $confirmingDelete = false;
    public ?IzinPengajuan $modelToDelete = null;
    
    // Modal detail approval
    public bool $showDetailModal = false;
    public ?IzinPengajuan $detailModel = null;

    // Form fields
    public ?int $izin_alasan_id = null;
    public ?string $tanggal_mulai = null;
    public ?string $tanggal_selesai = null;
    public ?int $jumlah_jam = null;
    public ?string $alasan = null;
    public ?string $file_surat_dokter = null;
    public ?string $tanggal_surat_dokter = null;

    public function rules()
    {
        return [
            'izin_alasan_id' => 'required|exists:izin_alasan,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'jumlah_jam' => 'nullable|integer|min:1|max:8',
            'alasan' => 'required|string|max:500',
            'file_surat_dokter' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'tanggal_surat_dokter' => 'nullable|date',
        ];
    }

    #[\Livewire\Attributes\Computed]
    public function izinPengajuan()
    {
        return IzinPengajuan::with(['user', 'tahunAjaran', 'approval.atasanUser', 'approvalHistories.user'])
            ->where('created_by', Auth::id())
            ->when($this->search, fn($q) => $q->where('alasan', 'like', "%{$this->search}%"))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterJenisIzin, fn($q) => $q->where('jenis_izin', $this->filterJenisIzin))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);
    }

    #[\Livewire\Attributes\Computed]
    public function tahunAjaranList()
    {
        return TahunAjaran::where('is_active', true)->get();
    }

    #[\Livewire\Attributes\Computed]
    public function alasanList()
    {
        return IzinAlasan::active()->ordered()->get();
    }

    #[\Livewire\Attributes\Computed]
    public function selectedAlasan()
    {
        return $this->izin_alasan_id ? IzinAlasan::find($this->izin_alasan_id) : null;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterStatus = null;
        $this->filterJenisIzin = null;
        $this->resetPage();
    }

    public function calculateJumlahJam()
    {
        if ($this->jam_mulai && $this->jam_selesai) {
            try {
                $mulai = Carbon::createFromFormat('H:i', $this->jam_mulai);
                $selesai = Carbon::createFromFormat('H:i', $this->jam_selesai);
                
                if ($mulai > $selesai) {
                    $selesai->addDay();
                }
                
                $jumlah = $selesai->diffInHours($mulai);
                $this->jumlah_jam = $jumlah > 0 ? $jumlah : 1;
            } catch (\Exception $e) {
                // Silent fail - let validation handle invalid times
            }
        }
    }

    public function updated($name, $value)
    {
        // Auto-calculate when times change
        if (in_array($name, ['jam_mulai', 'jam_selesai'])) {
            $this->calculateJumlahJam();
        }
    }

    public function create()
    {
        $this->authorize('izin.create');
        
        $this->resetForm();
        $this->isEdit = false;
        $this->editingModel = null;
        $this->showModal = true;
    }

    public function edit(IzinPengajuan $model)
    {
        $this->authorize('izin.edit');
        
        $this->editingModel = $model;
        $this->isEdit = true;
        $this->izin_alasan_id = $model->izin_alasan_id;
        $this->tanggal_mulai = $model->tanggal_mulai->format('Y-m-d');
        $this->tanggal_selesai = $model->tanggal_selesai?->format('Y-m-d');
        $this->jumlah_jam = $model->jumlah_jam;
        $this->alasan = $model->alasan;
        $this->file_surat_dokter = $model->file_surat_dokter;
        $this->tanggal_surat_dokter = $model->tanggal_surat_dokter?->format('Y-m-d');
        $this->showModal = true;
    }

    public function save()
    {
        $this->authorize($this->isEdit ? 'izin.edit' : 'izin.create');

        try {
            $validated = $this->validate();
            unset($validated['file_surat_dokter']); // Remove file from validation rules to handle separately
            
            // Handle file upload separately
            if ($this->file_surat_dokter instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                $filePath = $this->file_surat_dokter->store('izin-dokter', 'public');
                $validated['file_surat_dokter'] = $filePath;
            }
            
            // Get current active tahun ajaran
            $tahunAjaran = TahunAjaran::where('is_active', true)->first();
            if (!$tahunAjaran) {
                $this->dispatch('toast', type: 'error', message: 'Tahun ajaran aktif tidak ditemukan');
                return;
            }

            $validated['created_by'] = Auth::id();
            $validated['tahun_ajaran_id'] = $tahunAjaran->id;
            $validated['status'] = 'draft';

            if ($this->isEdit && $this->editingModel) {
                // Update
                $this->editingModel->update($validated);
                $this->dispatch('toast', type: 'success', message: 'Pengajuan izin berhasil diperbarui');
            } else {
                // Create
                IzinPengajuan::create($validated);
                $this->dispatch('toast', type: 'success', message: 'Pengajuan izin berhasil dibuat');
            }

            $this->closeModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function submit(IzinPengajuan $model)
    {
        $this->authorize('izin.submit');

        try {
            if ($model->status !== 'draft') {
                $this->dispatch('toast', type: 'error', message: 'Hanya pengajuan draft yang bisa disubmit');
                return;
            }

            $model->update(['status' => 'pending']);
            $this->dispatch('toast', type: 'success', message: 'Pengajuan izin berhasil disubmit untuk approval');
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function cancel(IzinPengajuan $model)
    {
        $this->authorize('izin.cancel');

        try {
            if (!in_array($model->status, ['draft', 'pending'])) {
                $this->dispatch('toast', type: 'error', message: 'Tidak bisa membatalkan pengajuan yang sudah diapprove/ditolak');
                return;
            }

            $model->update(['status' => 'cancelled']);
            $this->dispatch('toast', type: 'success', message: 'Pengajuan izin berhasil dibatalkan');
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function confirmDelete(IzinPengajuan $model)
    {
        $this->modelToDelete = $model;
        $this->confirmingDelete = true;
    }

    public function showDetail(IzinPengajuan $model)
    {
        $this->detailModel = $model->load(['approval.atasanUser.user', 'approvalHistories.user']);
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->detailModel = null;
    }

    public function delete()
    {
        $this->authorize('izin.delete');

        try {
            if ($this->modelToDelete) {
                $this->modelToDelete->delete();
                $this->dispatch('toast', type: 'success', message: 'Pengajuan izin berhasil dihapus');
                $this->confirmingDelete = false;
                $this->modelToDelete = null;
            }
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->izin_alasan_id = null;
        $this->tanggal_mulai = null;
        $this->tanggal_selesai = null;
        $this->jumlah_jam = null;
        $this->alasan = null;
        $this->file_surat_dokter = null;
        $this->tanggal_surat_dokter = null;
    }

    public function render()
    {
        return view('livewire.admin.izin.izin-pengajuan-index');
    }
}
