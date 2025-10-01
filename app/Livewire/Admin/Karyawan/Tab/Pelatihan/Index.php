<?php

namespace App\Livewire\Admin\Karyawan\Tab\Pelatihan;

use App\Models\Employee\KaryawanPelatihan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    // Main properties
    public $karyawan_id; // This should hold the employee ID
    public $pelatihan_id; // Add this to store the pelatihan record ID when editing
    public $document;
    public $document_path = '';
    
    // Form fields
    public $nama_pelatihan = '';
    public $penyelenggara = '';
    public $lokasi = null;
    public $tgl_mulai = '';
    public $tgl_selesai = null;
    public $jenis_pelatihan = null;
    public bool $sertifikat_diperoleh = false;
    public $keterangan = null;
    

    // Properties for search and filter
    public $search = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $showModalDetail = false;
    public $selectedPelatihan; // Consider renaming to $selectedPelatihan

    #[Url]
    public string $query = '';

    // Set default URL param supaya reset saat refresh
    #[Url(except: 'id')]
    public string $sortField = 'id';

    #[Url(except: 'desc')]
    public string $sortDirection = 'desc';

    public bool $showDeleted = false;

    // Initialize education levels
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
            'nama_pelatihan'      => 'required|string|max:255',
            'penyelenggara'       => 'required|string|max:255',
            'lokasi'              => 'required|string|max:255',
            'tgl_mulai'           => 'required|date|before_or_equal:today',
            'tgl_selesai'         => 'required|date|after:tgl_mulai',
            'jenis_pelatihan'     => 'required|in:internal,eksternal,online,offline,hybrid',
            'sertifikat_diperoleh'=> 'boolean',
            'keterangan'          => 'nullable|string|max:255',
        ];

        if ($this->document) {
            $rules['document'] = 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'; // max 5MB
        }

        return $rules;
    }

    protected $validationAttributes = [
        'tgl_mulai' => 'tanggal mulai',
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
        $sertifikasi = KaryawanPelatihan::findOrFail($id);

        // Store the education record ID for editing
        $this->pelatihan_id = $id;
        
        // Keep the employee ID intact
        $this->karyawan_id = $sertifikasi->karyawan_id;
        $this->nama_pelatihan = $sertifikasi->nama_pelatihan;
        $this->penyelenggara = $sertifikasi->penyelenggara;
        $this->lokasi = $sertifikasi->lokasi;
        $this->tgl_mulai = optional($sertifikasi->tgl_mulai)->format('Y-m-d');
        $this->tgl_selesai = optional($sertifikasi->tgl_selesai)->format('Y-m-d');
        $this->jenis_pelatihan = $sertifikasi->jenis_pelatihan;
        $this->keterangan = $sertifikasi->keterangan;
        $this->document_path = $sertifikasi->document_path;
        
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
                'nama_pelatihan'        => $this->nama_pelatihan,
                'penyelenggara'         => $this->penyelenggara,
                'lokasi'                => $this->lokasi,
                'tgl_mulai'             => $this->tgl_mulai,
                'tgl_selesai'           => $this->tgl_selesai,
                'jenis_pelatihan'       => $this->jenis_pelatihan,
                'sertifikat_diperoleh'  => $this->sertifikat_diperoleh,
                'keterangan'            => $this->keterangan,
                'updated_by'            => Auth::id(),
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
                    $path = $this->document->storeAs('documents/pelatihan', $filename, 'public');
                    
                    // Set the path in data array
                    $data['document_path'] = $path;
                    
                    // Delete old file if editing
                    if ($this->isEdit && $this->pelatihan_id) {
                        $oldRecord = KaryawanPelatihan::find($this->pelatihan_id);
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
                        $oldRecord = KaryawanPelatihan::find($this->pelatihan_id);
                        if ($oldRecord && $oldRecord->document_path) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldRecord->document_path);
                        }
                        $data['document_path'] = null;
                    }
                }
            }

            if ($this->isEdit && $this->pelatihan_id) {
                // Use the stored pelatihan_id for updating
                KaryawanPelatihan::findOrFail($this->pelatihan_id)->update($data);
                $this->dispatch('toast', [
                    'message' => "Data berhasil diedit",
                    'type' => 'success',
                ]);
            } else {
                KaryawanPelatihan::create($data);
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
            $data = KaryawanPelatihan::findOrFail($this->deleteId);

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
                'message' => 'Data Pelatihan berhasil dihapus.',
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
        $this->selectedPelatihan = KaryawanPelatihan::with(['karyawan', 'creator', 'updater'])
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
        $this->pelatihan_id   = null; // Reset the education record ID
        // karyawan_id jangan direset (biar tetap nempel)
        $this->nama_pelatihan           = '';
        $this->penyelenggara            = '';
        $this->lokasi                   = null;
        $this->tgl_mulai                = '';
        $this->tgl_selesai              = null;
        $this->jenis_pelatihan          = null;
        $this->keterangan               = null;
        $this->sertifikat_diperoleh     = '';
        $this->document                 = null;
        $this->document_path            = '';
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
        $query = KaryawanPelatihan::with([
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
                $q->where('nama_pelatihan', 'like', $search)
                  ->orWhere('penyelenggara', 'like', $search)
                  ->orWhere('jenis_pelatihan', 'like', $search);
            });
        });

        // urutkan & paginasi
        $pelatihans = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.karyawan.tab.pelatihan.index', compact('pelatihans'));
    }
}
