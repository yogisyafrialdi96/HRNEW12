<?php

namespace App\Livewire\Admin\Master;

use App\Models\IzinCuti\CutiSetup;
use Livewire\Component;

class CutiSetupIndex extends Component
{
    public $showModal = false;
    public $isEditMode = false;
    public $setup = null;

    public $h_min_cuti_tahunan = 7;
    public $max_cuti_tahunan_per_tahun = 12;
    public $max_carry_over = 5;
    public $hari_cuti_melahirkan = 45;
    public $h_min_cuti_melahirkan = 14;
    public $hari_kerja = '1,2,3,4,5';
    public $jam_kerja_per_hari = 8;
    public $catatan = '';

    protected $rules = [
        'h_min_cuti_tahunan' => 'required|integer|min:1',
        'max_cuti_tahunan_per_tahun' => 'required|integer|min:1',
        'max_carry_over' => 'required|integer|min:0',
        'hari_cuti_melahirkan' => 'required|integer|min:1',
        'h_min_cuti_melahirkan' => 'required|integer|min:1',
        'hari_kerja' => 'required|string',
        'jam_kerja_per_hari' => 'required|integer|min:1',
        'catatan' => 'nullable|string',
    ];

    public function mount()
    {
        $this->authorize('cuti_setup.view');
        $this->loadSetup();
    }

    public function loadSetup()
    {
        $this->setup = CutiSetup::first();
        if ($this->setup) {
            $this->h_min_cuti_tahunan = $this->setup->h_min_cuti_tahunan;
            $this->max_cuti_tahunan_per_tahun = $this->setup->max_cuti_tahunan_per_tahun;
            $this->max_carry_over = $this->setup->max_carry_over;
            $this->hari_cuti_melahirkan = $this->setup->hari_cuti_melahirkan;
            $this->h_min_cuti_melahirkan = $this->setup->h_min_cuti_melahirkan;
            $this->hari_kerja = $this->setup->hari_kerja;
            $this->jam_kerja_per_hari = $this->setup->jam_kerja_per_hari;
            $this->catatan = $this->setup->catatan ?? '';
            $this->isEditMode = true;
        }
    }

    public function openModal()
    {
        $this->authorize('cuti_setup.edit');
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->loadSetup();
    }

    public function save()
    {
        $this->authorize('cuti_setup.edit');
        $this->validate();

        try {
            if ($this->setup) {
                $this->setup->update([
                    'h_min_cuti_tahunan' => $this->h_min_cuti_tahunan,
                    'max_cuti_tahunan_per_tahun' => $this->max_cuti_tahunan_per_tahun,
                    'max_carry_over' => $this->max_carry_over,
                    'hari_cuti_melahirkan' => $this->hari_cuti_melahirkan,
                    'h_min_cuti_melahirkan' => $this->h_min_cuti_melahirkan,
                    'hari_kerja' => $this->hari_kerja,
                    'jam_kerja_per_hari' => $this->jam_kerja_per_hari,
                    'catatan' => $this->catatan,
                    'updated_by' => auth()->id(),
                ]);
            } else {
                CutiSetup::create([
                    'h_min_cuti_tahunan' => $this->h_min_cuti_tahunan,
                    'max_cuti_tahunan_per_tahun' => $this->max_cuti_tahunan_per_tahun,
                    'max_carry_over' => $this->max_carry_over,
                    'hari_cuti_melahirkan' => $this->hari_cuti_melahirkan,
                    'h_min_cuti_melahirkan' => $this->h_min_cuti_melahirkan,
                    'hari_kerja' => $this->hari_kerja,
                    'jam_kerja_per_hari' => $this->jam_kerja_per_hari,
                    'catatan' => $this->catatan,
                    'updated_by' => auth()->id(),
                ]);
            }

            $this->dispatch('toast', message: $this->isEditMode ? 'Setup cuti berhasil diupdate' : 'Setup cuti berhasil dibuat', type: 'success');
            $this->closeModal();
            $this->loadSetup();
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.admin.master.cuti-setup-index');
    }
}
