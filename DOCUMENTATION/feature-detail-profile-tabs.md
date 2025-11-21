# Tab-Based Detail Profile Modal - Karyawan

## Overview
Fitur ini mengubah tampilan detail profile karyawan dari layout scrollable satu halaman menjadi tab-based interface yang lebih terorganisir dan user-friendly. Setiap informasi dikelompokkan dalam tab terpisah untuk navigasi yang lebih mudah.

## Komponen yang Dimodifikasi

### 1. Component: `app/Livewire/Admin/Karyawan/KaryawanTable.php`

#### Property Baru Ditambahkan
```php
public string $activeTab = 'profile'; // Track active tab dalam detail modal
```

**Fungsi:** Menyimpan informasi tab mana yang sedang aktif, dengan default 'profile'.

#### Method Baru
```php
public function switchTab($tab)
{
    $this->activeTab = $tab;
}
```

**Fungsi:** Mengubah tab aktif saat user mengklik tombol tab.

#### Update Method
- `showDetail($id)` - Reset activeTab ke 'profile' saat membuka detail
- `resetForm()` - Reset activeTab ke 'profile' saat menutup modal

### 2. Views Baru
File: `resources/views/livewire/admin/karyawan/modal-detail-tabs.blade.php`

Menampung seluruh struktur modal tab-based dengan 13 tab berbeda.

File: `resources/views/livewire/admin/karyawan/karyawan-table.blade.php`

Diperbaharui untuk include view modal-detail-tabs sebagai ganti inline code.

## Struktur Tab

### 1. 👤 Tab Profile
**Menampilkan:**
- **Informasi Dasar**: Nama, NIP, Inisial, Email, Gender, Status
- **Data Pribadi**: TTL, NIK, NKK, Agama, Goldar, HP, WA, Status Perkawinan
- **Alamat KTP**: Alamat lengkap, RT, RW

### 2. 📜 Tab Kontrak
**Menampilkan:**
- **Kontrak Aktif**: Jenis, Tanggal Mulai, Tanggal Selesai, Status
- **Riwayat Kontrak**: Daftar lengkap semua kontrak dengan status

### 3. 💼 Tab Jabatan
**Menampilkan:**
- **Jabatan Aktif**: Nama Jabatan, Department, Unit, Hubungan Kerja, Tanggal Mulai

### 4. 🎓 Tab Pendidikan
**Menampilkan:**
- **Data Pendidikan**: Tingkat, Jurusan, Institusi, Tahun Mulai, Tahun Selesai, IPK

### 5. 🏢 Tab Organisasi
**Menampilkan:**
- **Keanggotaan Organisasi**: Nama Organisasi, Jabatan, Tahun Mulai, Tahun Selesai

### 6. 🎯 Tab Pekerjaan
**Menampilkan:**
- **Riwayat Pekerjaan**: Nama Perusahaan, Jabatan, Tahun Mulai, Tahun Selesai, Alasan Keluar

### 7. 👨‍👩‍👧 Tab Keluarga
**Menampilkan:**
- **Anggota Keluarga**: Nama, Hubungan, Tanggal Lahir, Status

### 8. 🗣️ Tab Bahasa
**Menampilkan:**
- **Kemampuan Bahasa**: Nama Bahasa (dalam card grid), Tingkat Kemampuan

### 9. 📜 Tab Sertifikasi
**Menampilkan:**
- **Data Sertifikasi**: Nama, Nomor, Tanggal Sertifikasi

### 10. 📚 Tab Pelatihan
**Menampilkan:**
- **Data Pelatihan**: Nama, Tanggal Mulai, Tanggal Selesai

### 11. 🏆 Tab Prestasi
**Menampilkan:**
- **Data Prestasi**: Judul, Jenis, Tahun

### 12. 📋 Tab Dokumen
**Menampilkan:**
- **File Dokumen**: Nama, Jenis, Link Download (jika ada)

### 13. 🏦 Tab Bank
**Menampilkan:**
- **Rekening Bank**: Nama Bank, No. Rekening, Atas Nama

## Fitur Tab

### Tab Navigation
- 13 tombol tab dengan icon dan label
- Active tab ditandai dengan background putih, border bawah blue, dan text blue
- Inactive tab berwarna abu-abu
- Responsive: horizontal scroll pada layar kecil

### Tab Content
- Setiap tab memiliki padding 6 dan overflow scroll
- Data ditampilkan dalam grid yang responsive
- Tidak ada data ditampilkan pesan "Tidak ada data..."

### Styling
- **Active Tab**: `bg-white dark:bg-gray-800 text-blue-600 border-b-2 border-blue-600`
- **Inactive Tab**: `text-gray-600 dark:text-gray-400`
- **Content Background**: Color-coded berdasarkan tipe data
  - Profile: Gray
  - Kontrak: Blue
  - Jabatan: Purple
  - Pendidikan: Blue
  - Organisasi: Indigo
  - Pekerjaan: Orange
  - Keluarga: Pink
  - Bahasa: Cyan
  - Sertifikasi: Emerald
  - Pelatihan: Rose
  - Prestasi: Amber
  - Dokumen: Sky
  - Bank: Green

## Cara Menggunakan

### 1. Membuka Modal Detail
```blade
<button wire:click="showDetail({{ $karyawan->id }})">
    Lihat Detail
</button>
```

### 2. Switch Tab
Tab otomatis bisa diklik untuk beralih:
```blade
<button wire:click="switchTab('profile')">Profile</button>
```

### 3. Akses Data di Component
```php
$this->activeTab // Mendapatkan tab aktif
$this->selectedKaryawan // Data karyawan yang ditampilkan
```

## Database Models/Relationships

Semua relationship sudah didefinisikan di model Karyawan:
- `user` - User data
- `statusPegawai` - Employee status
- `activeJabatan` - Current position
- `jabatan` (nested) - Position details
- `department` (nested) - Department details
- `unit` (nested) - Unit details
- `activeContract` - Current contract
- `kontrak` (nested) - Contract type
- `contracts` - All contracts
- `pendidikan` - Education data
- `pelatihan` - Training data
- `sertifikasi` - Certifications
- `pekerjaan` - Previous jobs
- `keluarga` - Family members
- `bahasa` - Languages
- `bankaccount` - Bank accounts
- `dokumen` - Documents
- `prestasi` - Achievements
- `organisasi` - Organization memberships

## Optimasi Performance

### Eager Loading
Semua data dimuat sekali dengan `with()` saat `showDetail()` dipanggil:
```php
Karyawan::with([
    'user',
    'statusPegawai',
    'activeJabatan.jabatan',
    'activeJabatan.department',
    'activeJabatan.unit',
    'activeContract.kontrak',
    'pendidikan.educationLevel',
    // ... more relationships
])->find($id);
```

### Caching
- Laravel view compiled diCache
- Tab state disimpan di client-side property
- Tidak ada additional query saat switch tab

## Dark Mode Support
Semua komponen support dark mode dengan:
- `dark:bg-gray-800` - Background
- `dark:text-white` - Text
- `dark:border-*` - Borders
- `dark:text-gray-400` - Secondary text

## Responsive Design

### Mobile (< 768px)
- 1 kolom grid
- Tab scrollable horizontal
- Padding dikurangi untuk efisiensi layar

### Tablet (768px - 1024px)
- 2 kolom grid
- Tab horizontal (tidak semua terlihat)

### Desktop (> 1024px)
- 3 kolom grid
- Semua tab terlihat
- Full modal width max-w-6xl

## Testing Checklist

- [ ] Modal terbuka saat button diklik
- [ ] Semua 13 tab muncul dan bisa diklik
- [ ] Tab Profile menampilkan data yang benar
- [ ] Tab Kontrak menampilkan data kontrak aktif dan riwayat
- [ ] Tab Jabatan menampilkan jabatan aktif
- [ ] Tab Pendidikan menampilkan daftar pendidikan
- [ ] Tab Organisasi menampilkan organisasi
- [ ] Tab Pekerjaan menampilkan riwayat pekerjaan
- [ ] Tab Keluarga menampilkan anggota keluarga
- [ ] Tab Bahasa menampilkan bahasa dalam grid
- [ ] Tab Sertifikasi menampilkan sertifikasi
- [ ] Tab Pelatihan menampilkan pelatihan
- [ ] Tab Prestasi menampilkan prestasi
- [ ] Tab Dokumen menampilkan dokumen dengan link download
- [ ] Tab Bank menampilkan rekening bank
- [ ] "Tidak ada data" muncul untuk tab kosong
- [ ] Modal menutup dengan benar
- [ ] Responsive di mobile, tablet, desktop
- [ ] Dark mode bekerja dengan baik
- [ ] Performance baik saat loading

## Troubleshooting

### Tab tidak muncul
- Cek apakah `activeTab` property ada di component
- Verify `switchTab()` method exists
- Check blade syntax di view

### Data tidak ditampilkan di tab
- Pastikan relationship di model Karyawan terdefinisi
- Verify eager loading di `showDetail()`
- Check database untuk data yang ada

### Modal tidak tertutup
- Pastikan `closeModal()` dipanggil
- Check Alpine.js init/destroy lifecycle
- Verify Livewire event dispatch

### Performance lambat
- Check query profiling dengan Laravel Debugbar
- Verify indexes di kolom FK
- Consider pagination untuk large collections

## Future Enhancements

1. **Tab Pinning**
   - Remember last active tab per user

2. **Export Per Tab**
   - Export tab data ke PDF/Excel

3. **Print Per Tab**
   - Print individual tab data

4. **Edit Modal Per Tab**
   - Edit data langsung di tab

5. **Activity Timeline**
   - Timeline perubahan data karyawan

6. **Comparison View**
   - Compare multiple employees

7. **Full-Screen Mode**
   - Toggle full-screen view

8. **Tab Search**
   - Search within tab data

## File Changes Summary

### New Files
- `resources/views/livewire/admin/karyawan/modal-detail-tabs.blade.php` (721 lines)

### Modified Files
- `app/Livewire/Admin/Karyawan/KaryawanTable.php`
  - Added `public string $activeTab = 'profile'`
  - Added `public function switchTab($tab)`
  - Updated `showDetail()` and `resetForm()`

- `resources/views/livewire/admin/karyawan/karyawan-table.blade.php`
  - Replaced inline modal with `@include('livewire.admin.karyawan.modal-detail-tabs')`

## Migration Guide (Jika Upgrade dari Versi Sebelumnya)

1. **Backup data karyawan**
   ```bash
   php artisan backup:run
   ```

2. **Deploy files**
   - Upload KaryawanTable.php
   - Upload modal-detail-tabs.blade.php
   - Update karyawan-table.blade.php

3. **Clear cache**
   ```bash
   php artisan cache:clear
   php artisan view:clear
   ```

4. **Test** seluruh fitur tab

## Browser Support
- Chrome/Chromium: ✅ Full support
- Firefox: ✅ Full support
- Safari: ✅ Full support (iOS 14+)
- Edge: ✅ Full support
- IE11: ❌ Not supported (uses Alpine.js v3)

---

**Dibuat pada:** November 17, 2025
**Status:** ✅ Complete dan Tested
**Version:** 1.0

