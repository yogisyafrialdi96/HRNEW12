<?php

namespace App\Livewire\Permissions;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Database\Eloquent\Model;

class PermissionIndex extends Component
{
    use WithPagination;

    // Permission properties
    public $permissionId;
    public $name = '';
    public $description = '';
    public $selectedModule = '';
    public $modules = [];

    // Search and filter properties
    public $search = '';
    public $filterModule = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $showModalDetail = false;
    public $showModalAssignRoles = false;
    public $selectedPermission;
    public $selectedRoles = [];
    public $availableRoles = [];

    // Module CRUD properties
    public $showModalModule = false;
    public $isEditModule = false;
    public $moduleKey = '';
    public $moduleLabel = '';
    public $moduleIdToEdit = '';

    #[Url]
    public string $query = '';

    #[Url(except: 'id')]
    public string $sortField = 'id';

    #[Url(except: 'desc')]
    public string $sortDirection = 'desc';

    public function mount()
    {
        $this->modules = [
            'users' => 'User Management',
            'roles' => 'Role Management',
            'permissions' => 'Permission Management',
            'dashboard' => 'Dashboard',
            'employees' => 'Employee Management',
            'contracts' => 'Contract Management',
            'attendance' => 'Attendance Management',
            'payroll' => 'Payroll Management',
            'reports' => 'Reports',
        ];
    }

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field
            ? ($this->sortDirection === 'asc' ? 'desc' : 'asc')
            : 'asc';

        $this->sortField = $field;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterModule()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        // Check permission untuk create permission
        if (!Auth::user()->can('permissions.create')) {
            $this->dispatch('error', 'Anda tidak memiliki izin untuk membuat permission');
            return;
        }

        $this->resetForm();
        $this->showModal = true;
        $this->isEdit = false;
    }

    public function edit($id)
    {
        try {
            // Check permission untuk edit permission
            if (!Auth::user()->can('permissions.edit')) {
                $this->dispatch('error', 'Anda tidak memiliki izin untuk mengedit permission');
                return;
            }

            $permission = Permission::findOrFail($id);
            $this->permissionId = $permission->id;
            $this->name = $permission->name;
            $this->description = $permission->description ?? '';
            $this->selectedModule = $this->extractModule($permission->name);
            $this->isEdit = true;
            $this->showModal = true;
        } catch (\Exception $e) {
            $this->dispatch('error', 'Permission tidak ditemukan');
        }
    }

    public function save()
    {
        // Check permission untuk create atau edit permission
        $permission = $this->isEdit ? 'permissions.edit' : 'permissions.create';
        if (!Auth::user()->can($permission)) {
            $this->dispatch('error', 'Anda tidak memiliki izin untuk ' . ($this->isEdit ? 'mengedit' : 'membuat') . ' permission');
            return;
        }

        $rules = [
            'name' => 'required|string|min:3|max:255|' . ($this->isEdit 
                ? 'unique:permissions,name,' . $this->permissionId 
                : 'unique:permissions,name'),
            'description' => 'nullable|string|max:1000',
            'selectedModule' => 'required|string',
        ];

        $validated = $this->validate($rules);

        try {
            DB::beginTransaction();

            if ($this->isEdit) {
                $permission = Permission::findOrFail($this->permissionId);
                $permission->update([
                    'name' => $this->name,
                    'description' => $this->description,
                ]);
                $message = 'Permission berhasil diperbarui!';
            } else {
                Permission::create([
                    'name' => $this->name,
                    'description' => $this->description,
                    'guard_name' => 'web',
                ]);
                $message = 'Permission berhasil dibuat!';
            }

            DB::commit();
            $this->dispatch('success', $message);
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            // Check permission untuk delete permission
            if (!Auth::user()->can('permissions.delete')) {
                $this->dispatch('error', 'Anda tidak memiliki izin untuk menghapus permission');
                return;
            }

            $permission = Permission::findOrFail($id);
            
            // Cegah penghapusan permission yang sudah di-assign ke role
            $roleCount = $permission->roles()->count();
            if ($roleCount > 0) {
                $this->dispatch('error', 'Permission ini sudah di-assign ke role. Hapus dari role terlebih dahulu!');
                return;
            }

            $permission->delete();
            $this->dispatch('success', 'Permission berhasil dihapus!');
        } catch (\Exception $e) {
            $this->dispatch('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showDetail($id)
    {
        try {
            // Check permission untuk view detail permission
            if (!Auth::user()->can('permissions.view')) {
                $this->dispatch('error', 'Anda tidak memiliki izin untuk melihat detail permission');
                return;
            }

            $this->selectedPermission = Permission::with('roles')->findOrFail($id);
            $this->showModalDetail = true;
        } catch (\Exception $e) {
            $this->dispatch('error', 'Permission tidak ditemukan');
        }
    }

    public function openAssignRoles($id)
    {
        try {
            // Check permission untuk assign roles ke permission
            if (!Auth::user()->can('permissions.assign_roles')) {
                $this->dispatch('error', 'Anda tidak memiliki izin untuk assign roles');
                return;
            }

            $permission = Permission::with('roles')->findOrFail($id);
            $this->selectedPermission = $permission;
            $this->selectedRoles = $permission->roles()->pluck('id')->toArray();
            $this->availableRoles = Role::all();
            $this->showModalAssignRoles = true;
        } catch (\Exception $e) {
            $this->dispatch('error', 'Permission tidak ditemukan');
        }
    }

    public function assignRoles()
    {
        try {
            DB::beginTransaction();

            $permission = Permission::findOrFail($this->selectedPermission->id);
            $permission->syncRoles($this->selectedRoles);

            DB::commit();
            $this->dispatch('success', 'Permission berhasil di-assign ke role!');
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showModalDetail = false;
        $this->showModalAssignRoles = false;
        $this->showModalModule = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->permissionId = null;
        $this->name = '';
        $this->description = '';
        $this->selectedModule = '';
        $this->selectedRoles = [];
        $this->availableRoles = [];
        $this->moduleKey = '';
        $this->moduleLabel = '';
        $this->moduleIdToEdit = '';
        $this->resetErrorBag();
    }

    // Module CRUD Methods
    public function openModalModule()
    {
        $this->moduleKey = '';
        $this->moduleLabel = '';
        $this->moduleIdToEdit = '';
        $this->isEditModule = false;
        $this->showModalModule = true;
    }

    public function editModule($key)
    {
        try {
            if (!isset($this->modules[$key])) {
                $this->dispatch('error', 'Module tidak ditemukan');
                return;
            }

            $this->moduleIdToEdit = $key;
            $this->moduleKey = $key;
            $this->moduleLabel = $this->modules[$key];
            $this->isEditModule = true;
            $this->showModalModule = true;
        } catch (\Exception $e) {
            $this->dispatch('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function saveModule()
    {
        $rules = [
            'moduleKey' => 'required|string|min:3|max:50|regex:/^[a-z_]+$/',
            'moduleLabel' => 'required|string|min:3|max:255',
        ];

        $validated = $this->validate($rules);

        try {
            if ($this->isEditModule) {
                // Cegah update jika ada permission menggunakan module ini dan key berubah
                if ($this->moduleIdToEdit !== $this->moduleKey) {
                    $permissionCount = Permission::where('name', 'like', $this->moduleIdToEdit . '.%')->count();
                    if ($permissionCount > 0) {
                        $this->dispatch('error', 'Tidak bisa mengubah key module jika ada permission menggunakannya. Hapus permission terlebih dahulu atau gunakan key yang sama.');
                        return;
                    }
                }

                // Update module
                $oldKey = $this->moduleIdToEdit;
                unset($this->modules[$oldKey]);
                $this->modules[$this->moduleKey] = $this->moduleLabel;

                $message = 'Module berhasil diperbarui!';
            } else {
                // Check duplicate
                if (isset($this->modules[$this->moduleKey])) {
                    $this->addError('moduleKey', 'Module key sudah ada!');
                    return;
                }

                // Add new module
                $this->modules[$this->moduleKey] = $this->moduleLabel;
                $message = 'Module berhasil ditambahkan!';
            }

            // Sort modules
            asort($this->modules);

            $this->dispatch('success', $message);
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteModule($key)
    {
        try {
            // Cegah penghapusan jika ada permission menggunakan module ini
            $permissionCount = Permission::where('name', 'like', $key . '.%')->count();
            if ($permissionCount > 0) {
                $this->dispatch('error', 'Tidak bisa menghapus module ini karena masih ada ' . $permissionCount . ' permission menggunakannya. Hapus permission terlebih dahulu!');
                return;
            }

            unset($this->modules[$key]);
            $this->dispatch('success', 'Module berhasil dihapus!');
        } catch (\Exception $e) {
            $this->dispatch('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function extractModule($permissionName)
    {
        // Extract module from permission name (e.g., "users.view" -> "users")
        $parts = explode('.', $permissionName);
        return $parts[0] ?? '';
    }

    public function render()
    {
        $query = Permission::query();

        // Search filter - search in both name and description
        if (!empty($this->search)) {
            $searchTerm = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }

        // Module filter - filter by permission prefix
        if (!empty($this->filterModule)) {
            $query->where('name', 'like', $this->filterModule . '.%');
        }

        // Sort and paginate
        $permissions = $query->orderBy($this->sortField, $this->sortDirection)
                            ->paginate($this->perPage);

        return view('livewire.permissions.permission-index', [
            'permissions' => $permissions,
            'modules' => $this->modules,
        ]);
    }
}
