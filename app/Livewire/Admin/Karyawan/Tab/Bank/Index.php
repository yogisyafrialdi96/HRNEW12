<?php

namespace App\Livewire\Admin\Karyawan\Tab\Bank;

use App\Models\Employee\KaryawanBankaccount;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    // Main properties
    public $karyawan_id; // This should hold the employee ID
    public $bank_id; // Add this to store the prestasi record ID when editing
    public $document;
    public $document_path = '';

    // Form fields
    public $nama_bank       = '';
    public $kode_bank       = '';
    public $nomor_rekening  = '';
    public $nama_pemilik    = '';
    public $jenis_rekening  = null;
    public $tujuan          = null;
    public $cabang          = null;
    public bool $is_primary = false;
    public $status          = null;
    public $tanggal_buka    = null;
    public $keterangan      = null;

    // Properties for search and filter
    public $search = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $showModalDetail = false;
    public $selectedBank; // Consider renaming to $selectedBank

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
            'nama_bank' => [
                'required',
                'string',
                'max:255',
                'min:3'
            ],
            'kode_bank' => ['required', 'string', 'size:3'],
            'nomor_rekening' => [
                'required',
                'string',
                'max:20',
                'min:5',
                Rule::unique('karyawan_akunbank', 'nomor_rekening')->ignore($this->bank_id, 'id')
            ],
            'nama_pemilik' => [
                'required',
                'string',
                'max:255',
                'min:3'
            ],
            'jenis_rekening'     => 'required|in:' . implode(',', array_keys(KaryawanBankaccount::JENIS_REKENING)),
            'tujuan'     => 'required|in:' . implode(',', array_keys(KaryawanBankaccount::TUJUAN_REKENING)),
            'status'     => 'required|in:' . implode(',', array_keys(KaryawanBankaccount::STATUS_REKENING)),
            'cabang' => [
                'nullable',
                'string',
            ],
            'tanggal_buka' => [
                'nullable',
                'date',
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
        $bank = KaryawanBankaccount::findOrFail($id);

        // Store the education record ID for editing
        $this->bank_id = $id;

        // Keep the employee ID intact
        $this->karyawan_id = $bank->karyawan_id;
        $this->nama_bank = $bank->nama_bank;
        $this->kode_bank = $bank->kode_bank;
        $this->nomor_rekening = $bank->nomor_rekening;
        $this->nama_pemilik = $bank->nama_pemilik;
        $this->jenis_rekening = $bank->jenis_rekening;
        $this->tujuan = $bank->tujuan;
        $this->cabang = $bank->cabang;
        $this->is_primary = $bank->is_primary;
        $this->status = $bank->status;
        $this->tanggal_buka = optional($bank->tanggal_buka)->format('Y-m-d');
        $this->keterangan = $bank->keterangan;
        $this->document_path = $bank->document_path; // ✅ Ini string path
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
                'karyawan_id'       => $this->karyawan_id,
                'nama_bank'         => $this->nama_bank,
                'kode_bank'         => $this->kode_bank,
                'nomor_rekening'    => $this->nomor_rekening,
                'nama_pemilik'      => $this->nama_pemilik,
                'jenis_rekening'    => $this->jenis_rekening,
                'tujuan'            => $this->tujuan,
                'cabang'            => $this->cabang,
                'is_primary'        => $this->is_primary,
                'status'            => $this->status,
                'tanggal_buka'      => $this->tanggal_buka,
                'keterangan'        => $this->keterangan,
                'document_path'     => $this->document_path,
                'updated_by'        => Auth::id(),
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
                    $path = $this->document->storeAs('documents/bukutabungan', $filename, 'public');

                    // Set the path in data array
                    $data['document_path'] = $path;

                    // Delete old file if editing
                    if ($this->isEdit && $this->bank_id) {
                        $oldRecord = KaryawanBankaccount::find($this->bank_id);
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
                        $oldRecord = KaryawanBankaccount::find($this->bank_id);
                        if ($oldRecord && $oldRecord->document_path) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldRecord->document_path);
                        }
                        $data['document_path'] = null;
                    }
                }
            }

            if ($this->isEdit && $this->bank_id) {
                // Use the stored bank_id for updating
                KaryawanBankaccount::findOrFail($this->bank_id)->update($data);
                $this->dispatch('toast', [
                    'message' => "Data berhasil diedit",
                    'type' => 'success',
                ]);
            } else {
                KaryawanBankaccount::create($data);
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
            $data = KaryawanBankaccount::findOrFail($this->deleteId);

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
                'message' => 'Data Bank berhasil dihapus.',
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
        $this->selectedBank = KaryawanBankaccount::with(['karyawan', 'creator', 'updater'])
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
        $this->bank_id   = null; // Reset the education record ID
        // karyawan_id jangan direset (biar tetap nempel)
        $this->nama_bank        = '';
        $this->kode_bank        = '';
        $this->nomor_rekening   = null;
        $this->nama_pemilik     = '';
        $this->jenis_rekening   = null;
        $this->tujuan           = null;
        $this->cabang           = null;
        $this->is_primary       = '';
        $this->status           = '';
        $this->tanggal_buka     = null;
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

    public function toggleStatus($id)
    {
        $unit = KaryawanBankaccount::findOrFail($id);
        $unit->update(['is_primary' => !$unit->is_primary]);

        $this->dispatch('toast', [
            'message' => "Status berhasil diedit",
            'type' => 'success',
        ]);
    }

    public function render()
    {
        $query = KaryawanBankaccount::with([
            'karyawan:id,full_name',
            'creator:id,name',
            'updater:id,name'
        ]);

        // Filter by employee if karyawan_id is set
        if ($this->karyawan_id) {
            $query->where('karyawan_id', $this->karyawan_id);
        }

        // Pencarian
        $query->when($this->search, function ($q) {
            $search = '%' . $this->search . '%';
            $q->where(function ($q) use ($search) {
                $q->where('nama_bank', 'like', $search)
                    ->orWhere('kode_bank', 'like', $search)
                    ->orWhere('nomor_rekening', 'like', $search)
                    ->orWhere('nama_pemilik', 'like', $search)
                    ->orWhere('jenis_rekening', 'like', $search)
                    ->orWhere('tujuan', 'like', $search)
                    ->orWhere('cabang', 'like', $search);
            });
        });

        $bankaccounts = $query
            ->orderByDesc('is_primary') // true duluan
            ->orderBy($this->sortField, $this->sortDirection) // lalu urut sesuai field yang dipilih
            ->paginate($this->perPage);

        return view('livewire.admin.karyawan.tab.bank.index', compact('bankaccounts'));
    }

}
