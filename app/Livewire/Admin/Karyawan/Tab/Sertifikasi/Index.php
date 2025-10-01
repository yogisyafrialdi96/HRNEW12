<?php

namespace App\Livewire\Admin\Karyawan\Tab\Sertifikasi;

use App\Models\Employee\KaryawanSertifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
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
    public $sertifikasi_id; // Add this to store the sertifikasi record ID when editing
    public $document;
    public $document_path = '';
    
    // Form fields
    public $nama_sertifikasi = '';
    public $lembaga_penerbit = '';
    public $nomor_sertifikat = null;
    public $tgl_terbit = '';
    public $tgl_kadaluwarsa = null;
    public $masa_berlaku_tahun = null;
    public $biaya_sertifikasi = null;
    public $metode_pembelajaran = null;
    public $durasi_jam = null;
    public bool $wajib_perpanjang = false;
    public $jenis_sertifikasi = '';
    public $tingkat = null;
    public $status_sertifikat = '';
    public $keterangan = null;

    // Properties for search and filter
    public $search = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $showModalDetail = false;
    public $selectedSertifikasi; // Consider renaming to $selectedSertifikasi

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
            'nama_sertifikasi'      => 'required|string|max:255',
            'lembaga_penerbit'      => 'required|in:' . implode(',', array_keys(KaryawanSertifikasi::LEMBAGA_POPULER)),
            'nomor_sertifikat' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('karyawan_sertifikasi', 'nomor_sertifikat')->ignore($this->sertifikasi_id, 'id')
            ],
            'tgl_terbit'            => 'required|date|before_or_equal:today',
            'tgl_kadaluwarsa'       => 'nullable|date|after:tgl_terbit',
            'biaya_sertifikasi'     => 'nullable|numeric|min:0|max:999999999999.99',
            'metode_pembelajaran'   => 'nullable|in:Online,Offline,Blended',
            'durasi_jam'            => 'nullable|numeric',
            'wajib_perpanjang'      => 'nullable',
            'jenis_sertifikasi'     => 'required|in:' . implode(',', array_keys(KaryawanSertifikasi::JENIS_SERTIFIKASI)),
            'tingkat'               => 'required|in:' . implode(',', array_keys(KaryawanSertifikasi::TINGKAT_SERTIFIKASI)),
            'status_sertifikat'     => 'required|in:' . implode(',', array_keys(KaryawanSertifikasi::STATUS_SERTIFIKASI)),
            'keterangan'            => 'nullable|string',
        ];

        if ($this->document) {
            $rules['document'] = 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'; // max 5MB
        }

        return $rules;
    }

    protected $validationAttributes = [
        'tgl_terbit' => 'tanggal terbit',
        'tgl_kadaluwarsa' => 'tanggal kadaluwarsa',
        'masa_berlaku_tahun' => 'masa berlaku',
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
        $sertifikasi = KaryawanSertifikasi::findOrFail($id);

        // Store the education record ID for editing
        $this->sertifikasi_id = $id;
        
        // Keep the employee ID intact
        $this->karyawan_id = $sertifikasi->karyawan_id;
        $this->nama_sertifikasi = $sertifikasi->nama_sertifikasi;
        $this->lembaga_penerbit = $sertifikasi->lembaga_penerbit;
        $this->nomor_sertifikat = $sertifikasi->nomor_sertifikat;
        $this->tgl_terbit = optional($sertifikasi->tgl_terbit)->format('Y-m-d');
        $this->tgl_kadaluwarsa = optional($sertifikasi->tgl_kadaluwarsa)->format('Y-m-d');
        $this->masa_berlaku_tahun = $sertifikasi->masa_berlaku_tahun;
        $this->biaya_sertifikasi = $sertifikasi->biaya_sertifikasi;
        $this->metode_pembelajaran = $sertifikasi->metode_pembelajaran;
        $this->durasi_jam = $sertifikasi->durasi_jam;
        $this->wajib_perpanjang = $sertifikasi->wajib_perpanjang;
        $this->jenis_sertifikasi = $sertifikasi->jenis_sertifikasi;
        $this->tingkat = $sertifikasi->tingkat;
        $this->status_sertifikat = $sertifikasi->status_sertifikat;
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
                'nama_sertifikasi'      => $this->nama_sertifikasi,
                'lembaga_penerbit'      => $this->lembaga_penerbit,
                'nomor_sertifikat'      => $this->nomor_sertifikat,
                'tgl_terbit'            => $this->tgl_terbit,
                'tgl_kadaluwarsa'       => $this->tgl_kadaluwarsa,
                'masa_berlaku_tahun'    => $this->masa_berlaku_tahun,
                'biaya_sertifikasi'     => $this->biaya_sertifikasi,
                'metode_pembelajaran'   => $this->metode_pembelajaran,
                'durasi_jam'            => $this->durasi_jam,
                'wajib_perpanjang'      => $this->wajib_perpanjang,
                'jenis_sertifikasi'     => $this->jenis_sertifikasi,
                'tingkat'               => $this->tingkat,
                'status_sertifikat'     => $this->status_sertifikat,
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
                    $path = $this->document->storeAs('documents/sertifikat', $filename, 'public');
                    
                    // Set the path in data array
                    $data['document_path'] = $path;
                    
                    // Delete old file if editing
                    if ($this->isEdit && $this->sertifikasi_id) {
                        $oldRecord = KaryawanSertifikasi::find($this->sertifikasi_id);
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
                        $oldRecord = KaryawanSertifikasi::find($this->sertifikasi_id);
                        if ($oldRecord && $oldRecord->document_path) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldRecord->document_path);
                        }
                        $data['document_path'] = null;
                    }
                }
            }

            if ($this->isEdit && $this->sertifikasi_id) {
                // Use the stored sertifikasi_id for updating
                KaryawanSertifikasi::findOrFail($this->sertifikasi_id)->update($data);
                $this->dispatch('toast', [
                    'message' => "Data berhasil diedit",
                    'type' => 'success',
                ]);
            } else {
                KaryawanSertifikasi::create($data);
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
            $data = KaryawanSertifikasi::findOrFail($this->deleteId);

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
                'message' => 'Data Sertifikasi berhasil dihapus.',
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
        $this->selectedSertifikasi = KaryawanSertifikasi::with(['karyawan', 'creator', 'updater'])
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
        $this->sertifikasi_id   = null; // Reset the education record ID
        // karyawan_id jangan direset (biar tetap nempel)
        $this->nama_sertifikasi     = '';
        $this->lembaga_penerbit     = '';
        $this->nomor_sertifikat     = null;
        $this->tgl_terbit           = null;
        $this->tgl_kadaluwarsa      = null;
        $this->masa_berlaku_tahun   = null;
        $this->biaya_sertifikasi    = null;
        $this->metode_pembelajaran  = null;
        $this->durasi_jam           = null;
        $this->wajib_perpanjang     = '';
        $this->jenis_sertifikasi    = null;
        $this->tingkat              = '';
        $this->status_sertifikat    = null;
        $this->keterangan           = null;
        $this->document             = null;
        $this->document_path        = '';
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
        $query = KaryawanSertifikasi::with([
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
                $q->where('nama_sertifikasi', 'like', $search)
                  ->orWhere('lembaga_penerbit', 'like', $search)
                  ->orWhere('nomor_sertifikat', 'like', $search);
            });
        });

        // urutkan & paginasi
        $sertifikasis = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.karyawan.tab.sertifikasi.index', compact('sertifikasis'));
    }
}