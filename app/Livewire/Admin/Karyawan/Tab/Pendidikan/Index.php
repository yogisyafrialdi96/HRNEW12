<?php

namespace App\Livewire\Admin\Karyawan\Tab\Pendidikan;

use App\Models\Employee\KaryawanPendidikan;
use App\Models\Master\EducationLevel;
use App\Traits\HasTabPermission;
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
    use HasTabPermission;

    // Main properties
    public $karyawan_id; // This should hold the employee ID
    public $pendidikan_id; // Add this to store the education record ID when editing
    public $document;
    public $document_path = '';
    public $educationLevels = [];
    
    // Form fields
    public $education_level_id = '';
    public $nama_institusi = '';
    public $fakultas = null;
    public $jurusan = null;
    public $gelar = null;
    public $ipk = null;
    public $skala_ipk = null;
    public $judul_skripsi = null;
    public $tanggal_ijazah = null;
    public $nomor_ijazah = null;
    public $jenis_belajar = '';
    public $akreditasi = null;
    public $is_current = false;
    public $spesialisasi = null;
    public $sumber_dana = null;
    public $nama_beasiswa = null;
    public $jenis_institusi = null;
    public $status = '';
    public $tahun_mulai = '';
    public $tahun_selesai = null;
    public $negara = '';
    public $kota = '';
    public $ket = '';

    // Properties for search and filter
    public $search = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $showModalDetail = false;
    public $selectedPendidikan; // Consider renaming to $selectedPendidikan

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
        // Authorize view access
        $this->authorizeView();

        // Set the employee ID from the parent component
        if ($karyawan) {
            $this->karyawan_id = $karyawan->id ?? $karyawan;
        }

        $this->educationLevels = EducationLevel::query()
            ->where('is_active', 1)
            ->where('is_formal', 1)
            ->orderBy('level_order')
            ->get()
            ->toArray();
    }

    // Regular methods instead of computed properties
    public function getSelectedLevelCode()
    {
        if (!$this->education_level_id) {
            return null;
        }

        $level = collect($this->educationLevels)->firstWhere('id', $this->education_level_id);
        return $level ? $level['level_code'] : null;
    }

    public function showSkripsiField()
    {
        $levelCode = $this->getSelectedLevelCode();
        return in_array($levelCode, ['D1', 'D2', 'D3', 'S1', 'S2', 'S3']);
    }

    public function showJurusanField()
    {
        $levelCode = $this->getSelectedLevelCode();
        return in_array($levelCode, ['SMK', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3']);
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
            'education_level_id' => 'required|exists:master_educationlevel,id',
            'nama_institusi'     => 'required|string|max:255',
            'jenis_institusi'    => 'required|in:negeri,swasta,internasional',
            'status'             => 'required|in:completed,ongoing,droped_out,transferred',
            'akreditasi'         => 'required|in:A,B,C,unaccredited,international',
            'jenis_belajar'      => 'required|in:full_time,part_time,distance,online',
            'tahun_mulai'        => 'required|digits:4',
            'negara'             => 'required|string',
            'kota'               => 'required|string',
            'sumber_dana'        => 'required|in:pribadi,beasiswa,perusahaan,pemerintah',
        ];

        if ($this->showJurusanField()) {
            $rules['jurusan'] = 'required|string|max:255';
        }

        if ($this->showSkripsiField()) {
            $rules = array_merge($rules, [
                'fakultas'       => 'required|string|max:255',
                'sumber_dana'    => 'required|in:pribadi,beasiswa,perusahaan,pemerintah',
            ]);

            if ($this->sumber_dana === 'beasiswa') {
                $rules['nama_beasiswa'] = 'required|string|max:255';
            }
            
            if ($this->status === 'completed') {
                $rules = array_merge($rules, [
                    'gelar'          => 'required|string|max:255',
                    'judul_skripsi'  => 'required|string|max:255',
                    'ipk'            => 'required|numeric|between:0,4.00',
                    'skala_ipk'      => 'required|numeric|between:1,4.00',
                ]);
            }
        }

        if (in_array($this->status, ['completed', 'droped_out', 'transferred'])) {
            $rules['tahun_selesai'] = 'required|digits:4|gte:tahun_mulai';
        }

        if ($this->status === 'completed') {
            $rules['tanggal_ijazah'] = 'required|date';
            $rules['nomor_ijazah'] = 'required|string|max:50';
        }

        if ($this->document) {
            $rules['document'] = 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'; // max 5MB
        }

        return $rules;
    }

    protected $validationAttributes = [
        'education_level_id' => 'Education Level',
        'nama_institusi' => 'Nama Institusi',
        'jenis_institusi' => 'Jenis Institusi',
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
        $pendidikan = KaryawanPendidikan::findOrFail($id);

        // Store the education record ID for editing
        $this->pendidikan_id = $id;
        
        // Keep the employee ID intact
        $this->karyawan_id = $pendidikan->karyawan_id;
        $this->education_level_id = $pendidikan->education_level_id;
        $this->nama_institusi = $pendidikan->nama_institusi;
        $this->jenis_institusi = $pendidikan->jenis_institusi;
        $this->fakultas = $pendidikan->fakultas;
        $this->jurusan = $pendidikan->jurusan;
        $this->spesialisasi = $pendidikan->spesialisasi;
        $this->gelar = $pendidikan->gelar;
        $this->ipk = $pendidikan->ipk;
        $this->skala_ipk = $pendidikan->skala_ipk;
        $this->judul_skripsi = $pendidikan->judul_skripsi;
        $this->tanggal_ijazah = $pendidikan->tanggal_ijazah;
        $this->nomor_ijazah = $pendidikan->nomor_ijazah;
        $this->jenis_belajar = $pendidikan->jenis_belajar;
        $this->akreditasi = $pendidikan->akreditasi;
        $this->is_current = $pendidikan->is_current;
        $this->sumber_dana = $pendidikan->sumber_dana;
        $this->nama_beasiswa = $pendidikan->nama_beasiswa;
        $this->status = $pendidikan->status;
        $this->tahun_mulai = $pendidikan->tahun_mulai;
        $this->tahun_selesai = $pendidikan->tahun_selesai;
        $this->negara = $pendidikan->negara;
        $this->kota = $pendidikan->kota;
        $this->ket = $pendidikan->ket;
        $this->document_path = $pendidikan->document_path;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        try {
            // Check authorization based on create/edit
            if ($this->isEdit) {
                $this->authorizeEdit();
            } else {
                $this->authorizeCreate();
            }

            // Validate that we have a karyawan_id before proceeding
            if (!$this->karyawan_id) {
                $this->dispatch('toast', [
                    'message' => 'Employee ID is required.',
                    'type' => 'error',
                ]);
                return;
            }

            $validated = $this->validate($this->rules());

            // Set is_current based on status
            $is_current = $this->status === 'ongoing';

            $data = [
                'karyawan_id' => $this->karyawan_id,
                'education_level_id' => $this->education_level_id,
                'nama_institusi' => $this->nama_institusi,
                'jenis_institusi' => $this->jenis_institusi,
                'fakultas' => $this->fakultas,
                'jurusan' => $this->jurusan,
                'gelar' => $this->gelar,
                'ipk' => $this->ipk,
                'skala_ipk' => $this->skala_ipk,
                'judul_skripsi' => $this->judul_skripsi,
                'tanggal_ijazah' => $this->tanggal_ijazah,
                'nomor_ijazah' => $this->nomor_ijazah,
                'jenis_belajar' => $this->jenis_belajar,
                'akreditasi' => $this->akreditasi,
                'is_current' => $is_current,
                'spesialisasi' => $this->spesialisasi,
                'sumber_dana' => $this->sumber_dana,
                'nama_beasiswa' => $this->nama_beasiswa,
                'status' => $this->status,
                'tahun_mulai' => $this->tahun_mulai,
                'tahun_selesai' => $this->tahun_selesai,
                'negara' => $this->negara,
                'kota' => $this->kota,
                'ket' => $this->ket,
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
                    $path = $this->document->storeAs('documents/pendidikan', $filename, 'public');
                    
                    // Set the path in data array
                    $data['document_path'] = $path;
                    
                    // Delete old file if editing
                    if ($this->isEdit && $this->pendidikan_id) {
                        $oldRecord = KaryawanPendidikan::find($this->pendidikan_id);
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
                        $oldRecord = KaryawanPendidikan::find($this->pendidikan_id);
                        if ($oldRecord && $oldRecord->document_path) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldRecord->document_path);
                        }
                        $data['document_path'] = null;
                    }
                }
            }

            if ($this->isEdit && $this->pendidikan_id) {
                // Use the stored pendidikan_id for updating
                KaryawanPendidikan::findOrFail($this->pendidikan_id)->update($data);
                $this->dispatch('toast', [
                    'message' => "Data berhasil diedit",
                    'type' => 'success',
                ]);
            } else {
                KaryawanPendidikan::create($data);
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
            // Authorize delete access
            $this->authorizeDelete();

            $data = KaryawanPendidikan::findOrFail($this->deleteId);

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
                'message' => 'Data pendidikan berhasil dihapus.',
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
        $this->selectedPendidikan = KaryawanPendidikan::with(['karyawan', 'educationLevel', 'creator', 'updater'])
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
        $this->pendidikan_id   = null; // Reset the education record ID
        // karyawan_id jangan direset (biar tetap nempel)
        $this->education_level_id = '';
        $this->nama_institusi     = '';
        $this->jenis_institusi    = null;
        $this->fakultas           = null;
        $this->jurusan            = null;
        $this->gelar              = null;
        $this->ipk                = null;
        $this->skala_ipk          = null;
        $this->judul_skripsi      = null;
        $this->tanggal_ijazah     = null;
        $this->nomor_ijazah       = null;
        $this->jenis_belajar      = '';
        $this->akreditasi         = null;
        $this->is_current         = false;
        $this->spesialisasi       = null;
        $this->sumber_dana        = null;
        $this->nama_beasiswa      = null;
        $this->status             = '';
        $this->tahun_mulai        = '';
        $this->tahun_selesai      = null;
        $this->negara             = '';
        $this->kota               = '';
        $this->ket                = '';
        $this->document           = null;
        $this->document_path      = '';
        $this->resetValidation();
    }

    private function resetJurusanFields()
    {
        $this->jurusan      = null;
        $this->fakultas     = null;
        $this->spesialisasi = null;
    }

    private function resetSkripsiFields()
    {
        $this->judul_skripsi = null;
        $this->gelar         = null;
        $this->ipk           = null;
        $this->skala_ipk     = null;
    }

    private function resetBeasiswaFields()
    {
        $this->nama_beasiswa = null;
    }

    public function updatedEducationLevelId($value)
    {
        if (!$this->showJurusanField()) {
            $this->resetJurusanFields();
        }

        if (!$this->showSkripsiField()) {
            $this->resetSkripsiFields();
        }
    }

    public function updatedSumberDana($value)
    {
        if ($value !== 'beasiswa') {
            $this->resetBeasiswaFields();
        }
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
        $query = KaryawanPendidikan::with([
            'educationLevel',
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
                $q->where('nama_institusi', 'like', $search)
                  ->orWhere('jurusan', 'like', $search)
                  ->orWhere('fakultas', 'like', $search);
            });
        });

        // urutkan & paginasi
        $pendidikans = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $edulevel = EducationLevel::query()
            ->where('is_active', 1)
            ->where('is_formal', 1)
            ->orderBy('level_order')
            ->get();

        return view('livewire.admin.karyawan.tab.pendidikan.index', compact('pendidikans', 'edulevel'));
    }
}