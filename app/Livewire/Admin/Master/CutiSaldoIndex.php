<?php

namespace App\Livewire\Admin\Master;

use App\Models\IzinCuti\CutiSaldo;
use App\Models\Master\TahunAjaran;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class CutiSaldoIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';
    public ?int $filterTahunAjaran = null;
    public bool $showModal = false;
    public bool $isEdit = false;
    public ?CutiSaldo $editingModel = null;
    public bool $confirmingDelete = false;
    public ?CutiSaldo $modelToDelete = null;

    // Form fields
    public ?int $user_id = null;
    public ?int $tahun_ajaran_id = null;
    public ?int $cuti_tahunan_awal = null;
    public ?int $cuti_tahunan_terpakai = 0;
    public ?int $cuti_tahunan_sisa = null;
    public ?int $cuti_melahirkan_awal = 0;
    public ?int $cuti_melahirkan_terpakai = 0;
    public ?int $cuti_melahirkan_sisa = 0;
    public ?int $carry_over_tahunan = 0;
    public ?int $carry_over_digunakan = 0;
    public ?string $catatan = null;

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'tahun_ajaran_id' => 'required|exists:master_tahunajaran,id',
        'cuti_tahunan_awal' => 'required|integer|min:0|max:100',
        'cuti_tahunan_terpakai' => 'nullable|integer|min:0',
        'cuti_tahunan_sisa' => 'nullable|integer|min:0',
        'cuti_melahirkan_awal' => 'nullable|integer|min:0',
        'cuti_melahirkan_terpakai' => 'nullable|integer|min:0',
        'cuti_melahirkan_sisa' => 'nullable|integer|min:0',
        'carry_over_tahunan' => 'nullable|integer|min:0',
        'carry_over_digunakan' => 'nullable|integer|min:0',
        'catatan' => 'nullable|string|max:500',
    ];

    public function rules()
    {
        return $this->rules;
    }

    #[\Livewire\Attributes\Computed]
    public function cutiSaldo()
    {
        return CutiSaldo::with(['user', 'tahunAjaran', 'updatedBy'])
            ->when($this->search, fn($q) => 
                $q->whereHas('user', fn($query) => 
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                )
            )
            ->when($this->filterTahunAjaran, fn($q) => 
                $q->where('tahun_ajaran_id', $this->filterTahunAjaran)
            )
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);
    }

    #[\Livewire\Attributes\Computed]
    public function tahunAjaranList()
    {
        return TahunAjaran::orderBy('periode', 'desc')->get();
    }

    #[\Livewire\Attributes\Computed]
    public function userList()
    {
        return User::with('karyawan')
            ->orderBy('name')
            ->get();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterTahunAjaran = null;
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit(CutiSaldo $model)
    {
        $this->editingModel = $model;
        $this->isEdit = true;
        $this->user_id = $model->user_id;
        $this->tahun_ajaran_id = $model->tahun_ajaran_id;
        $this->cuti_tahunan_awal = $model->cuti_tahunan_awal;
        $this->cuti_tahunan_terpakai = $model->cuti_tahunan_terpakai;
        $this->cuti_tahunan_sisa = $model->cuti_tahunan_sisa;
        $this->cuti_melahirkan_awal = $model->cuti_melahirkan_awal;
        $this->cuti_melahirkan_terpakai = $model->cuti_melahirkan_terpakai;
        $this->cuti_melahirkan_sisa = $model->cuti_melahirkan_sisa;
        $this->carry_over_tahunan = $model->carry_over_tahunan;
        $this->carry_over_digunakan = $model->carry_over_digunakan;
        $this->catatan = $model->catatan;
        $this->showModal = true;
    }

    public function save()
    {
        try {
            $validated = $this->validate();

            if ($this->isEdit && $this->editingModel) {
                $this->editingModel->update($validated);
                $this->dispatch('notify', type: 'success', message: 'Cuti saldo berhasil diperbarui');
            } else {
                CutiSaldo::create($validated);
                $this->dispatch('notify', type: 'success', message: 'Cuti saldo berhasil dibuat');
            }

            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function confirmDelete(CutiSaldo $model)
    {
        $this->modelToDelete = $model;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        try {
            if ($this->modelToDelete) {
                $this->modelToDelete->delete();
                $this->dispatch('notify', type: 'success', message: 'Cuti saldo berhasil dihapus');
            }

            $this->confirmingDelete = false;
            $this->modelToDelete = null;
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->user_id = null;
        $this->tahun_ajaran_id = null;
        $this->cuti_tahunan_awal = null;
        $this->cuti_tahunan_terpakai = 0;
        $this->cuti_tahunan_sisa = null;
        $this->cuti_melahirkan_awal = 0;
        $this->cuti_melahirkan_terpakai = 0;
        $this->cuti_melahirkan_sisa = 0;
        $this->carry_over_tahunan = 0;
        $this->carry_over_digunakan = 0;
        $this->catatan = null;
        $this->editingModel = null;
        $this->isEdit = false;
    }

    public function render()
    {
        return view('livewire.admin.master.cuti-saldo-index', [
            'saldoList' => $this->cutiSaldo,
            'tahunAjaranList' => $this->tahunAjaranList,
            'userList' => $this->userList,
        ]);
    }
}
