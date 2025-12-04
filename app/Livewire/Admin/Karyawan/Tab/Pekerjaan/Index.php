<?php

namespace App\Livewire\Admin\Karyawan\Tab\Pekerjaan;

use App\Models\Employee\KaryawanPekerjaan;
use App\Traits\HasTabPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;
    use HasTabPermission;

    // Main properties
    public $karyawan_id; // This should hold the employee ID
    public $pekerjaan_id; // Add this to store the organisasi record ID when editing
    
    // Form fields
    public $nama_instansi = '';
    public $departemen = '';
    public $jabatan = '';
    public $lokasi_pekerjaan = '';
    public $bidang_industri = '';
    public $jenis_kontrak = null;
    public $tgl_awal = null;
    public $tgl_akhir = null;
    public $status_kerja = null;
    public $gaji_awal = null;
    public $gaji_akhir = null;
    public $mata_uang = null;
    public $peran = null;
    public $alasan_berhenti = null;

    // Properties for search and filter
    public $search = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $showModalDetail = false;
    public $selectedPekerjaan; // Consider renaming to $selectedPekerjaan

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
            'nama_instansi'     => 'required|string|max:255',
            'departemen'        => 'required|string|max:255',
            'jabatan'           => 'required|string|max:255',
            'tgl_awal'          => 'required|date',
            'tgl_akhir'         => 'nullable|date|after_or_equal:tgl_awal',
            'jenis_kontrak'     => 'required|in:kontrak,tetap,magang,freelance,konsultan,paruh_waktu,harian,borongan,lainnya',
            'lokasi_pekerjaan'  => 'required|string|max:255',
            'bidang_industri'   => 'required|string|max:255',
            'status_kerja'      => 'required|in:aktif,selesai,resign,phk,mutasi,pensiun',
            'gaji_awal'         => 'nullable|numeric|min:0|max:999999999999.99',
            'gaji_akhir'        => 'nullable|numeric|min:0|max:999999999999.99',
            'mata_uang'         => 'nullable|string|max:255',
            'peran'             => 'required|string|max:255',
            'alasan_berhenti'   => 'nullable|string',
        ];

        return $rules;
    }

    protected $validationAttributes = [
        'tgl_awal' => 'tanggal efektif',
        'tgl_akhir' => 'tanggal berakhir',
        'jenis_kontrak' => 'jenis kontrak',
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
        $pekerjaan = KaryawanPekerjaan::findOrFail($id);

        // Store the education record ID for editing
        $this->pekerjaan_id = $id;
        
        // Keep the employee ID intact
        $this->karyawan_id = $pekerjaan->karyawan_id;
        $this->nama_instansi = $pekerjaan->nama_instansi;
        $this->departemen = $pekerjaan->departemen;
        $this->jabatan = $pekerjaan->jabatan;
        $this->lokasi_pekerjaan = $pekerjaan->lokasi_pekerjaan;
        $this->bidang_industri = $pekerjaan->bidang_industri;
        $this->jenis_kontrak = $pekerjaan->jenis_kontrak;
        $this->tgl_awal = optional($pekerjaan->tgl_awal)->format('Y-m-d');
        $this->tgl_akhir = optional($pekerjaan->tgl_awal)->format('Y-m-d');
        $this->status_kerja = $pekerjaan->status_kerja;
        $this->gaji_awal = $pekerjaan->gaji_awal;
        $this->gaji_akhir = $pekerjaan->gaji_akhir;
        $this->mata_uang = $pekerjaan->mata_uang;
        $this->peran = $pekerjaan->peran;
        $this->alasan_berhenti = $pekerjaan->alasan_berhenti;
        
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
                'karyawan_id' => $this->karyawan_id,
                'nama_instansi' => $this->nama_instansi,
                'departemen' => $this->departemen,
                'jabatan' => $this->jabatan,
                'lokasi_pekerjaan' => $this->lokasi_pekerjaan,
                'bidang_industri' => $this->bidang_industri,
                'jenis_kontrak' => $this->jenis_kontrak,
                'tgl_awal' => $this->tgl_awal,
                'tgl_akhir' => $this->tgl_akhir,
                'status_kerja' => $this->status_kerja,
                'gaji_awal' => $this->gaji_awal,
                'gaji_akhir' => $this->gaji_akhir,
                'mata_uang' => $this->mata_uang,
                'peran' => $this->peran,
                'alasan_berhenti' => $this->alasan_berhenti,
                'updated_by' => Auth::id(),
            ];

            if (!$this->isEdit) {
                $data['created_by'] = Auth::id();
            }

            if ($this->isEdit && $this->pekerjaan_id) {
                // Use the stored pekerjaan_id for updating
                KaryawanPekerjaan::findOrFail($this->pekerjaan_id)->update($data);
                $this->dispatch('toast', [
                    'message' => "Data berhasil diedit",
                    'type' => 'success',
                ]);
            } else {
                KaryawanPekerjaan::create($data);
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
            $data = KaryawanPekerjaan::findOrFail($this->deleteId);
            // Hapus record dari database
            $data->delete();

            $this->deleteSuccess = true;

            // Tutup modal via Alpine
            $this->dispatch('modal:success');

            // Optional: flash message atau toast
            $this->dispatch('toast', [
                'message' => 'Data Pekerjaan berhasil dihapus.',
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
        $this->selectedPekerjaan = KaryawanPekerjaan::with(['karyawan','creator', 'updater'])
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
        $this->pekerjaan_id   = null; // Reset the education record ID
        // karyawan_id jangan direset (biar tetap nempel)
        $this->nama_instansi      = '';
        $this->departemen         = '';
        $this->jabatan            = null;
        $this->lokasi_pekerjaan   = null;
        $this->bidang_industri    = null;
        $this->jenis_kontrak      = null;
        $this->tgl_awal           = null;
        $this->tgl_akhir          = null;
        $this->status_kerja       = null;
        $this->gaji_awal          = null;
        $this->gaji_akhir         = null;
        $this->mata_uang          = null;
        $this->peran              = null;
        $this->alasan_berhenti    = null;

        $this->resetValidation();
    }


    public function render()
    {
        $query = KaryawanPekerjaan::with([
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
                $q->where('nama_instansi', 'like', $search)
                  ->orWhere('departemen', 'like', $search)
                  ->orWhere('jabatan', 'like', $search);
            });
        });

        // urutkan & paginasi
        $pekerjaans = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.karyawan.tab.pekerjaan.index', compact('pekerjaans'));
    }
}