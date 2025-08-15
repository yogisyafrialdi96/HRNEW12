<?php

namespace App\Livewire\Admin\Master\Department;

use App\Models\Master\Companies;
use App\Models\Master\Departments;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;

    public $departmentId;
    public $company_id;
    public $department = '';
    public $kode_department = '';
    public $deskripsi = '';
    public $kepala_department = '';
    public $status = 'aktif';

    // Properties for search and filter
    public $search = '';
    public $statusFilter = '';
    public $companyFilter = '';
    public $perPage = 10;

    public $showModal = false;
    public $showDeleteModal = false;
    public $isEdit = false;

    // search kepaladepartment
    // public string $searchUser = '';
    // public $filteredUsers = [];

    // public function updatedSearchUser()
    // {
    //     $this->filteredUsers = User::query()
    //         ->where('name', 'like', '%' . $this->searchUser . '%')
    //         ->limit(10)
    //         ->get();
    // }


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

    protected $rules = [
        'company_id' => 'required|exists:master_companies,id',
        'department' => 'required|string|max:255',
        'kode_department' => 'nullable|string|max:10',
        'deskripsi' => 'nullable|string',
        'kepala_department' => 'nullable|exists:users,id',
        'status' => 'required|in:aktif,nonaktif',
    ];

    protected $validationAttributes = [
        'company_id' => 'Company',
        'department' => 'Department Name',
        'kode_department' => 'Department Code',
        'deskripsi' => 'Description',
        'kepala_department' => 'Department Head',
        'status' => 'Status',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingCompanyFilter()
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
        $department = Departments::findOrFail($id);

        $this->departmentId = $department->id;
        $this->company_id = $department->company_id;
        $this->department = $department->department;
        $this->kode_department = $department->kode_department;
        $this->deskripsi = $department->deskripsi;
        $this->kepala_department = $department->kepala_department;
        $this->status = $department->status;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        
        try {
            $this->validate();

            if ($this->isEdit) {
                Departments::findOrFail($this->departmentId)->update([
                    'company_id' => $this->company_id,
                    'department' => $this->department,
                    'kode_department' => $this->kode_department,
                    'deskripsi' => $this->deskripsi,
                    'kepala_department' => $this->kepala_department ?: null,
                    'status' => $this->status,
                ]);
                session()->flash('success', 'Department updated successfully!');
            } else {
                Departments::create([
                    'company_id' => $this->company_id,
                    'department' => $this->department,
                    'kode_department' => $this->kode_department,
                    'deskripsi' => $this->deskripsi,
                    'kepala_department' => $this->kepala_department ?: null,
                    'status' => $this->status,
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
        
        }catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->departmentId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            Departments::findOrFail($this->departmentId)->delete();
            session()->flash('success', 'Department deleted successfully!');
            $this->showDeleteModal = false;
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->departmentId = null;
    }

    private function resetForm()
    {
        $this->departmentId = null;
        $this->company_id = '';
        $this->department = '';
        $this->kode_department = '';
        $this->deskripsi = '';
        $this->kepala_department = '';
        $this->status = 'aktif';
        $this->resetValidation();
    }

    public function render()
    {
        $query = Departments::with([
            'company:id,nama_companies',
            'kepalaDepartment:id,name',
            'creator:id,name',
            'updater:id,name'
        ]);

        // tampilkan data terhapus jika perlu
        $query->when($this->showDeleted, function ($q) {
            $q->onlyTrashed(); // hanya data yang sudah dihapus
        });

        // filter by status
        $query->when($this->statusFilter, function ($q) {
            $q->where('status', $this->statusFilter);
        });

        // filter by company
        $query->when($this->companyFilter, function ($q) {
            $q->where('company_id', $this->companyFilter);
        });

        // pencarian
        $query->when($this->search, function ($q) {
            $search = '%' . $this->search . '%';
            $q->where(function ($q) use ($search) {
                $q->where('department', 'like', $search)
                    ->orWhere('kode_department', 'like', $search)
                    ->orWhere('deskripsi', 'like', $search)
                    ->orWhereHas('company', function ($company) use ($search) {
                        $company->where('nama_companies', 'like', $search);
                    })
                    ->orWhereHas('kepalaDepartment', function ($kepala) use ($search) {
                        $kepala->where('name', 'like', $search);
                    });
            });
        });

        // urutkan & paginasi
        $departments = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $companies = Companies::orderBy('nama_companies')->get();
        $users = User::orderBy('name')->get();
        return view('livewire.admin.master.department.index', compact('departments','companies','users'));
    }
}
