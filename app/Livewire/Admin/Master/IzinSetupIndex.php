<?php

namespace App\Livewire\Admin\Master;

use App\Models\IzinCuti\IzinSetup;
use Livewire\Component;

class IzinSetupIndex extends Component
{
    public $showModal = false;
    public $isEditMode = false;
    public $setup = null;

    public $h_min_izin_sakit = 1;
    public $max_izin_sakit_per_tahun = 10;
    public $sakit_perlu_surat_dokter = false;
    public $hari_ke_berapa_perlu_dokter = 3;
    public $h_min_izin_penting = 3;
    public $max_izin_penting_per_tahun = 3;
    public $h_min_izin_ibadah = 7;
    public $max_hari_ibadah_per_tahun = 3;
    public $tidak_hitung_libnas = true;
    public $tidak_hitung_libur_unit = true;
    public $catatan = '';

    protected $rules = [
        'h_min_izin_sakit' => 'required|integer|min:1',
        'max_izin_sakit_per_tahun' => 'required|integer|min:1',
        'hari_ke_berapa_perlu_dokter' => 'required|integer|min:1',
        'h_min_izin_penting' => 'required|integer|min:1',
        'max_izin_penting_per_tahun' => 'required|integer|min:1',
        'h_min_izin_ibadah' => 'required|integer|min:1',
        'max_hari_ibadah_per_tahun' => 'required|integer|min:1',
        'catatan' => 'nullable|string',
    ];

    public function mount()
    {
        $this->authorize('izin_setup.view');
        $this->loadSetup();
    }

    public function loadSetup()
    {
        $this->setup = IzinSetup::first();
        if ($this->setup) {
            $this->h_min_izin_sakit = $this->setup->h_min_izin_sakit;
            $this->max_izin_sakit_per_tahun = $this->setup->max_izin_sakit_per_tahun;
            $this->sakit_perlu_surat_dokter = $this->setup->sakit_perlu_surat_dokter;
            $this->hari_ke_berapa_perlu_dokter = $this->setup->hari_ke_berapa_perlu_dokter;
            $this->h_min_izin_penting = $this->setup->h_min_izin_penting;
            $this->max_izin_penting_per_tahun = $this->setup->max_izin_penting_per_tahun;
            $this->h_min_izin_ibadah = $this->setup->h_min_izin_ibadah;
            $this->max_hari_ibadah_per_tahun = $this->setup->max_hari_ibadah_per_tahun;
            $this->tidak_hitung_libnas = $this->setup->tidak_hitung_libnas;
            $this->tidak_hitung_libur_unit = $this->setup->tidak_hitung_libur_unit;
            $this->catatan = $this->setup->catatan ?? '';
            $this->isEditMode = true;
        }
    }

    public function openModal()
    {
        $this->authorize('izin_setup.edit');
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
        $this->authorize('izin_setup.edit');
        $this->validate();

        try {
            if ($this->setup) {
                $this->setup->update([
                    'h_min_izin_sakit' => $this->h_min_izin_sakit,
                    'max_izin_sakit_per_tahun' => $this->max_izin_sakit_per_tahun,
                    'sakit_perlu_surat_dokter' => $this->sakit_perlu_surat_dokter,
                    'hari_ke_berapa_perlu_dokter' => $this->hari_ke_berapa_perlu_dokter,
                    'h_min_izin_penting' => $this->h_min_izin_penting,
                    'max_izin_penting_per_tahun' => $this->max_izin_penting_per_tahun,
                    'h_min_izin_ibadah' => $this->h_min_izin_ibadah,
                    'max_hari_ibadah_per_tahun' => $this->max_hari_ibadah_per_tahun,
                    'tidak_hitung_libnas' => $this->tidak_hitung_libnas,
                    'tidak_hitung_libur_unit' => $this->tidak_hitung_libur_unit,
                    'catatan' => $this->catatan,
                    'updated_by' => auth()->id(),
                ]);
            } else {
                IzinSetup::create([
                    'h_min_izin_sakit' => $this->h_min_izin_sakit,
                    'max_izin_sakit_per_tahun' => $this->max_izin_sakit_per_tahun,
                    'sakit_perlu_surat_dokter' => $this->sakit_perlu_surat_dokter,
                    'hari_ke_berapa_perlu_dokter' => $this->hari_ke_berapa_perlu_dokter,
                    'h_min_izin_penting' => $this->h_min_izin_penting,
                    'max_izin_penting_per_tahun' => $this->max_izin_penting_per_tahun,
                    'h_min_izin_ibadah' => $this->h_min_izin_ibadah,
                    'max_hari_ibadah_per_tahun' => $this->max_hari_ibadah_per_tahun,
                    'tidak_hitung_libnas' => $this->tidak_hitung_libnas,
                    'tidak_hitung_libur_unit' => $this->tidak_hitung_libur_unit,
                    'catatan' => $this->catatan,
                    'updated_by' => auth()->id(),
                ]);
            }

            $this->dispatch('toast', message: $this->isEditMode ? 'Setup izin berhasil diupdate' : 'Setup izin berhasil dibuat', type: 'success');
            $this->closeModal();
            $this->loadSetup();
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.admin.master.izin-setup-index');
    }
}
