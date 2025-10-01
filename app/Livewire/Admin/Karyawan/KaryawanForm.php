<?php

namespace App\Livewire\Admin\Karyawan;

use Livewire\Component;
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
    use WithFileUploads;

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
    public bool $isMounting = true;

    // Lists untuk dropdown KTP
    public $provinsiList = [];
    public $kabupatenList = [];
    public $kecamatanList = [];
    public $desaList = [];

    // Lists untuk dropdown Domisili
    public $kabDomisiliList = [];
    public $kecDomisiliList = [];
    public $desaDomisiliList = [];

    // Cache untuk menghindari duplicate queries
    private $wilayahCache = [];

    public function mount(Karyawan $karyawan)
    {
        $this->activeTab = 'profile';
        $this->karyawanId = $karyawan->id;

        // Load karyawan dengan relasi user sekaligus
        $karyawanWithUser = Karyawan::with('user')->find($karyawan->id);
        $this->userId = $karyawanWithUser->user?->id;

        // Set semua data karyawan
        $this->setKaryawanData($karyawan, $karyawanWithUser);

        // Load data wilayah secara optimal
        $this->loadWilayahDataOptimized();
    }

    /**
     * Set data karyawan dari model
     */
    private function setKaryawanData(Karyawan $karyawan, $karyawanWithUser)
    {
        // Data dasar
        $this->full_name = $karyawan->full_name;
        $this->panggilan = $karyawan->panggilan;
        $this->inisial = $karyawan->inisial;
        $this->email = $karyawanWithUser->user?->email;
        $this->nip = $karyawan->nip;
        $this->jenis_karyawan = $karyawan->jenis_karyawan;
        $this->statuskaryawan_id = $karyawan->statuskaryawan_id;
        $this->statuskawin_id = $karyawan->statuskawin_id;
        $this->golongan_id = $karyawan->golongan_id;
        $this->npwp = $karyawan->npwp;
        $this->tgl_masuk = $karyawan->tgl_masuk;
        $this->tgl_karyawan_tetap = $karyawan->tgl_karyawan_tetap;
        $this->tgl_berhenti = $karyawan->tgl_berhenti;

        // Data personal
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

        // Data alamat KTP
        $this->alamat_ktp = $karyawan->alamat_ktp;
        $this->rt_ktp = $karyawan->rt_ktp;
        $this->rw_ktp = $karyawan->rw_ktp;
        $this->provktp_id = $karyawan->prov_id;
        $this->kabktp_id = $karyawan->kab_id;
        $this->kecktp_id = $karyawan->kec_id;
        $this->desaktp_id = $karyawan->desa_id;

        // Data alamat domisili
        $this->alamat_dom = $karyawan->alamat_dom;
        $this->rt_dom = $karyawan->rt_dom;
        $this->rw_dom = $karyawan->rw_dom;
        $this->provdom_id = $karyawan->provdom_id;
        $this->kabdom_id = $karyawan->kabdom_id;
        $this->kecdom_id = $karyawan->kecdom_id;
        $this->desdom_id = $karyawan->desdom_id;

        // File paths
        $this->foto = $karyawan->foto;
        $this->originalFoto = $karyawan->foto;
        $this->ttd = $karyawan->ttd;
        $this->originalTtd = $karyawan->ttd;
    }

    /**
     * Load data wilayah secara optimal dengan menghindari duplicate queries
     */
    private function loadWilayahDataOptimized()
    {
        // Load provinsi (selalu diperlukan)
        $this->loadProvinsiList();

        // Kumpulkan semua ID yang diperlukan untuk batch query
        $provinsiIds = array_filter([$this->provktp_id, $this->provdom_id]);
        $kabupatenIds = array_filter([$this->kabktp_id, $this->kabdom_id]);
        $kecamatanIds = array_filter([$this->kecktp_id, $this->kecdom_id]);

        // Batch load kabupaten
        if (!empty($provinsiIds)) {
            $this->batchLoadKabupaten($provinsiIds);
        }

        // Batch load kecamatan
        if (!empty($kabupatenIds)) {
            $this->batchLoadKecamatan($kabupatenIds);
        }

        // Batch load desa
        if (!empty($kecamatanIds)) {
            $this->batchLoadDesa($kecamatanIds);
        }

        // Set data untuk dropdown yang sudah terisi
        $this->setExistingWilayahData();
    }

    /**
     * Batch load kabupaten untuk menghindari duplicate queries
     */
    private function batchLoadKabupaten(array $provinsiIds)
    {
        $kabupatenData = \App\Models\Wilayah\Kabupaten::whereIn('provinsi_id', array_unique($provinsiIds))
            ->orderBy('nama')
            ->get()
            ->groupBy('provinsi_id');

        foreach ($kabupatenData as $provinsiId => $kabupaten) {
            $this->wilayahCache['kabupaten'][$provinsiId] = $kabupaten;
        }
    }

    /**
     * Batch load kecamatan untuk menghindari duplicate queries
     */
    private function batchLoadKecamatan(array $kabupatenIds)
    {
        $kecamatanData = \App\Models\Wilayah\Kecamatan::whereIn('kabupaten_id', array_unique($kabupatenIds))
            ->orderBy('nama')
            ->get()
            ->groupBy('kabupaten_id');

        foreach ($kecamatanData as $kabupatenId => $kecamatan) {
            $this->wilayahCache['kecamatan'][$kabupatenId] = $kecamatan;
        }
    }

    /**
     * Batch load desa untuk menghindari duplicate queries
     */
    private function batchLoadDesa(array $kecamatanIds)
    {
        $desaData = \App\Models\Wilayah\Desa::whereIn('kecamatan_id', array_unique($kecamatanIds))
            ->orderBy('nama')
            ->get()
            ->groupBy('kecamatan_id');

        foreach ($desaData as $kecamatanId => $desa) {
            $this->wilayahCache['desa'][$kecamatanId] = $desa;
        }
    }

    /**
     * Set data dropdown untuk wilayah yang sudah ada
     */
    private function setExistingWilayahData()
    {
        // Set kabupaten untuk KTP
        if ($this->provktp_id && isset($this->wilayahCache['kabupaten'][$this->provktp_id])) {
            $this->kabupatenList = $this->wilayahCache['kabupaten'][$this->provktp_id];
        }

        // Set kabupaten untuk domisili
        if ($this->provdom_id && isset($this->wilayahCache['kabupaten'][$this->provdom_id])) {
            $this->kabDomisiliList = $this->wilayahCache['kabupaten'][$this->provdom_id];
        }

        // Set kecamatan untuk KTP
        if ($this->kabktp_id && isset($this->wilayahCache['kecamatan'][$this->kabktp_id])) {
            $this->kecamatanList = $this->wilayahCache['kecamatan'][$this->kabktp_id];
        }

        // Set kecamatan untuk domisili
        if ($this->kabdom_id && isset($this->wilayahCache['kecamatan'][$this->kabdom_id])) {
            $this->kecDomisiliList = $this->wilayahCache['kecamatan'][$this->kabdom_id];
        }

        // Set desa untuk KTP
        if ($this->kecktp_id && isset($this->wilayahCache['desa'][$this->kecktp_id])) {
            $this->desaList = $this->wilayahCache['desa'][$this->kecktp_id];
        }

        // Set desa untuk domisili
        if ($this->kecdom_id && isset($this->wilayahCache['desa'][$this->kecdom_id])) {
            $this->desaDomisiliList = $this->wilayahCache['desa'][$this->kecdom_id];
        }
    }

    // Methods untuk load data dropdown dengan cache
    private function loadProvinsiList()
    {
        if (!isset($this->wilayahCache['provinsi'])) {
            $this->wilayahCache['provinsi'] = \App\Models\Wilayah\Provinsi::orderBy('nama')->get();
        }
        $this->provinsiList = $this->wilayahCache['provinsi'];
    }

    private function loadKabupatenList($provinsiId)
    {
        if ($provinsiId) {
            if (!isset($this->wilayahCache['kabupaten'][$provinsiId])) {
                $this->wilayahCache['kabupaten'][$provinsiId] = \App\Models\Wilayah\Kabupaten::where('provinsi_id', $provinsiId)
                    ->orderBy('nama')->get();
            }
            $this->kabupatenList = $this->wilayahCache['kabupaten'][$provinsiId];
        } else {
            $this->kabupatenList = collect();
        }
    }

    private function loadKecamatanList($kabupatenId)
    {
        if ($kabupatenId) {
            if (!isset($this->wilayahCache['kecamatan'][$kabupatenId])) {
                $this->wilayahCache['kecamatan'][$kabupatenId] = \App\Models\Wilayah\Kecamatan::where('kabupaten_id', $kabupatenId)
                    ->orderBy('nama')->get();
            }
            $this->kecamatanList = $this->wilayahCache['kecamatan'][$kabupatenId];
        } else {
            $this->kecamatanList = collect();
        }
    }

    private function loadDesaList($kecamatanId)
    {
        if ($kecamatanId) {
            if (!isset($this->wilayahCache['desa'][$kecamatanId])) {
                $this->wilayahCache['desa'][$kecamatanId] = \App\Models\Wilayah\Desa::where('kecamatan_id', $kecamatanId)
                    ->orderBy('nama')->get();
            }
            $this->desaList = $this->wilayahCache['desa'][$kecamatanId];
        } else {
            $this->desaList = collect();
        }
    }

    private function loadKabDomisiliList($provinsiId)
    {
        if ($provinsiId) {
            if (!isset($this->wilayahCache['kabupaten'][$provinsiId])) {
                $this->wilayahCache['kabupaten'][$provinsiId] = \App\Models\Wilayah\Kabupaten::where('provinsi_id', $provinsiId)
                    ->orderBy('nama')->get();
            }
            $this->kabDomisiliList = $this->wilayahCache['kabupaten'][$provinsiId];
        } else {
            $this->kabDomisiliList = collect();
        }
    }

    private function loadKecDomisiliList($kabupatenId)
    {
        if ($kabupatenId) {
            if (!isset($this->wilayahCache['kecamatan'][$kabupatenId])) {
                $this->wilayahCache['kecamatan'][$kabupatenId] = \App\Models\Wilayah\Kecamatan::where('kabupaten_id', $kabupatenId)
                    ->orderBy('nama')->get();
            }
            $this->kecDomisiliList = $this->wilayahCache['kecamatan'][$kabupatenId];
        } else {
            $this->kecDomisiliList = collect();
        }
    }

    private function loadDesaDomisiliList($kecamatanId)
    {
        if ($kecamatanId) {
            if (!isset($this->wilayahCache['desa'][$kecamatanId])) {
                $this->wilayahCache['desa'][$kecamatanId] = \App\Models\Wilayah\Desa::where('kecamatan_id', $kecamatanId)
                    ->orderBy('nama')->get();
            }
            $this->desaDomisiliList = $this->wilayahCache['desa'][$kecamatanId];
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

            // Load dropdown lists untuk domisili menggunakan cache
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
            'desaktp_id' => 'required|exists:desa,id',
            'kecktp_id' => 'required|exists:kecamatan,id',
            'kabktp_id' => 'required|exists:kabupaten,id',
            'provktp_id' => 'required|exists:provinsi,id',
        ];

        // Jika domisili beda, validasi tambahan
        if (!$this->domisili_sama_ktp) {
            $rules = array_merge($rules, [
                'alamat_dom' => 'required|string|max:255',
                'rt_dom' => 'required|string|max:5',
                'rw_dom' => 'required|string|max:5',
                'provdom_id' => 'required|exists:provinsi,id',
                'kabdom_id' => 'required|exists:kabupaten,id',
                'kecdom_id' => 'required|exists:kecamatan,id',
                'desdom_id' => 'required|exists:desa,id',
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
        'panggilan' => 'Panggilan',
        'inisial' => 'Insiail',
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
    ];

    // Property untuk mencegah double submit
    public $isUpdating = false;

    public function save()
    {
        // Cegah double submit
        if ($this->isUpdating) {
            return;
        }

        $this->isUpdating = true;

        DB::beginTransaction();

        try {
            $this->validate();

            // Normalisasi nomor HP
            $plainHp = preg_replace('/[^\d+]/', '', $this->hp);
            // Normalisasi nomor Whatsapp
            $plainWhatsapp = preg_replace('/[^\d+]/', '', $this->whatsapp);
            // Normalisasi nomor Emergency
            $plainCEmergency = preg_replace('/[^\d+]/', '', $this->emergency_contact_phone);

            // Handle foto upload dengan logika yang benar
            $fotoPath = $this->originalFoto; // default tetap pakai foto lama
            $newFotoPath = null; // untuk tracking file baru

            if ($this->foto instanceof \Illuminate\Http\UploadedFile) {
                // Ada foto baru yang diupload
                $newFotoPath = $this->foto->store('karyawan/foto', 'public');

                // Hapus foto lama HANYA jika ada foto lama dan berbeda dari foto baru
                if (
                    $this->originalFoto &&
                    $this->originalFoto !== $newFotoPath &&
                    Storage::disk('public')->exists($this->originalFoto)
                ) {
                    Storage::disk('public')->delete($this->originalFoto);
                }

                $fotoPath = $newFotoPath;
            }

            // Handle TTD upload dengan logika yang benar
            $ttdPath = $this->originalTtd; // default tetap pakai ttd lama
            $newTtdPath = null; // untuk tracking file baru

            if ($this->ttd instanceof \Illuminate\Http\UploadedFile) {
                // Ada TTD baru yang diupload
                $newTtdPath = $this->ttd->store('karyawan/ttd', 'public');

                // Hapus TTD lama HANYA jika ada TTD lama dan berbeda dari TTD baru
                if (
                    $this->originalTtd &&
                    $this->originalTtd !== $newTtdPath &&
                    Storage::disk('public')->exists($this->originalTtd)
                ) {
                    Storage::disk('public')->delete($this->originalTtd);
                }

                $ttdPath = $newTtdPath;
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
                'full_name' => $this->full_name,
                'panggilan' => $this->panggilan,
                'inisial' => $this->inisial,
                'hp' => $plainHp,
                'whatsapp' => $plainWhatsapp,
                'jenis_karyawan' => $this->jenis_karyawan,
                'statuskaryawan_id' => $this->statuskaryawan_id,
                'statuskawin_id' => $this->statuskawin_id,
                'golongan_id' => $this->golongan_id,
                'npwp' => $this->npwp,
                'tgl_masuk' => $this->tgl_masuk,
                'tgl_berhenti' => $this->tgl_berhenti,
                'tgl_karyawan_tetap' => $this->tgl_karyawan_tetap,
                'nip' => $this->nip,
                'nik' => $this->nik,
                'nkk' => $this->nkk,
                'gender' => $this->gender,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'agama' => $this->agama,
                'status_kawin' => $this->status_kawin,
                'pndk_akhir' => $this->pndk_akhir,
                'gelar_depan' => $this->gelar_depan,
                'gelar_belakang' => $this->gelar_belakang,
                'blood_type' => $this->blood_type,
                'emergency_contact_name' => $this->emergency_contact_name,
                'emergency_contact_phone' => $plainCEmergency,
                'alamat_ktp' => $this->alamat_ktp,
                'rt_ktp' => $this->rt_ktp,
                'rw_ktp' => $this->rw_ktp,
                'desa_id' => $this->desaktp_id,
                'kec_id' => $this->kecktp_id,
                'kab_id' => $this->kabktp_id,
                'prov_id' => $this->provktp_id,
                'alamat_dom' => $this->alamat_dom,
                'rt_dom' => $this->rt_dom,
                'rw_dom' => $this->rw_dom,
                'provdom_id' => $this->provdom_id,
                'kabdom_id' => $this->kabdom_id,
                'kecdom_id' => $this->kecdom_id,
                'desdom_id' => $this->desdom_id,
                'foto' => $fotoPath,
                'ttd' => $ttdPath,
            ];

            $karyawan->update($karyawanData);

            // Update nilai originalFoto dan originalTtd setelah berhasil update
            $this->originalFoto = $fotoPath;
            $this->originalTtd = $ttdPath;

            $this->dispatch('toast', [
                'message' => "Data berhasil diedit",
                'type'    => 'success',
            ]);

            DB::commit();
        } catch (ValidationException $e) {
            DB::rollBack();
            $this->isUpdating = false; // Reset flag

            // Hapus file yang baru diupload HANYA jika ada upload file dan terjadi error
            if (isset($newFotoPath) && $newFotoPath && Storage::disk('public')->exists($newFotoPath)) {
                Storage::disk('public')->delete($newFotoPath);
            }
            if (isset($newTtdPath) && $newTtdPath && Storage::disk('public')->exists($newTtdPath)) {
                Storage::disk('public')->delete($newTtdPath);
            }

            $errors = $e->validator->errors()->all();
            $count  = count($errors);

            $this->dispatch('toast', [
                'message' => "Terdapat $count kesalahan:\n- " . implode("\n- ", $errors),
                'type'    => 'error',
            ]);
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->isUpdating = false; // Reset flag

            // Hapus file yang baru diupload HANYA jika ada upload file dan terjadi error
            if (isset($newFotoPath) && $newFotoPath && Storage::disk('public')->exists($newFotoPath)) {
                Storage::disk('public')->delete($newFotoPath);
            }
            if (isset($newTtdPath) && $newTtdPath && Storage::disk('public')->exists($newTtdPath)) {
                Storage::disk('public')->delete($newTtdPath);
            }

            $this->dispatch('toast', [
                'message' => $e->getMessage() ?: 'Terjadi kesalahan server.',
                'type'    => 'error',
            ]);
            throw $e;
        } finally {
            $this->isUpdating = false; // Reset flag di akhir
        }
    }

    // Method untuk hapus foto dengan aman
    public function removeFoto()
    {
        $this->foto = null;
    }

    // Method untuk hapus TTD dengan aman
    public function removeTtd()
    {
        $this->ttd = null;
    }

    public function render()
    {
        // Cache data master untuk menghindari query berulang
        static $masterData = null;
        
        if ($masterData === null) {
            $masterData = [
                'statusKaryawan' => StatusPegawai::orderBy('nama_status')->get(),
                'statusKawin' => StatusKawin::orderBy('nama')->get(),
                'golongan' => Golongan::orderBy('nama_golongan')->get()
            ];
        }

        return view('livewire.admin.karyawan.karyawan-form', $masterData);
    }
}