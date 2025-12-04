<?php

namespace App\Livewire\Admin\Karyawan\Tab\Bahasa;

use App\Models\Employee\KaryawanBahasa;
use App\Traits\HasTabPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use HasTabPermission;

    // Main properties
    public $karyawan_id; // This should hold the employee ID
    public $bahasa_id; // Add this to store the organisasi record ID when editing
    
    // Form fields
    public $nama_bahasa = '';
    public $level_bahasa = null;
    public $jenis_test = null;
    public $jenistest_lain = null;
    public $lembaga_sertifikasi = '';
    public $skor_numerik = '';
    public bool $is_active = false;
    public $tgl_sertifikasi = '';
    public $tgl_expired_sertifikasi = null;
    public $keterangan = null;

    // Properties for search and filter
    public $search = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $showModalDetail = false;
    public $selectedBahasa; // Consider renaming to $selectedBahasa

    #[Url()]
    public string $query = '';

    // Set default URL param supaya reset saat refresh
    #[Url(except: 'id')]
    public string $sortField = 'id';

    #[Url(except: 'desc')]
    public string $sortDirection = 'desc';

    public bool $showDeleted = false;

     public function mount($karyawan = null)
    {
        // Set the employee ID from the parent component
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
            'nama_bahasa'           => 'required|string|max:255',
            'level_bahasa'          => 'required|in:pemula,dasar,menengah,mahir,fasih,native',
            'jenis_test'            => 'required|in:TOEFL,IELTS,HSK,JLPT,DELF,TestDaF,TOEIC,CAE,Lainnya',
            'lembaga_sertifikasi'   => 'required|string|max:255',
            'skor_numerik'          => 'required|numeric|min:0|max:999',
            'tgl_sertifikasi'       => 'required|date',
            'tgl_expired_sertifikasi'     => 'nullable|date|after_or_equal:tgl_sertifikasi',
            'keterangan'            => 'nullable|string|max:255',
            'is_active'             => 'boolean',
        ];

        if ($this->jenis_test === 'Lainnya') {
            $rules['jenistest_lain'] = 'required|string|max:255';
        }
       
        return $rules;
    }

    protected $validationAttributes = [
        'jenistest_lain' => 'jenis test',
        'lembaga_sertifikasi' => 'lembaga',
        'tgl_expired_sertifikasi' => 'tanggal expired',
        'tgl_sertifikasi' => 'tanggal sertifikasi',
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
        $keluarga = KaryawanBahasa::findOrFail($id);

        // Store the education record ID for editing
        $this->bahasa_id = $id;
        
        // Keep the employee ID intact
        $this->karyawan_id          = $keluarga->karyawan_id;
        $this->nama_bahasa          = $keluarga->nama_bahasa;
        $this->level_bahasa         = $keluarga->level_bahasa;
        $this->jenis_test           = $keluarga->jenis_test;
        $this->jenistest_lain       = $keluarga->jenistest_lain;
        $this->lembaga_sertifikasi  = $keluarga->lembaga_sertifikasi;
        $this->skor_numerik         = $keluarga->skor_numerik;
        $this->is_active            = $keluarga->is_active;
        // Format dates to Y-m-d for input date fields
        $this->tgl_expired_sertifikasi = $keluarga->tgl_expired_sertifikasi ? \Carbon\Carbon::parse($keluarga->tgl_expired_sertifikasi)->format('Y-m-d') : null;
        $this->tgl_sertifikasi      = $keluarga->tgl_sertifikasi ? \Carbon\Carbon::parse($keluarga->tgl_sertifikasi)->format('Y-m-d') : '';
        $this->keterangan           = $keluarga->keterangan;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        try {
            // Validate that we have a karyawan_id before proceeding
            if (!$this->karyawan_id) {
                $this->dispatch('toast', [
                    'message' => 'Employee ID is required.',
                    'type' => 'error',
                ]);
                return;
            }

            $validated = $this->validate($this->rules());

        
            $data = [
                'karyawan_id'           => $this->karyawan_id,
                'nama_bahasa'           => $this->nama_bahasa,
                'level_bahasa'          => $this->level_bahasa,
                'jenis_test'            => $this->jenis_test,
                'jenistest_lain'        => $this->jenistest_lain,
                'lembaga_sertifikasi'   => $this->lembaga_sertifikasi,
                'skor_numerik'          => $this->skor_numerik,
                'is_active'             => $this->is_active,
                'tgl_expired_sertifikasi'  => $this->tgl_expired_sertifikasi ?: null,
                'tgl_sertifikasi'       => $this->tgl_sertifikasi,
                'keterangan'            => $this->keterangan,
                'updated_by'            => Auth::id(),
            ];

            if (!$this->isEdit) {
                $data['created_by'] = Auth::id();
            }

            if ($this->isEdit && $this->bahasa_id) {
                // Use the stored bahasa_id for updating
                KaryawanBahasa::findOrFail($this->bahasa_id)->update($data);
                $this->dispatch('toast', [
                    'message' => "Data berhasil diedit",
                    'type' => 'success',
                ]);
            } else {
                KaryawanBahasa::create($data);
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
            $data = KaryawanBahasa::findOrFail($this->deleteId);

            // Hapus record dari database
            $data->delete();

            $this->deleteSuccess = true;

            // Tutup modal via Alpine
            $this->dispatch('modal:success');

            // Optional: flash message atau toast
            $this->dispatch('toast', [
                'message' => 'Data Bahasa berhasil dihapus.',
                'type' => 'success',
            ]);

        } catch (\Exception $e) {
            // Log atau tampilkan error jika perlu
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
        $this->selectedBahasa = KaryawanBahasa::with(['karyawan','creator', 'updater'])
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
        $this->bahasa_id   = null; // Reset the education record ID
        // karyawan_id jangan direset (biar tetap nempel)
        $this->nama_bahasa   = '';
        $this->level_bahasa       = '';
        $this->jenis_test  = null;
        $this->jenistest_lain  = '';
        $this->lembaga_sertifikasi   = '';
        $this->skor_numerik     = '';
        $this->is_active      = '';
        $this->tgl_expired_sertifikasi   = '';
        $this->tgl_sertifikasi     = '';
        $this->keterangan         = null;
        $this->resetValidation();
    }

    public function render()
    {
        $query = KaryawanBahasa::with([
            'karyawan:id,full_name',
            'creator:id,name',
            'updater:id,name'
        ]);

        // Filter by employee if karyawan_id is set
        if ($this->karyawan_id) {
            $query->where('karyawan_id', $this->karyawan_id);
        }

        // pencarian
        $query->when($this->search, function ($q) {
            $search = '%' . $this->search . '%';
            $q->where(function ($q) use ($search) {
                $q->where('nama_bahasa', 'like', $search)
                  ->orWhere('level_bahasa', 'like', $search)
                  ->orWhere('jenis_test', 'like', $search);
            });
        });

        // urutkan & paginasi
        $bahasas = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.karyawan.tab.bahasa.index', compact('bahasas'));
    }
}