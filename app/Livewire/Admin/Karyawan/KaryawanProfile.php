<?php

namespace App\Livewire\Admin\Karyawan;

use App\Models\Employee\Karyawan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class KaryawanProfile extends Component
{
    use AuthorizesRequests;

    #[\Livewire\Attributes\Title('Edit Karyawan')]
    public Karyawan $karyawan;
    public string $activeTab = 'profile';
    public bool $isOwnProfile = false;
    public bool $canEdit = false;
    public bool $canView = false;

    public function mount(Karyawan $karyawan, $tab = 'profile')
    {
        $this->karyawan = $karyawan;
        $this->activeTab = $tab;

        // Check if user viewing their own profile
        $user = Auth::user();
        $this->isOwnProfile = $user?->karyawan?->id === $karyawan->id;

        // Permission checks
        $this->canView = $this->hasViewPermission($user, $karyawan);
        $this->canEdit = $this->hasEditPermission($user, $karyawan);

        // Reject if no permission to view
        if (!$this->canView) {
            abort(403, 'Anda tidak memiliki izin untuk melihat profile ini');
        }
    }

    /**
     * Check if user can view this karyawan profile
     */
    private function hasViewPermission($user, $karyawan): bool
    {
        if (!$user) {
            return false;
        }

        // Super Admin dan Admin dapat view semua
        if ($user->hasAnyRole(['super_admin', 'admin', 'hr_manager', 'manager'])) {
            return $user->can('karyawan.view');
        }

        // Staff hanya bisa view profile mereka sendiri
        if ($user->hasRole('staff') && $this->isOwnProfile) {
            return $user->can('karyawan.view');
        }

        return false;
    }

    /**
     * Check if user can edit this karyawan profile
     */
    private function hasEditPermission($user, $karyawan): bool
    {
        if (!$user) {
            return false;
        }

        // Super Admin dapat edit semua
        if ($user->hasRole('super_admin')) {
            return $user->can('karyawan.edit');
        }

        // Admin dapat edit semua
        if ($user->hasRole('admin')) {
            return $user->can('karyawan.edit');
        }

        // HR Manager dapat edit semua
        if ($user->hasRole('hr_manager')) {
            return $user->can('karyawan.edit');
        }

        // Manager dapat edit dengan permission
        if ($user->hasRole('manager')) {
            return $user->can('karyawan.edit');
        }

        // Staff hanya bisa edit profile mereka sendiri
        if ($user->hasRole('staff') && $this->isOwnProfile) {
            return $user->can('karyawan.edit_own_profile');
        }

        return false;
    }

    public function render()
    {
        return view('livewire.admin.karyawan.karyawan-profile', [
            'karyawan' => $this->karyawan,
            'isOwnProfile' => $this->isOwnProfile,
            'canEdit' => $this->canEdit,
            'canView' => $this->canView,
        ]);
    }
}
