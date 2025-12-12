<?php

namespace App\Livewire\Admin\Master;

use App\Models\IzinCuti\IzinAlasan;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class IzinAlasanIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'urutan';
    public string $sortDirection = 'asc';
    public bool $showModal = false;
    public bool $isEdit = false;
    public ?IzinAlasan $editingModel = null;
    public bool $confirmingDelete = false;
    public ?IzinAlasan $modelToDelete = null;

    // Form fields
    public ?string $nama_alasan = null;
    public ?string $jenis_izin = null;
    public ?int $max_hari_setahun = null;
    public bool $is_bayar_penuh = false;
    public bool $perlu_surat_dokter = false;
    public ?string $keterangan = null;
    public bool $is_active = true;
    public ?int $urutan = 0;

    public function rules()
    {
        return [
            'nama_alasan' => 'required|string|max:100|unique:izin_alasan,nama_alasan' . ($this->isEdit && $this->editingModel ? ",{$this->editingModel->id}" : ''),
            'jenis_izin' => 'required|in:jam,hari',
            'max_hari_setahun' => 'nullable|integer|min:1',
            'is_bayar_penuh' => 'boolean',
            'perlu_surat_dokter' => 'boolean',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
            'urutan' => 'nullable|integer|min:0',
        ];
    }

    #[\Livewire\Attributes\Computed]
    public function alasanList()
    {
        return IzinAlasan::query()
            ->when($this->search, fn($q) => $q->where('nama_alasan', 'like', "%{$this->search}%"))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEdit = false;
    }

    public function edit(IzinAlasan $model)
    {
        $this->resetForm();
        $this->editingModel = $model;
        $this->nama_alasan = $model->nama_alasan;
        $this->jenis_izin = $model->jenis_izin;
        $this->max_hari_setahun = $model->max_hari_setahun;
        $this->is_bayar_penuh = $model->is_bayar_penuh;
        $this->perlu_surat_dokter = $model->perlu_surat_dokter;
        $this->keterangan = $model->keterangan;
        $this->is_active = $model->is_active;
        $this->urutan = $model->urutan;
        $this->showModal = true;
        $this->isEdit = true;
    }

    public function save()
    {
        $this->authorize('master.edit');

        try {
            $validated = $this->validate();

            if ($this->isEdit && $this->editingModel) {
                $this->editingModel->update($validated);
                session()->flash('success', 'Alasan izin berhasil diperbarui');
            } else {
                $validated['created_by'] = Auth::id();
                IzinAlasan::create($validated);
                session()->flash('success', 'Alasan izin berhasil dibuat');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function delete(IzinAlasan $model)
    {
        $this->authorize('master.delete');
        
        if ($model->izinPengajuan()->exists()) {
            session()->flash('error', 'Tidak dapat menghapus alasan izin yang sudah digunakan');
            return;
        }

        $model->delete();
        session()->flash('success', 'Alasan izin berhasil dihapus');
        $this->confirmingDelete = false;
    }

    public function confirmDelete(IzinAlasan $model)
    {
        $this->modelToDelete = $model;
        $this->confirmingDelete = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->nama_alasan = null;
        $this->jenis_izin = null;
        $this->max_hari_setahun = null;
        $this->is_bayar_penuh = false;
        $this->perlu_surat_dokter = false;
        $this->keterangan = null;
        $this->is_active = true;
        $this->urutan = 0;
        $this->editingModel = null;
        $this->isEdit = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.master.izin-alasan-index', [
            'alasanList' => $this->alasanList,
        ]);
    }
}
