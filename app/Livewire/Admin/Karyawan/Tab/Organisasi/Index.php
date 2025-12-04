<?php

namespace App\Livewire\Admin\Karyawan\Tab\Organisasi;

use App\Models\Employee\KaryawanOrganisasi;
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
    public $organisasi_id; // Add this to store the organisasi record ID when editing
    public $document;
    public $document_path = '';
    
    // Form fields
    public $organisasi = '';
    public $level = '';
    public $jabatan = '';
    public $tgl_awal = '';
    public $tgl_akhir = null;
    public $status_organisasi = '';
    public $peran = null;
    public $jenis_organisasi = null;
    public $jenisorg_lain = '';
    public $alamat_organisasi = null;
    public $website = null;
    public $email_organisasi = null;
    public $catatan = null;

    // Properties for search and filter
    public $search = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $showModalDetail = false;
    public $selectedOrganisasi; // Consider renaming to $selectedOrganisasi

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
            'organisasi'        => 'required|string|max:255',
            'level'             => 'required|in:sekolah,fakultas,universitas,nasional,regional,lokal,internasional',
            'jabatan'           => 'required|string|max:255',
            'tgl_awal'          => 'required|date',
            'tgl_akhir'         => 'nullable|date|after_or_equal:tgl_awal',
            'status_organisasi' => 'required|in:aktif,tidak_aktif,alumni,pensiun',
            'peran'             => 'required|string|max:255',
            'jenis_organisasi'  => 'required|in:profesi,kemasyarakatan,keagamaan,politik,pendidikan,sosial,ekonomi,budaya,olahraga,lainnya',
            'jenisorg_lain'     => 'nullable|string|max:255',
            'alamat_organisasi' => 'nullable|string|max:255',
            'website'           => 'nullable|url|max:255',
            'email_organisasi'  => 'nullable|email|max:255',
            'catatan'           => 'nullable|string',
            'document'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        return $rules;
    }

    protected $validationAttributes = [
        'tgl_awal' => 'tanggal efektif',
        'tgl_akhir' => 'tanggal berakhir',
        'status_organisasi' => 'status organisasi',
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
        $organisasi = KaryawanOrganisasi::findOrFail($id);

        // Store the education record ID for editing
        $this->organisasi_id = $id;
        
        // Keep the employee ID intact
        $this->karyawan_id = $organisasi->karyawan_id;
        $this->organisasi = $organisasi->organisasi;
        $this->level = $organisasi->level;
        $this->jabatan = $organisasi->jabatan;
        $this->tgl_awal = $organisasi->tgl_awal;
        $this->tgl_akhir = $organisasi->tgl_akhir;
        $this->status_organisasi = $organisasi->status_organisasi;
        $this->peran = $organisasi->peran;
        $this->jenis_organisasi = $organisasi->jenis_organisasi;
        $this->jenisorg_lain = $organisasi->jenisorg_lain;
        $this->alamat_organisasi = $organisasi->alamat_organisasi;
        $this->website = $organisasi->website;
        $this->email_organisasi = $organisasi->email_organisasi;
        $this->document_path = $organisasi->document_path;
        $this->catatan = $organisasi->catatan;
        
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
                'organisasi' => $this->organisasi,
                'level' => $this->level,
                'jabatan' => $this->jabatan,
                'tgl_awal' => $this->tgl_awal,
                'tgl_akhir' => $this->tgl_akhir,
                'status_organisasi' => $this->status_organisasi,
                'peran' => $this->peran,
                'jenis_organisasi' => $this->jenis_organisasi,
                'jenisorg_lain' => $this->jenisorg_lain,
                'alamat_organisasi' => $this->alamat_organisasi,
                'website' => $this->website,
                'email_organisasi' => $this->email_organisasi,
                'catatan' => $this->catatan,
                'updated_by' => Auth::id(),
            ];

            if (!$this->isEdit) {
                $data['created_by'] = Auth::id();
            }

            // Handle file upload if exists
            if ($this->document) {
                try {
                    // Generate unique filename
                    $filename = time() . '_' . $this->document->getClientOriginalName();
                    
                    // Store file in public disk
                    $path = $this->document->storeAs('documents/organisasi', $filename, 'public');
                    
                    // Set the path in data array
                    $data['document_path'] = $path;
                    
                    // Delete old file if editing
                    if ($this->isEdit && $this->organisasi_id) {
                        $oldRecord = KaryawanOrganisasi::find($this->organisasi_id);
                        if ($oldRecord && $oldRecord->document_path) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldRecord->document_path);
                        }
                    }
                } catch (\Exception $e) {
                    $this->dispatch('toast', [
                        'message' => 'Gagal mengupload file: ' . $e->getMessage(),
                        'type' => 'error',
                    ]);
                    return;
                }
            } else {
                // If editing and no new document uploaded
                if ($this->isEdit) {
                    if ($this->document_path) {
                        // Keep existing document path
                        $data['document_path'] = $this->document_path;
                    } else {
                        // Document was removed, delete old file and set null
                        $oldRecord = KaryawanOrganisasi::find($this->organisasi_id);
                        if ($oldRecord && $oldRecord->document_path) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldRecord->document_path);
                        }
                        $data['document_path'] = null;
                    }
                }
            }

            if ($this->isEdit && $this->organisasi_id) {
                // Use the stored organisasi_id for updating
                KaryawanOrganisasi::findOrFail($this->organisasi_id)->update($data);
                $this->dispatch('toast', [
                    'message' => "Data berhasil diedit",
                    'type' => 'success',
                ]);
            } else {
                KaryawanOrganisasi::create($data);
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
            $data = KaryawanOrganisasi::findOrFail($this->deleteId);

            // Hapus file dari storage jika ada
            if ($data->document_path && Storage::disk('public')->exists($data->document_path)) {
                Storage::disk('public')->delete($data->document_path);
            }

            // Hapus record dari database
            $data->delete();

            $this->deleteSuccess = true;

            // Tutup modal via Alpine
            $this->dispatch('modal:success');

            // Optional: flash message atau toast
            $this->dispatch('toast', [
                'message' => 'Data Organisasi berhasil dihapus.',
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
        $this->selectedOrganisasi = KaryawanOrganisasi::with(['karyawan','creator', 'updater'])
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
        $this->organisasi_id   = null; // Reset the education record ID
        // karyawan_id jangan direset (biar tetap nempel)
        $this->organisasi         = '';
        $this->level              = '';
        $this->jabatan            = null;
        $this->tgl_awal           = null;
        $this->tgl_akhir          = null;
        $this->status_organisasi  = null;
        $this->peran              = null;
        $this->jenis_organisasi   = null;
        $this->jenisorg_lain      = null;
        $this->website            = null;
        $this->email_organisasi   = null;
        $this->document           = null;
        $this->document_path      = '';
        $this->resetValidation();
    }


    public function removeExistingDocument()
    {
        $this->document_path = '';
        
        $this->dispatch('toast', [
            'message' => "Dokumen akan dihapus saat menyimpan data",
            'type' => 'info',
        ]);
    }

    public function render()
    {
        $query = KaryawanOrganisasi::with([
            'karyawan:id,full_name',
            'creator:id,name',
            'updater:id,name'
        ]);

        // Filter by employee if karyawan_id is set
        if ($this->karyawan_id) {
            $query->where('karyawan_id', $this->karyawan_id);
        }

        // tampilkan data terhapus jika perlu
        $query->when($this->showDeleted, function ($q) {
            $q->onlyTrashed(); // hanya data yang sudah dihapus
        });

        // pencarian
        $query->when($this->search, function ($q) {
            $search = '%' . $this->search . '%';
            $q->where(function ($q) use ($search) {
                $q->where('organisasi', 'like', $search)
                  ->orWhere('level', 'like', $search)
                  ->orWhere('jabatan', 'like', $search);
            });
        });

        // urutkan & paginasi
        $organisasis = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.karyawan.tab.organisasi.index', compact('organisasis'));
    }
}
