<?php

namespace App\Livewire\Admin\Karyawan;

use App\Models\Employee\Karyawan;
use Livewire\Component;

class KaryawanProfile extends Component
{
    #[\Livewire\Attributes\Title('Edit Karyawan')]
    public Karyawan $karyawan;
    public string $activeTab = 'profile'; // default ke tab kontrak jika diakses langsung


    public function mount(Karyawan $karyawan, $tab = 'profile')
    {
        $this->karyawan = $karyawan;
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.karyawan.karyawan-profile', [
        'karyawan' => $this->karyawan,]);
    }
}
