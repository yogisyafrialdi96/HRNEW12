<?php

namespace App\Livewire\Admin\Karyawan\Tab\Jabatan;

use App\Models\Employee\KaryawanJabatan;
use App\Traits\HasTabPermission;
use App\Models\Master\Departments;
use App\Models\Master\Jabatans;
use App\Models\Master\Units;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Index extends Component
{
    use WithPagination;
    use HasTabPermission;

    // Main properties
    public $karyawan_id;
    public $jabatan_karyawan_id; // ID record jabatan saat editing
    
    // Form fields
    public $department_id = null;
    public $unit_id = null;
    public $jabatan_id = null;
    public $hub_kerja = 'Default';
    public $tgl_mulai = '';
    public $tgl_selesai = null;
    public $keterangan = null;
    public bool $is_active = true;

    // Properties for search and filter
    public $search = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $showModalDetail = false;
    public $selectedJabatan;

    #[Url()]
    public string $query = '';

    #[Url(except: 'id')]
    public string $sortField = 'id';

    #[Url(except: 'desc')]
    public string $sortDirection = 'desc';

    public bool $showDeleted = false;

    public function mount($karyawan = null)
    {
        $this->authorizeView();
        
        if ($karyawan) {
            $this->karyawan_id = is_object($karyawan) ? $karyawan->id : $karyawan;
        }
    }
    
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
            'department_id' => 'required|exists:master_department,id',
            'unit_id' => 'required|exists:master_unit,id',
            'jabatan_id' => 'required|exists:master_jabatan,id',
            'hub_kerja' => 'required|in:Mutasi,Promosi,Demosi,Rotasi,Default,PJS',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_mulai',
            'keterangan' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
       
        return $rules;
    }

    protected $validationAttributes = [
        'department_id' => 'department',
        'unit_id' => 'unit',
        'jabatan_id' => 'jabatan',
        'hub_kerja' => 'hubungan kerja',
        'tgl_mulai' => 'tanggal mulai',
        'tgl_selesai' => 'tanggal selesai',
        'keterangan' => 'keterangan',
        'is_active' => 'status aktif',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        // Validasi karyawan_id sebelum membuka modal
        if (!$this->karyawan_id) {
            $this->dispatch('toast', [
                'message' => 'Employee ID is missing. Please refresh the page.',
                'type' => 'error',
            ]);
            return;
        }
        
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $jabatan = KaryawanJabatan::findOrFail($id);

        $this->jabatan_karyawan_id = $id;
        $this->karyawan_id = $jabatan->karyawan_id;
        $this->department_id = $jabatan->department_id;
        $this->unit_id = $jabatan->unit_id;
        $this->jabatan_id = $jabatan->jabatan_id;
        $this->hub_kerja = $jabatan->hub_kerja;
        $this->tgl_mulai = $jabatan->tgl_mulai;
        $this->tgl_selesai = $jabatan->tgl_selesai;
        $this->keterangan = $jabatan->keterangan;
        $this->is_active = $jabatan->is_active;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        try {
            $this->authorizeCreate();
            
            if (!$this->karyawan_id) {
                $this->dispatch('toast', [
                    'message' => 'Employee ID is required.',
                    'type' => 'error',
                ]);
                return;
            }

            $validated = $this->validate($this->rules());

            DB::beginTransaction();

            // Jika status jabatan baru adalah aktif, nonaktifkan semua jabatan aktif lainnya
            if ($this->is_active) {
                $updateQuery = KaryawanJabatan::where('karyawan_id', $this->karyawan_id)
                    ->where('is_active', true);
                
                // Jika edit, exclude jabatan yang sedang diedit
                if ($this->isEdit && $this->jabatan_karyawan_id) {
                    $updateQuery->where('id', '!=', $this->jabatan_karyawan_id);
                }
                
                $affectedRows = $updateQuery->update([
                    'is_active' => false,
                    'updated_by' => Auth::id(),
                ]);
            }

            $data = [
                'karyawan_id' => $this->karyawan_id,
                'department_id' => $this->department_id,
                'unit_id' => $this->unit_id,
                'jabatan_id' => $this->jabatan_id,
                'hub_kerja' => $this->hub_kerja,
                'tgl_mulai' => $this->tgl_mulai,
                'tgl_selesai' => $this->tgl_selesai,
                'keterangan' => $this->keterangan,
                'is_active' => $this->is_active,
                'updated_by' => Auth::id(),
            ];

            if (!$this->isEdit) {
                $data['created_by'] = Auth::id();
            }

            if ($this->isEdit && $this->jabatan_karyawan_id) {
                KaryawanJabatan::findOrFail($this->jabatan_karyawan_id)->update($data);
                
                $message = "Data riwayat jabatan berhasil diedit";
                if ($this->is_active && isset($affectedRows) && $affectedRows > 0) {
                    $message .= " dan {$affectedRows} jabatan lama telah dinonaktifkan";
                }
                
                $this->dispatch('toast', [
                    'message' => $message,
                    'type' => 'success',
                ]);
            } else {
                KaryawanJabatan::create($data);
                
                $message = "Data riwayat jabatan berhasil disimpan";
                if ($this->is_active && isset($affectedRows) && $affectedRows > 0) {
                    $message .= " dan {$affectedRows} jabatan lama telah dinonaktifkan";
                }
                
                $this->dispatch('toast', [
                    'message' => $message,
                    'type' => 'success',
                ]);
            }

            DB::commit();

            $this->dispatch('$refresh');
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
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
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
        try {
            $this->authorizeDelete();
            
            $data = KaryawanJabatan::findOrFail($this->deleteId);
            $data->delete();

            $this->deleteSuccess = true;
            $this->dispatch('modal:success');

            $this->dispatch('toast', [
                'message' => 'Data Riwayat Jabatan berhasil dihapus.',
                'type' => 'success',
            ]);

        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Gagal menghapus data.',
                'type' => 'error',
            ]);
        }
    }

    public function resetDeleteModal()
    {
        $this->confirmingDelete = false;
        $this->deleteSuccess = false;
        $this->deleteId = null;
    }
    // End SoftDelete

    public function showDetail($id)
    {
        $this->selectedJabatan = KaryawanJabatan::with([
            'karyawan',
            'department',
            'unit',
            'jabatan',
            'creator',
            'updater'
        ])->find($id);
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
        $this->jabatan_karyawan_id = null;
        // JANGAN reset karyawan_id agar tetap tersimpan
        // $this->karyawan_id tetap dipertahankan
        $this->department_id = null;
        $this->unit_id = null;
        $this->jabatan_id = null;
        $this->hub_kerja = 'Default';
        $this->tgl_mulai = '';
        $this->tgl_selesai = null;
        $this->keterangan = null;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function updatedDepartmentId($value)
    {
        $this->unit_id = null;
        $this->jabatan_id = null;
    }

    public function updatedUnitId($value)
    {
        $this->jabatan_id = null;
    }

    public function render()
    {
        $query = KaryawanJabatan::with([
            'karyawan:id,full_name',
            'department:id,department',
            'unit:id,unit',
            'jabatan:id,nama_jabatan',
            'creator:id,name',
            'updater:id,name'
        ]);

        if ($this->karyawan_id) {
            $query->where('karyawan_id', $this->karyawan_id);
        }

        $query->when($this->search, function ($q) {
            $search = '%' . $this->search . '%';
            $q->where(function ($q) use ($search) {
                $q->where('hub_kerja', 'like', $search)
                  ->orWhere('keterangan', 'like', $search)
                  ->orWhereHas('department', function ($q) use ($search) {
                      $q->where('department', 'like', $search);
                  })
                  ->orWhereHas('jabatan', function ($q) use ($search) {
                      $q->where('nama_jabatan', 'like', $search);
                  });
            });
        });

        $jabatans = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Data untuk dropdown
        $masterDepartment = Departments::orderBy('department')->get();
        
        // Filter units based on selected department
        $masterUnit = $this->department_id
            ? Units::where('department_id', $this->department_id)->orderBy('unit')->get()
            : collect();

        // Filter jabatan based on selected department
        $masterJabatan = $this->department_id
            ? Jabatans::where('department_id', $this->department_id)->orderBy('nama_jabatan')->get()
            : collect();

        return view('livewire.admin.karyawan.tab.jabatan.index', compact(
            'jabatans',
            'masterDepartment',
            'masterUnit',
            'masterJabatan'
        ));
    }
}