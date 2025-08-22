<?php

namespace App\Livewire\Admin\Master\StatusKawin;

use App\Models\Master\StatusKawin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;

    public $statuskawinId = '';
    public $nama = '';
    public $tarif_pkp = '';
    public $keterangan = '';

    // Properties for search and filter
    public $search = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;


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

    public function rules()
    {
        return [
            'nama' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('master_statuskawin', 'nama')
                    ->ignore($this->statuskawinId),
            ],
            'tarif_pkp' => 'required|numeric|min:0|max:999999999999.99',
            'keterangan' => 'nullable|string',
        ];
    }

    protected $validationAttributes = [
        'nama' => 'Status Kawin',
        'tarif_pkp' => 'Tarif PKP',
        'keterangan' => 'Keterangan',
    ];

    public function updatingSearch()
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
        $statuskawin = StatusKawin::findOrFail($id);

        $this->statuskawinId = $statuskawin->id;
        $this->nama = $statuskawin->nama;
        $this->tarif_pkp = $statuskawin->tarif_pkp;
        $this->keterangan = $statuskawin->keterangan;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        try {
            $this->validate();

            // Fungsi helper untuk mengkonversi format rupiah ke decimal (untuk database decimal)
            $convertRupiahToDecimal = function ($value) {
                if (empty($value)) return null;
                // Hapus semua karakter kecuali digit
                $cleanValue = preg_replace('/[^0-9]/', '', $value);
                return $cleanValue ? $cleanValue : null;
            };

            $data = [
                'nama' => strtoupper($this->nama),
                'tarif_pkp' => $convertRupiahToDecimal($this->tarif_pkp),
                'keterangan' => $this->keterangan,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];

            if ($this->isEdit) {
                StatusKawin::findOrFail($this->statuskawinId)->update($data);
                $this->dispatch('toast', [
                    'message' => "Data berhasil diedit",
                    'type' => 'success',
                ]);
            } else {
                StatusKawin::create($data);
                $this->dispatch('toast', [
                    'message' => "Data berhasil disimpan",
                    'type' => 'success',
                ]);
            }

            $this->closeModal();
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $count = count($errors);

            $this->dispatch('toast', [
                'message' => "Terdapat $count kesalahan:\n- " . implode("\n- ", $errors),
                'type' => 'error',
            ]);
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Terjadi kesalahan server.',
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
        StatusKawin::find($this->deleteId)?->delete();

        $this->deleteSuccess = true;

        // Trigger Alpine to auto-close modal
        $this->dispatch('modal:success');
    }

    public function resetDeleteModal()
    {
        $this->confirmingDelete = false;
        $this->deleteSuccess = false;
        $this->deleteId = null;
    }
    // End SoftDelete

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
        StatusKawin::withTrashed()->find($this->restoreId)?->restore();

        $this->restoreSuccess = true;

        // Trigger Alpine to auto-close modal
        $this->dispatch('modal:success-restore');
    }

    public function resetRestoreModal()
    {
        $this->confirmingRestore = false;
        $this->restoreSuccess = false;
        $this->restoreId = null;
    }
    // End Restore Data

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
        // Reset success state setiap kali method dipanggil
        $this->forceDeleteSuccess = false;
        
        $statuskawin = StatusKawin::withTrashed()->find($this->forceDeleteId);

        // Cek apakah statuskawin ditemukan
        if (!$statuskawin) {
            $this->dispatch('toast', [
                'message' => 'Data tidak ditemukan.',
                'type' => 'error',
            ]);
            return;
        }

        // Jika tidak ada masalah, baru lakukan force delete
        $statuskawin->forceDelete();
        
        // Set success state dan dispatch modal success
        $this->forceDeleteSuccess = true;
        
        $this->dispatch('toast', [
            'message' => 'Data berhasil dihapus permanen.',
            'type' => 'success',
        ]);
    }

    public function resetForceDeleteModal()
    {
        $this->confirmingForceDelete = false;
        $this->forceDeleteSuccess = false;
        $this->forceDeleteId = null;
    }
    // End Force Delete

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->statuskawinId = null;
        $this->nama = '';
        $this->tarif_pkp = '';
        $this->keterangan = '';
        $this->resetValidation();
    }

    public function render()
    {
        $query = StatusKawin::with([
            'creator:id,name',
            'updater:id,name'
        ]);

        // tampilkan data terhapus jika perlu
        $query->when($this->showDeleted, function ($q) {
            $q->onlyTrashed(); // hanya data yang sudah dihapus
        });

        // pencarian
        $query->when($this->search, function ($q) {
            $search = '%' . $this->search . '%';
            $q->where(function ($q) use ($search) {
                $q->where('nama', 'like', $search)
                    ->orWhere('tarif_pkp', 'like', $search)
                    ->orWhere('keterangan', 'like', $search);
            });
        });

        // urutkan & paginasi
        $statuskawins = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.master.status-kawin.index', compact('statuskawins'));
    }
}
