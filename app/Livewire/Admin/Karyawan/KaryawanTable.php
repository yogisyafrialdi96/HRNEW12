<?php

namespace App\Livewire\Admin\Karyawan;

use App\Models\Employee\Karyawan;
use App\Models\Master\StatusPegawai;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class KaryawanTable extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $foto;
    public $originalFoto; // Store original photo path

    public $userId; // ID dari tabel users
    public $karyawanId; // ID dari tabel karyawan

    public $full_name = '';
    public $nip = '';
    public $gender = '';
    public $inisial = null;
    public $jenis_karyawan = '';
    public $statuskaryawan_id = '';
    public $tgl_masuk = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    // Properties for search and filter
    public $search = '';
    public $statusFilter = '';
    public $posisiFilter = '';
    public $jenisFilter = '';
    public $levelFilter = '';
    public $unitFilter = '';
    public $jabatanFilter = '';
    public $tgl_masuk_dari = '';
    public $tgl_masuk_sampai = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    
    // Modal Detail properties
    public $showModalDetail = false;
    public $selectedKaryawan = null;
    public $activeTab = 'profile';

    // Import Excel properties
    public $showImportModal = false;
    public $importFile = null;
    public $importProgress = 0;
    public $importResult = null;

    public function openImportModal()
    {
        $this->showImportModal = true;
        $this->importFile = null;
        $this->importResult = null;
    }

    public function closeImportModal()
    {
        $this->showImportModal = false;
        $this->importFile = null;
        $this->importProgress = 0;
        $this->importResult = null;
        $this->resetValidation();
    }

    public function importKaryawan()
    {
        try {
            $this->validate([
                'importFile' => [
                    'required',
                    'file',
                    'max:5120', // Max 5MB
                    function ($attribute, $value, $fail) {
                        $extension = strtolower($value->getClientOriginalExtension());
                        $mimeType = $value->getMimeType();
                        
                        // Allow common Excel and CSV MIME types and extensions
                        $allowedExtensions = ['xlsx', 'xls', 'csv'];
                        $allowedMimeTypes = [
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                            'application/vnd.ms-excel', // .xls
                            'application/x-msexcel',
                            'text/csv',
                            'text/plain', // CSV sometimes reported as text/plain
                            'application/csv',
                        ];
                        
                        // Check extension
                        if (!in_array($extension, $allowedExtensions)) {
                            $fail('File harus berupa Excel (.xlsx, .xls) atau CSV');
                            return;
                        }
                        
                        // Check mime type (lenient - don't fail if mime is unknown)
                        if ($mimeType && !in_array($mimeType, $allowedMimeTypes)) {
                            // For CSV, check if file extension is CSV
                            if ($extension !== 'csv') {
                                $fail('File type tidak didukung. Gunakan .xlsx, .xls, atau .csv');
                                return;
                            }
                        }
                    }
                ],
            ]);

            // Import menggunakan Laravel Excel
            $import = new \App\Imports\KaryawanImport();
            \Maatwebsite\Excel\Facades\Excel::import($import, $this->importFile);

            // Get results
            $successCount = $import->getSuccessCount();
            $errorRows = $import->getErrorRows();

            $this->importResult = [
                'success' => true,
                'successCount' => $successCount,
                'errorCount' => count($errorRows),
                'errors' => $errorRows,
            ];

            if ($successCount > 0) {
                $this->dispatch('toast', [
                    'message' => "Berhasil import {$successCount} karyawan",
                    'type' => 'success',
                ]);
                
                // Reset page dan refresh data
                $this->resetPage();
            }

            if (count($errorRows) > 0) {
                $this->dispatch('toast', [
                    'message' => "Ada " . count($errorRows) . " baris dengan error",
                    'type' => 'warning',
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $errorMessage = !empty($errors) ? $errors[0] : 'File harus berupa Excel (.xlsx, .xls) atau CSV dengan ukuran maksimal 5MB';
            
            $this->dispatch('toast', [
                'message' => $errorMessage,
                'type' => 'error',
            ]);
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Terjadi kesalahan saat import: ' . $e->getMessage(),
                'type' => 'error',
            ]);
            \Illuminate\Support\Facades\Log::error('Karyawan import error: ' . $e->getMessage());
        }
    }

    #[Url]
    public string $query = '';

    // Set default URL param supaya reset saat refresh
    #[Url(except: 'id')]
    public string $sortField = 'id';

    #[Url(except: 'desc')]
    public string $sortDirection = 'desc';

    public bool $showDeleted = false;

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
            'full_name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('karyawan', 'full_name')->ignore($this->karyawanId)
            ],
            'inisial' => [
                'required',
                'string',
                'size:3',
                Rule::unique('karyawan', 'inisial')->ignore($this->karyawanId)
            ],
            'nip' => [
                'required',
                'string',
                'size:6',
                Rule::unique('karyawan', 'nip')->ignore($this->karyawanId)
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->userId)
            ],
            'password' => [
                $this->karyawanId ? 'nullable' : 'required',
                'string',
                'min:8',
                'confirmed'
            ],
            'gender' => 'required|string|in:laki-laki,perempuan',
            'jenis_karyawan' => 'required|string|in:Guru,Pegawai',
            'statuskaryawan_id' => 'required|exists:master_statuspegawai,id',
            'tgl_masuk' => 'required|date',
        ];

        // Validasi foto - berbeda untuk create dan edit
        if ($this->foto instanceof \Illuminate\Http\UploadedFile) {
            // Jika ada file yang diupload (create atau edit dengan file baru)
            $rules['foto'] = [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048' // 2MB max
            ];
        } else {
            // Jika tidak ada file upload (edit tanpa ganti foto)
            $rules['foto'] = 'nullable|string';
        }

        return $rules;
    }

    protected $validationAttributes = [
        'full_name' => 'Nama Lengkap',
        'gender' => 'Jenis Kelamin',
        'statuskaryawan_id' => 'Status Karyawan',
        'tgl_masuk' => 'Tanggal Masuk',
        'jenis_karyawan' => 'Jenis Karyawan',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPosisiFilter()
    {
        $this->resetPage();
    }

    public function updatingJenisFilter()
    {
        $this->resetPage();
    }

    public function updatingLevelFilter()
    {
        $this->resetPage();
    }

    public function updatingUnitFilter()
    {
        $this->resetPage();
    }

    public function updatingJabatanFilter()
    {
        $this->resetPage();
    }

    public function updatingTglMasukDari()
    {
        $this->resetPage();
    }

    public function updatingTglMasukSampai()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function save()
    {
        DB::beginTransaction(); // mulai transaksi

        try {
            $this->validate();

            // Handle foto upload
            $fotoPath = null;
            if ($this->foto instanceof \Illuminate\Http\UploadedFile) {
                // Ada foto baru yang diupload
                $fotoPath = $this->foto->store('fotos', 'public');

                // Jika edit dan ada foto lama, hapus foto lama
                if ($this->isEdit && $this->originalFoto && Storage::disk('public')->exists($this->originalFoto)) {
                    Storage::disk('public')->delete($this->originalFoto);
                }
            } elseif ($this->isEdit && is_string($this->foto)) {
                // Edit mode dan foto tidak diubah (tetap string path)
                $fotoPath = $this->foto;
            }

            // Validasi field wajib untuk create
            if (empty($this->email) || empty($this->full_name) || empty($this->password)) {
                throw new \Exception('Email, Nama, dan Password wajib diisi untuk karyawan baru.');
            }

            // Create user baru
            $user = User::create([
                'name'     => $this->full_name,
                'email'    => $this->email,
                'password' => bcrypt($this->password),
            ]);

            // Create karyawan baru
            Karyawan::create([
                'user_id'           => $user->id,
                'full_name'         => $this->full_name,
                'inisial'           => $this->inisial ?: null,
                'nip'               => $this->nip,
                'gender'            => $this->gender,
                'jenis_karyawan'    => $this->jenis_karyawan,
                'statuskaryawan_id' => $this->statuskaryawan_id ?: null,
                'foto'              => $fotoPath,
                'tgl_masuk'         => $this->tgl_masuk ?: null,
                'created_by'        => Auth::id(),
                'updated_by'        => Auth::id(),
            ]);

            $this->dispatch('toast', [
                'message' => "Data berhasil disimpan",
                'type'    => 'success',
            ]);


            DB::commit(); // sukses → simpan perubahan
            $this->closeModal();
        } catch (ValidationException $e) {
            DB::rollBack(); // rollback kalau error

            // Hapus file yang baru diupload jika terjadi error
            if (!empty($fotoPath) && ($this->isEdit ? $fotoPath !== $this->originalFoto : true)) {
                Storage::disk('public')->delete($fotoPath);
            }

            $errors = $e->validator->errors()->all();
            $count  = count($errors);

            $this->dispatch('toast', [
                'message' => "Terdapat $count kesalahan:\n- " . implode("\n- ", $errors),
                'type'    => 'error',
            ]);
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack(); // rollback kalau error

            // Hapus file yang baru diupload jika terjadi error
            if (!empty($fotoPath) && ($this->isEdit ? $fotoPath !== $this->originalFoto : true)) {
                Storage::disk('public')->delete($fotoPath);
            }

            $this->dispatch('toast', [
                'message' => $e->getMessage() ?: 'Terjadi kesalahan server.',
                'type'    => 'error',
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
        Karyawan::find($this->deleteId)?->delete();

        $this->deleteSuccess = true;

        // Trigger Alpine to auto-close modal
        $this->dispatch('modal:success');
    }

    public function resetDeleteModal()
    {
        $this->confirmingDelete = false;
        $this->deleteSuccess = false;
        $this->deleteId = null;
    }
    // End SoftDelete

    // Restore Data
    public bool $confirmingRestore = false;
    public bool $restoreSuccess = false;
    public ?int $restoreId = null;

    public function confirmRestore($id)
    {
        $this->restoreId = $id;
        $this->confirmingRestore = true;
        $this->restoreSuccess = false;
    }

    public function restore()
    {
        Karyawan::withTrashed()->find($this->restoreId)?->restore();

        $this->restoreSuccess = true;

        // Trigger Alpine to auto-close modal
        $this->dispatch('modal:success-restore');
    }

    public function resetRestoreModal()
    {
        $this->confirmingRestore = false;
        $this->restoreSuccess = false;
        $this->restoreId = null;
    }
    // End Restore Data

    // ForceDelete
    public bool $confirmingForceDelete = false;
    public bool $forceDeleteSuccess = false;
    public ?int $forceDeleteId = null;

    public function confirmForceDelete($id)
    {
        $this->forceDeleteId = $id;
        $this->confirmingForceDelete = true;
        $this->forceDeleteSuccess = false;
    }

    public function forceDelete()
    {
        // Reset success state setiap kali method dipanggil
        $this->forceDeleteSuccess = false;

        $karyawan = Karyawan::withTrashed()->find($this->forceDeleteId);

        // Cek apakah karyawan ditemukan
        if (!$karyawan) {
            $this->dispatch('toast', [
                'message' => 'Data tidak ditemukan.',
                'type' => 'error',
            ]);
            return;
        }

        // Hapus file foto jika ada
        if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
            Storage::disk('public')->delete($karyawan->foto);
        }

        // Hapus user yang terkait
        if ($karyawan->user) {
            $karyawan->user->forceDelete();
        }

        // Jika tidak ada masalah, baru lakukan force delete
        $karyawan->forceDelete();

        // Set success state dan dispatch modal success
        $this->forceDeleteSuccess = true;

        $this->dispatch('toast', [
            'message' => 'Data berhasil dihapus permanen.',
            'type' => 'success',
        ]);
    }

    public function resetForceDeleteModal()
    {
        $this->confirmingForceDelete = false;
        $this->forceDeleteSuccess = false;
        $this->forceDeleteId = null;
    }
    // End Force Delete


    public function closeModal()
    {
        $this->showModal = false;
        $this->showModalDetail = false;
        $this->selectedKaryawan = null;
        $this->activeTab = 'profile';
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->karyawanId = null;
        $this->userId = null;
        $this->full_name = '';
        $this->inisial = null;
        $this->nip = '';
        $this->gender = '';
        $this->jenis_karyawan = '';
        $this->statuskaryawan_id = '';
        $this->tgl_masuk = '';
        $this->foto = null;
        $this->originalFoto = null;
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->resetValidation();
    }

    public function toggleStatus($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->update(['statuskaryawan_id' => $karyawan->statuskaryawan_id == 1 ? 2 : 1]);

        $this->dispatch('toast', [
            'message' => "Status berhasil diedit",
            'type' => 'success',
        ]);
    }


    public function render()
    {
        $query = Karyawan::with([
            'user',
            'activeJabatan',
            'statusPegawai',
        ]);

        // tampilkan data terhapus jika perlu
        $query->when($this->showDeleted, function ($q) {
            $q->onlyTrashed(); // hanya data yang sudah dihapus
        });

        // filter by status pegawai
        $query->when($this->statusFilter !== '', function ($q) {
            $q->where('statuskaryawan_id', $this->statusFilter);
        });

        // filter by jabatan aktif
        $query->when($this->jabatanFilter !== '', function ($q) {
            $q->whereHas('activeJabatan.jabatan', function ($sub) {
                $sub->where('id', $this->jabatanFilter);
            });
        });

        // filter by unit aktif
        $query->when($this->unitFilter !== '', function ($q) {
            $q->whereHas('activeJabatan.unit', function ($sub) {
                $sub->where('id', $this->unitFilter)
                    ->whereHas('department', function ($dept) {
                        $dept->where('department', '!=', 'YAYASAN');
                    });
            });
        });

        // filter by date range tgl_masuk
        $query->when($this->tgl_masuk_dari !== '', function ($q) {
            $q->whereDate('tgl_masuk', '>=', $this->tgl_masuk_dari);
        });

        $query->when($this->tgl_masuk_sampai !== '', function ($q) {
            $q->whereDate('tgl_masuk', '<=', $this->tgl_masuk_sampai);
        });

        // filter by posisi
        $query->when($this->posisiFilter !== '', function ($q) {
            $q->where('posisi', 'like', '%' . $this->posisiFilter . '%');
        });

        // filter by jenis & level (kolom ada di tabel jabatan)
        $query->when($this->jenisFilter !== '' || $this->levelFilter !== '', function ($q) {
            $q->whereHas('jabatan', function ($sub) {
                if ($this->jenisFilter !== '') {
                    $sub->where('jenis_jabatan', $this->jenisFilter);
                }
                if ($this->levelFilter !== '') {
                    $sub->where('level_jabatan', $this->levelFilter);
                }
            });
        });

        // pencarian
        $query->when($this->search, function ($q) {
            $search = '%' . $this->search . '%';
            $q->where(function ($q) use ($search) {
                $q->where('full_name', 'like', $search)
                    ->orWhere('nip', 'like', $search)
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('email', 'like', $search)
                            ->orWhere('name', 'like', $search);
                    });
            });
        });

        // urutkan & paginasi
        $karyawans = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $statusKaryawan = StatusPegawai::orderBy('nama_status')->get();

        return view('livewire.admin.karyawan.karyawan-table', compact('karyawans', 'statusKaryawan'));
    }

    /**
     * Show detail modal for karyawan
     */
    public function showDetail($id)
    {
        $this->selectedKaryawan = Karyawan::with([
            'user',
            'statusPegawai',
            'activeJabatan' => function ($q) {
                $q->with(['jabatan', 'unit']);
            },
            'contracts' => function ($q) {
                $q->with('kontrak')->latest('tglmulai_kontrak');
            }
        ])->findOrFail($id);
        
        $this->showModalDetail = true;
        $this->activeTab = 'profile';
    }

    /**
     * Switch active tab in detail modal
     */
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    /**
     * Get list of all units for filter dropdown (excluding YAYASAN department)
     */
    public function getUnits()
    {
        return \App\Models\Master\Units::with('department')
            ->whereHas('department', function ($query) {
                $query->where('department', '!=', 'YAYASAN');
            })
            ->orderBy('unit')
            ->get();
    }

    /**
     * Get list of all jabatan for filter dropdown
     */
    public function getJabatans()
    {
        return \App\Models\Master\Jabatans::orderBy('nama_jabatan')->get();
    }

    /**
     * Reset all filters
     */
    public function resetFilters()
    {
        $this->statusFilter = '';
        $this->unitFilter = '';
        $this->jabatanFilter = '';
        $this->tgl_masuk_dari = '';
        $this->tgl_masuk_sampai = '';
        $this->posisiFilter = '';
        $this->jenisFilter = '';
        $this->levelFilter = '';
        $this->search = '';
        $this->resetPage();
    }

    /**
     * Export karyawan data to Excel with applied filters
     */
    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\KaryawanExport(
                $this->statusFilter,
                $this->unitFilter,
                $this->jabatanFilter,
                $this->tgl_masuk_dari,
                $this->tgl_masuk_sampai
            ),
            'Karyawan_' . now()->format('d-m-Y_H-i-s') . '.xlsx'
        );
    }

    /**
     * Get total all karyawan (tidak dihapus)
     */
    public function getTotalKaryawan()
    {
        return Karyawan::whereNull('deleted_at')->count();
    }

    /**
     * Get total karyawan by status pegawai
     */
    public function getKaryawanByStatus()
    {
        return Karyawan::whereNull('deleted_at')
            ->with('statusPegawai')
            ->get()
            ->groupBy('statusPegawai.nama_status')
            ->map(fn($group) => $group->count());
    }

    /**
     * Get total pegawai (jenis_karyawan = Pegawai)
     */
    public function getTotalPegawai()
    {
        return Karyawan::whereNull('deleted_at')
            ->where('jenis_karyawan', 'Pegawai')
            ->count();
    }

    /**
     * Get total guru (jenis_karyawan = Guru)
     */
    public function getTotalGuru()
    {
        return Karyawan::whereNull('deleted_at')
            ->where('jenis_karyawan', 'Guru')
            ->count();
    }

    /**
     * Get total pegawai aktif (status id = 1 for active)
     */
    public function getTotalAktif()
    {
        return Karyawan::whereNull('deleted_at')
            ->where('statuskaryawan_id', 1)
            ->count();
    }

    /**
     * Get total pegawai tidak aktif (status id != 1)
     */
    public function getTotalTidakAktif()
    {
        return Karyawan::whereNull('deleted_at')
            ->where('statuskaryawan_id', '!=', 1)
            ->count();
    }

    /**
     * Get total pegawai laki-laki
     */
    public function getTotalLakiLaki()
    {
        return Karyawan::whereNull('deleted_at')
            ->where('gender', 'laki-laki')
            ->count();
    }

    /**
     * Get total pegawai perempuan
     */
    public function getTotalPerempuan()
    {
        return Karyawan::whereNull('deleted_at')
            ->where('gender', 'perempuan')
            ->count();
    }

    /**
     * Get all stats for display
     */
    public function getStats()
    {
        return [
            'total_karyawan' => $this->getTotalKaryawan(),
            'total_pegawai' => $this->getTotalPegawai(),
            'total_guru' => $this->getTotalGuru(),
            'total_aktif' => $this->getTotalAktif(),
            'total_tidak_aktif' => $this->getTotalTidakAktif(),
            'total_laki_laki' => $this->getTotalLakiLaki(),
            'total_perempuan' => $this->getTotalPerempuan(),
            'by_status' => $this->getKaryawanByStatus(),
        ];
    }
}
