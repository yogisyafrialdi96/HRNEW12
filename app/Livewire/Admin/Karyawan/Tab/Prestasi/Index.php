<?php

namespace App\Livewire\Admin\Karyawan\Tab\Prestasi;

use App\Models\Employee\KaryawanPrestasi;
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
    public $prestasi_id; // Add this to store the prestasi record ID when editing
    public $document;
    public $document_path = '';
    
    // Form fields
    public $nama_prestasi = '';
    public $tingkat = '';
    public $peringkat = '';
    public $kategori = null;
    public $penyelenggara = null;
    public $tanggal = null;
    public $lokasi = null;
    public $keterangan = null;

    // Properties for search and filter
    public $search = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $showModalDetail = false;
    public $selectedPrestasi; // Consider renaming to $selectedPrestasi

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
            'nama_prestasi' => [
                'required',
                'string',
                'max:255',
                'min:3'
            ],
            'tingkat' => [
                'required',
                'in:lokal,regional,nasional,internasional'
            ],
            'peringkat' => [
                'required',
                'in:juara_1,juara_2,juara_3,harapan_1,harapan_2,harapan_3,partisipasi,nominasi'
            ],
            'kategori' => [
                'required',
                'in:individu,tim,organisasi'
            ],
            'penyelenggara' => [
                'required',
                'string',
                'max:255',
                'min:3'
            ],
            'tanggal' => [
                'required',
                'date',
            ],
            'lokasi' => [
                'required',
                'string',
                'max:255'
            ],
            'keterangan' => [
                'nullable',
                'string',
                'max:1000'
            ]
        ];

        if ($this->document) {
            $rules['document'] = 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'; // max 5MB
        }

        return $rules;
    }

    protected $validationAttributes = [
        'document_path' => 'document',
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
        $prestasi = KaryawanPrestasi::findOrFail($id);

        // Store the education record ID for editing
        $this->prestasi_id = $id;
        
        // Keep the employee ID intact
        $this->karyawan_id = $prestasi->karyawan_id;
        $this->nama_prestasi = $prestasi->nama_prestasi;
        $this->tingkat = $prestasi->tingkat;
        $this->peringkat = $prestasi->peringkat;
        $this->kategori = $prestasi->kategori;
        $this->penyelenggara = $prestasi->penyelenggara;
        $this->tanggal = optional($prestasi->tanggal)->format('Y-m-d');
        $this->lokasi = $prestasi->lokasi;
        $this->keterangan = $prestasi->keterangan;
        $this->document_path = $prestasi->document_path; // ✅ Ini string path
        $this->document = null; // ✅ biarkan null, nanti diisi kalau user upload ulang
        
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
                'nama_prestasi'   => $this->nama_prestasi,
                'tingkat'         => $this->tingkat,
                'peringkat'       => $this->peringkat,
                'kategori'        => $this->kategori,
                'tanggal'         => $this->tanggal,
                'lokasi'          => $this->lokasi,
                'penyelenggara'   => $this->penyelenggara,
                'keterangan'      => $this->keterangan,
                'updated_by'      => Auth::id(),
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
                    $path = $this->document->storeAs('documents/prestasi', $filename, 'public');
                    
                    // Set the path in data array
                    $data['document_path'] = $path;
                    
                    // Delete old file if editing
                    if ($this->isEdit && $this->prestasi_id) {
                        $oldRecord = KaryawanPrestasi::find($this->prestasi_id);
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
                        $oldRecord = KaryawanPrestasi::find($this->prestasi_id);
                        if ($oldRecord && $oldRecord->document_path) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldRecord->document_path);
                        }
                        $data['document_path'] = null;
                    }
                }
            }

            if ($this->isEdit && $this->prestasi_id) {
                // Use the stored prestasi_id for updating
                KaryawanPrestasi::findOrFail($this->prestasi_id)->update($data);
                $this->dispatch('toast', [
                    'message' => "Data berhasil diedit",
                    'type' => 'success',
                ]);
            } else {
                KaryawanPrestasi::create($data);
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
            $data = KaryawanPrestasi::findOrFail($this->deleteId);

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
                'message' => 'Data Prestasi berhasil dihapus.',
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
        $this->selectedPrestasi = KaryawanPrestasi::with(['karyawan', 'creator', 'updater'])
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
        $this->prestasi_id   = null; // Reset the education record ID
        // karyawan_id jangan direset (biar tetap nempel)
        $this->nama_prestasi    = '';
        $this->tingkat          = '';
        $this->peringkat        = null;
        $this->kategori         = '';
        $this->penyelenggara    = null;
        $this->tanggal          = null;
        $this->lokasi           = null;
        $this->keterangan       = '';
        $this->document         = null;
        $this->document_path    = '';
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
        $query = KaryawanPrestasi::with([
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
                $q->where('nama_prestasi', 'like', $search)
                  ->orWhere('tingkat', 'like', $search)
                  ->orWhere('peringkat', 'like', $search);
            });
        });

        // urutkan & paginasi
        $prestasis = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.karyawan.tab.prestasi.index', compact('prestasis'));
    }
}