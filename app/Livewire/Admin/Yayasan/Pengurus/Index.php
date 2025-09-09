<?php

namespace App\Livewire\Admin\Yayasan\Pengurus;

use App\Models\Master\Departments;
use App\Models\Master\Jabatans;
use App\Models\User;
use App\Models\Yayasan\Pengurus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;


class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $foto;
    public $originalFoto; // Store original photo path

    public $ttd;
    public $originalTtd; // Store original ttd path

    public $userId; // ID dari tabel users
    public $pengurusId; // ID dari tabel pengurus

    public $jabatan_id = '';
    public $nama_pengurus = '';
    public $inisial = null;
    public $gelar_depan = '';
    public $gelar_belakang = '';
    public $hp = '';
    public $jenis_kelamin = '';
    public $tempat_lahir = '';
    public $tanggal_lahir = null;
    public $alamat = '';
    public $tanggal_masuk = '';
    public $tanggal_keluar = null;
    public $posisi = '';
    public $is_active = true;
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    // Properties for search and filter
    public $search = '';
    public $statusFilter = '';
    public $posisiFilter = '';
    public $jenisFilter = '';
    public $levelFilter = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $showModalDetail = false;
    public $selectedJabatan;



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
            'jabatan_id' => [
                'required',
                'integer',
                'exists:master_jabatan,id'
            ],
            'nama_pengurus' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('pengurus', 'nama_pengurus')->ignore($this->pengurusId)
            ],
            'inisial' => [
                'required',
                'string',
                'size:3',
                Rule::unique('pengurus', 'inisial')->ignore($this->pengurusId)
            ],
            'hp' => [
                'nullable',
                'regex:/^\+62\s\d{3}-\d{4}-\d{4}$/',
                'max:17',
                Rule::unique('pengurus', 'hp')->ignore($this->pengurusId)
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->userId)
            ],
            'password' => [
                $this->pengurusId ? 'nullable' : 'required',
                'string',
                'min:8',
                'confirmed'
            ],
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date|after_or_equal:tanggal_masuk',
            'gelar_depan' => 'nullable|string|max:10',
            'gelar_belakang' => 'nullable|string|max:15',
            'jenis_kelamin' => 'required|string|in:laki-laki,perempuan',
            'posisi' => 'required|string|in:ketua,anggota',
            'is_active' => 'boolean',
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

        // Validasi TTD - berbeda untuk create dan edit
        if ($this->ttd instanceof \Illuminate\Http\UploadedFile) {
            // Jika ada file yang diupload (create atau edit dengan file baru)
            $rules['ttd'] = [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048' // 2MB max
            ];
        } else {
            // Jika tidak ada file upload (edit tanpa ganti TTD)
            $rules['ttd'] = 'nullable|string';
        }

        return $rules;
    }

    protected $validationAttributes = [
        'jabatan_id' => 'Jabatan',
        'nama_pengurus' => 'Nama Pengurus',
        'is_active' => 'Status',
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

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $pengurus = Pengurus::with('user')->find($id);

        $this->pengurusId = $pengurus->id;
        $this->userId = $pengurus->user_id;
        $this->jabatan_id = $pengurus->jabatan_id;
        $this->posisi = $pengurus->posisi;
        $this->nama_pengurus = $pengurus->nama_pengurus;
        $this->email = $pengurus->user?->email;
        $this->inisial = $pengurus->inisial;
        $this->hp = $pengurus->hp;
        $this->jenis_kelamin = $pengurus->jenis_kelamin;
        $this->gelar_depan = $pengurus->gelar_depan;
        $this->gelar_belakang = $pengurus->gelar_belakang;
        $this->tempat_lahir = $pengurus->tempat_lahir;
        $this->tanggal_lahir = $pengurus->tanggal_lahir;
        $this->alamat = $pengurus->alamat;
        $this->foto = $pengurus->foto;
        $this->originalFoto = $pengurus->foto;
        $this->ttd = $pengurus->ttd;
        $this->originalTtd = $pengurus->ttd;
        $this->tanggal_masuk = $pengurus->tanggal_masuk;
        $this->tanggal_keluar = $pengurus->tanggal_keluar;
        $this->is_active = $pengurus->is_active;

        $this->isEdit = true;
        $this->showModal = true;
    }


    public function save()
    {
        DB::beginTransaction(); // mulai transaksi

        try {
            $this->validate();

            // Normalisasi nomor HP
            $plainHp = preg_replace('/[^\d+]/', '', $this->hp);

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

            // Handle TTD upload
            $ttdPath = null;
            if ($this->ttd instanceof \Illuminate\Http\UploadedFile) {
                // Ada TTD baru yang diupload
                $ttdPath = $this->ttd->store('tandatangan', 'public');

                // Jika edit dan ada TTD lama, hapus TTD lama
                if ($this->isEdit && $this->originalTtd && Storage::disk('public')->exists($this->originalTtd)) {
                    Storage::disk('public')->delete($this->originalTtd);
                }
            } elseif ($this->isEdit && is_string($this->ttd)) {
                // Edit mode dan TTD tidak diubah (tetap string path)
                $ttdPath = $this->ttd;
            }

            if ($this->isEdit) {
                // Edit Pengurus
                $pengurus = Pengurus::findOrFail($this->pengurusId);

                // Persiapkan data user untuk update
                $userData = [];

                // Hanya update field yang ada nilainya dan berbeda dari yang lama
                if (!empty($this->email) && $this->email !== $pengurus->user->email) {
                    $userData['email'] = $this->email;
                }

                if (!empty($this->nama_pengurus) && $this->nama_pengurus !== $pengurus->user->name) {
                    $userData['name'] = $this->nama_pengurus;
                }

                // Hanya update password jika diisi
                if (!empty($this->password)) {
                    $userData['password'] = bcrypt($this->password);
                }

                // Update user hanya jika ada data yang berubah
                if (!empty($userData)) {
                    $pengurus->user->update($userData);
                }

                // Persiapkan data pengurus
                $pengurusData = [
                    'jabatan_id'     => $this->jabatan_id,
                    'nama_pengurus'  => $this->nama_pengurus,
                    'inisial'        => $this->inisial ?: null,
                    'hp'             => $plainHp,
                    'jenis_kelamin'  => $this->jenis_kelamin,
                    'gelar_depan'    => $this->gelar_depan,
                    'gelar_belakang' => $this->gelar_belakang,
                    'tempat_lahir'   => $this->tempat_lahir,
                    'tanggal_lahir'  => $this->tanggal_lahir ?: null,
                    'alamat'         => $this->alamat,
                    'tanggal_masuk'  => $this->tanggal_masuk ?: null,
                    'tanggal_keluar' => $this->tanggal_keluar ?: null,
                    'posisi'         => $this->posisi,
                    'is_active'      => $this->is_active,
                ];

                // Update foto dan ttd hanya jika ada perubahan
                if ($fotoPath !== null) {
                    $pengurusData['foto'] = $fotoPath;
                }
                if ($ttdPath !== null) {
                    $pengurusData['ttd'] = $ttdPath;
                }

                $pengurus->update($pengurusData);

                $this->dispatch('toast', [
                    'message' => "Data berhasil diedit",
                    'type'    => 'success',
                ]);
            } else {
                // Validasi field wajib untuk create
                if (empty($this->email) || empty($this->nama_pengurus) || empty($this->password)) {
                    throw new \Exception('Email, Nama, dan Password wajib diisi untuk pengurus baru.');
                }

                // Create user baru
                $user = User::create([
                    'name'     => $this->nama_pengurus,
                    'email'    => $this->email,
                    'password' => bcrypt($this->password),
                ]);

                // Create pengurus baru
                Pengurus::create([
                    'user_id'        => $user->id,
                    'jabatan_id'     => $this->jabatan_id,
                    'nama_pengurus'  => $this->nama_pengurus,
                    'inisial'        => $this->inisial ?: null,
                    'hp'             => $plainHp,
                    'jenis_kelamin'  => $this->jenis_kelamin,
                    'gelar_depan'    => $this->gelar_depan,
                    'gelar_belakang' => $this->gelar_belakang,
                    'tempat_lahir'   => $this->tempat_lahir,
                    'tanggal_lahir'  => $this->tanggal_lahir ?: null,
                    'alamat'         => $this->alamat,
                    'foto'           => $fotoPath,
                    'ttd'            => $ttdPath,
                    'tanggal_masuk'  => $this->tanggal_masuk ?: null,
                    'tanggal_keluar' => $this->tanggal_keluar ?: null,
                    'posisi'         => $this->posisi,
                    'is_active'      => $this->is_active,
                ]);

                $this->dispatch('toast', [
                    'message' => "Data berhasil disimpan",
                    'type'    => 'success',
                ]);
            }

            DB::commit(); // sukses → simpan perubahan
            $this->closeModal();
        } catch (ValidationException $e) {
            DB::rollBack(); // rollback kalau error

            // Hapus file yang baru diupload jika terjadi error
            if (!empty($fotoPath) && ($this->isEdit ? $fotoPath !== $this->originalFoto : true)) {
                Storage::disk('public')->delete($fotoPath);
            }
            if (!empty($ttdPath) && ($this->isEdit ? $ttdPath !== $this->originalTtd : true)) {
                Storage::disk('public')->delete($ttdPath);
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
            if (!empty($ttdPath) && ($this->isEdit ? $ttdPath !== $this->originalTtd : true)) {
                Storage::disk('public')->delete($ttdPath);
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
        Pengurus::find($this->deleteId)?->delete();

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
        Pengurus::withTrashed()->find($this->restoreId)?->restore();

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

        $pengurus = Pengurus::withTrashed()->find($this->forceDeleteId);

        // Cek apakah pengurus ditemukan
        if (!$pengurus) {
            $this->dispatch('toast', [
                'message' => 'Data tidak ditemukan.',
                'type' => 'error',
            ]);
            return;
        }

        // Hapus file foto jika ada
        if ($pengurus->foto && Storage::disk('public')->exists($pengurus->foto)) {
            Storage::disk('public')->delete($pengurus->foto);
        }

        // Hapus file TTD jika ada
        if ($pengurus->ttd && Storage::disk('public')->exists($pengurus->ttd)) {
            Storage::disk('public')->delete($pengurus->ttd);
        }

        // Hapus user yang terkait
        if ($pengurus->user) {
            $pengurus->user->forceDelete();
        }

        // Jika tidak ada masalah, baru lakukan force delete
        $pengurus->forceDelete();

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

    public function showDetail($id)
    {
        $this->selectedJabatan = Pengurus::with(['jabatan.department'])
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
        $this->pengurusId = null;
        $this->userId = null;
        $this->jabatan_id = '';
        $this->nama_pengurus = '';
        $this->inisial = null;
        $this->gelar_depan = '';
        $this->gelar_belakang = '';
        $this->hp = '';
        $this->jenis_kelamin = '';
        $this->tempat_lahir = '';
        $this->tanggal_lahir = null;
        $this->alamat = '';
        $this->foto = null;
        $this->originalFoto = null;
        $this->ttd = null;
        $this->originalTtd = null;
        $this->tanggal_masuk = '';
        $this->tanggal_keluar = null;
        $this->posisi = '';
        $this->is_active = true;
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->resetValidation();
    }

    public function toggleStatus($id)
    {
        $unit = Pengurus::findOrFail($id);
        $unit->update(['is_active' => !$unit->is_active]);

        $this->dispatch('toast', [
            'message' => "Status berhasil diedit",
            'type' => 'success',
        ]);
    }


    public function render()
    {
        $query = Pengurus::with([
            'jabatan.department'
        ]);

        // tampilkan data terhapus jika perlu
        $query->when($this->showDeleted, function ($q) {
            $q->onlyTrashed(); // hanya data yang sudah dihapus
        });

        // filter by status
        $query->when($this->statusFilter !== '', function ($q) {
            $q->where('is_active', (bool) $this->statusFilter);
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
                $q->where('nama_jabatan', 'like', $search)
                    ->orWhere('kode_jabatan', 'like', $search)
                    ->orWhereHas('department', function ($department) use ($search) {
                        $department->where('department', 'like', $search);
                    });
            });
        });

        // urutkan & paginasi
        $penguruss = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $jabatans = Jabatans::with('department')
            ->where('is_active', 1)
            ->whereHas('department', function ($q) {
                $q->where('department', 'YAYASAN'); // sesuaikan field penanda
            })
            ->orderBy('nama_jabatan') // urut berdasarkan nama jabatan
            ->get();

        return view('livewire.admin.yayasan.pengurus.index', compact('penguruss', 'jabatans'));
    }
}
