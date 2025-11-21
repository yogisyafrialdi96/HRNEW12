<?php

namespace App\Livewire\Admin\Karyawan\Kontrak;

use App\Models\Employee\KaryawanKontrak;
use App\Models\Master\Departments;
use App\Models\Master\Golongan;
use App\Models\Master\Jabatans;
use App\Models\Master\Kontrak;
use App\Models\Master\Mapel;
use App\Models\Master\Units;
use App\Models\Yayasan\Pengurus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;

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
            
            // Hitung selisih waktu
            $diff = $now->diff($endDate);
            
            // Total hari tersisa (untuk menentukan warna)
            $totalDays = $now->diffInDays($endDate, false);
            
            // Format teks berdasarkan durasi
            if ($totalDays < 0) {
                // Sudah berakhir - tampilkan berapa lama sudah lewat
                $text = 'Sudah berakhir';
                if (abs($totalDays) > 0) {
                    $text .= ' (' . $this->formatDuration($diff, true) . ' yang lalu)';
                }
                return [
                    'color' => 'red',
                    'text' => $text
                ];
            }
            
            // Format durasi tersisa
            $durationText = $this->formatDuration($diff);
            
            // Tentukan warna berdasarkan hari tersisa
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
            // Jika ada error parsing tanggal, tampilkan sebagai tidak terbatas
            return [
                'color' => 'gray',
                'text' => 'Tidak terbatas'
            ];
        }
    }

    private function formatDuration($diff, $isPast = false)
    {
        try {
            // Hitung total hari terlebih dahulu
            $totalDays = $diff->days;
            
            if ($totalDays == 0) {
                return 'Hari ini';
            }
            
            if ($totalDays < 0) {
                $totalDays = abs($totalDays);
            }
            
            // Konversi total hari ke tahun, bulan, hari
            $years = intdiv($totalDays, 365);
            $remainingDays = $totalDays % 365;
            $months = intdiv($remainingDays, 30);
            $days = $remainingDays % 30;
            
            $parts = [];
            
            // Tahun
            if ($years > 0) {
                $parts[] = $years . ' tahun';
            }
            
            // Bulan
            if ($months > 0) {
                $parts[] = $months . ' bulan';
            }
            
            // Hari (hanya jika < 30 hari atau jika tidak ada tahun/bulan)
            if ($days > 0 && ($years === 0 && $months === 0)) {
                $parts[] = $days . ' hari';
            }
            
            // Jika tidak ada bagian apapun
            if (empty($parts)) {
                return 'Hari ini';
            }
            
            // Gabungkan maksimal 2 bagian terbesar untuk keterbacaan
            $parts = array_slice($parts, 0, 2);
            
            return implode(' ', $parts);
        } catch (\Exception $e) {
            return 'Tidak ada data';
        }
    }

    // Auto-sync contract status based on end date
    private function syncContractStatusBasedOnDate($tglselesai_kontrak)
    {
        // Jika tidak ada tanggal akhir (null atau kosong), status 'aktif' (kontrak tetap/tidak terbatas)
        if (empty($tglselesai_kontrak) || is_null($tglselesai_kontrak)) {
            return 'aktif';
        }

        try {
            $endDate = \Carbon\Carbon::parse($tglselesai_kontrak);
            $now = \Carbon\Carbon::now();

            // Jika tanggal akhir sudah lewat, set status menjadi 'selesai'
            if ($endDate->endOfDay() < $now->startOfDay()) {
                return 'selesai';
            }

            // Jika tanggal akhir masih di masa depan, set status 'aktif'
            return 'aktif';
        } catch (\Exception $e) {
            // Jika ada error parsing tanggal, default ke 'aktif'
            return 'aktif';
        }
    }

    // Sync expired contracts: set status to 'selesai' when end date <= today
    public function syncExpiredContracts(): void
    {
        $now = \Carbon\Carbon::now()->endOfDay();

        // Update in bulk to avoid loading many models; set updated_by if available
        KaryawanKontrak::whereNotNull('tglselesai_kontrak')
            ->whereDate('tglselesai_kontrak', '<=', $now->toDateString())
            ->where('status', '!=', 'selesai')
            ->update([
                'status' => 'selesai',
                'updated_by' => Auth::id(),
                'updated_at' => now(),
            ]);
    }

    // Main properties
    public $karyawan_id;
    public $kontrak_karyawan_id; // ID record kontrak saat editing
    public $karyawanSearch = '';
    public $selectedKaryawanName = '';
    public $filteredKaryawan = [];
    
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
    public $approved_1 = null; // Approver from karyawan table
    public $approved_2 = null; // Approver from pengurus table

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

    // Filter properties
    public $jenis_kontrak_filter = '';
    public $status_kontrak_filter = '';
    public $sisa_kontrak_filter = '';

    public function generateNomorKontrak()
    {
        // Get current year and month
        $year = date('Y');
        $month = date('n'); // 1-12 without leading zeros
        
        // Convert month number to Roman numeral
        $romanMonths = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        $romanMonth = $romanMonths[$month];

        // Get the last contract number for this year
        $lastContract = KaryawanKontrak::where('nomor_kontrak', 'like', "%/KU-YKPI/$romanMonth/$year")
            ->orderBy('nomor_kontrak', 'desc')
            ->first();

        // Extract number from last contract or start from 0
        $lastNumber = 0;
        if ($lastContract) {
            $parts = explode('/', $lastContract->nomor_kontrak);
            $lastNumber = (int) $parts[0];
        }

        // Generate new number (increment by 1)
        $newNumber = $lastNumber + 1;

        // Format with leading zeros (3 digits)
        $formattedNumber = str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // Set the nomor_kontrak property
        $this->nomor_kontrak = "$formattedNumber/KU-YKPI/$romanMonth/$year";

        return $this->nomor_kontrak;
    }

    /**
     * Get nama kontrak from selected kontrak_id
     * Used to check if kontrak type is "TETAP" (permanent)
     */
    public function getSelectedKontrakType()
    {
        if (!$this->kontrak_id) {
            return null;
        }
        
        $kontrak = Kontrak::find($this->kontrak_id);
        return $kontrak ? $kontrak->nama_kontrak : null;
    }

    /**
     * Check if selected kontrak is "TETAP" (permanent)
     */
    public function isKontrakTetap()
    {
        $tipe = $this->getSelectedKontrakType();
        return $tipe && strtoupper(trim($tipe)) === 'TETAP';
    }

    public function mount($karyawan = null)
    {
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
            'karyawan_id' => 'required|exists:users,id',
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
            'approved_1' => 'nullable|exists:karyawan,id',
            'approved_2' => 'nullable|exists:pengurus,id',
        ];
       
        return $rules;
    }

    protected $validationAttributes = [
        'karyawan_id' => 'karyawan',
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
        'approved_1' => 'persetujuan 1',
        'approved_2' => 'persetujuan 2',
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
        
        // Get karyawan name for display
        $karyawan = $kontrak->karyawan;
        if ($karyawan && $karyawan->user) {
            $this->selectedKaryawanName = $karyawan->full_name ?? $karyawan->user->name;
        }
        
        $this->nomor_kontrak = $kontrak->nomor_kontrak;
        $this->kontrak_id = $kontrak->kontrak_id;
        $this->golongan_id = $kontrak->golongan_id;
        // Get department_id from unit
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
        $this->approved_1 = $kontrak->approved_1;
        $this->approved_2 = $kontrak->approved_2;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    // Search karyawan by name or email
    public function searchKaryawan($search)
    {
        $this->karyawanSearch = $search;
        
        if (empty($search)) {
            $this->filteredKaryawan = [];
            return;
        }

        $this->filteredKaryawan = \App\Models\Employee\Karyawan::with('user')
            ->where('statuskaryawan_id', 1) // Only active employees
            ->where(function ($query) use ($search) {
                $query->where('full_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('email', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('full_name')
            ->limit(10)
            ->get();
    }

    // Select karyawan from dropdown
    public function selectKaryawan($karyawanId, $fullName)
    {
        $this->karyawan_id = $karyawanId;
        $this->selectedKaryawanName = $fullName;
        $this->karyawanSearch = '';
        $this->filteredKaryawan = [];
    }

    // Clear selected karyawan
    public function clearKaryawan()
    {
        $this->karyawan_id = null;
        $this->selectedKaryawanName = '';
        $this->karyawanSearch = '';
        $this->filteredKaryawan = [];
    }

    /**
     * Auto-clear tanggal selesai kontrak jika jenis kontrak adalah "TETAP"
     * Dipanggil saat user mengubah jenis kontrak
     * Berlaku untuk KEDUANYA: CREATE dan EDIT mode
     * 
     * TETAP kontrak (permanent/unlimited) tidak boleh memiliki tanggal selesai
     */
    public function updatedKontrakId($value)
    {
        if (!$value) {
            return;
        }

        // ALWAYS auto-clear untuk kontrak TETAP, baik CREATE maupun EDIT
        if ($this->isKontrakTetap()) {
            $this->tglselesai_kontrak = null;
            Log::info("User selected TETAP contract type, auto-cleared tglselesai_kontrak (CREATE/EDIT)");
        }
    }

    /**
     * Handle multiple active contracts for same employee
     * If two contracts are active for the same employee, set older one to 'selesai'
     */
    private function handleDuplicateActiveContracts()
    {
        try {
            // Get all active contracts for this karyawan (excluding current one)
            $activeContracts = KaryawanKontrak::where('karyawan_id', $this->karyawan_id)
                ->where('status', 'aktif')
                ->where('id', '!=', $this->kontrak_karyawan_id ?? 'null')
                ->orderBy('tglmulai_kontrak', 'desc')
                ->get();

            // If there are any other active contracts, set them to 'selesai'
            if ($activeContracts->count() > 0) {
                foreach ($activeContracts as $contract) {
                    $contract->update([
                        'status' => 'selesai',
                        'tglselesai_kontrak' => now()->format('Y-m-d'),
                        'updated_by' => Auth::id(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Log the error but don't stop the save process
            Log::error('Error handling duplicate active contracts: ' . $e->getMessage());
        }
    }

    /**
     * Handle duplicate active contracts during EDIT operation
     * Checks old vs new status to determine proper action
     */
    private function handleDuplicateActiveContractsOnEdit($oldStatus, $newStatus)
    {
        try {
            // Jika status berubah menjadi 'aktif', tutup kontrak aktif lainnya untuk karyawan ini
            if ($newStatus === 'aktif' && $oldStatus !== 'aktif') {
                // Get all OTHER active contracts for this karyawan
                $otherActiveContracts = KaryawanKontrak::where('karyawan_id', $this->karyawan_id)
                    ->where('status', 'aktif')
                    ->where('id', '!=', $this->kontrak_karyawan_id)
                    ->orderBy('tglmulai_kontrak', 'desc')
                    ->get();

                // Set all other active contracts to 'selesai'
                foreach ($otherActiveContracts as $contract) {
                    $contract->update([
                        'status' => 'selesai',
                        'tglselesai_kontrak' => now()->format('Y-m-d'),
                        'updated_by' => Auth::id(),
                    ]);

                    Log::info("Contract #{$contract->id} auto-closed when contract #{$this->kontrak_karyawan_id} set to aktif");
                }
            }
            // Jika status berubah dari 'aktif' menjadi 'selesai', biarkan user menutup secara eksplisit
            elseif ($oldStatus === 'aktif' && $newStatus === 'selesai') {
                Log::info("Contract #{$this->kontrak_karyawan_id} explicitly closed by user");
            }
        } catch (\Exception $e) {
            Log::error('Error handling duplicate active contracts on edit: ' . $e->getMessage());
        }
    }

    public function save()
    {
        try {
            if (!$this->karyawan_id) {
                $this->dispatch('toast', [
                    'message' => 'Employee ID is required.',
                    'type' => 'error',
                ]);
                return;
            }

            $validated = $this->validate($this->rules());

            // Generate nomor kontrak if not provided
            if (empty($this->nomor_kontrak)) {
                $this->nomor_kontrak = $this->generateNomorKontrak();
            }

            // Convert empty string to NULL for date fields
            $tglselesai_kontrak = empty($this->tglselesai_kontrak) ? null : $this->tglselesai_kontrak;
            $tglmulai_kontrak = empty($this->tglmulai_kontrak) ? null : $this->tglmulai_kontrak;

            // IMPORTANT: If kontrak type is "TETAP" (permanent), ALWAYS force tglselesai to null
            // This applies to both CREATE and EDIT operations
            if ($this->isKontrakTetap()) {
                $tglselesai_kontrak = null;
                $this->status = 'aktif'; // TETAP kontrak always has 'aktif' status
                Log::info("Kontrak type is TETAP, forcing tglselesai_kontrak to null and status to aktif");
            }

            // Determine final status - ALWAYS sync from date, don't use user choice
            // Status should ALWAYS reflect the contract end date status
            // - If tglselesai is null (TETAP/unlimited): status = "aktif"
            // - If tglselesai has passed: status = "selesai"
            // - If tglselesai is in future: status = "aktif"
            $finalStatus = $this->syncContractStatusBasedOnDate($tglselesai_kontrak);
            
            Log::info("Auto-syncing status from date. tglselesai: $tglselesai_kontrak, finalStatus: $finalStatus");

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
                'tglmulai_kontrak' => $tglmulai_kontrak,
                'tglselesai_kontrak' => $tglselesai_kontrak,
                'status' => $finalStatus,
                'catatan' => $this->catatan,
                'deskripsi' => $this->deskripsi,
                'approved_1' => $this->approved_1,
                'approved_2' => $this->approved_2,
                'updated_by' => Auth::id(),
            ];

            if (!$this->isEdit) {
                $data['created_by'] = Auth::id();
            }

            if ($this->isEdit && $this->kontrak_karyawan_id) {
                // Get the old status before updating
                $oldKontrak = KaryawanKontrak::findOrFail($this->kontrak_karyawan_id);
                $oldStatus = $oldKontrak->status;
                
                // Update the contract
                $oldKontrak->update($data);
                
                // Handle duplicate active contracts based on status change
                $this->handleDuplicateActiveContractsOnEdit($oldStatus, $finalStatus);
                
                $this->dispatch('toast', [
                    'message' => "Data kontrak berhasil diedit",
                    'type' => 'success',
                ]);
                // Reset karyawan_id filter so all contracts are displayed after update
                $this->karyawan_id = null;
            } else {
                KaryawanKontrak::create($data);
                
                // Handle duplicate active contracts for new contracts
                if ($finalStatus === 'aktif') {
                    $this->handleDuplicateActiveContracts();
                }
                
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

    // SoftDelete
    public bool $confirmingDelete = false;
    public bool $deleteSuccess = false;
    public ?int $deleteId = null;

    // Force Delete
    public bool $confirmingForceDelete = false;
    public bool $forceDeleteSuccess = false;
    public ?int $forceDeleteId = null;

    // Restore Data
    public bool $confirmingRestore = false;
    public bool $restoreSuccess = false;
    public ?int $restoreId = null;

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
        $this->deleteSuccess = false;
    }

    public function delete()
    {
        try {
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

    public function confirmRestore($id)
    {
        $this->restoreId = $id;
        $this->confirmingRestore = true;
        $this->restoreSuccess = false;
    }

    public function restore()
    {
        KaryawanKontrak::withTrashed()->find($this->restoreId)?->restore();

        $this->restoreSuccess = true;

        // Trigger Alpine to auto-close modal
        $this->dispatch('modal:success-restore');

        $this->dispatch('toast', [
            'message' => 'Data Kontrak berhasil dipulihkan.',
            'type' => 'success',
        ]);

        $this->resetRestoreModal();
    }

    public function resetRestoreModal()
    {
        $this->confirmingRestore = false;
        $this->restoreSuccess = false;
        $this->restoreId = null;
    }

    public function confirmForceDelete($id)
    {
        $this->forceDeleteId = $id;
        $this->confirmingForceDelete = true;
        $this->forceDeleteSuccess = false;
    }

    public function forceDelete()
    {
        try {
            $data = KaryawanKontrak::withTrashed()->findOrFail($this->forceDeleteId);
            
            // Log sebelum delete
            Log::info('Attempting to force delete KaryawanKontrak', [
                'id' => $data->id,
                'karyawan_id' => $data->karyawan_id,
                'deleted_at' => $data->deleted_at,
            ]);
            
            // Disable foreign key checks sementara untuk menghindari constraint violation
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            try {
                // Force delete data
                $result = $data->forceDelete();
                
                if ($result) {
                    $this->forceDeleteSuccess = true;
                    $this->resetPage();
                    
                    Log::info('Successfully force deleted KaryawanKontrak', [
                        'id' => $this->forceDeleteId,
                    ]);

                    $this->dispatch('toast', [
                        'message' => 'Data Kontrak berhasil dihapus permanent.',
                        'type' => 'success',
                    ]);
                } else {
                    Log::warning('Force delete returned false for KaryawanKontrak', [
                        'id' => $this->forceDeleteId,
                    ]);
                    
                    $this->dispatch('toast', [
                        'message' => 'Gagal menghapus data permanent (result false).',
                        'type' => 'error',
                    ]);
                }
            } finally {
                // Re-enable foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error while force deleting KaryawanKontrak', [
                'error' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'id' => $this->forceDeleteId,
            ]);
            
            $errorMsg = 'Gagal menghapus data permanent.';
            if (strpos($e->getMessage(), 'Integrity constraint violation') !== false) {
                $errorMsg = 'Tidak dapat menghapus: Data masih direferensi oleh data lain.';
            }
            
            $this->dispatch('toast', [
                'message' => $errorMsg,
                'type' => 'error',
            ]);
        } catch (\Exception $e) {
            Log::error('Error while force deleting KaryawanKontrak', [
                'error' => $e->getMessage(),
                'id' => $this->forceDeleteId,
                'trace' => $e->getTraceAsString(),
            ]);
            
            $this->dispatch('toast', [
                'message' => 'Gagal menghapus data permanent: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        } finally {
            $this->confirmingForceDelete = false;
            $this->forceDeleteId = null;
        }
    }
    // End SoftDelete

    public function showDetail($id)
    {
        $this->selectedKontrak = KaryawanKontrak::with([
            'karyawan',
            'kontrak',
            'golongan',
            'unit',
            'jabatan',
            'creator',
            'updater',
            'approver1',
            'approver2'
        ])->find($id);
        $this->showModalDetail = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showModalDetail = false;
        $this->resetForm();
        // Reset karyawan_id filter so all contracts are displayed when modal is closed
        $this->karyawan_id = null;
    }

    private function resetForm()
    {
        $this->kontrak_karyawan_id = null;
        $this->karyawan_id = null;
        $this->karyawanSearch = '';
        $this->selectedKaryawanName = '';
        $this->filteredKaryawan = [];
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
        $this->approved_1 = null;
        $this->approved_2 = null;
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



    public function render()
    {
        // Ensure expired contracts are synced to status 'selesai' before rendering
        $this->syncExpiredContracts();

        $query = KaryawanKontrak::with([
            'karyawan.activeJabatan.jabatan',
            'karyawan.activeJabatan.unit',
            'kontrak:id,nama_kontrak',
            'golongan:id,nama_golongan',
            'unit:id,unit',
            'jabatan:id,nama_jabatan',
            'createdBy:id,name',
            'updatedBy:id,name'
        ]);

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

        // Filter by jenis kontrak
        $query->when($this->jenis_kontrak_filter, function ($q) {
            $q->whereHas('kontrak', function ($q) {
                $q->where('id', $this->jenis_kontrak_filter);
            });
        });

        // Filter by status kontrak
        $query->when($this->status_kontrak_filter, function ($q) {
            $q->where('status', $this->status_kontrak_filter);
        });

        // Filter by sisa kontrak (remaining days)
        $query->when($this->sisa_kontrak_filter, function ($q) {
            $today = \Carbon\Carbon::now();
            
            if ($this->sisa_kontrak_filter === 'expired') {
                // Sudah berakhir
                $q->whereNotNull('tglselesai_kontrak')
                  ->whereDate('tglselesai_kontrak', '<', $today);
            } elseif ($this->sisa_kontrak_filter === 'expiring_soon') {
                // Akan berakhir dalam 30 hari
                $q->whereNotNull('tglselesai_kontrak')
                  ->whereDate('tglselesai_kontrak', '>=', $today)
                  ->whereDate('tglselesai_kontrak', '<=', $today->copy()->addDays(30));
            } elseif ($this->sisa_kontrak_filter === 'valid') {
                // Masih berlaku (> 30 hari)
                $q->whereNotNull('tglselesai_kontrak')
                  ->whereDate('tglselesai_kontrak', '>', $today->copy()->addDays(30));
            } elseif ($this->sisa_kontrak_filter === 'unlimited') {
                // Tidak terbatas (tglselesai_kontrak NULL)
                $q->whereNull('tglselesai_kontrak');
            }
        });

        // Show deleted or only active (non-deleted)
        if ($this->showDeleted) {
            $query->onlyTrashed();
        }

        $kontraks = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Data untuk dropdown
        $masterKontrak = Kontrak::orderBy('nama_kontrak')->get();
        $masterGolongan = Golongan::orderBy('nama_golongan')->get();
        $masterDepartment = Departments::orderBy('department')->get();
        
        // Get active karyawan for dropdown (filter by statuskaryawan_id = 1 or active status)
        $masterKaryawan = \App\Models\Employee\Karyawan::with('user')
            ->where('statuskaryawan_id', 1) // Assuming 1 is 'Aktif' status
            ->orderBy('full_name')
            ->get();
        
        // Get karyawan with level_jabatan = top_managerial untuk approved_1 dropdown
        $masterApproved1 = \App\Models\Employee\Karyawan::with(['user', 'activeJabatan.jabatan'])
            ->where('statuskaryawan_id', 1) // Only active employees
            ->whereHas('activeJabatan.jabatan', function ($query) {
                $query->where('level_jabatan', 'top_managerial');
            })
            ->orderBy('full_name')
            ->get();
        
        // Filter units based on selected department
        $masterUnit = $this->department_id
            ? Units::where('department_id', $this->department_id)->orderBy('unit')->get()
            : collect();

        // Filter jabatan based on selected department
        $masterJabatan = $this->department_id
            ? Jabatans::where('department_id', $this->department_id)->orderBy('nama_jabatan')->get()
            : collect();

        // Get mata pelajaran for dropdown
        $masterMapel = Mapel::orderBy('nama_mapel')->get();

        // Get pengurus for approved_2 dropdown
        $masterPengurus = Pengurus::orderBy('nama_pengurus')->get();

        return view('livewire.admin.karyawan.kontrak.index', compact(
            'kontraks',
            'masterKontrak',
            'masterGolongan',
            'masterDepartment',
            'masterKaryawan',
            'masterApproved1',
            'masterUnit',
            'masterJabatan',
            'masterMapel',
            'masterPengurus'
        ));
    }
}
