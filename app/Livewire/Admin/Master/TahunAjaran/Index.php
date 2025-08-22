<?php

namespace App\Livewire\Admin\Master\TahunAjaran;

use App\Models\Master\TahunAjaran;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;

    public $tahunajaranId = '';
    public $periode = '';
    public $awal_periode = '';
    public $akhir_periode = '';
    public $keterangan = '';
    public $is_active = true;

    // Properties for search and filter
    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;



    #[Url]
    public string $query = '';

    // Set default URL param supaya reset saat refresh
    #[Url(except: 'id')]
    public string $sortField = 'id';

    #[Url(except: 'desc')]
    public string $sortDirection = 'desc';

    public bool $showDeleted = false;

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function rules()
    {
        return [
            'periode' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('master_tahunajaran', 'periode')
                    ->ignore($this->tahunajaranId),
            ],
            'awal_periode'  => 'required|date',
            'akhir_periode' => 'required|date|after_or_equal:awal_periode',
            'keterangan'    => 'nullable|string|max:255',
            'is_active'     => 'boolean',
        ];
    }

    protected $validationAttributes = [
        'periode' => 'Periode',
        'awal_periode' => 'Awal Periode',
        'akhir_periode' => 'Akhir Periode',
        'keterangan' => 'Keterangan',
        'is_active' => 'Status',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $ta = TahunAjaran::findOrFail($id);

        $this->tahunajaranId = $ta->id;
        $this->periode = $ta->periode;
        $this->awal_periode = $ta->awal_periode;
        $this->akhir_periode = $ta->akhir_periode;
        $this->keterangan = $ta->keterangan;
        $this->is_active = $ta->is_active;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        try {
            $this->validate();

            $data = [
                'periode' => strtoupper($this->periode),
                'awal_periode' => $this->awal_periode,
                'akhir_periode' => $this->akhir_periode,
                'keterangan' => $this->keterangan ?: null,
                'is_active' => $this->is_active,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];

            if ($this->isEdit) {
                TahunAjaran::findOrFail($this->tahunajaranId)->update($data);
                $this->dispatch('toast', [
                    'message' => "Data berhasil diedit",
                    'type' => 'success',
                ]);
            } else {
                TahunAjaran::create($data);
                $this->dispatch('toast', [
                    'message' => "Data berhasil disimpan",
                    'type' => 'success',
                ]);
            }

            $this->closeModal();
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $count = count($errors);

            $this->dispatch('toast', [
                'message' => "Terdapat $count kesalahan:\n- " . implode("\n- ", $errors),
                'type' => 'error',
            ]);
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Terjadi kesalahan server.',
                'type' => 'error',
            ]);
            throw $e;
        }
    }

    // SoftDelete
    public bool $confirmingDelete = false;
    public bool $deleteSuccess = false;
    public ?int $deleteId = null;

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
        $this->deleteSuccess = false;
    }

    public function delete()
    {
        TahunAjaran::find($this->deleteId)?->delete();

        $this->deleteSuccess = true;

        // Trigger Alpine to auto-close modal
        $this->dispatch('modal:success');
    }

    public function resetDeleteModal()
    {
        $this->confirmingDelete = false;
        $this->deleteSuccess = false;
        $this->deleteId = null;
    }
    // End SoftDelete

    // Restore Data
    public bool $confirmingRestore = false;
    public bool $restoreSuccess = false;
    public ?int $restoreId = null;

    public function confirmRestore($id)
    {
        $this->restoreId = $id;
        $this->confirmingRestore = true;
        $this->restoreSuccess = false;
    }

    public function restore()
    {
        TahunAjaran::withTrashed()->find($this->restoreId)?->restore();

        $this->restoreSuccess = true;

        // Trigger Alpine to auto-close modal
        $this->dispatch('modal:success-restore');
    }

    public function resetRestoreModal()
    {
        $this->confirmingRestore = false;
        $this->restoreSuccess = false;
        $this->restoreId = null;
    }
    // End Restore Data

    // ForceDelete
    public bool $confirmingForceDelete = false;
    public bool $forceDeleteSuccess = false;
    public ?int $forceDeleteId = null;

    public function confirmForceDelete($id)
    {
        $this->forceDeleteId = $id;
        $this->confirmingForceDelete = true;
        $this->forceDeleteSuccess = false;
    }

    public function forceDelete()
    {
        // Reset success state setiap kali method dipanggil
        $this->forceDeleteSuccess = false;
        
        $ta = TahunAjaran::withTrashed()->find($this->forceDeleteId);

        // Cek apakah ta ditemukan
        if (!$ta) {
            $this->dispatch('toast', [
                'message' => 'Data tidak ditemukan.',
                'type' => 'error',
            ]);
            return;
        }

        // Jika tidak ada masalah, baru lakukan force delete
        $ta->forceDelete();
        
        // Set success state dan dispatch modal success
        $this->forceDeleteSuccess = true;
        
        $this->dispatch('toast', [
            'message' => 'Data berhasil dihapus permanen.',
            'type' => 'success',
        ]);
    }

    public function resetForceDeleteModal()
    {
        $this->confirmingForceDelete = false;
        $this->forceDeleteSuccess = false;
        $this->forceDeleteId = null;
    }
    // End Force Delete

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->tahunajaranId = null;
        $this->periode = '';
        $this->awal_periode = '';
        $this->akhir_periode = '';
        $this->keterangan = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function toggleStatus($id)
    {
        $ta = TahunAjaran::findOrFail($id);
        $ta->update(['is_active' => !$ta->is_active]);
        
        $this->dispatch('toast', [
                    'message' => "Status berhasi diedit",
                    'type' => 'success',
                ]);
    }

    public function render()
    {
        $query = TahunAjaran::with([
            'creator:id,name',
            'updater:id,name'
        ]);

        // tampilkan data terhapus jika perlu
        $query->when($this->showDeleted, function ($q) {
            $q->onlyTrashed(); // hanya data yang sudah dihapus
        });

        // filter by status
        $query->when($this->statusFilter !== '', function ($q) {
            $q->where('is_active', (bool) $this->statusFilter);
        });

        // pencarian
        $query->when($this->search, function ($q) {
            $search = '%' . $this->search . '%';
            $q->where(function ($q) use ($search) {
                $q->where('periode', 'like', $search)
                    ->orWhere('awal_periode', 'like', $search)
                    ->orWhere('akhir_periode', 'like', $search)
                    ->orWhere('keterangan', 'like', $search);
            });
        });

        // urutkan & paginasi
        $tahunAjarans = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.master.tahun-ajaran.index', compact('tahunAjarans'));
    }
}

