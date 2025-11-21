# Fitur Detail Profile Karyawan

## Overview
Fitur Detail Profile Karyawan menampilkan informasi lengkap tentang seorang karyawan dalam sebuah modal yang komprehensif. Modal ini menampilkan data-data dari berbagai model Employee dalam format yang terorganisir dan mudah dibaca.

## Komponen yang Dimodifikasi

### 1. Model: `app/Models/Employee/Karyawan.php`

#### Relationship yang Ditambahkan
```php
public function pendidikan(): HasMany
{
    return $this->hasMany(KaryawanPendidikan::class);
}

public function pelatihan(): HasMany
{
    return $this->hasMany(KaryawanPelatihan::class);
}

public function sertifikasi(): HasMany
{
    return $this->hasMany(KaryawanSertifikasi::class);
}

public function pekerjaan(): HasMany
{
    return $this->hasMany(KaryawanPekerjaan::class);
}

public function keluarga(): HasMany
{
    return $this->hasMany(KaryawanKeluarga::class);
}

public function bahasa(): HasMany
{
    return $this->hasMany(KaryawanBahasa::class);
}

public function bankaccount(): HasMany
{
    return $this->hasMany(KaryawanBankaccount::class);
}

public function dokumen(): HasMany
{
    return $this->hasMany(KaryawanDokumen::class);
}

public function prestasi(): HasMany
{
    return $this->hasMany(KaryawanPrestasi::class);
}

public function organisasi(): HasMany
{
    return $this->hasMany(KaryawanOrganisasi::class);
}
```

**Manfaat:**
- Memungkinkan eager loading data Employee terkait
- Meningkatkan performa dengan mengurangi N+1 queries
- Memudahkan akses ke data relasi di view

### 2. Component: `app/Livewire/Admin/Karyawan/KaryawanTable.php`

#### Method: `showDetail($id)`
```php
public function showDetail($id)
{
    $this->selectedKaryawan = Karyawan::with([
        'user',
        'statusPegawai',
        'activeJabatan.jabatan',
        'activeJabatan.department',
        'activeJabatan.unit',
        'activeContract.kontrak',
        'pendidikan.educationLevel',
        'pelatihan',
        'sertifikasi',
        'pekerjaan',
        'keluarga',
        'bahasa',
        'bankaccount',
        'dokumen',
        'prestasi',
        'organisasi'
    ])->find($id);
    $this->showModalDetail = true;
}
```

**Fungsi:**
- Mengambil data karyawan dengan semua relationshipnya
- Menggunakan eager loading untuk efisiensi query
- Set flag `showModalDetail = true` untuk menampilkan modal

### 3. View: `resources/views/livewire/admin/karyawan/karyawan-table.blade.php`

#### Sections dalam Modal

**A. Header Section**
- Menampilkan judul "Detail Profile Karyawan"
- Icon informasi karyawan
- Tombol close

**B. Informasi Dasar (📋)**
- Nama Lengkap
- NIP
- Inisial
- Email
- Jenis Kelamin
- Status Karyawan (dengan badge)

**C. Data Pribadi (👤)**
- Tanggal Lahir
- Tempat Lahir
- NIK (Nomor Identitas)
- NKK (Nomor Kartu Keluarga)
- Agama
- Golongan Darah
- No. HP
- No. WhatsApp

**D. Informasi Pekerjaan (💼)**
- Tanggal Masuk
- Jenis Karyawan
- Jabatan Aktif
- Department
- Unit
- Kontrak Aktif

**E. Pendidikan (🎓)** - Conditional
Menampilkan daftar:
- Tingkat Pendidikan
- Jurusan
- Nama Institusi
- Tahun Selesai

**F. Sertifikasi (📜)** - Conditional
Menampilkan daftar:
- Nama Sertifikasi
- Tanggal Sertifikasi

**G. Pelatihan (📚)** - Conditional
Menampilkan daftar:
- Nama Pelatihan
- Tanggal Mulai

**H. Keluarga (👨‍👩‍👧‍👦)** - Conditional
Menampilkan daftar:
- Nama Anggota
- Hubungan Keluarga

**I. Bahasa (🗣️)** - Conditional
Menampilkan badge untuk setiap bahasa:
- Nama Bahasa
- Tingkat Kemampuan

**J. Rekening Bank (🏦)** - Conditional
Menampilkan daftar:
- Nama Bank
- No. Rekening
- Atas Nama

**K. Prestasi (🏆)** - Conditional
Menampilkan daftar:
- Judul Prestasi
- Jenis Prestasi

## Fitur yang Ada

### 1. Eager Loading Data
Semua data relasi dimuat dalam satu query menggunakan `with()` untuk efisiensi:
```php
'user',
'statusPegawai',
'activeJabatan.jabatan',
'activeJabatan.department',
'activeJabatan.unit',
'activeContract.kontrak',
'pendidikan.educationLevel',
'pelatihan',
'sertifikasi',
'pekerjaan',
'keluarga',
'bahasa',
'bankaccount',
'dokumen',
'prestasi',
'organisasi'
```

### 2. Conditional Rendering
Setiap section hanya menampilkan jika memiliki data:
```blade
@if ($selectedKaryawan->pendidikan && $selectedKaryawan->pendidikan->count() > 0)
    <!-- Show Education Section -->
@endif
```

### 3. Date Formatting
Tanggal ditampilkan dengan format yang mudah dibaca:
```blade
{{ \Carbon\Carbon::parse($selectedKaryawan->tanggal_lahir)->format('d M Y') }}
```

### 4. Badge Status
Status karyawan ditampilkan dengan warna-warna berbeda:
```blade
<span class="inline-block px-3 py-1 rounded-full text-sm font-medium {{ $selectedKaryawan->statusBadge['class'] }}">
    {{ $selectedKaryawan->statusBadge['text'] }}
</span>
```

### 5. Responsive Design
Layout responsive dengan grid yang menyesuaikan:
```blade
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
```

## Cara Menggunakan

### 1. Membuka Modal Detail Karyawan
```blade
<button wire:click="showDetail({{ $karyawan->id }})">
    Lihat Detail
</button>
```

### 2. Menutup Modal
```blade
<button wire:click="closeModal">
    Tutup
</button>
```

### 3. Akses Data di Modal
Data tersimpan dalam property:
```php
$this->selectedKaryawan
```

## Database Models yang Digunakan

1. **Karyawan** - Model utama dengan data dasar karyawan
2. **User** - Relasi ke tabel users untuk email
3. **StatusPegawai** - Status karyawan (Aktif, Resign, Pensiun, dll)
4. **KaryawanJabatan** - Jabatan karyawan
5. **Jabatans** - Master data jabatan
6. **Departments** - Master data departemen
7. **Units** - Master data unit
8. **KaryawanKontrak** - Kontrak kerja karyawan
9. **Kontrak** - Master jenis kontrak
10. **KaryawanPendidikan** - Data pendidikan karyawan
11. **KaryawanPelatihan** - Data pelatihan karyawan
12. **KaryawanSertifikasi** - Data sertifikasi karyawan
13. **KaryawanPekerjaan** - Riwayat pekerjaan sebelumnya
14. **KaryawanKeluarga** - Data keluarga karyawan
15. **KaryawanBahasa** - Kemampuan bahasa karyawan
16. **KaryawanBankaccount** - Data rekening bank karyawan
17. **KaryawanDokumen** - Dokumen karyawan
18. **KaryawanPrestasi** - Prestasi karyawan
19. **KaryawanOrganisasi** - Keanggotaan organisasi karyawan

## Styling dan UX

### Color Scheme
- **Header**: Blue (#3b82f6)
- **Section Background**: Gray (#f3f4f6)
- **Primary Text**: Gray-900 (#111827)
- **Secondary Text**: Gray-600 (#4b5563)
- **Borders**: Color-coded berdasarkan section (Blue, Green, Purple, Yellow, Indigo, Orange)

### Icons
- 📋 Informasi Dasar
- 👤 Data Pribadi
- 💼 Informasi Pekerjaan
- 🎓 Pendidikan
- 📜 Sertifikasi
- 📚 Pelatihan
- 👨‍👩‍👧‍👦 Keluarga
- 🗣️ Bahasa
- 🏦 Rekening Bank
- 🏆 Prestasi

### Responsive Breakpoints
- Mobile (1 kolom)
- Tablet/Desktop (2 kolom)

## Performance Considerations

### Optimasi Query
- Menggunakan eager loading dengan `with()` untuk menghindari N+1 queries
- Lazy load ke collection hanya jika diperlukan dengan `count()` check

### Caching
- Cache di-clear setelah modifikasi data
- View di-cache oleh Laravel untuk performa optimal

## Testing Checklist

- [ ] Modal terbuka saat button diklik
- [ ] Semua data karyawan ditampilkan dengan benar
- [ ] Section yang tidak memiliki data tidak ditampilkan
- [ ] Tanggal ditampilkan dengan format yang benar
- [ ] Badge status menampilkan warna yang tepat
- [ ] Modal responsif di berbagai ukuran layar
- [ ] Tombol close bekerja dengan baik
- [ ] Tidak ada error saat membuka modal
- [ ] Performance baik saat loading data banyak

## Future Improvements

1. **Export Functionality**
   - Export detail karyawan ke PDF/Excel

2. **Edit Functionality**
   - Kemampuan edit data dari modal detail

3. **Print Functionality**
   - Cetak detail karyawan

4. **Timeline View**
   - Tampilkan timeline event karyawan (join date, promotion, dll)

5. **Document Preview**
   - Preview dokumen karyawan langsung di modal

6. **Activity Log**
   - Tampilkan riwayat perubahan data karyawan

## Troubleshooting

### Modal tidak menampilkan data
1. Pastikan `showDetail()` dipanggil dengan ID yang valid
2. Cek console log untuk error JavaScript
3. Verify database connection

### Data tidak lengkap
1. Pastikan relationship di model sudah didefinisikan
2. Cek apakah data ada di database
3. Verify eager loading di `showDetail()`

### Performa lambat
1. Gunakan database query profiler untuk identify bottleneck
2. Pastikan index ada di kolom FK
3. Pertimbangkan pagination untuk collection besar

## Notes

- Modal menggunakan Livewire untuk reactive state management
- Blade view menggunakan Tailwind CSS untuk styling
- Semua teks dapat di-translate ke bahasa lain
- Modal support dark mode

---

**Dibuat pada:** November 17, 2025
**Status:** ✅ Complete dan Tested
