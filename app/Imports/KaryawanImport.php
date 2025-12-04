<?php

namespace App\Imports;

use App\Models\Employee\Karyawan;
use App\Models\Master\StatusPegawai;
use App\Models\Master\StatusKawin;
use App\Models\Master\Golongan;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;

class KaryawanImport implements ToCollection, WithHeadingRow
{
    private $successCount = 0;
    private $errorRows = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $user = null;
            
            try {
                // Skip empty rows
                if (empty($row['nip']) && empty($row['full_name'])) {
                    continue;
                }

                // Validasi field wajib
                if (empty($row['nip'])) {
                    throw new \Exception('NIP tidak boleh kosong');
                }
                // Convert NIP to string jika numeric
                $nip = (string)$row['nip'];
                if (empty($nip)) {
                    throw new \Exception('NIP tidak boleh kosong');
                }
                if (empty($row['full_name'])) {
                    throw new \Exception('Full Name tidak boleh kosong');
                }
                if (empty($row['tgl_masuk'])) {
                    throw new \Exception('Tgl Masuk tidak boleh kosong');
                }
                if (empty($row['status_pegawai'])) {
                    throw new \Exception('Status Pegawai tidak boleh kosong');
                }

                // Check NIP duplicate BEFORE processing
                if (Karyawan::where('nip', $nip)->exists()) {
                    throw new \Exception('NIP sudah terdaftar di database');
                }

                // Generate unique inisial
                $inisial = $this->generateInisial($row['full_name']);
                $inisial = $this->ensureUniqueInisial($inisial);

                // Map status pegawai (REQUIRED) - SEBELUM buat user
                $statusPegawaiId = null;
                if (!empty($row['status_pegawai'])) {
                    $query = is_numeric($row['status_pegawai']) 
                        ? StatusPegawai::where('id', $row['status_pegawai'])
                        : StatusPegawai::where('nama_status', $row['status_pegawai']);
                    $statusPegawaiId = $query->first()?->id;
                    if (!$statusPegawaiId) {
                        throw new \Exception('Status Pegawai "' . $row['status_pegawai'] . '" tidak ditemukan di database');
                    }
                }

                // Map status kawin - sebagai ENUM (lajang, menikah, cerai)
                $statusKawinEnum = 'lajang'; // default
                if (!empty($row['status_kawin'])) {
                    // Jika numeric, ambil dari StatusKawin table
                    if (is_numeric($row['status_kawin'])) {
                        $statusKawin = StatusKawin::find($row['status_kawin']);
                        if ($statusKawin) {
                            // Map nama status kawin ke enum
                            $statusKawinEnum = $this->mapStatusKawinToEnum($statusKawin->nama);
                        }
                    } else {
                        // String langsung, cek apakah valid enum
                        $statusKawinEnum = $this->mapStatusKawinToEnum($row['status_kawin']);
                    }
                }

                // Map statuskawin_id - foreign key ke master_statuskawin
                $statusKawinId = null;
                if (!empty($row['status_kawin'])) {
                    if (is_numeric($row['status_kawin'])) {
                        $statusKawinId = $row['status_kawin'];
                    } else {
                        $statusKawin = StatusKawin::where('nama', $row['status_kawin'])->first();
                        $statusKawinId = $statusKawin?->id;
                    }
                }

                // Map golongan
                $golonganId = null;
                if (!empty($row['golongan'])) {
                    $query = is_numeric($row['golongan'])
                        ? Golongan::where('id', $row['golongan'])
                        : Golongan::where('nama_golongan', $row['golongan']);
                    $golonganId = $query->first()?->id;
                }

                // Normalize gender
                $gender = null;
                if (!empty($row['gender'])) {
                    $genderLower = strtolower($row['gender']);
                    if (in_array($genderLower, ['laki-laki', 'l', 'male', 'm'])) {
                        $gender = 'laki-laki';
                    } elseif (in_array($genderLower, ['perempuan', 'p', 'female', 'f'])) {
                        $gender = 'perempuan';
                    }
                }

                // Normalize agama
                $agama = $this->normalizeAgama($row['agama'] ?? null);

                // Jika semua validasi lolos, BARU buat user jika email ada
                if (!empty($row['email'])) {
                    try {
                        $user = User::firstOrCreate(
                            ['email' => $row['email']],
                            [
                                'name' => $row['full_name'],
                                'password' => bcrypt('password123'),
                                'email_verified_at' => now(),
                            ]
                        );
                        
                        // Assign default role "staff" to new user
                        if ($user->wasRecentlyCreated) {
                            $user->assignRole('staff');
                        }
                    } catch (\Exception $userException) {
                        throw new \Exception('Gagal membuat user: ' . $userException->getMessage());
                    }
                }

                // Create karyawan - SETELAH semua validasi & user creation berhasil
                try {
                    Karyawan::create([
                        'user_id' => $user?->id,
                        'nip' => $nip,
                        'inisial' => $inisial,
                        'full_name' => $row['full_name'],
                        'panggilan' => $row['panggilan'] ?? null,
                        'hp' => $row['hp'] ?? null,
                        'whatsapp' => $row['whatsapp'] ?? null,
                        'gender' => $gender,
                        'gelar_depan' => $row['gelar_depan'] ?? null,
                        'gelar_belakang' => $row['gelar_belakang'] ?? null,
                        'tempat_lahir' => $row['tempat_lahir'] ?? null,
                        'tanggal_lahir' => $this->parseDate($row['tanggal_lahir'] ?? null),
                        'pndk_akhir' => $row['pndk_akhir'] ?? 'S1',
                        'agama' => $agama,
                        'status_kawin' => $statusKawinEnum,
                        'blood_type' => $row['blood_type'] ?? null,
                        'emergency_contact_name' => $row['emergency_contact_name'] ?? null,
                        'emergency_contact_phone' => $row['emergency_contact_phone'] ?? null,
                        'alamat_ktp' => $row['alamat_ktp'] ?? null,
                        'rt_ktp' => $row['rt_ktp'] ?? null,
                        'rw_ktp' => $row['rw_ktp'] ?? null,
                        'nik' => $row['nik'] ?? null,
                        'nkk' => $row['nkk'] ?? null,
                        'statuskaryawan_id' => $statusPegawaiId,
                        'statuskawin_id' => $statusKawinId,
                        'golongan_id' => $golonganId,
                        'npwp' => $row['npwp'] ?? null,
                        'tgl_masuk' => $this->parseDate($row['tgl_masuk']),
                        'tgl_karyawan_tetap' => $this->parseDate($row['tgl_karyawan_tetap'] ?? null),
                        'tgl_berhenti' => $this->parseDate($row['tgl_berhenti'] ?? null),
                        'jenis_karyawan' => $row['jenis_karyawan'] ?? null,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);

                    $this->successCount++;
                } catch (\Exception $karyawanException) {
                    // Jika karyawan gagal dibuat, hapus user yang sudah dibuat
                    if ($user) {
                        try {
                            $user->delete();
                        } catch (\Exception $deleteException) {
                            // Log error jika gagal hapus user
                        }
                    }
                    throw new \Exception('Gagal membuat data Karyawan: ' . $karyawanException->getMessage());
                }

            } catch (\Exception $e) {
                // Hapus user jika ada error
                if ($user) {
                    try {
                        $user->delete();
                    } catch (\Exception $deleteException) {
                        // Silent fail
                    }
                }

                $this->errorRows[] = [
                    'row' => $rows->search($row) + 2,
                    'nip' => $row['nip'] ?? 'N/A',
                    'full_name' => $row['full_name'] ?? 'N/A',
                    'error' => $e->getMessage(),
                ];
            }
        }
    }

    /**
     * Normalize agama to match enum values
     */
    private function normalizeAgama($agama): ?string
    {
        if (empty($agama)) {
            return null;
        }

        $agamaMap = [
            'islam' => 'Islam',
            'hindu' => 'Hindu',
            'budha' => 'Budha',
            'buddha' => 'Budha',
            'katolik' => 'Katolik',
            'kristen' => 'Protestan', // Kristen diasumsikan Protestan
            'protestan' => 'Protestan',
            'konghucu' => 'Konghucu',
        ];

        return $agamaMap[strtolower($agama)] ?? null;
    }

    /**
     * Map status kawin string to enum value
     */
    private function mapStatusKawinToEnum($status): string
    {
        $mapping = [
            'lajang' => 'lajang',
            'single' => 'lajang',
            'belum kawin' => 'lajang',
            'menikah' => 'menikah',
            'kawin' => 'menikah',
            'cerai' => 'cerai',
            'divorced' => 'cerai',
            'janda' => 'cerai',
            'duda' => 'cerai',
        ];

        return $mapping[strtolower($status)] ?? 'lajang';
    }

    /**
     * Ensure inisial is unique by adding suffix if needed
     */
    private function ensureUniqueInisial(string $inisial): string
    {
        $original = $inisial;
        $counter = 1;

        while (Karyawan::where('inisial', $inisial)->exists()) {
            $inisial = substr($original, 0, 2) . $counter;
            $counter++;
        }

        return $inisial;
    }

    /**
     * Generate initials from full name
     */
    private function generateInisial(string $name): string
    {
        if (empty($name)) {
            return 'XXX';
        }

        $words = explode(' ', trim($name));
        $inisial = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $inisial .= strtoupper(substr($word, 0, 1));
            }
        }

        // Ensure max 3 characters
        return substr($inisial, 0, 3) ?: 'XXX';
    }

    /**
     * Parse date with multiple format support
     */
    private function parseDate($dateString): ?string
    {
        if (empty($dateString)) {
            return null;
        }

        $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d', 'Y/m/d', 'd M Y'];

        foreach ($formats as $format) {
            try {
                $date = \Carbon\Carbon::createFromFormat($format, $dateString);
                return $date->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }
        }

        throw new \Exception("Format tanggal '$dateString' tidak valid. Gunakan format: dd/mm/yyyy, dd-mm-yyyy, atau yyyy-mm-dd");
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getErrorRows(): array
    {
        return $this->errorRows;
    }
}
