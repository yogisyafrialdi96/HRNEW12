<?php

namespace App\Livewire\Roles;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class RoleIndex extends Component
{
    use WithPagination;

    // Role properties
    public $roleId;
    public $name = '';
    public $description = '';
    public $selectedPermissions = [];

    // Search and filter properties
    public $search = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $showModalDetail = false;
    public $selectedRole;

    #[Url]
    public string $query = '';

    #[Url(except: 'id')]
    public string $sortField = 'id';

    #[Url(except: 'desc')]
    public string $sortDirection = 'desc';

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field
            ? ($this->sortDirection === 'asc' ? 'desc' : 'asc')
            : 'asc';

        $this->sortField = $field;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        // Check permission untuk create role
        if (!Auth::user()->can('roles.create')) {
            $this->dispatch('error', 'Anda tidak memiliki izin untuk membuat role');
            return;
        }

        $this->resetForm();
        $this->showModal = true;
        $this->isEdit = false;
    }

    public function edit($id)
    {
        try {
            // Check permission untuk edit role
            if (!Auth::user()->can('roles.edit')) {
                $this->dispatch('error', 'Anda tidak memiliki izin untuk mengedit role');
                return;
            }

            $role = Role::findOrFail($id);
            $this->roleId = $role->id;
            $this->name = $role->name;
            $this->description = $role->description ?? '';
            $this->selectedPermissions = $role->permissions()->pluck('id')->toArray();
            $this->isEdit = true;
            $this->showModal = true;
        } catch (\Exception $e) {
            $this->dispatch('error', 'Role tidak ditemukan');
        }
    }

    public function save()
    {
        // Check permission untuk create atau edit role
        $permission = $this->isEdit ? 'roles.edit' : 'roles.create';
        if (!Auth::user()->can($permission)) {
            $this->dispatch('error', 'Anda tidak memiliki izin untuk ' . ($this->isEdit ? 'mengedit' : 'membuat') . ' role');
            return;
        }

        $rules = [
            'name' => 'required|string|min:2|max:255|' . ($this->isEdit 
                ? 'unique:roles,name,' . $this->roleId 
                : 'unique:roles,name'),
            'description' => 'nullable|string|max:1000',
            'selectedPermissions' => 'array',
        ];

        $this->validate($rules);

        try {
            DB::beginTransaction();

            if ($this->isEdit) {
                $role = Role::findOrFail($this->roleId);
                $role->update([
                    'name' => trim($this->name),
                    'description' => trim($this->description),
                ]);
                
                // Pastikan guard_name tidak berubah
                if ($role->guard_name !== 'web') {
                    $role->update(['guard_name' => 'web']);
                }
            } else {
                $role = Role::create([
                    'name' => trim($this->name),
                    'description' => trim($this->description),
                    'guard_name' => 'web',
                ]);
            }

            // Sinkronisasi permissions - convert ID ke permission names
            if (!empty($this->selectedPermissions)) {
                $permissionNames = Permission::whereIn('id', $this->selectedPermissions)
                    ->pluck('name')
                    ->toArray();
                $role->syncPermissions($permissionNames);
            } else {
                $role->permissions()->detach();
            }

            DB::commit();

            $this->dispatch('success', $this->isEdit 
                ? 'Role berhasil diperbarui!' 
                : 'Role berhasil dibuat!');
            
            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Role save error: ' . $e->getMessage());
            $this->dispatch('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            // Check permission untuk delete role
            if (!Auth::user()->can('roles.delete')) {
                $this->dispatch('error', 'Anda tidak memiliki izin untuk menghapus role');
                return;
            }

            $role = Role::findOrFail($id);
            
            // Cegah penghapusan role 'super_admin' dan 'admin'
            if (in_array($role->name, ['super_admin', 'admin'])) {
                $this->dispatch('error', 'Role ini tidak dapat dihapus!');
                return;
            }

            $role->delete();
            $this->dispatch('success', 'Role berhasil dihapus!');
        } catch (\Exception $e) {
            $this->dispatch('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showDetail($id)
    {
        try {
            // Check permission untuk view detail role
            if (!Auth::user()->can('roles.view')) {
                $this->dispatch('error', 'Anda tidak memiliki izin untuk melihat detail role');
                return;
            }

            $this->selectedRole = Role::with('permissions')->findOrFail($id);
            $this->showModalDetail = true;
        } catch (\Exception $e) {
            $this->dispatch('error', 'Role tidak ditemukan');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showModalDetail = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->roleId = null;
        $this->name = '';
        $this->description = '';
        $this->selectedPermissions = [];
        $this->resetErrorBag();
    }

    public function render()
    {
        $query = Role::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $roles = $query->orderBy($this->sortField, $this->sortDirection)
                       ->paginate($this->perPage);

        // Group permissions by module
        $allPermissions = Permission::all();
        $permissionsByModule = [];
        
        foreach ($allPermissions as $permission) {
            // Extract module from permission name (e.g., 'users.view' -> 'users')
            $parts = explode('.', $permission->name);
            $module = $parts[0] ?? 'other';
            
            if (!isset($permissionsByModule[$module])) {
                $permissionsByModule[$module] = [];
            }
            $permissionsByModule[$module][] = $permission;
        }
        
        // Sort modules alphabetically
        ksort($permissionsByModule);

        return view('livewire.roles.role-index', [
            'roles' => $roles,
            'permissionsByModule' => $permissionsByModule,
        ]);
    }
}
