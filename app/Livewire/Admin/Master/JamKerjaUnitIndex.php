<?php

namespace App\Livewire\Admin\Master;

use App\Models\IzinCuti\JamKerjaUnit;
use App\Models\Master\Units;
use Livewire\Component;
use Livewire\WithPagination;

class JamKerjaUnitIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterUnit = '';
    public $showModal = false;
    public $isEditMode = false;

    public $jam_kerja_id = null;
    public $unit_id = null;
    public $hari_ke = 1;
    public $jam_masuk = '07:15';
    public $jam_pulang = '16:00';
    public $jam_istirahat = 60;
    public $is_libur = false;
    public $is_full_day = true;
    public $keterangan = '';

    public $units = [];
    public $hari_list = [];

    protected $rules = [
        'unit_id' => 'required|exists:master_unit,id',
        'hari_ke' => 'required|integer|between:1,7',
        'jam_masuk' => 'required|date_format:H:i',
        'jam_pulang' => 'required|date_format:H:i',
        'jam_istirahat' => 'required|integer|min:0',
        'keterangan' => 'nullable|string',
    ];

    public function mount()
    {
        $this->authorize('master_data.view');
        $this->units = Units::with('department')
            ->whereHas('department', function ($q) {
                $q->where('department', '!=', 'YAYASAN');
            })
            ->orderBy('unit')
            ->get();
        $this->hari_list = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];
    }

    #[\Livewire\Attributes\Computed]
    public function jamKerjas()
    {
        $query = JamKerjaUnit::with('unit');

        if ($this->search) {
            $query->whereHas('unit', function ($q) {
                $q->where('nama', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterUnit) {
            $query->where('unit_id', $this->filterUnit);
        }

        return $query->orderBy('unit_id')->orderBy('hari_ke')->paginate(15);
    }

    public function openModal()
    {
        $this->authorize('master_data.create');
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->jam_kerja_id = null;
        $this->unit_id = null;
        $this->hari_ke = 1;
        $this->jam_masuk = '07:15';
        $this->jam_pulang = '16:00';
        $this->jam_istirahat = 60;
        $this->is_libur = false;
        $this->is_full_day = true;
        $this->keterangan = '';
        $this->isEditMode = false;
    }

    public function edit($id)
    {
        $this->authorize('master_data.edit');
        $jam = JamKerjaUnit::findOrFail($id);
        
        $this->jam_kerja_id = $jam->id;
        $this->unit_id = $jam->unit_id;
        $this->hari_ke = $jam->hari_ke;
        // Format time untuk input type="time" (pastikan H:i format)
        $this->jam_masuk = is_string($jam->jam_masuk) ? substr($jam->jam_masuk, 0, 5) : $jam->jam_masuk;
        $this->jam_pulang = is_string($jam->jam_pulang) ? substr($jam->jam_pulang, 0, 5) : $jam->jam_pulang;
        $this->jam_istirahat = $jam->jam_istirahat;
        $this->is_libur = $jam->is_libur;
        $this->is_full_day = $jam->is_full_day;
        $this->keterangan = $jam->keterangan;
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->isEditMode) {
                $jam = JamKerjaUnit::findOrFail($this->jam_kerja_id);
                $jam->update([
                    'unit_id' => $this->unit_id,
                    'hari_ke' => $this->hari_ke,
                    'jam_masuk' => $this->jam_masuk,
                    'jam_pulang' => $this->jam_pulang,
                    'jam_istirahat' => $this->jam_istirahat,
                    'is_libur' => $this->is_libur,
                    'is_full_day' => $this->is_full_day,
                    'keterangan' => $this->keterangan,
                ]);
            } else {
                JamKerjaUnit::create([
                    'unit_id' => $this->unit_id,
                    'hari_ke' => $this->hari_ke,
                    'jam_masuk' => $this->jam_masuk,
                    'jam_pulang' => $this->jam_pulang,
                    'jam_istirahat' => $this->jam_istirahat,
                    'is_libur' => $this->is_libur,
                    'is_full_day' => $this->is_full_day,
                    'keterangan' => $this->keterangan,
                ]);
            }

            $this->dispatch('toast', message: $this->isEditMode ? 'Jam kerja unit berhasil diupdate' : 'Jam kerja unit berhasil ditambahkan', type: 'success');
            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function delete($id)
    {
        $this->authorize('master_data.delete');
        
        try {
            JamKerjaUnit::findOrFail($id)->delete();
            $this->dispatch('toast', message: 'Jam kerja unit berhasil dihapus', type: 'success');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.admin.master.jam-kerja-unit-index', [
            'jamKerjas' => $this->jamKerjas
        ]);
    }
}
