# Filter & Sort Implementation untuk Kontrak Karyawan

## 📋 Overview

Telah ditambahkan fitur filter dan sort yang komprehensif pada halaman `Kontrak Karyawan` agar memudahkan user mencari dan mengelola kontrak dengan lebih efisien.

## ✨ Fitur Yang Ditambahkan

### 1. **Filter Jenis Kontrak**
- **Lokasi UI:** Dropdown di sebelah kiri filter section
- **Fungsi:** Filter kontrak berdasarkan jenis (TETAP, PKWT, dll)
- **Options:** 
  - Semua Jenis Kontrak (default)
  - TETAP (Permanen)
  - PKWT (Perjanjian Kerja Waktu Tertentu)
  - Kontrak lainnya sesuai master data
- **Query:** Menggunakan relationship `kontrak` untuk filter berdasarkan `master_kontrak.id`

### 2. **Filter Status Kontrak**
- **Lokasi UI:** Dropdown di tengah filter section
- **Fungsi:** Filter kontrak berdasarkan status
- **Options:**
  - Semua Status (default)
  - Aktif (kontrak masih berlaku)
  - Selesai (kontrak sudah berakhir)
  - Perpanjangan (dalam proses perpanjangan)
  - Dibatalkan (kontrak dibatalkan)
- **Query:** Direct field filter pada `karyawan_kontrak.status`

### 3. **Filter Sisa Kontrak (Remaining Days)**
- **Lokasi UI:** Dropdown di sebelah kanan filter section
- **Fungsi:** Filter berdasarkan durasi sisa kontrak
- **Options:**
  - Semua Sisa Kontrak (default)
  - Sudah Berakhir (tglselesai < hari ini)
  - Akan Berakhir (≤30 hari dari hari ini)
  - Masih Berlaku (>30 hari dari hari ini)
  - Tidak Terbatas (tglselesai IS NULL - TETAP contracts)
- **Logic:**
  ```php
  if ($sisa_kontrak_filter === 'expired') {
      // whereDate('tglselesai_kontrak', '<', today)
  } elseif ($sisa_kontrak_filter === 'expiring_soon') {
      // whereDate >= today AND <= today + 30 days
  } elseif ($sisa_kontrak_filter === 'valid') {
      // whereDate > today + 30 days
  } elseif ($sisa_kontrak_filter === 'unlimited') {
      // whereNull('tglselesai_kontrak')
  }
  ```

### 4. **Show/Hide Deleted Button**
- **Lokasi UI:** Button di sebelah kanan action buttons
- **Fungsi:** Toggle untuk menampilkan kontrak yang sudah dihapus (soft-deleted)
- **Icon:** Trash/Recycle icon
- **Label:**
  - "Show Deleted" (saat menampilkan active records)
  - "Show Exist" (saat menampilkan deleted records)
- **Query Impact:**
  ```php
  if ($showDeleted) {
      $query->onlyTrashed();  // Hanya menampilkan deleted records
  }
  // else: automatically filters out deleted (default Laravel behavior)
  ```

### 5. **Restore & Force Delete Actions**
- **Kondisi:** Muncul hanya saat `showDeleted = true`
- **Restore Button:**
  - Icon: Sync/Rotate icon
  - Fungsi: Mengembalikan kontrak yang dihapus
  - Method: `confirmRestore()` → `restore()`
  - Soft deletes restored menjadi active records
- **Force Delete Button:**
  - Icon: Trash/Delete icon
  - Fungsi: Menghapus kontrak secara permanent dari database
  - Method: `confirmForceDelete()` → `forceDelete()`
  - **⚠️ WARNING:** Ini adalah hard delete, data tidak dapat dikembalikan

### 6. **Smart Action Buttons**
- **Normal Mode (showDeleted = false):**
  - Detail button (view)
  - Edit button (modify)
  - Delete button (soft delete)
- **Deleted Mode (showDeleted = true):**
  - Restore button (restore soft delete)
  - Force Delete button (permanent delete)

## 🔧 Technical Implementation

### Properties Added to Index.php

```php
// Filter properties
public $jenis_kontrak_filter = '';
public $status_kontrak_filter = '';
public $sisa_kontrak_filter = '';

// Already existed
public bool $showDeleted = false;
```

### Methods Added/Modified

#### 1. `render()` - Query Builder Updates
```php
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
        $q->whereNotNull('tglselesai_kontrak')
          ->whereDate('tglselesai_kontrak', '<', $today);
    } elseif ($this->sisa_kontrak_filter === 'expiring_soon') {
        $q->whereNotNull('tglselesai_kontrak')
          ->whereDate('tglselesai_kontrak', '>=', $today)
          ->whereDate('tglselesai_kontrak', '<=', $today->copy()->addDays(30));
    } elseif ($this->sisa_kontrak_filter === 'valid') {
        $q->whereNotNull('tglselesai_kontrak')
          ->whereDate('tglselesai_kontrak', '>', $today->copy()->addDays(30));
    } elseif ($this->sisa_kontrak_filter === 'unlimited') {
        $q->whereNull('tglselesai_kontrak');
    }
});

// Show deleted or only active (non-deleted)
if ($this->showDeleted) {
    $query->onlyTrashed();
}
```

#### 2. New Methods for Delete Management

```php
public function confirmRestore($id)
public function restore()
public function confirmForceDelete($id)
public function forceDelete()
```

### UI Changes in Blade Template

#### Before:
```blade
<div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between p-3">
    <!-- Only had perPage dropdown and search input -->
</div>
```

#### After:
```blade
<!-- Filters and Actions Row -->
<div class="bg-white rounded-t-lg p-4 border-b border-gray-200">
    <div class="space-y-4">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
            <!-- Filters Grid (3 columns) -->
            <div class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-3 gap-3">
                <!-- Filter Jenis Kontrak -->
                <!-- Filter Status Kontrak -->
                <!-- Filter Sisa Kontrak -->
            </div>

            <!-- Action Buttons (2 columns) -->
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- Show Deleted Button -->
            </div>
        </div>
    </div>
</div>

<!-- Existing perPage and search row -->
```

#### Action Buttons in Table:
```blade
@if ($showDeleted)
    <!-- Restore Button -->
    <!-- Force Delete Button -->
@else
    <!-- Detail Button -->
    <!-- Edit Button -->
    <!-- Delete Button -->
@endif
```

## 🎯 User Workflows

### Workflow 1: Filter Kontrak PKWT yang Akan Berakhir
1. Klik dropdown "Semua Sisa Kontrak"
2. Pilih "Akan Berakhir (≤30 hari)"
3. Hasil: Hanya kontrak PKWT dengan durasi ≤ 30 hari yang ditampilkan
4. Manager bisa langsung melihat kontrak mana yang perlu renewal

### Workflow 2: Lihat Kontrak TETAP
1. Klik dropdown "Semua Jenis Kontrak"
2. Pilih "TETAP"
3. Hasil: Hanya kontrak permanent employees yang ditampilkan

### Workflow 3: Monitor Kontrak Aktif
1. Klik dropdown "Semua Status"
2. Pilih "Aktif"
3. Hasil: Hanya active contracts (baik TETAP maupun PKWT) yang ditampilkan

### Workflow 4: Restore Data Terhapus
1. Klik "Show Deleted" button
2. Table berubah menampilkan soft-deleted records
3. Klik ikon Sync pada kontrak yang ingin dikembalikan
4. Confirm restore
5. Kontrak dikembalikan ke status normal

### Workflow 5: Permanent Delete
1. Klik "Show Deleted" button
2. Klik ikon Trash pada kontrak yang ingin dihapus permanent
3. Confirm hard delete
4. **⚠️ Data dihapus permanent, tidak dapat dikembalikan**

## 📊 Filter Combinations (Examples)

### Use Case 1: "Kontrak TETAP yang Aktif"
- Filter Jenis Kontrak = TETAP
- Filter Status = Aktif
- Filter Sisa Kontrak = Tidak Terbatas (optional, sudah otomatis untuk TETAP)

### Use Case 2: "PKWT yang Belum Selesai Dalam 6 Bulan"
- Filter Jenis Kontrak = PKWT
- Filter Status = Aktif
- Filter Sisa Kontrak = Masih Berlaku (>30 hari)

### Use Case 3: "Semua Kontrak yang Sudah Berakhir"
- Filter Jenis Kontrak = (kosong - semua)
- Filter Status = Selesai
- Filter Sisa Kontrak = Sudah Berakhir

### Use Case 4: "Kontrak dalam Perpanjangan"
- Filter Jenis Kontrak = (optional)
- Filter Status = Perpanjangan
- Filter Sisa Kontrak = (optional)

## 🔐 Data Integrity

### Soft Delete Protection
- Menggunakan Laravel's SoftDeletes trait
- Data tidak benar-benar terhapus dari database
- Bisa di-restore kapan saja
- Default query hanya menampilkan active records

### Audit Trail
- Soft delete mencatat `deleted_at` timestamp
- Bisa tracking kontrak mana saja yang sudah dihapus
- Membantu audit compliance

## 📱 Responsive Design

### Desktop (≥1024px)
- Filters: 3 kolom (jenis kontrak, status, sisa kontrak)
- Action buttons: 1 kolom (show deleted)
- Total: 5 kolom grid

### Tablet (768px - 1023px)
- Filters: 3 dropdown dalam 1 baris
- Action buttons: 1-2 button per baris
- Responsive stacking

### Mobile (<768px)
- Filters: 1 dropdown per baris (full width)
- Action buttons: 1 button per baris (full width)
- Single column layout

## ✅ Testing Checklist

- [ ] Filter jenis kontrak berfungsi (hanya menampilkan kontrak terpilih)
- [ ] Filter status kontrak berfungsi (aktif, selesai, perpanjangan, dibatalkan)
- [ ] Filter sisa kontrak berfungsi (expired, expiring soon, valid, unlimited)
- [ ] Multiple filters dapat dikombinasikan
- [ ] Show deleted button toggle bekerja
- [ ] Restore button muncul hanya saat showDeleted = true
- [ ] Force delete button muncul hanya saat showDeleted = true
- [ ] Search masih bekerja dengan filter
- [ ] Sort masih bekerja dengan filter
- [ ] Pagination bekerja dengan semua kombinasi filter
- [ ] Mobile responsive layout proper

## 📝 Notes

1. **Filter Resets:** Filters di-persist dalam component state, tidak reset saat page refresh
2. **Query Optimization:** Menggunakan `when()` for conditional clauses, tidak menambah query
3. **Performance:** Query keseluruhan efficient, index pada `tglselesai_kontrak` recommended untuk production
4. **UX:** Button label berubah dinamis ("Show Deleted" ↔ "Show Exist") untuk clarity

## 🚀 Future Enhancements

1. **Advanced Filters:**
   - Filter berdasarkan karyawan (employee name/NIP)
   - Filter berdasarkan unit/department
   - Date range picker untuk periode kontrak

2. **Export/Report:**
   - Export filtered results to Excel
   - Generate reports berdasarkan filter

3. **Bulk Actions:**
   - Bulk restore deleted contracts
   - Bulk update status
   - Bulk renew contracts

4. **Saved Filters:**
   - Save filter combinations untuk quick access
   - Predefined common filters

---

**Last Updated:** November 12, 2025
**Status:** ✅ Implementation Complete
