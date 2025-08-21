<?php

namespace App\Livewire\Admin\Master\Unit;

use App\Models\Master\Departments;
use App\Models\Master\Units;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;

    public $unitId = '';
    public $department_id = '';
    public $unit = '';
    public $kode_unit = '';
    public $deskripsi = '';
    public $kepala_unit = '';
    public $is_active = true;

    // Properties for search and filter
    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;

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
            'department_id' => 'required|exists:master_department,id',
            'unit' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('master_unit', 'unit')
                    ->ignore($this->unitId) // saat edit, kalau create bisa null
                    ->where(fn($query) => $query->where('department_id', $this->department_id)),
            ],
            'kode_unit' => 'nullable|string|max:10',
            'deskripsi' => 'nullable|string',
            'kepala_unit' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ];
    }

    protected $validationAttributes = [
        'department_id' => 'Department',
        'unit' => 'Unit Name',
        'kode_unit' => 'Unit Code',
        'deskripsi' => 'Description',
        'kepala_unit' => 'Unit Head',
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
        $unit = Units::findOrFail($id);

        $this->unitId = $unit->id;
        $this->department_id = $unit->department_id;
        $this->unit = $unit->unit;
        $this->kode_unit = $unit->kode_unit;
        $this->deskripsi = $unit->deskripsi;
        $this->kepala_unit = $unit->kepala_unit;
        $this->is_active = $unit->is_active;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {

        try {
            $this->validate();

            if ($this->isEdit) {
                Units::findOrFail($this->unitId)->update([
                    'department_id' => $this->department_id,
                    'unit' => strtoupper($this->unit),
                    'kode_unit' => $this->kode_unit,
                    'deskripsi' => $this->deskripsi,
                    'kepala_unit' => $this->kepala_unit ?: null,
                    'is_active' => $this->is_active,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
                $this->dispatch('toast', [
                    'message' => "Data berhasi diedit",
                    'type' => 'success',
                ]);
            } else {
                Units::create([
                    'department_id' => $this->department_id,
                    'unit' => strtoupper($this->unit),
                    'kode_unit' => $this->kode_unit,
                    'deskripsi' => $this->deskripsi,
                    'kepala_unit' => $this->kepala_unit ?: null,
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
        Units::find($this->deleteId)?->delete();

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
        Units::withTrashed()->find($this->restoreId)?->restore();

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
        
        $unit = Units::withTrashed()->find($this->forceDeleteId);

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

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->unitId = null;
        $this->department_id = '';
        $this->unit = '';
        $this->kode_unit = '';
        $this->deskripsi = '';
        $this->kepala_unit = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function toggleStatus($id)
    {
        $unit = Units::findOrFail($id);
        $unit->update(['is_active' => !$unit->is_active]);
        
        $this->dispatch('toast', [
                    'message' => "Status berhasi diedit",
                    'type' => 'success',
                ]);
    }

    public function getNextCode()
    {
        return Units::generateCode();
    }

    public function render()
    {
        $query = Units::with([
            'department:id,department',
            'kepalaUnit:id,name',
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
                $q->where('unit', 'like', $search)
                    ->orWhere('kode_unit', 'like', $search)
                    ->orWhere('deskripsi', 'like', $search)
                    ->orWhereHas('department', function ($department) use ($search) {
                        $department->where('department', 'like', $search);
                    })
                    ->orWhereHas('kepalaUnit', function ($kepala) use ($search) {
                        $kepala->where('name', 'like', $search);
                    });
            });
        });

        // urutkan & paginasi
        $units = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $departments = Departments::query()->where('is_active', 1)->orderBy('department')->get();
        $users = User::orderBy('name')->get();
        return view('livewire.admin.master.unit.index', compact('units', 'departments', 'users'));
    }
}
