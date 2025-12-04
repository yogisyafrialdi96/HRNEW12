<?php

namespace App\Livewire\Admin\Karyawan\Tab\Keluarga;

use App\Models\Employee\KaryawanKeluarga;
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
    public $keluarga_id; // Add this to store the organisasi record ID when editing
    
    // Form fields
    public $nama_anggota = '';
    public $hubungan = '';
    public $hubungan_lain = null;
    public $jenis_kelamin = '';
    public $tempat_lahir = '';
    public $tgl_lahir = '';
    public $pekerjaan = '';
    public $status_hidup = '';
    public bool $ditanggung = false;
    public $alamat = null;
    public $no_hp = null;
    public $keterangan = null;

    // Properties for search and filter
    public $search = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $showModalDetail = false;
    public $selectedKeluarga; // Consider renaming to $selectedKeluarga

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
        $this->authorizeView();
        
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
            'nama_anggota'     => 'required|string|max:255',
            'hubungan'         => 'required|in:suami,istri,anak,ayah,ibu,saudara,mertua,lainnya',
            'jenis_kelamin'    => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir'     => 'required|string|max:255',
            'tgl_lahir'        => 'required|date',
            'pekerjaan'        => 'required|string|max:255',
            'status_hidup'     => 'required|in:Hidup,Meninggal',
            'alamat'           => 'required|string|max:255',
            'no_hp'            => ['required', 'regex:/^\+62\s\d{3}-\d{4}-\d{4}$/', 'max:17'],
            'ditanggung'       => 'required|boolean',
            ];

            if ($this->hubungan === 'lainnya') {
                $rules['hubungan_lain'] = 'required|string|max:255';
            }
       
        return $rules;
    }

    protected $validationAttributes = [
        'nama_anggota' => 'nama lengkap',
        'tgl_lahir' => 'tanggal lahir',
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
        $keluarga = KaryawanKeluarga::findOrFail($id);

        // Store the education record ID for editing
        $this->keluarga_id = $id;
        
        // Keep the employee ID intact
        $this->karyawan_id      = $keluarga->karyawan_id;
        $this->nama_anggota     = $keluarga->nama_anggota;
        $this->hubungan         = $keluarga->hubungan;
        $this->hubungan_lain    = $keluarga->hubungan_lain;
        $this->jenis_kelamin    = $keluarga->jenis_kelamin;
        $this->tempat_lahir     = $keluarga->tempat_lahir;
        $this->tgl_lahir        = $keluarga->tgl_lahir;
        $this->pekerjaan        = $keluarga->pekerjaan;
        $this->status_hidup     = $keluarga->status_hidup;
        $this->ditanggung       = $keluarga->ditanggung;
        $this->alamat           = $keluarga->alamat;
        $this->no_hp            = $keluarga->no_hp;
        $this->keterangan       = $keluarga->keterangan;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        try {
            $this->authorizeCreate();
            
            // Validate that we have a karyawan_id before proceeding
            if (!$this->karyawan_id) {
                $this->dispatch('toast', [
                    'message' => 'Employee ID is required.',
                    'type' => 'error',
                ]);
                return;
            }

            $validated = $this->validate($this->rules());

            // Normalisasi nomor HP
            $plainHp = preg_replace('/[^\d+]/', '', $this->no_hp);

            $data = [
                'karyawan_id'   => $this->karyawan_id,
                'nama_anggota'  => $this->nama_anggota,
                'hubungan'      => $this->hubungan,
                'hubungan_lain' => $this->hubungan_lain,
                'jenis_kelamin' => $this->jenis_kelamin,
                'tempat_lahir'  => $this->tempat_lahir,
                'tgl_lahir'     => $this->tgl_lahir,
                'pekerjaan'     => $this->pekerjaan,
                'status_hidup'  => $this->status_hidup,
                'ditanggung'    => $this->ditanggung,
                'alamat'        => $this->alamat,
                'no_hp'         => $plainHp,
                'keterangan'    => $this->keterangan,
                'updated_by'    => Auth::id(),
            ];

            if (!$this->isEdit) {
                $data['created_by'] = Auth::id();
            }

            if ($this->isEdit && $this->keluarga_id) {
                // Use the stored keluarga_id for updating
                KaryawanKeluarga::findOrFail($this->keluarga_id)->update($data);
                $this->dispatch('toast', [
                    'message' => "Data berhasil diedit",
                    'type' => 'success',
                ]);
            } else {
                KaryawanKeluarga::create($data);
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
            $this->authorizeDelete();
            
            $data = KaryawanKeluarga::findOrFail($this->deleteId);

            // Hapus record dari database
            $data->delete();

            $this->deleteSuccess = true;

            // Tutup modal via Alpine
            $this->dispatch('modal:success');

            // Optional: flash message atau toast
            $this->dispatch('toast', [
                'message' => 'Data Keluarga berhasil dihapus.',
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
        $this->selectedKeluarga = KaryawanKeluarga::with(['karyawan','creator', 'updater'])
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
        $this->keluarga_id   = null; // Reset the education record ID
        // karyawan_id jangan direset (biar tetap nempel)
        $this->nama_anggota   = '';
        $this->hubungan       = '';
        $this->hubungan_lain  = null;
        $this->jenis_kelamin  = '';
        $this->tempat_lahir   = '';
        $this->tgl_lahir      = '';
        $this->pekerjaan      = null;
        $this->status_hidup   = '';
        $this->ditanggung     = '';
        $this->alamat         = null;
        $this->no_hp          = null;
        $this->keterangan     = null;
        $this->resetValidation();
    }

    public function render()
    {
        $query = KaryawanKeluarga::with([
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
                $q->where('nama_anggota', 'like', $search)
                  ->orWhere('hubungan', 'like', $search)
                  ->orWhere('no_hp', 'like', $search);
            });
        });

        // urutkan & paginasi
        $keluargas = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.karyawan.tab.keluarga.index', compact('keluargas'));
    }
}
