<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Models\Employee\Karyawan;
use App\Models\Yayasan\Pengurus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Spatie\Permission\Models\Role;

class UserIndex extends Component
{
    use WithPagination;

    // User properties
    public $userId;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $userType = 'karyawan'; // 'karyawan' or 'pengurus'

    // Search and filter properties
    public $search = '';
    public $statusFilter = '';
    public $typeFilter = ''; // Filter by user type
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $showModalDetail = false;
    public $selectedUser;

    // Role assignment modal properties
    public $showModalRoles = false;
    public $selectedUserId;
    public $selectedRoles = [];

    #[Url]
    public string $query = '';

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
        $rules = [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('users', 'name')->ignore($this->userId)
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->userId)
            ],
            'password' => [
                $this->userId ? 'nullable' : 'required',
                'string',
                'min:8',
                'confirmed'
            ],
            'userType' => [
                'required',
                'in:karyawan,pengurus'
            ],
        ];
        return $rules;
    }

    protected $validationAttributes = [
        'name' => 'Nama',
        'email' => 'Email',
        'password' => 'Password',
        'userType' => 'Tipe User',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function create()
    {
        // Check permission untuk create user
        if (!Auth::user()->can('users.create')) {
            $this->dispatch('error', 'Anda tidak memiliki izin untuk membuat user');
            return;
        }

        $this->resetForm();
        $this->isEdit = false;
        $this->userType = 'karyawan';
        $this->showModal = true;
    }

    public function edit($id)
    {
        // Check permission untuk edit user
        if (!Auth::user()->can('users.edit')) {
            $this->dispatch('error', 'Anda tidak memiliki izin untuk mengedit user');
            return;
        }

        $user = User::find($id);
        
        if (!$user) {
            $this->dispatch('toast', [
                'message' => 'User tidak ditemukan',
                'type' => 'error',
            ]);
            return;
        }

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        
        // Determine user type
        if ($user->karyawan) {
            $this->userType = 'karyawan';
        } elseif ($user->pengurus) {
            $this->userType = 'pengurus';
        }
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        // Check permission untuk create atau edit user
        $permission = $this->isEdit ? 'users.edit' : 'users.create';
        if (!Auth::user()->can($permission)) {
            $this->dispatch('error', 'Anda tidak memiliki izin untuk ' . ($this->isEdit ? 'mengedit' : 'membuat') . ' user');
            return;
        }

        DB::beginTransaction();

        try {
            $this->validate();

            if ($this->isEdit) {
                // Edit existing user
                $user = User::findOrFail($this->userId);

                $userData = [];

                if ($this->email !== $user->email) {
                    $userData['email'] = $this->email;
                }

                if ($this->name !== $user->name) {
                    $userData['name'] = $this->name;
                }

                // Only update password if provided
                if (!empty($this->password)) {
                    $userData['password'] = bcrypt($this->password);
                }

                if (!empty($userData)) {
                    $user->update($userData);
                }

                // Handle user type changes and update related records
                $currentType = null;
                if ($user->karyawan) {
                    $currentType = 'karyawan';
                } elseif ($user->pengurus) {
                    $currentType = 'pengurus';
                }

                // If user type changed or is being set for the first time, update related records
                if ($currentType !== $this->userType) {
                    // Delete existing relations first
                    if ($user->karyawan) {
                        $user->karyawan->delete();
                    }
                    if ($user->pengurus) {
                        $user->pengurus->delete();
                    }

                    // Create new relation based on userType
                    if ($this->userType === 'karyawan') {
                        // Generate NIP
                        $nip = 'NIP' . str_pad($user->id, 8, '0', STR_PAD_LEFT);
                        
                        // Generate inisial dari nama
                        $words = explode(' ', $user->name);
                        $inisial = '';
                        foreach ($words as $word) {
                            $inisial .= substr($word, 0, 1);
                        }
                        $inisial = strtoupper(substr($inisial, 0, 3));
                        if (strlen($inisial) < 3) {
                            $inisial = str_pad($inisial, 3, 'X');
                        }

                        Karyawan::create([
                            'user_id' => $user->id,
                            'nip' => $nip,
                            'inisial' => $inisial,
                            'full_name' => $user->name,
                            'jenis_karyawan' => 'pegawai',
                            'statuskaryawan_id' => 1,
                            'gender' => 'laki-laki',
                            'pndk_akhir' => 'S1',
                            'agama' => 'Islam',
                            'tgl_masuk' => now()->format('Y-m-d'),
                        ]);
                    } elseif ($this->userType === 'pengurus') {
                        // Generate inisial dari nama
                        $words = explode(' ', $user->name);
                        $inisial = '';
                        foreach ($words as $word) {
                            $inisial .= substr($word, 0, 1);
                        }
                        $inisial = strtoupper(substr($inisial, 0, 3));
                        if (strlen($inisial) < 3) {
                            $inisial = str_pad($inisial, 3, 'X');
                        }

                        // Get default jabatan (first available)
                        $defaultJabatan = DB::table('master_jabatan')->first();
                        $jabatanId = $defaultJabatan ? $defaultJabatan->id : null;

                        Pengurus::create([
                            'user_id' => $user->id,
                            'jabatan_id' => $jabatanId,
                            'nama_pengurus' => $user->name,
                            'inisial' => $inisial,
                            'hp' => '0',
                            'jenis_kelamin' => 'laki-laki',
                            'tempat_lahir' => '-',
                            'alamat' => '-',
                            'tanggal_masuk' => now()->format('Y-m-d'),
                            'is_active' => true,
                        ]);
                    }
                } else {
                    // If type hasn't changed, update the name in related record
                    if ($this->userType === 'karyawan' && $user->karyawan) {
                        $user->karyawan->update(['full_name' => $user->name]);
                    } elseif ($this->userType === 'pengurus' && $user->pengurus) {
                        $user->pengurus->update(['nama_pengurus' => $user->name]);
                    }
                }

                $this->dispatch('toast', [
                    'message' => "User berhasil diperbarui",
                    'type' => 'success',
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => bcrypt($this->password),
                ]);

                // Create related record based on user type and assign default role
                if ($this->userType === 'karyawan') {
                    // Generate NIP - simple format: NIP + user_id
                    $nip = 'NIP' . str_pad($user->id, 8, '0', STR_PAD_LEFT);
                    
                    // Generate inisial from name
                    $words = explode(' ', $user->name);
                    $inisial = '';
                    foreach ($words as $word) {
                        $inisial .= substr($word, 0, 1);
                    }
                    $inisial = strtoupper(substr($inisial, 0, 3));
                    if (strlen($inisial) < 3) {
                        $inisial = str_pad($inisial, 3, 'X');
                    }

                    Karyawan::create([
                        'user_id' => $user->id,
                        'nip' => $nip,
                        'inisial' => $inisial,
                        'full_name' => $this->name,
                        'jenis_karyawan' => 'pegawai',
                        'statuskaryawan_id' => 1, // Default: Aktif
                        'gender' => 'laki-laki', // Default gender
                        'pndk_akhir' => 'S1', // Default pendidikan
                        'agama' => 'Islam', // Default agama
                        'tgl_masuk' => now()->format('Y-m-d'), // Default tanggal masuk hari ini
                    ]);
                    
                    // Assign "staff" role for karyawan
                    $staffRole = Role::where('name', 'staff')->first();
                    if ($staffRole) {
                        $user->assignRole($staffRole->name);
                    }
                } elseif ($this->userType === 'pengurus') {
                    // Generate inisial dari nama
                    $words = explode(' ', $user->name);
                    $inisial = '';
                    foreach ($words as $word) {
                        $inisial .= substr($word, 0, 1);
                    }
                    $inisial = strtoupper(substr($inisial, 0, 3));
                    if (strlen($inisial) < 3) {
                        $inisial = str_pad($inisial, 3, 'X');
                    }

                    // Get default jabatan (first available)
                    $defaultJabatan = DB::table('master_jabatan')->first();
                    $jabatanId = $defaultJabatan ? $defaultJabatan->id : null;

                    Pengurus::create([
                        'user_id' => $user->id,
                        'jabatan_id' => $jabatanId,
                        'nama_pengurus' => $this->name,
                        'inisial' => $inisial,
                        'hp' => '0', // Default hp
                        'jenis_kelamin' => 'laki-laki', // Default gender
                        'tempat_lahir' => '-', // Default tempat lahir
                        'alamat' => '-', // Default alamat
                        'tanggal_masuk' => now()->format('Y-m-d'), // Default tanggal masuk
                        'is_active' => true,
                    ]);
                    
                    // Assign "pengurus" role for pengurus
                    $pengurusRole = Role::where('name', 'pengurus')->first();
                    if ($pengurusRole) {
                        $user->assignRole($pengurusRole->name);
                    }
                }

                $this->dispatch('toast', [
                    'message' => "User berhasil dibuat dengan role default",
                    'type' => 'success',
                ]);
            }

            DB::commit();
            $this->closeModal();
        } catch (ValidationException $e) {
            DB::rollBack();
            $errors = $e->validator->errors()->all();
            $count = count($errors);

            $this->dispatch('toast', [
                'message' => "Terdapat $count kesalahan:\n- " . implode("\n- ", $errors),
                'type' => 'error',
            ]);
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('toast', [
                'message' => $e->getMessage() ?: 'Terjadi kesalahan server.',
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
        // Check permission untuk delete user
        if (!Auth::user()->can('users.delete')) {
            $this->dispatch('error', 'Anda tidak memiliki izin untuk menghapus user');
            return;
        }

        User::find($this->deleteId)?->delete();

        $this->deleteSuccess = true;
        $this->dispatch('modal:success');
    }

    public function resetDeleteModal()
    {
        $this->confirmingDelete = false;
        $this->deleteSuccess = false;
        $this->deleteId = null;
    }

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
        User::withTrashed()->find($this->restoreId)?->restore();

        $this->restoreSuccess = true;
        $this->dispatch('modal:success-restore');
    }

    public function resetRestoreModal()
    {
        $this->confirmingRestore = false;
        $this->restoreSuccess = false;
        $this->restoreId = null;
    }

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
        $this->forceDeleteSuccess = false;

        $user = User::withTrashed()->find($this->forceDeleteId);

        if (!$user) {
            $this->dispatch('toast', [
                'message' => 'User tidak ditemukan.',
                'type' => 'error',
            ]);
            return;
        }

        // Delete related karyawan or pengurus records
        if ($user->karyawan) {
            $user->karyawan->forceDelete();
        }
        if ($user->pengurus) {
            $user->pengurus->forceDelete();
        }

        $user->forceDelete();

        $this->forceDeleteSuccess = true;

        $this->dispatch('toast', [
            'message' => 'User berhasil dihapus permanen.',
            'type' => 'success',
        ]);
    }

    public function resetForceDeleteModal()
    {
        $this->confirmingForceDelete = false;
        $this->forceDeleteSuccess = false;
        $this->forceDeleteId = null;
    }

    public function showDetail($id)
    {
        // Check permission untuk view detail user
        if (!Auth::user()->can('users.view')) {
            $this->dispatch('error', 'Anda tidak memiliki izin untuk melihat detail user');
            return;
        }

        $this->selectedUser = User::with(['karyawan', 'pengurus'])
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
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->userType = 'karyawan';
        $this->resetValidation();
    }

    public function toggleStatus($id)
    {
        try {
            DB::beginTransaction();

            $user = User::with(['karyawan', 'pengurus'])->findOrFail($id);
            
            // Toggle karyawan or pengurus status
            if ($user->karyawan) {
                $newStatus = $user->karyawan->statuskaryawan_id == 1 ? 2 : 1;
                $user->karyawan->update(['statuskaryawan_id' => $newStatus]);
            } elseif ($user->pengurus) {
                $user->pengurus->update(['is_active' => !$user->pengurus->is_active]);
            }

            DB::commit();

            $this->dispatch('toast', [
                'message' => "Status berhasil diubah",
                'type' => 'success',
            ]);

            // Refresh the component to reflect changes
            $this->resetPage();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('toast', [
                'message' => "Terjadi kesalahan: " . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    // Role Assignment
    public function openModalRoles($id)
    {
        // Check permission untuk assign roles ke user
        if (!Auth::user()->can('users.assign_roles')) {
            $this->dispatch('error', 'Anda tidak memiliki izin untuk assign roles');
            return;
        }

        $user = User::findOrFail($id);
        $this->selectedUserId = $user->id;
        $this->selectedRoles = $user->roles()->pluck('id')->toArray();
        $this->showModalRoles = true;
    }

    public function saveRoles()
    {
        // Check permission untuk save roles
        if (!Auth::user()->can('users.assign_roles')) {
            $this->dispatch('error', 'Anda tidak memiliki izin untuk assign roles');
            return;
        }

        try {
            $user = User::findOrFail($this->selectedUserId);
            
            // Ensure selectedRoles is array
            $roleIds = is_array($this->selectedRoles) ? $this->selectedRoles : [];
            
            // Sync roles
            if (!empty($roleIds)) {
                $roles = Role::whereIn('id', $roleIds)->pluck('name')->toArray();
                $user->syncRoles($roles);
            } else {
                $user->syncRoles([]);
            }

            $this->dispatch('toast', [
                'message' => 'Roles berhasil diassign',
                'type' => 'success',
            ]);
            
            $this->closeModalRoles();
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function closeModalRoles()
    {
        $this->showModalRoles = false;
        $this->selectedUserId = null;
        $this->selectedRoles = [];
    }

    public function render()
    {
        $query = User::with([
            'karyawan' => fn($q) => $q->select('id', 'user_id', 'full_name', 'statuskaryawan_id'),
            'pengurus' => fn($q) => $q->select('id', 'user_id', 'nama_pengurus', 'is_active'),
            'roles' => fn($q) => $q->select('id', 'name'),
        ]);

        // Show deleted data if needed
        $query->when($this->showDeleted, function ($q) {
            $q->onlyTrashed();
        });

        // Filter by status
        $query->when($this->statusFilter !== '', function ($q) {
            if ($this->statusFilter === 'active') {
                $q->whereHas('karyawan', function ($kq) {
                    $kq->where('statuskaryawan_id', 1);
                })->orWhereHas('pengurus', function ($pq) {
                    $pq->where('is_active', true);
                });
            } else {
                $q->whereHas('karyawan', function ($kq) {
                    $kq->where('statuskaryawan_id', '!=', 1);
                })->orWhereHas('pengurus', function ($pq) {
                    $pq->where('is_active', false);
                });
            }
        });

        // Filter by user type
        $query->when($this->typeFilter !== '', function ($q) {
            if ($this->typeFilter === 'karyawan') {
                $q->whereHas('karyawan');
            } elseif ($this->typeFilter === 'pengurus') {
                $q->whereHas('pengurus');
            }
        });

        // Search
        $query->when($this->search, function ($q) {
            $search = '%' . $this->search . '%';
            $q->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('email', 'like', $search)
                    ->orWhereHas('karyawan', function ($kq) use ($search) {
                        $kq->where('full_name', 'like', $search);
                    })
                    ->orWhereHas('pengurus', function ($pq) use ($search) {
                        $pq->where('nama_pengurus', 'like', $search);
                    });
            });
        });

        // Sort and paginate
        $users = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $roles = Role::all();

        // Get statistics for all users (not filtered)
        $totalUsers = User::count();
        $karyawanUsers = User::whereHas('karyawan')->count();
        $pengurusUsers = User::whereHas('pengurus')->count();
        $noStatusUsers = $totalUsers - $karyawanUsers - $pengurusUsers;
        $activeUsers = User::whereHas('karyawan', function ($q) {
            $q->where('statuskaryawan_id', 1);
        })->orWhereHas('pengurus', function ($q) {
            $q->where('is_active', true);
        })->count();
        $inactiveUsers = $totalUsers - $activeUsers;

        return view('livewire.admin.users.user-index', compact('users', 'roles', 'totalUsers', 'karyawanUsers', 'pengurusUsers', 'noStatusUsers', 'activeUsers', 'inactiveUsers'));
    }
}
