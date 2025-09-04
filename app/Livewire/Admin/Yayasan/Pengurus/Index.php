<?php

namespace App\Livewire\Admin\Yayasan\Pengurus;

use App\Models\Master\Departments;
use App\Models\Master\Jabatans;
use App\Models\User;
use App\Models\Yayasan\Pengurus;
use Illuminate\Support\Facades\DB;
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

    protected $rules = [
        'foto' => 'nullable|image|max:2048', // 2MB max
        // ... other rules
    ];

    public $foto;
    public $ttd;
    public $pengurusId = '';
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
        return [
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
            'hp' => [
                    'nullable',
                    'regex:/^\+62\s\d{3}-\d{4}-\d{4}$/',
                    'max:17',
                    Rule::unique('pengurus', 'hp')->ignore($this->pengurusId)
                ],
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date',
            'is_active' => 'boolean',
        ];
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
        $pengurus = Pengurus::findOrFail($id);

        $this->pengurusId = $pengurus->id;
        $this->department_id = $pengurus->department_id;
        $this->jabatan_id = $pengurus->jabatan_id;
        $this->nama_pengurus = $pengurus->nama_pengurus;
        $this->hp = $pengurus->hp;
        $this->jenis_kelamin = $pengurus->jenis_kelamin;
        $this->gelar_depan = $pengurus->gelar_depan;
        $this->gelar_belakang = $pengurus->gelar_belakang;
        $this->tempat_lahir = $pengurus->tempat_lahir;
        $this->tanggal_lahir = $pengurus->tanggal_lahir;
        $this->alamat = $pengurus->alamat;
        $this->foto = $pengurus->foto;
        $this->ttd = $pengurus->ttd;
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

        // Siapkan path foto & ttd
        $fotoPath = $this->foto ? $this->foto->store('fotos', 'public') : null;
        $ttdPath  = $this->ttd ? $this->ttd->store('tandatangan', 'public') : null;

        if ($this->isEdit) {
            // Edit Pengurus
            $pengurus = Pengurus::findOrFail($this->pengurusId);

            // Update user terkait
            $user = $pengurus->user;
            if ($user) {
                $user->update([
                    'name'     => $this->nama_pengurus,
                    'email'    => $this->email,
                    'password' => $this->password ? bcrypt($this->password) : $user->password,
                ]);
            }

            $data = [
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

            if ($fotoPath) $data['foto'] = $fotoPath;
            if ($ttdPath)  $data['ttd']  = $ttdPath;

            $pengurus->update($data);

            $this->dispatch('toast', [
                'message' => "Data berhasil diedit",
                'type'    => 'success',
            ]);

        } else {
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
        $errors = $e->validator->errors()->all();
        $count  = count($errors);

        $this->dispatch('toast', [
            'message' => "Terdapat $count kesalahan:\n- " . implode("\n- ", $errors),
            'type'    => 'error',
        ]);
        throw $e;

    } catch (\Exception $e) {
        DB::rollBack(); // rollback kalau error
        $this->dispatch('toast', [
            'message' => 'Terjadi kesalahan server.',
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

        $unit = Pengurus::withTrashed()->find($this->forceDeleteId);

        // Cek apakah unit ditemukan
        if (!$unit) {
            $this->dispatch('toast', [
                'message' => 'Data tidak ditemukan.',
                'type' => 'error',
            ]);
            return;
        }

        // Jika tidak ada masalah, baru lakukan force delete
        $unit->forceDelete();

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
        $this->jabatanId = null;
        $this->department_id = '';
        $this->nama_jabatan = '';
        $this->kode_jabatan = '';
        $this->jenis_jabatan = '';
        $this->level_jabatan = '';
        $this->tugas_pokok = '';
        $this->requirements = '';
        $this->min_salary = '';
        $this->max_salary = '';
        $this->is_active = true;
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

        // filter by jenis
        $query->when($this->jenisFilter !== '', function ($q) {
            $q->where('jenis_jabatan', $this->jenisFilter);
        });

        // filter by level
        $query->when($this->levelFilter !== '', function ($q) {
            $q->where('level_jabatan', $this->levelFilter);
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
            ->whereHas('department', function($q) {
                $q->where('department', 'YAYASAN'); // sesuaikan field penanda
            })
            ->orderBy('nama_jabatan') // urut berdasarkan nama jabatan
            ->get();

        return view('livewire.admin.yayasan.pengurus.index', compact('penguruss', 'jabatans'));
    }
}