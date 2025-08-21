<?php

namespace App\Livewire\Admin\Master\Mapel;

use App\Models\Master\Mapel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Str;

class Index extends Component
{
    use WithPagination;

    public $mapelId = '';
    public $nama_mapel = '';
    public $kode_mapel = '';
    public $requirements = '';
    public $tugas_pokok = '';
    public $is_active = true;

    // Properties for search and filter
    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $showModalDetail = false;
    public $selectedMapel;



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
            'nama_mapel' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('master_mapel', 'nama_mapel')
                    ->ignore($this->mapelId),
            ],
            'kode_mapel' => 'required|string|size:3|unique:master_mapel,kode_mapel,' . $this->mapelId,
            'requirements' => 'nullable|string',
            'tugas_pokok' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    protected $validationAttributes = [
        'nama_mapel' => 'Mapel Name',
        'kode_mapel' => 'Kode Mapel',
        'requirements' => 'Requirements',
        'tugas_pokok' => 'Tugas Pokok',
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
        $mapel = Mapel::findOrFail($id);

        $this->mapelId = $mapel->id;
        $this->nama_mapel = $mapel->nama_mapel;
        $this->kode_mapel = $mapel->kode_mapel;
        $this->requirements = $mapel->requirements;
        $this->tugas_pokok = $mapel->tugas_pokok;
        $this->is_active = $mapel->is_active;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {

        try {
            $this->validate();

            if ($this->isEdit) {
                Mapel::findOrFail($this->mapelId)->update([
                    'nama_mapel' => ucwords($this->nama_mapel),
                    'kode_mapel' => strtoupper($this->kode_mapel),
                    'requirements' => $this->requirements ?: null,
                    'tugas_pokok' => $this->tugas_pokok ?: null,
                    'is_active' => $this->is_active,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
                $this->dispatch('toast', [
                    'message' => "Data berhasi diedit",
                    'type' => 'success',
                ]);
            } else {
                Mapel::create([
                    'nama_mapel' => ucwords($this->nama_mapel),
                    'kode_mapel' => strtoupper($this->kode_mapel),
                    'requirements' => $this->requirements ?: null,
                    'tugas_pokok' => $this->tugas_pokok ?: null,
                    'is_active' => $this->is_active,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
                $this->dispatch('toast', [
                    'message' => "Data berhasi disimpan",
                    'type' => 'success',
                ]);
            }

            $this->closeModal();
        } catch (ValidationException $e) {

            $errors = $e->validator->errors()->all();
            $count = count($errors);

            // Kirim semua pesan error ke Alpine (misal ditampilkan satu-satu atau semua sekaligus)
            $this->dispatch('toast', [
                'message' => "Terdapat $count kesalahan:\n- " . implode("\n- ", $errors),
                'type' => 'error',
            ]);
            throw $e; // Masih melempar error agar tetap bisa ditampilkan di bawah input

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
        Mapel::find($this->deleteId)?->delete();

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
        Mapel::withTrashed()->find($this->restoreId)?->restore();

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
        
        $unit = Mapel::withTrashed()->find($this->forceDeleteId);

        // Cek apakah unit ditemukan
        if (!$unit) {
            $this->dispatch('toast', [
                'message' => 'Data tidak ditemukan.',
                'type' => 'error',
            ]);
            return;
        }

        // Jika tidak ada masalah, baru lakukan force delete
        $unit->forceDelete();
        
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

    public function showDetail($id)
    {
        $this->selectedMapel = Mapel::with(['creator', 'updater'])
            ->find($id);
        $this->showModalDetail = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showModalDetail = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->mapelId = null;
        $this->nama_mapel = '';
        $this->kode_mapel = '';
        $this->requirements = '';
        $this->tugas_pokok = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function toggleStatus($id)
    {
        $unit = Mapel::findOrFail($id);
        $unit->update(['is_active' => !$unit->is_active]);
        
        $this->dispatch('toast', [
                    'message' => "Status berhasi diedit",
                    'type' => 'success',
                ]);
    }

    public function render()
    {
        $query = Mapel::with([
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
                $q->where('nama_mapel', 'like', $search)
                    ->orWhere('kode_mapel', 'like', $search)
                    ->orWhere('status', 'like', $search);
            });
        });

        // urutkan & paginasi
        $mapels = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.master.mapel.index', compact('mapels'));
    }
}
