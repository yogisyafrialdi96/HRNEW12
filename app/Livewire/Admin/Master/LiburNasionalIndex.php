<?php

namespace App\Livewire\Admin\Master;

use App\Models\IzinCuti\LiburNasional;
use App\Models\Wilayah\Provinsi;
use Livewire\Component;

class LiburNasionalIndex extends Component
{
    public $showModal = false;
    public $isEditMode = false;

    public $libur_id = null;
    public $nama_libur = '';
    public $tanggal_libur = '';
    public $tanggal_libur_akhir = '';
    public $tipe = 'nasional';
    public $provinsi_id = null;
    public $is_active = true;
    public $keterangan = '';

    public $provinsis = [];
    public $events = [];

    protected $rules = [
        'nama_libur' => 'required|string|max:255',
        'tanggal_libur' => 'required|date',
        'tanggal_libur_akhir' => 'nullable|date|after_or_equal:tanggal_libur',
        'tipe' => 'required|in:nasional,lokal,cuti_bersama',
        'provinsi_id' => 'nullable|exists:provinsi,id',
        'keterangan' => 'nullable|string',
    ];

    public function mount()
    {
        $this->authorize('master_data.view');
        $this->provinsis = Provinsi::orderBy('nama')->get();
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $liburs = LiburNasional::where('is_active', true)->get();
        
        $this->events = $liburs->map(function ($libur) {
            return [
                'id' => $libur->id,
                'title' => $libur->nama_libur . ' (' . strtoupper($libur->tipe) . ')',
                'start' => $libur->tanggal_libur,
                'end' => $libur->tanggal_libur_akhir ?? $libur->tanggal_libur,
                'extendedProps' => [
                    'tipe' => $libur->tipe,
                    'keterangan' => $libur->keterangan,
                    'provinsi_id' => $libur->provinsi_id,
                ],
                'backgroundColor' => $this->getTipeColor($libur->tipe),
                'borderColor' => $this->getTipeColor($libur->tipe),
                'textColor' => '#ffffff',
            ];
        })->toArray();
    }

    private function getTipeColor($tipe)
    {
        return match($tipe) {
            'nasional' => '#2563eb', // blue
            'lokal' => '#a855f7',     // purple
            'cuti_bersama' => '#f97316', // orange
            default => '#6b7280',
        };
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
        $this->libur_id = null;
        $this->nama_libur = '';
        $this->tanggal_libur = '';
        $this->tanggal_libur_akhir = '';
        $this->tipe = 'nasional';
        $this->provinsi_id = null;
        $this->is_active = true;
        $this->keterangan = '';
        $this->isEditMode = false;
    }

    public function edit($id)
    {
        $this->authorize('master_data.edit');
        $libur = LiburNasional::findOrFail($id);
        
        $this->libur_id = $libur->id;
        $this->nama_libur = $libur->nama_libur;
        $this->tanggal_libur = $libur->tanggal_libur->format('Y-m-d');
        $this->tanggal_libur_akhir = $libur->tanggal_libur_akhir?->format('Y-m-d');
        $this->tipe = $libur->tipe;
        $this->provinsi_id = $libur->provinsi_id;
        $this->is_active = $libur->is_active;
        $this->keterangan = $libur->keterangan;
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->isEditMode) {
                $libur = LiburNasional::findOrFail($this->libur_id);
                $libur->update([
                    'nama_libur' => $this->nama_libur,
                    'tanggal_libur' => $this->tanggal_libur,
                    'tanggal_libur_akhir' => $this->tanggal_libur_akhir ?: null,
                    'tipe' => $this->tipe,
                    'provinsi_id' => $this->provinsi_id,
                    'is_active' => $this->is_active,
                    'keterangan' => $this->keterangan,
                ]);
            } else {
                LiburNasional::create([
                    'nama_libur' => $this->nama_libur,
                    'tanggal_libur' => $this->tanggal_libur,
                    'tanggal_libur_akhir' => $this->tanggal_libur_akhir ?: null,
                    'tipe' => $this->tipe,
                    'provinsi_id' => $this->provinsi_id,
                    'is_active' => $this->is_active,
                    'keterangan' => $this->keterangan,
                ]);
            }

            // Load events before closing modal
            $this->loadEvents();
            $this->closeModal();
            
            $this->dispatch('toast', message: $this->isEditMode ? 'Libur nasional berhasil diupdate' : 'Libur nasional berhasil ditambahkan', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function delete($id)
    {
        $this->authorize('master_data.delete');
        
        try {
            LiburNasional::findOrFail($id)->delete();
            
            // Load events before closing modal
            $this->loadEvents();
            $this->closeModal();
            
            $this->dispatch('toast', message: 'Libur nasional berhasil dihapus', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function eventClick($eventId)
    {
        $this->edit($eventId);
    }

    public function updateEventDates($id, $newStart, $newEnd = null)
    {
        $this->authorize('master_data.edit');
        
        try {
            $libur = LiburNasional::findOrFail($id);
            $libur->update([
                'tanggal_libur' => $newStart,
                'tanggal_libur_akhir' => $newEnd ?: null,
            ]);

            $this->loadEvents();
            $this->dispatch('toast', message: 'Tanggal libur berhasil diperbarui', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.admin.master.libur-nasional-calendar', [
            'events' => $this->events,
        ]);
    }
}
