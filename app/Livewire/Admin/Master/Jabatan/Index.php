<?php

namespace App\Livewire\Admin\Master\Jabatan;

use App\Models\Master\Departments;
use App\Models\Master\Jabatans;
use App\Models\Employee\Karyawan;
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

    public $jabatanId = '';
    public $department_id = '';
    public $nama_jabatan = '';
    public $kode_jabatan = '';
    public $jenis_jabatan = '';
    public $level_jabatan = '';
    public $tugas_pokok = '';
    public $requirements = '';
    public $min_salary = '';
    public $max_salary = '';
    public $is_active = true;

    // Properties for search and filter
    public $search = '';
    public $statusFilter = '';
    public $jenisFilter = '';
    public $levelFilter = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $showModalDetail = false;
    public $selectedJabatan;



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
            'department_id' => [
                'required',
                'integer',
                'exists:master_department,id'
            ],
            'nama_jabatan' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('master_jabatan', 'nama_jabatan')->ignore($this->jabatanId)
            ],
            'kode_jabatan' => 'nullable|string|max:10',
            'jenis_jabatan' => 'required|in:struktural,fungsional,pelaksana',
            'level_jabatan' => 'required|in:top_managerial,middle_manager,supervisor,staff,staff_operasional,operator,phl,jabatan_khusus',
            'tugas_pokok' => 'nullable|string',
            'requirements' => 'nullable|string',
            'min_salary' => 'required|numeric|min:0|max:999999999999.99',
            'max_salary' => 'required|numeric|gte:min_salary|max:999999999999.99', // max ≥ min
            'is_active' => 'boolean',
        ];
    }

    protected $validationAttributes = [
        'department_id' => 'Department',
        'nama_jabatan' => 'Nama Jabatan',
        'kode_jabatan' => 'Jabatan Code',
        'jenis_jabatan' => 'Jenis Jabatan',
        'level_jabatan' => 'Level Jabatan',
        'tugas_pokok' => 'Tugas Pokok',
        'requirements' => 'Requirements',
        'min_salary' => 'Min Salary',
        'max_salary' => 'Max Salary',
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

    public function updatingJenisFilter()
    {
        $this->resetPage();
    }

    public function updatingLevelFilter()
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
        $jabatan = Jabatans::findOrFail($id);

        $this->jabatanId = $jabatan->id;
        $this->department_id = $jabatan->department_id;
        $this->nama_jabatan = $jabatan->nama_jabatan;
        $this->kode_jabatan = $jabatan->kode_jabatan;
        $this->jenis_jabatan = $jabatan->jenis_jabatan;
        $this->level_jabatan = $jabatan->level_jabatan;
        $this->tugas_pokok = $jabatan->tugas_pokok;
        $this->requirements = $jabatan->requirements;
        $this->min_salary = $jabatan->min_salary ? number_format($jabatan->min_salary, 0, '', '') : null;
        $this->max_salary = $jabatan->max_salary ? number_format($jabatan->max_salary, 0, '', '') : null;
        $this->is_active = $jabatan->is_active;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        try {
            $this->validate();

            // Fungsi helper untuk mengkonversi format rupiah ke decimal (untuk database decimal)
            $convertRupiahToDecimal = function ($value) {
                if (empty($value)) return null;
                // Hapus semua karakter kecuali digit
                $cleanValue = preg_replace('/[^0-9]/', '', $value);
                return $cleanValue ? $cleanValue : null;
            };

            $data = [
                'department_id' => $this->department_id,
                'nama_jabatan' => $this->nama_jabatan,
                'kode_jabatan' => $this->kode_jabatan,
                'jenis_jabatan' => $this->jenis_jabatan,
                'level_jabatan' => $this->level_jabatan,
                'tugas_pokok' => $this->tugas_pokok,
                'requirements' => $this->requirements,
                'min_salary' => $convertRupiahToDecimal($this->min_salary),
                'max_salary' => $convertRupiahToDecimal($this->max_salary),
                'is_active' => $this->is_active,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];

            if ($this->isEdit) {
                Jabatans::findOrFail($this->jabatanId)->update($data);
                $this->dispatch('toast', [
                    'message' => "Data berhasil diedit",
                    'type' => 'success',
                ]);
            } else {
                Jabatans::create($data);
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
        Jabatans::find($this->deleteId)?->delete();

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
        Jabatans::withTrashed()->find($this->restoreId)?->restore();

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

        $unit = Jabatans::withTrashed()->find($this->forceDeleteId);

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
        $this->selectedJabatan = Jabatans::with(['department', 'creator', 'updater'])
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
        $this->jabatanId = null;
        $this->department_id = '';
        $this->nama_jabatan = '';
        $this->kode_jabatan = '';
        $this->jenis_jabatan = '';
        $this->level_jabatan = '';
        $this->tugas_pokok = '';
        $this->requirements = '';
        $this->min_salary = '';
        $this->max_salary = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function toggleStatus($id)
    {
        $unit = Jabatans::findOrFail($id);
        $unit->update(['is_active' => !$unit->is_active]);

        $this->dispatch('toast', [
            'message' => "Status berhasil diedit",
            'type' => 'success',
        ]);
    }

    public function getNextCode()
    {
        return Jabatans::generateCode();
    }

    public function getEmployeeCount($jabatanId)
    {
        return Karyawan::whereHas('activeJabatan', function ($query) use ($jabatanId) {
            $query->where('jabatan_id', $jabatanId);
        })
            ->whereNull('deleted_at')
            ->count();
    }

    public function render()
    {
        $query = Jabatans::with([
            'department:id,department',
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

        // filter by jenis
        $query->when($this->jenisFilter !== '', function ($q) {
            $q->where('jenis_jabatan', $this->jenisFilter);
        });

        // filter by level
        $query->when($this->levelFilter !== '', function ($q) {
            $q->where('level_jabatan', $this->levelFilter);
        });

        // pencarian
        $query->when($this->search, function ($q) {
            $search = '%' . $this->search . '%';
            $q->where(function ($q) use ($search) {
                $q->where('nama_jabatan', 'like', $search)
                    ->orWhere('kode_jabatan', 'like', $search)
                    ->orWhereHas('department', function ($department) use ($search) {
                        $department->where('department', 'like', $search);
                    });
            });
        });

        // urutkan & paginasi
        $jabatans = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $departments = Departments::query()->where('is_active', 1)->orderBy('department')->get();
        $users = User::orderBy('name')->get();
        return view('livewire.admin.master.jabatan.index', compact('jabatans', 'departments', 'users'));
    }
}
