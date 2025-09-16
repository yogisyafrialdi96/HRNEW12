<?php

namespace App\Livewire\Admin\Karyawan;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Employee\Karyawan;
use App\Models\Master\Golongan;
use App\Models\Master\StatusKawin;
use App\Models\Master\StatusPegawai;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class KaryawanForm extends Component
{
    public Karyawan $karyawan;
    public string $activeTab;

    // Data karyawan
    public string $full_name;
    public $foto;
    public $originalFoto; // Store original photo path

    public $ttd;
    public $originalTtd; // Store original ttd path

    public $userId; // ID dari tabel user
    public $karyawanId; // ID dari tabel karyawan

    public $panggilan;
    public $inisial;
    public $email;
    public $password;
    public $password_confirmation;
    public $nip;
    public $jenis_karyawan;
    public $statuskaryawan_id;
    public $statuskawin_id;
    public $golongan_id;
    public $npwp;
    public $tgl_masuk;
    public $tgl_karyawan_tetap;
    public $tgl_berhenti;
    public $nik;
    public $nkk;
    public $hp;
    public $whatsapp;
    public $gender;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $agama;
    public $status_kawin;
    public $pndk_akhir;
    public $gelar_depan;
    public $gelar_belakang;
    public $blood_type;
    public $emergency_contact_name;
    public $emergency_contact_phone;

    // Data alamat KTP
    public $alamat_ktp;
    public $rt_ktp;
    public $rw_ktp;
    public $provktp_id;
    public $kabktp_id;
    public $kecktp_id;
    public $desaktp_id;
    
    // Data alamat domisili
    public bool $domisili_sama_ktp = false;
    public $alamat_dom;
    public $rt_dom;
    public $rw_dom;
    public $provdom_id;
    public $kabdom_id;
    public $kecdom_id;
    public $desdom_id;

    // Lists untuk dropdown KTP
    public $provinsiList = [];
    public $kabupatenList = [];
    public $kecamatanList = [];
    public $desaList = [];
    
    // Lists untuk dropdown Domisili
    public $kabDomisiliList = [];
    public $kecDomisiliList = [];
    public $desaDomisiliList = [];

    public function mount(Karyawan $karyawan)
    {
        $this->activeTab = 'profile';
        $this->karyawanId = $karyawan->id;

        $karyawanemail = Karyawan::with('user')->find($karyawan->id);
        $this->userId = $karyawanemail->user?->id;

        $this->full_name = $karyawan->full_name;
        $this->panggilan = $karyawan->panggilan;
        $this->inisial = $karyawan->inisial;
        $this->email = $karyawanemail->user?->email;
        $this->nip = $karyawan->nip;
        $this->jenis_karyawan = $karyawan->jenis_karyawan;
        $this->statuskaryawan_id = $karyawan->statuskaryawan_id;
        $this->statuskawin_id = $karyawan->statuskawin_id;
        $this->golongan_id = $karyawan->golongan_id;
        $this->npwp = $karyawan->npwp;
        $this->tgl_masuk = $karyawan->tgl_masuk;
        $this->tgl_karyawan_tetap = $karyawan->tgl_karyawan_tetap;
        $this->tgl_berhenti = $karyawan->tgl_berhenti;

        $this->nik = $karyawan->nik;
        $this->nkk = $karyawan->nkk;
        $this->hp = $karyawan->hp;
        $this->whatsapp = $karyawan->whatsapp;
        $this->gender = $karyawan->gender;
        $this->tempat_lahir = $karyawan->tempat_lahir;
        $this->tanggal_lahir = $karyawan->tanggal_lahir;
        $this->agama = $karyawan->agama;
        $this->status_kawin = $karyawan->status_kawin;
        $this->pndk_akhir = $karyawan->pndk_akhir;
        $this->gelar_depan = $karyawan->gelar_depan;
        $this->gelar_belakang = $karyawan->gelar_belakang;
        $this->blood_type = $karyawan->blood_type;
        $this->emergency_contact_name = $karyawan->emergency_contact_name;
        $this->emergency_contact_phone = $karyawan->emergency_contact_phone;

        // Set data alamat KTP
        $this->alamat_ktp = $karyawan->alamat_ktp;
        $this->rt_ktp = $karyawan->rt_ktp;
        $this->rw_ktp = $karyawan->rw_ktp;
        $this->provktp_id = $karyawan->prov_id;
        $this->kabktp_id = $karyawan->kab_id;
        $this->kecktp_id = $karyawan->kec_id;
        $this->desaktp_id = $karyawan->desa_id;
        
        // Set data alamat domisili
        $this->alamat_dom = $karyawan->alamat_dom;
        $this->rt_dom = $karyawan->rt_dom;
        $this->rw_dom = $karyawan->rw_dom;
        $this->provdom_id = $karyawan->provdom_id;
        $this->kabdom_id = $karyawan->kabdom_id;
        $this->kecdom_id = $karyawan->kecdom_id;
        $this->desdom_id = $karyawan->desadom_id;

        // Load provinsi list
        $this->loadProvinsiList();
        
        // Load data yang sudah ada untuk KTP
        if ($this->provktp_id) {
            $this->loadKabupatenList($this->provktp_id);
        }
        
        if ($this->kabktp_id) {
            $this->loadKecamatanList($this->kabktp_id);
        }
        
        if ($this->kecktp_id) {
            $this->loadDesaList($this->kecktp_id);
        }

        // Load data yang sudah ada untuk Domisili
        if ($this->provdom_id) {
            $this->loadKabDomisiliList($this->provdom_id);
        }
        
        if ($this->kabdom_id) {
            $this->loadKecDomisiliList($this->kabdom_id);
        }
        
        if ($this->kecdom_id) {
            $this->loadDesaDomisiliList($this->kecdom_id);
        }
    }

    // Methods untuk load data dropdown
    private function loadProvinsiList()
    {
        $this->provinsiList = \App\Models\Wilayah\Provinsi::orderBy('nama')->get();
    }

    private function loadKabupatenList($provinsiId)
    {
        if ($provinsiId) {
            $this->kabupatenList = \App\Models\Wilayah\Kabupaten::where('provinsi_id', $provinsiId)
                ->orderBy('nama')->get();
        } else {
            $this->kabupatenList = collect();
        }
    }

    private function loadKecamatanList($kabupatenId)
    {
        if ($kabupatenId) {
            $this->kecamatanList = \App\Models\Wilayah\Kecamatan::where('kabupaten_id', $kabupatenId)
                ->orderBy('nama')->get();
        } else {
            $this->kecamatanList = collect();
        }
    }

    private function loadDesaList($kecamatanId)
    {
        if ($kecamatanId) {
            $this->desaList = \App\Models\Wilayah\Desa::where('kecamatan_id', $kecamatanId)
                ->orderBy('nama')->get();
        } else {
            $this->desaList = collect();
        }
    }

    private function loadKabDomisiliList($provinsiId)
    {
        if ($provinsiId) {
            $this->kabDomisiliList = \App\Models\Wilayah\Kabupaten::where('provinsi_id', $provinsiId)
                ->orderBy('nama')->get();
        } else {
            $this->kabDomisiliList = collect();
        }
    }

    private function loadKecDomisiliList($kabupatenId)
    {
        if ($kabupatenId) {
            $this->kecDomisiliList = \App\Models\Wilayah\Kecamatan::where('kabupaten_id', $kabupatenId)
                ->orderBy('nama')->get();
        } else {
            $this->kecDomisiliList = collect();
        }
    }

    private function loadDesaDomisiliList($kecamatanId)
    {
        if ($kecamatanId) {
            $this->desaDomisiliList = \App\Models\Wilayah\Desa::where('kecamatan_id', $kecamatanId)
                ->orderBy('nama')->get();
        } else {
            $this->desaDomisiliList = collect();
        }
    }

    // Event handlers untuk dropdown KTP
    public function updatedProvktpId($value)
    {
        $this->loadKabupatenList($value);
        
        // Reset nilai dan list di bawahnya
        $this->kabktp_id = null;
        $this->kecktp_id = null;
        $this->desaktp_id = null;
        $this->kecamatanList = collect();
        $this->desaList = collect();
    }

    public function updatedKabktpId($value)
    {
        $this->loadKecamatanList($value);
        
        // Reset nilai dan list di bawahnya
        $this->kecktp_id = null;
        $this->desaktp_id = null;
        $this->desaList = collect();
    }

    public function updatedKecktpId($value)
    {
        $this->loadDesaList($value);
        
        // Reset nilai di bawahnya
        $this->desaktp_id = null;
    }

    // Event handlers untuk dropdown Domisili
    public function updatedProvdomId($value)
    {
        $this->loadKabDomisiliList($value);
        
        // Reset nilai dan list di bawahnya
        $this->kabdom_id = null;
        $this->kecdom_id = null;
        $this->desdom_id = null;
        $this->kecDomisiliList = collect();
        $this->desaDomisiliList = collect();
    }

    public function updatedKabdomId($value)
    {
        $this->loadKecDomisiliList($value);
        
        // Reset nilai dan list di bawahnya
        $this->kecdom_id = null;
        $this->desdom_id = null;
        $this->desaDomisiliList = collect();
    }

    public function updatedKecdomId($value)
    {
        $this->loadDesaDomisiliList($value);
        
        // Reset nilai di bawahnya
        $this->desdom_id = null;
    }

    // Method untuk copy alamat KTP ke domisili
    public function updatedDomisiliSamaKtp($value)
    {
        if ($value) {
            // Copy data dari KTP ke domisili
            $this->alamat_dom = $this->alamat_ktp;
            $this->rt_dom = $this->rt_ktp;
            $this->rw_dom = $this->rw_ktp;
            $this->provdom_id = $this->provktp_id;
            $this->kabdom_id = $this->kabktp_id;
            $this->kecdom_id = $this->kecktp_id;
            $this->desdom_id = $this->desaktp_id;

            // Load dropdown lists untuk domisili
            if ($this->provdom_id) {
                $this->loadKabDomisiliList($this->provdom_id);
            }
            if ($this->kabdom_id) {
                $this->loadKecDomisiliList($this->kabdom_id);
            }
            if ($this->kecdom_id) {
                $this->loadDesaDomisiliList($this->kecdom_id);
            }
        } else {
            // Reset data domisili
            $this->alamat_dom = null;
            $this->rt_dom = null;
            $this->rw_dom = null;
            $this->provdom_id = null;
            $this->kabdom_id = null;
            $this->kecdom_id = null;
            $this->desdom_id = null;
            $this->kabDomisiliList = collect();
            $this->kecDomisiliList = collect();
            $this->desaDomisiliList = collect();
        }
    }

    public function rules()
    {
        $rules = [
            'full_name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('karyawan', 'full_name')->ignore($this->karyawanId, 'id')
            ],
            'inisial' => [
                'required',
                'string',
                'size:3',
                Rule::unique('karyawan', 'inisial')->ignore($this->karyawanId)
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
            'nip' => [
                'required',
                'string',
                'size:6',
                Rule::unique('karyawan', 'nip')->ignore($this->karyawanId)
            ],
            'hp' => [
                'nullable',
                'regex:/^\+62\s\d{3}-\d{4}-\d{4}$/',
                'max:17',
                Rule::unique('karyawan', 'hp')->ignore($this->karyawanId)
            ],
            'whatsapp' => [
                'nullable',
                'regex:/^\+62\s\d{3}-\d{4}-\d{4}$/',
                'max:17',
                Rule::unique('karyawan', 'whatsapp')->ignore($this->karyawanId)
            ],
            'jenis_karyawan' => 'required|string|in:Guru,Pegawai',
            'statuskaryawan_id' => 'required|exists:master_statuspegawai,id',
            'statuskawin_id' => 'required|exists:master_statuskawin,id',
            'golongan_id' => 'required|exists:master_golongan,id',
            'npwp' => 'nullable|digits:16',
            'tgl_masuk' => 'required|date',
            'tgl_berhenti' => 'nullable|date|after:tgl_masuk',
            'tgl_karyawan_tetap' => 'nullable|date|after:tgl_masuk',
            'nik' => [
                'required',
                'digits:16',
                Rule::unique('karyawan', 'nik')->ignore($this->karyawanId)
            ],
            'nkk' => [
                'required',
                'digits:16',
                Rule::unique('karyawan', 'nkk')->ignore($this->karyawanId)
            ],
            'gender' => 'required|string|in:laki-laki,perempuan',
            'tempat_lahir' => 'required|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'agama' => 'required|in:Islam,Hindu,Budha,Katolik,Protestan,Konghucu',
            'status_kawin' => 'required|in:lajang,menikah,cerai',
            'pndk_akhir' => 'required|in:SD,SMP,SMA,D1,D2,D3,D4,S1,S2,S3',
            'gelar_depan' => 'nullable|max:20',
            'gelar_belakang' => 'nullable|max:20',
            'blood_type' => 'nullable|in:A,B,AB,O',
            'emergency_contact_name' => 'nullable|max:255',
            'emergency_contact_phone' => 'nullable|max:17|regex:/^\+62\s\d{3}-\d{4}-\d{4}$/',

            // Validasi alamat KTP
            'alamat_ktp' => 'required|string|max:255',
            'rt_ktp' => 'required|string|max:5',
            'rw_ktp' => 'required|string|max:5',
            'desaktp_id' => 'required|string|exists:desa,id',
            'kecktp_id' => 'required|string|exists:kecamatan,id',
            'kabktp_id' => 'required|string|exists:kabupaten,id',
            'provktp_id' => 'required|string|exists:provinsi,id',
        ];

        // Jika domisili beda, validasi tambahan
        if (!$this->domisili_sama_ktp) {
            $rules = array_merge($rules, [
                'alamat_dom' => 'required|string|max:255',
                'rt_dom' => 'required|string|max:5',
                'rw_dom' => 'required|string|max:5',
                'provdom_id' => 'required|string|exists:provinsi,id',
                'kabdom_id' => 'required|string|exists:kabupaten,id',
                'kecdom_id' => 'required|string|exists:kecamatan,id',
                'desdom_id' => 'required|string|exists:desa,id',
            ]);
        }

        // Validasi foto - berbeda untuk create dan edit
        if ($this->foto instanceof \Illuminate\Http\UploadedFile) {
            $rules['foto'] = [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048' // 2MB max
            ];
        } else {
            $rules['foto'] = 'nullable|string';
        }

        // Validasi TTD - berbeda untuk create dan edit
        if ($this->ttd instanceof \Illuminate\Http\UploadedFile) {
            $rules['ttd'] = [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048' // 2MB max
            ];
        } else {
            $rules['ttd'] = 'nullable|string';
        }

        return $rules;
    }

    protected $validationAttributes = [
        'full_name' => 'Nama Lengkap',
        'inisial' => 'Inisial',
        'email' => 'Email',
        'password' => 'Password',
        'nip' => 'NIP',
        'hp' => 'Nomor HP',
        'whatsapp' => 'WhatsApp',
        'jenis_karyawan' => 'Jenis Karyawan',
        'statuskaryawan_id' => 'Status Karyawan',
        'statuskawin_id' => 'Status Kawin',
        'golongan_id' => 'Golongan',
        'npwp' => 'NPWP',
        'tgl_masuk' => 'Tanggal Masuk',
        'tgl_berhenti' => 'Tanggal Berhenti',
        'tgl_karyawan_tetap' => 'Tanggal Karyawan Tetap',
        'nik' => 'NIK',
        'nkk' => 'NKK',
        'gender' => 'Jenis Kelamin',
        'tempat_lahir' => 'Tempat Lahir',
        'tanggal_lahir' => 'Tanggal Lahir',
        'agama' => 'Agama',
        'status_kawin' => 'Status Kawin',
        'pndk_akhir' => 'Pendidikan Akhir',
        'gelar_depan' => 'Gelar Depan',
        'gelar_belakang' => 'Gelar Belakang',
        'blood_type' => 'Golongan Darah',
        'emergency_contact_name' => 'Nama Kontak Darurat',
        'emergency_contact_phone' => 'Telepon Kontak Darurat',
        'alamat_ktp' => 'Alamat KTP',
        'rt_ktp' => 'RT KTP',
        'rw_ktp' => 'RW KTP',
        'desaktp_id' => 'Desa KTP',
        'kecktp_id' => 'Kecamatan KTP',
        'kabktp_id' => 'Kabupaten KTP',
        'provktp_id' => 'Provinsi KTP',
        'alamat_dom' => 'Alamat Domisili',
        'rt_dom' => 'RT Domisili',
        'rw_dom' => 'RW Domisili',
        'provdom_id' => 'Provinsi Domisili',
        'kabdom_id' => 'Kabupaten Domisili',
        'kecdom_id' => 'Kecamatan Domisili',
        'desdom_id' => 'Desa Domisili',
        'foto' => 'Foto',
        'ttd' => 'Tanda Tangan',
        'jabatan_id' => 'Jabatan',
        'nama_pengurus' => 'Nama Pengurus',
        'is_active' => 'Status'
    ];


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

           
                // Edit Karyawan
                $karyawan = Karyawan::findOrFail($this->karyawanId);

                // Persiapkan data user untuk update
                $userData = [];

                // Hanya update field yang ada nilainya dan berbeda dari yang lama
                if (!empty($this->email) && $this->email !== $karyawan->user->email) {
                    $userData['email'] = $this->email;
                }

                if (!empty($this->full_name) && $this->full_name !== $karyawan->user->name) {
                    $userData['name'] = $this->full_name;
                }

                // Hanya update password jika diisi
                if (!empty($this->password)) {
                    $userData['password'] = bcrypt($this->password);
                }

                // Update user hanya jika ada data yang berubah
                if (!empty($userData)) {
                    $karyawan->user->update($userData);
                }

                // Persiapkan data Karyawan
                $karyawanData = [
                    'full_name'  => $this->full_name,
                    'inisial'        => $this->inisial ?: null,
                    'hp'             => $plainHp,
                    'gender'  => $this->gender,
                    'gelar_depan'    => $this->gelar_depan,
                    'gelar_belakang' => $this->gelar_belakang,
                    'tempat_lahir'   => $this->tempat_lahir,
                    'tanggal_lahir'  => $this->tanggal_lahir ?: null,
                    'tgl_masuk'  => $this->tgl_masuk ?: null,
                    'tgl_berhenti' => $this->tgl_berhenti ?: null,
                    'tgl_karyawan_tetap'         => $this->tgl_karyawan_tetap,
                    'nip'      => $this->nip,
                ];

                // Update foto dan ttd hanya jika ada perubahan
                if ($fotoPath !== null) {
                    $karyawanData['foto'] = $fotoPath;
                }
                if ($ttdPath !== null) {
                    $karyawanData['ttd'] = $ttdPath;
                }

                $karyawan->update($karyawanData);

                $this->dispatch('toast', [
                    'message' => "Data berhasil diedit",
                    'type'    => 'success',
                ]);

            DB::commit(); // sukses → simpan perubahan

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

    public function render()
    {
        $statusKaryawan = StatusPegawai::orderBy('nama_status')->get();
        $statusKawin = StatusKawin::orderBy('nama')->get();
        $golongan = Golongan::orderBy('nama_golongan')->get();
        
        return view('livewire.admin.karyawan.karyawan-form', compact('statusKaryawan', 'statusKawin','golongan'));
    }
}