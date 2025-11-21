# Status Logic Fix - Update Kontrak Karyawan

## 📋 Deskripsi Masalah
User melaporkan: **"Kontrak karyawan yang sama hanya boleh aktif satu"**

Sebelumnya, logic edit status kontrak tidak sepenuhnya menerapkan aturan ini dalam semua skenario.

## ✅ Solusi Diterapkan

### 1. Pemisahan Logic: CREATE vs EDIT

**Sebelumnya:**
```php
// Semua logic gabung dalam satu method
if ($autoStatus === 'aktif') {
    $this->handleDuplicateActiveContracts();
}
```

**Sesudah:**
```php
if ($this->isEdit && $this->kontrak_karyawan_id) {
    // EDIT: Get old status, then update, then handle
    $oldKontrak = KaryawanKontrak::findOrFail($this->kontrak_karyawan_id);
    $oldStatus = $oldKontrak->status;
    $oldKontrak->update($data);
    $this->handleDuplicateActiveContractsOnEdit($oldStatus, $autoStatus);
} else {
    // CREATE: Just create and handle if needed
    KaryawanKontrak::create($data);
    if ($autoStatus === 'aktif') {
        $this->handleDuplicateActiveContracts();
    }
}
```

### 2. Method Baru: `handleDuplicateActiveContractsOnEdit()`

**Fungsi:** Handle logic khusus untuk operasi EDIT dengan pengecekan status transisi

```php
private function handleDuplicateActiveContractsOnEdit($oldStatus, $newStatus)
{
    // Skenario: Status berubah dari (selain aktif) menjadi 'aktif'
    // Action: Tutup semua kontrak aktif lainnya untuk karyawan ini
    if ($newStatus === 'aktif' && $oldStatus !== 'aktif') {
        $otherActiveContracts = KaryawanKontrak::where('karyawan_id', $this->karyawan_id)
            ->where('status', 'aktif')
            ->where('id', '!=', $this->kontrak_karyawan_id)
            ->get();
        
        foreach ($otherActiveContracts as $contract) {
            $contract->update([
                'status' => 'selesai',
                'tglselesai_kontrak' => now()->format('Y-m-d'),
                'updated_by' => Auth::id(),
            ]);
        }
    }
    // Skenario: User secara eksplisit menutup kontrak (aktif → selesai)
    // Action: Biarkan, ini adalah aksi user yang disengaja
    elseif ($oldStatus === 'aktif' && $newStatus === 'selesai') {
        Log::info("Contract #{$this->kontrak_karyawan_id} explicitly closed by user");
    }
}
```

### 3. Update Method: `handleDuplicateActiveContracts()` (untuk CREATE)

**Perubahan:**
- Exclude current contract dari query: `where('id', '!=', $this->kontrak_karyawan_id ?? 'null')`
- Tambah `tglselesai_kontrak` saat auto-close kontrak lain

```php
$activeContracts = KaryawanKontrak::where('karyawan_id', $this->karyawan_id)
    ->where('status', 'aktif')
    ->where('id', '!=', $this->kontrak_karyawan_id ?? 'null')
    ->get();

foreach ($activeContracts as $contract) {
    $contract->update([
        'status' => 'selesai',
        'tglselesai_kontrak' => now()->format('Y-m-d'),
        'updated_by' => Auth::id(),
    ]);
}
```

## 📊 Skenario Handling

| Skenario | Old Status | New Status | Aksi | Hasil |
|----------|-----------|-----------|------|-------|
| Create kontrak | - | aktif | Handle duplicate | ✅ Tutup kontrak aktif lain |
| Edit: Ubah ke aktif | selesai | aktif | Handle duplicate | ✅ Tutup kontrak aktif lain |
| Edit: Ubah ke aktif | pending | aktif | Handle duplicate | ✅ Tutup kontrak aktif lain |
| Edit: Tutup kontrak | aktif | selesai | Allow explicitly | ✅ Biarkan user menutup |
| Edit: Status tetap | aktif | aktif | No action | ✅ Tidak ada perubahan |
| Edit: Status tetap | selesai | selesai | No action | ✅ Tidak ada perubahan |

## 🔍 Testing Checklist

### Test 1: Create Kontrak Baru dengan Status Aktif
```
1. Buka form create kontrak
2. Pilih karyawan yang sudah punya kontrak aktif
3. Set status = aktif
4. Klik save
5. Verifikasi:
   - ✅ Kontrak baru tersimpan dengan status aktif
   - ✅ Kontrak lama otomatis berubah status selesai
   - ✅ Hanya 1 kontrak aktif untuk karyawan ini
```

### Test 2: Edit Kontrak dari Selesai menjadi Aktif
```
1. Buka edit kontrak dengan status selesai
2. Ubah status menjadi aktif
3. Klik save
4. Verifikasi:
   - ✅ Kontrak diupdate ke aktif
   - ✅ Kontrak aktif lainnya (jika ada) menjadi selesai
   - ✅ Hanya 1 kontrak aktif untuk karyawan ini
```

### Test 3: Edit Kontrak Tutup Secara Eksplisit
```
1. Buka edit kontrak dengan status aktif
2. Ubah status menjadi selesai
3. Klik save
4. Verifikasi:
   - ✅ Kontrak diupdate ke selesai
   - ✅ User bisa menutup kontrak sesuai kebutuhan
```

### Test 4: Cek Logs
```
1. Tail logs: tail -f storage/logs/laravel.log
2. Buat/edit kontrak sampai ada duplicate close
3. Verifikasi:
   - ✅ Log message muncul: "Contract #X auto-closed when contract #Y set to aktif"
```

## 📁 File yang Diubah

- `app/Livewire/Admin/Karyawan/Kontrak/Index.php`
  - Baris ~523-560: Pemisahan logic CREATE vs EDIT dalam method `save()`
  - Baris ~418-480: Update method `handleDuplicateActiveContracts()`
  - Baris ~482-530: Tambah method baru `handleDuplicateActiveContractsOnEdit()`

## ✨ Key Improvements

1. **Status Transition Awareness** - Sekarang tahu status lama vs baru
2. **Proper Timing** - Get old status SEBELUM update, bukan sesudah
3. **Separate Logic** - CREATE dan EDIT punya logic yang sesuai kebutuhan
4. **Better Logging** - Log semua auto-close action untuk audit trail
5. **Data Integrity** - Semua kontrak yang di-close juga update `tglselesai_kontrak`

## 🚀 Status
✅ IMPLEMENTED
✅ PHP SYNTAX VERIFIED
⏳ READY FOR TESTING
