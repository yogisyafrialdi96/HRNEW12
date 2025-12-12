<?php

namespace App\Livewire\Izin;

use App\Models\IzinCuti\IzinPengajuan;
use App\Models\IzinCuti\IzinAlasan;
use App\Models\Master\TahunAjaran;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IzinPengajuanIndex extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';
    public ?string $filterStatus = '';
    public ?string $filterJenisIzin = '';
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
            ->where('user_id', Auth::id())
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
        $this->filterStatus = '';
        $this->filterJenisIzin = '';
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEdit = false;
    }

    public function edit(IzinPengajuan $model)
    {
        $this->resetForm();
        $this->editingModel = $model;
        $this->izin_alasan_id = $model->izin_alasan_id;
        $this->tanggal_mulai = $model->tanggal_mulai?->format('Y-m-d');
        $this->tanggal_selesai = $model->tanggal_selesai?->format('Y-m-d');
        $this->jumlah_jam = $model->jumlah_jam;
        $this->alasan = $model->alasan;
        $this->tanggal_surat_dokter = $model->tanggal_surat_dokter?->format('Y-m-d');
        $this->showModal = true;
        $this->isEdit = true;
    }

    public function save()
    {
        $validated = $this->validate();
        unset($validated['file_surat_dokter']); // Remove file from validation rules to handle separately

        try {
            // Handle file upload separately
            $filePath = null;
            if ($this->file_surat_dokter instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                $filePath = $this->file_surat_dokter->store('izin-dokter', 'public');
                $validated['file_surat_dokter'] = $filePath;
            }

            if ($this->isEdit) {
                $this->editingModel->update($validated);
            } else {
                $validated['user_id'] = Auth::id();
                $validated['tahun_ajaran_id'] = TahunAjaran::where('is_active', true)->first()?->id;
                $validated['status'] = 'draft';
                $validated['created_by'] = Auth::id();
                IzinPengajuan::create($validated);
            }

            $this->closeModal();
            session()->flash('success', 'Pengajuan izin berhasil disimpan');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function submit(IzinPengajuan $model)
    {
        if ($model->status !== 'draft') {
            return;
        }

        $model->update(['status' => 'pending']);
        $this->dispatch('notify', message: 'Pengajuan izin berhasil disubmit');
    }

    public function cancel(IzinPengajuan $model)
    {
        if (!in_array($model->status, ['draft', 'pending'])) {
            return;
        }

        $model->update(['status' => 'cancelled']);
        $this->dispatch('notify', message: 'Pengajuan izin berhasil dibatalkan');
    }

    public function confirmDelete(IzinPengajuan $model)
    {
        $this->modelToDelete = $model;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        if ($this->modelToDelete?->status !== 'draft') {
            return;
        }

        $this->modelToDelete->delete();
        $this->confirmingDelete = false;
        $this->dispatch('notify', message: 'Pengajuan izin berhasil dihapus');
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

    public function closeModal()
    {
        $this->resetForm();
        $this->showModal = false;
        $this->isEdit = false;
        $this->editingModel = null;
    }

    private function resetForm()
    {
        $this->izin_alasan_id = null;
        $this->tanggal_mulai = null;
        $this->tanggal_selesai = null;
        $this->jumlah_jam = null;
        $this->alasan = null;
        $this->file_surat_dokter = null;
        $this->tanggal_surat_dokter = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.izin.izin-pengajuan-index');
    }
}
