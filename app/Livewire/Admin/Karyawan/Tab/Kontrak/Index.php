<?php

namespace App\Livewire\Admin\Karyawan\Tab\Kontrak;

use App\Models\Employee\Karyawan;
use App\Models\Employee\KaryawanKontrak;
use App\Traits\HasTabPermission;
use App\Models\Master\Golongan;
use App\Models\Master\Jabatans;
use App\Models\Master\Kontrak;
use App\Models\Master\Units;
use App\Models\Master\Departments;
use App\Models\Master\Mapel;
use App\Models\Yayasan\Pengurus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;
use App\Models\User;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Index extends Component {

    use WithPagination, WithFileUploads;
    use HasTabPermission;

    // Main properties
    public $karyawan_id;
    public $kontrak_karyawan_id;
    public $jenis_karyawan = null; // Store jenis_karyawan untuk kondisi mata pelajaran
    
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
    public $approver1_id = null;
    public $approver2_id = null;

    // Upload document properties
    public $showUploadModal = false;
    public $uploadKontrakId = null;
    public $uploadedDocument = null;

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

    public function getContractStatus($tglselesai_kontrak)
    {
        if (empty($tglselesai_kontrak) || is_null($tglselesai_kontrak)) {
            return [
                'color' => 'gray',
                'text' => 'Tidak terbatas'
            ];
        }

        try {
            $endDate = \Carbon\Carbon::parse($tglselesai_kontrak);
            $now = \Carbon\Carbon::now();
            
            $diff = $now->diff($endDate);
            $totalDays = $now->diffInDays($endDate, false);
            
            if ($totalDays < 0) {
                $text = 'Sudah berakhir';
                if (abs($totalDays) > 0) {
                    $text .= ' (' . $this->formatDuration($diff, true) . ' yang lalu)';
                }
                return [
                    'color' => 'red',
                    'text' => $text
                ];
            }
            
            $durationText = $this->formatDuration($diff);
            
            if ($totalDays <= 30) {
                $color = 'yellow';
            } else if ($totalDays <= 90) {
                $color = 'blue';
            } else {
                $color = 'green';
            }
            
            return [
                'color' => $color,
                'text' => $durationText . ' tersisa'
            ];
        } catch (\Exception $e) {
            return [
                'color' => 'gray',
                'text' => 'Tidak terbatas'
            ];
        }
    }

    private function formatDuration($diff, $isPast = false)
    {
        try {
            $totalDays = $diff->days;
            
            if ($totalDays == 0) {
                return 'Hari ini';
            }
            
            if ($totalDays < 0) {
                $totalDays = abs($totalDays);
            }
            
            $years = intdiv($totalDays, 365);
            $remainingDays = $totalDays % 365;
            $months = intdiv($remainingDays, 30);
            $days = $remainingDays % 30;
            
            $parts = [];
            
            if ($years > 0) {
                $parts[] = $years . ' tahun';
            }
            
            if ($months > 0) {
                $parts[] = $months . ' bulan';
            }
            
            if ($days > 0 && ($years === 0 && $months === 0)) {
                $parts[] = $days . ' hari';
            }
            
            if (empty($parts)) {
                return 'Hari ini';
            }
            
            $parts = array_slice($parts, 0, 2);
            
            return implode(' ', $parts);
        } catch (\Exception $e) {
            return 'Tidak ada data';
        }
    }

    public function generateNomorKontrak()
    {
        $year = date('Y');
        $month = date('n');
        
        $romanMonths = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        $romanMonth = $romanMonths[$month];

        $lastContract = KaryawanKontrak::where('nomor_kontrak', 'like', "%/KU-YKPI/$romanMonth/$year")
            ->orderBy('nomor_kontrak', 'desc')
            ->first();

        $lastNumber = 0;
        if ($lastContract) {
            $parts = explode('/', $lastContract->nomor_kontrak);
            $lastNumber = (int) $parts[0];
        }

        $newNumber = $lastNumber + 1;
        $formattedNumber = str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $this->nomor_kontrak = "$formattedNumber/KU-YKPI/$romanMonth/$year";

        return $this->nomor_kontrak;
    }

    public function mount($karyawan = null)
    {
        $this->authorizeView();
        
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
            'approver1_id' => 'nullable|exists:users,id',
            'approver2_id' => 'nullable|exists:users,id',
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
        'approver1_id' => 'approver 1',
        'approver2_id' => 'approver 2',
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
        $this->approver1_id = $kontrak->approver1_id;
        $this->approver2_id = $kontrak->approver2_id;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        try {
            $this->authorizeCreate();
            
            if (!$this->karyawan_id) {
                $this->dispatch('toast', [
                    'message' => 'Employee ID is required.',
                    'type' => 'error',
                ]);
                return;
            }

            $validated = $this->validate($this->rules());

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
                'approver1_id' => $this->approver1_id,
                'approver2_id' => $this->approver2_id,
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

    // Upload Document Methods
    public function openUploadModal($kontrakId)
    {
        $this->uploadKontrakId = $kontrakId;
        $this->uploadedDocument = null;
        $this->showUploadModal = true;
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->uploadKontrakId = null;
        $this->uploadedDocument = null;
    }

    public function uploadDocument()
    {
        try {
            $this->validate([
                'uploadedDocument' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            ]);

            $kontrak = KaryawanKontrak::findOrFail($this->uploadKontrakId);

            // Delete old document if exists
            if ($kontrak->document_path && Storage::disk('public')->exists($kontrak->document_path)) {
                Storage::disk('public')->delete($kontrak->document_path);
            }

            // Store new document
            $fileName = 'kontrak_' . $kontrak->nomor_kontrak . '_' . time() . '.' . $this->uploadedDocument->getClientOriginalExtension();
            $filePath = $this->uploadedDocument->storeAs('kontrak', $fileName, 'public');

            // Update kontrak record
            $kontrak->update(['document_path' => $filePath]);

            $this->dispatch('toast', [
                'message' => 'Dokumen berhasil diupload',
                'type' => 'success',
            ]);

            $this->closeUploadModal();
        } catch (ValidationException $e) {
            $this->dispatch('toast', [
                'message' => 'File harus berupa PDF, DOC, DOCX, JPG, JPEG, atau PNG dengan ukuran maksimal 5MB',
                'type' => 'error',
            ]);
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Gagal mengupload dokumen: ' . $e->getMessage(),
                'type' => 'error',
            ]);
            throw $e;
        }
    }

    public function deleteDocument($kontrakId)
    {
        try {
            $kontrak = KaryawanKontrak::findOrFail($kontrakId);

            if ($kontrak->document_path && Storage::disk('public')->exists($kontrak->document_path)) {
                Storage::disk('public')->delete($kontrak->document_path);
                $kontrak->update(['document_path' => null]);

                $this->dispatch('toast', [
                    'message' => 'Dokumen berhasil dihapus',
                    'type' => 'success',
                ]);
            } else {
                $this->dispatch('toast', [
                    'message' => 'Dokumen tidak ditemukan',
                    'type' => 'warning',
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Gagal menghapus dokumen: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function downloadDocument($kontrakId)
    {
        try {
            $kontrak = KaryawanKontrak::findOrFail($kontrakId);

            if (!$kontrak->document_path || !Storage::disk('public')->exists($kontrak->document_path)) {
                $this->dispatch('toast', [
                    'message' => 'Dokumen tidak ditemukan',
                    'type' => 'error',
                ]);
                return;
            }

            // Create streamed response for download
            $filePath = $kontrak->document_path;
            $fileName = basename($filePath);
            
            return response()->streamDownload(
                function () use ($filePath) {
                    echo Storage::disk('public')->get($filePath);
                },
                $fileName
            );
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Gagal mengunduh dokumen: ' . $e->getMessage(),
                'type' => 'error',
            ]);
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

    public function showDetail($id)
    {
        $this->selectedKontrak = KaryawanKontrak::with([
            'karyawan',
            'kontrak',
            'golongan',
            'unit',
            'jabatan',
            'createdBy',
            'updatedBy'
        ])->find($id);
        $this->showModalDetail = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showModalDetail = false;
        // Only reset form if in create/edit mode, not detail view
        if ($this->isEdit || !$this->showModalDetail) {
            $this->resetFormFields();
        }
    }

    private function resetForm()
    {
        // Keep karyawan_id intact - it's essential for filtering
        $this->resetFormFields();
    }

    private function resetFormFields()
    {
        // Reset only form fields, NOT karyawan_id
        $this->kontrak_karyawan_id = null;
        // $this->karyawan_id = null; // DO NOT RESET - needed for filter
        $this->jenis_karyawan = null;
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
        $this->approver1_id = null;
        $this->approver2_id = null;
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

    public function selectKaryawan($karyawanId, $karyawanName)
    {
        $this->karyawan_id = $karyawanId;
        $karyawan = Karyawan::findOrFail($karyawanId);
        $this->jenis_karyawan = $karyawan->jenis_karyawan;
    }

    public function clearKaryawan()
    {
        $this->karyawan_id = null;
        $this->jenis_karyawan = null;
        $this->mapel_id = null;
    }

    public function searchKaryawan($value)
    {
        // Search logic bisa ditambahkan di future jika diperlukan
    }

    public function render()
    {
        // Always filter by the karyawan that was passed during mount
        $query = KaryawanKontrak::with([
            'karyawan:id,full_name',
            'kontrak:id,nama_kontrak',
            'golongan:id,nama_golongan',
            'unit:id,unit,department_id',
            'jabatan:id,nama_jabatan',
            'mapel:id,nama_mapel',
            'createdBy:id,name',
            'updatedBy:id,name'
        ]);

        // ALWAYS filter by karyawan_id (required - set during mount)
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
        
        $masterUnit = $this->department_id
            ? Units::where('department_id', $this->department_id)->orderBy('unit')->get()
            : collect();

        $masterJabatan = $this->department_id
            ? Jabatans::where('department_id', $this->department_id)->orderBy('nama_jabatan')->get()
            : collect();

        $masterMapel = Mapel::orderBy('nama_mapel')->get();
        
        // Approver 1: Karyawan dengan jabatan top level (top_managerial)
        // Diambil dari karyawan yang punya kontrak aktif dengan jabatan top level
        $approver1Karyawan = Karyawan::whereHas('contracts', function ($q) {
            $q->whereHas('jabatan', function ($subQ) {
                $subQ->where('level_jabatan', 'top_managerial');
            })->where('status', 'aktif');
        })
        ->with(['user:id,name', 'contracts' => function ($q) {
            $q->whereHas('jabatan', function ($subQ) {
                $subQ->where('level_jabatan', 'top_managerial');
            })->where('status', 'aktif')
            ->with('jabatan:id,nama_jabatan');
        }])
        ->orderBy('full_name')
        ->get();
        
        // Approver 2: Dari tabel pengurus (aktif)
        $approver2Pengurus = Pengurus::where('is_active', true)
            ->with('user:id,name', 'jabatan:id,nama_jabatan')
            ->orderBy('nama_pengurus')
            ->get();

        return view('livewire.admin.karyawan.tab.kontrak.index', compact(
            'kontraks',
            'masterKontrak',
            'masterGolongan',
            'masterDepartment',
            'masterUnit',
            'masterJabatan',
            'masterMapel',
            'approver1Karyawan',
            'approver2Pengurus'
        ));
    }
}