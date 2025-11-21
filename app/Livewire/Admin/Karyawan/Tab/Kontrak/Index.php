<?php

namespace App\Livewire\Admin\Karyawan\Tab\Kontrak;

use App\Models\Employee\KaryawanKontrak;
use App\Models\Master\Golongan;
use App\Models\Master\Jabatans;
use App\Models\Master\Kontrak;
use App\Models\Master\Units;
use App\Models\Master\Departments;
use App\Models\Master\Mapel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Index extends Component {

    use WithPagination;

    // Main properties
    public $karyawan_id;
    public $kontrak_karyawan_id; // ID record kontrak saat editing
    
    // Form fields
    public $nomor_kontrak = '';
    public $kontrak_id = null;
    public $golongan_id = null;
    public $department_id = null;
    public $unit_id = null;
    public $jabatan_id = null;
    public $mapel_id = null;
    public $gaji_paket = null;
    public $gaji_pokok = null;
    public $transport = null;
    public $tglmulai_kontrak = '';
    public $tglselesai_kontrak = null;
    public $status = 'aktif';
    public $catatan = null;
    public $deskripsi = null;

    // Properties for search and filter
    public $search = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $showModalDetail = false;
    public $selectedKontrak;

    #[Url()]
    public string $query = '';

    #[Url(except: 'id')]
    public string $sortField = 'id';

    #[Url(except: 'desc')]
    public string $sortDirection = 'desc';

    public bool $showDeleted = false;

    public function generateNomorKontrak()
    {
        // Get current year and month
        $year = date('Y');
        $month = date('n'); // 1-12 without leading zeros
        
        // Convert month number to Roman numeral
        $romanMonths = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        $romanMonth = $romanMonths[$month];

        // Get the last contract number for this year
        $lastContract = KaryawanKontrak::where('nomor_kontrak', 'like', "%/KU-YKPI/$romanMonth/$year")
            ->orderBy('nomor_kontrak', 'desc')
            ->first();

        // Extract number from last contract or start from 0
        $lastNumber = 0;
        if ($lastContract) {
            $parts = explode('/', $lastContract->nomor_kontrak);
            $lastNumber = (int) $parts[0];
        }

        // Generate new number (increment by 1)
        $newNumber = $lastNumber + 1;

        // Format with leading zeros (3 digits)
        $formattedNumber = str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // Set the nomor_kontrak property
        $this->nomor_kontrak = "$formattedNumber/KU-YKPI/$romanMonth/$year";

        return $this->nomor_kontrak;
    }

    public function mount($karyawan = null)
    {
        if ($karyawan) {
            $this->karyawan_id = $karyawan->id ?? $karyawan;
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
            'nomor_kontrak' => [
                'required',
                'string',
                'max:50',
                $this->isEdit && $this->kontrak_karyawan_id
                    ? 'unique:karyawan_kontrak,nomor_kontrak,' . $this->kontrak_karyawan_id
                    : 'unique:karyawan_kontrak,nomor_kontrak'
            ],
            'kontrak_id' => 'required|exists:master_kontrak,id',
            'golongan_id' => 'required|exists:master_golongan,id',
            'department_id' => 'required|exists:master_department,id',
            'unit_id' => 'required|exists:master_unit,id',
            'jabatan_id' => 'required|exists:master_jabatan,id',
            'mapel_id' => 'nullable|exists:master_mapel,id',
            'gaji_paket' => 'nullable|string|max:255',
            'gaji_pokok' => 'nullable|string|max:255',
            'transport' => 'nullable|string|max:255',
            'tglmulai_kontrak' => 'required|date',
            'tglselesai_kontrak' => 'nullable|date|after_or_equal:tglmulai_kontrak',
            'status' => 'required|in:aktif,selesai,perpanjangan,dibatalkan',
            'catatan' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ];
       
        return $rules;
    }

    protected $validationAttributes = [
        'nomor_kontrak' => 'nomor kontrak',
        'kontrak_id' => 'jenis kontrak',
        'golongan_id' => 'golongan',
        'department_id' => 'department',
        'unit_id' => 'unit',
        'jabatan_id' => 'jabatan',
        'mapel_id' => 'mata pelajaran',
        'gaji_paket' => 'gaji paket',
        'gaji_pokok' => 'gaji pokok',
        'transport' => 'transport',
        'tglmulai_kontrak' => 'tanggal mulai kontrak',
        'tglselesai_kontrak' => 'tanggal selesai kontrak',
        'status' => 'status',
        'catatan' => 'catatan',
        'deskripsi' => 'deskripsi',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->nomor_kontrak = $this->generateNomorKontrak();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $kontrak = KaryawanKontrak::findOrFail($id);

        $this->kontrak_karyawan_id = $id;
        $this->karyawan_id = $kontrak->karyawan_id;
        $this->nomor_kontrak = $kontrak->nomor_kontrak;
        $this->kontrak_id = $kontrak->kontrak_id;
        $this->golongan_id = $kontrak->golongan_id;
        // Get department_id from unit
        if ($kontrak->unit) {
            $this->department_id = $kontrak->unit->department_id;
        }
        $this->unit_id = $kontrak->unit_id;
        $this->jabatan_id = $kontrak->jabatan_id;
        $this->mapel_id = $kontrak->mapel_id;
        $this->gaji_paket = $kontrak->gaji_paket;
        $this->gaji_pokok = $kontrak->gaji_pokok;
        $this->transport = $kontrak->transport;
        $this->tglmulai_kontrak = $kontrak->tglmulai_kontrak;
        $this->tglselesai_kontrak = $kontrak->tglselesai_kontrak;
        $this->status = $kontrak->status;
        $this->catatan = $kontrak->catatan;
        $this->deskripsi = $kontrak->deskripsi;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        try {
            if (!$this->karyawan_id) {
                $this->dispatch('toast', [
                    'message' => 'Employee ID is required.',
                    'type' => 'error',
                ]);
                return;
            }

            $validated = $this->validate($this->rules());

            // Generate nomor kontrak if not provided
            if (empty($this->nomor_kontrak)) {
                $this->nomor_kontrak = $this->generateNomorKontrak();
            }

            $data = [
                'karyawan_id' => $this->karyawan_id,
                'nomor_kontrak' => $this->nomor_kontrak,
                'kontrak_id' => $this->kontrak_id,
                'golongan_id' => $this->golongan_id,
                'unit_id' => $this->unit_id,
                'jabatan_id' => $this->jabatan_id,
                'mapel_id' => $this->mapel_id,
                'gaji_paket' => $this->gaji_paket,
                'gaji_pokok' => $this->gaji_pokok,
                'transport' => $this->transport,
                'tglmulai_kontrak' => $this->tglmulai_kontrak,
                'tglselesai_kontrak' => $this->tglselesai_kontrak,
                'status' => $this->status,
                'catatan' => $this->catatan,
                'deskripsi' => $this->deskripsi,
                'updated_by' => Auth::id(),
            ];

            if (!$this->isEdit) {
                $data['created_by'] = Auth::id();
            }

            if ($this->isEdit && $this->kontrak_karyawan_id) {
                KaryawanKontrak::findOrFail($this->kontrak_karyawan_id)->update($data);
                $this->dispatch('toast', [
                    'message' => "Data kontrak berhasil diedit",
                    'type' => 'success',
                ]);
            } else {
                KaryawanKontrak::create($data);
                $this->dispatch('toast', [
                    'message' => "Data kontrak berhasil disimpan",
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
            $data = KaryawanKontrak::findOrFail($this->deleteId);
            $data->delete();

            $this->deleteSuccess = true;
            $this->dispatch('modal:success');

            $this->dispatch('toast', [
                'message' => 'Data Kontrak berhasil dihapus.',
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
        $this->selectedKontrak = KaryawanKontrak::with([
            'karyawan',
            'kontrak',
            'golongan',
            'unit',
            'jabatan',
            'creator',
            'updater',
            'approver1',
            'approver2'
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
        $this->kontrak_karyawan_id = null;
        $this->nomor_kontrak = '';
        $this->kontrak_id = null;
        $this->golongan_id = null;
        $this->department_id = null;
        $this->unit_id = null;
        $this->jabatan_id = null;
        $this->mapel_id = null;
        $this->gaji_paket = null;
        $this->gaji_pokok = null;
        $this->transport = null;
        $this->tglmulai_kontrak = '';
        $this->tglselesai_kontrak = null;
        $this->status = 'aktif';
        $this->catatan = null;
        $this->deskripsi = null;
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
        $query = KaryawanKontrak::with([
            'karyawan:id,full_name',
            'kontrak:id,nama_kontrak',
            'golongan:id,nama_golongan',
            'unit:id,unit',
            'jabatan:id,nama_jabatan',
            'mapel:id,nama_mapel',
            'creator:id,name',
            'updater:id,name'
        ]);

        if ($this->karyawan_id) {
            $query->where('karyawan_id', $this->karyawan_id);
        }

        $query->when($this->search, function ($q) {
            $search = '%' . $this->search . '%';
            $q->where(function ($q) use ($search) {
                $q->where('nomor_kontrak', 'like', $search)
                  ->orWhere('status', 'like', $search)
                  ->orWhereHas('kontrak', function ($q) use ($search) {
                      $q->where('nama_kontrak', 'like', $search);
                  })
                  ->orWhereHas('jabatan', function ($q) use ($search) {
                      $q->where('nama_jabatan', 'like', $search);
                  });
            });
        });

        $kontraks = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Data untuk dropdown
        $masterKontrak = Kontrak::orderBy('nama_kontrak')->get();
        $masterGolongan = Golongan::orderBy('nama_golongan')->get();
        $masterDepartment = Departments::orderBy('department')->get();
        
        // Filter units based on selected department
        $masterUnit = $this->department_id
            ? Units::where('department_id', $this->department_id)->orderBy('unit')->get()
            : collect();

        // Filter jabatan based on selected department
        $masterJabatan = $this->department_id
            ? Jabatans::where('department_id', $this->department_id)->orderBy('nama_jabatan')->get()
            : collect();

        // Get mata pelajaran for dropdown
        $masterMapel = Mapel::orderBy('nama_mapel')->get();

        return view('livewire.admin.karyawan.tab.kontrak.index', compact(
            'kontraks',
            'masterKontrak',
            'masterGolongan',
            'masterDepartment',
            'masterUnit',
            'masterJabatan',
            'masterMapel'
        ));
    }
}