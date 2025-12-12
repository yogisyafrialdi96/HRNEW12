# Debug Guide - Approval Cuti Button Tidak Berfungsi

## Update 2: Detailing Issue Analysis

### Issues Ditemukan & Diperbaiki:

#### 1. **Field Name Mismatch di Blade View**
- **Masalah:** Blade menggunakan `$pengajuan->user->karyawan->nama` tapi field di database adalah `full_name`
- **File:** `resources/views/livewire/admin/cuti/cuti-approval-index.blade.php`
- **Fixes:**
  - Line 69: Ubah `->nama` menjadi `->full_name`
  - Line 155: Ubah `$selectedApproval->karyawan->name` menjadi `$selectedApproval->user->karyawan->full_name`

#### 2. **Search Query Menggunakan Field yang Salah**
- **Masalah:** Query filter search mencari 'nama' padahal field adalah 'full_name'
- **File:** `app/Livewire/Admin/Cuti/CutiApprovalIndex.php`
- **Fix:** Ubah query di method `pengajuans()` untuk search by `full_name` bukan `nama`

---

## Component Function Flow

### Method: `approve($pengajuanId)`
1. Menerima ID dari `wire:click="approve({{ $selectedApproval->id }})"`
2. Log info untuk debugging
3. Call `processApproval($pengajuanId, 'approved')`
4. Catch exception jika ada

### Method: `reject($pengajuanId)`
1. Menerima ID dari `wire:click="reject({{ $selectedApproval->id }})"`
2. Log info untuk debugging
3. Call `processApproval($pengajuanId, 'rejected')`
4. Catch exception jika ada

### Method: `processApproval($pengajuanId, $action)`

**Logic Flow:**
```
1. Start Transaction
   ↓
2. Get current user ID
   ↓
3. Load pengajuan dengan relasi approval & atasanUser
   ↓
4. Filter approval yang:
   - user_id = current user
   - is_active = true
   - status = pending
   ↓
5. Jika tidak ada approval pending → Throw Exception
   ↓
6. Jika ada, loop setiap approval:
   - Update status ke 'approved' atau 'rejected'
   - Set approved_by = current user
   - Set approved_at = sekarang
   - Create entry di cuti_approval_history
   ↓
7. Refresh pengajuan & get all approvals
   ↓
8. Tentukan status pengajuan:
   - Jika ada 1 rejection → status = 'rejected'
   - Jika semua done & no rejection → status = 'approved'
   - Jika masih ada pending → status tetap 'pending'
   ↓
9. Commit Transaction
   ↓
10. Close Modal
    Dispatch Toast Success
```

---

## Debugging Steps

### Untuk Check Apakah Button Trigger:

1. **Buka Browser Console (F12)**
2. **Masuk ke Network Tab**
3. **Click button Setujui atau Tolak**
4. **Lihat apakah ada request Livewire** (akan ke `/livewire/update`)
5. **Check response** untuk error message

### Untuk Check Log File:

```bash
cd c:\laragon\www\HRNEW12
tail -f storage/logs/laravel.log
```

Seharusnya muncul log:
- `Approve called` atau `Reject called`
- `Start processApproval`
- `Pengajuan loaded`
- `Filtered pending approvals`
- `Approval updated`
- `History created`
- `All approvals after refresh`
- `processApproval completed successfully`

atau

- Error log dengan message yang spesifik

---

## Testing Checklist

### Prerequisite:
- [ ] User sudah login
- [ ] User memiliki permission `cuti.approve`
- [ ] User ada di tabel `atasan_user` dengan `is_active = true`
- [ ] Ada `cuti_pengajuan` dengan `status = 'pending'`
- [ ] Ada `cuti_approval` untuk pengajuan tersebut dengan `status = 'pending'`

### Test Steps:

1. **Test Modal Buka:**
   - [ ] Klik button "Review" pada salah satu pengajuan
   - [ ] Modal seharusnya muncul dengan data lengkap
   - [ ] Textarea "Komentar" seharusnya kosong

2. **Test Button Setujui:**
   - [ ] Klik button "✓ Setujui"
   - [ ] Button seharusnya menunjukkan loading state (⊙ Memproses...)
   - [ ] Toast message seharusnya muncul (success atau error)
   - [ ] Modal seharusnya close
   - [ ] Status di table seharusnya berubah

3. **Test Button Tolak:**
   - [ ] Click button "✗ Tolak"
   - [ ] Seharusnya sama dengan test setujui
   - [ ] Status seharusnya menjadi "Rejected"

4. **Test dengan Komentar:**
   - [ ] Isi textarea dengan komentar
   - [ ] Klik button approval
   - [ ] Verify komentar tersimpan di database

---

## Database Structure Check

### Tabel cuti_pengajuan:
```sql
SELECT * FROM cuti_pengajuan WHERE id = ?
-- Fields penting: id, user_id, status, created_at
```

### Tabel cuti_approval:
```sql
SELECT * FROM cuti_approval WHERE cuti_pengajuan_id = ?
-- Fields penting: id, atasan_user_id, status, approved_by, approved_at
```

### Tabel cuti_approval_history:
```sql
SELECT * FROM cuti_approval_history WHERE cuti_pengajuan_id = ?
-- Fields penting: id, level, status, approved_by, approval_comment
```

### Tabel atasan_user (current user):
```sql
SELECT * FROM atasan_user WHERE user_id = ? AND is_active = 1
-- Fields penting: user_id, level, is_active
```

---

## Possible Issues & Solutions

### Issue #1: "Tidak ada approval yang pending"
**Cause:** 
- Approval sudah di-approve/reject sebelumnya
- User bukan atasan dari pengajuan tersebut
- Approval sudah ter-update oleh user lain

**Solution:**
- Check database bahwa `cuti_approval.status = 'pending'`
- Check bahwa user ada di `atasan_user` dengan `is_active = 1`

### Issue #2: Toast tidak muncul
**Cause:**
- Event listener untuk 'toast' belum terdaftar di frontend
- JavaScript error di console

**Solution:**
- Check browser console (F12) untuk error
- Verifikasi ada toast event listener di Livewire

### Issue #3: Loading state stuck
**Cause:**
- Exception terjadi tapi tidak ter-catch
- Database error saat update

**Solution:**
- Check `storage/logs/laravel.log`
- Run query manual di database untuk check constraints

### Issue #4: Modal tidak close
**Cause:**
- `closeModal()` tidak ter-execute
- Exception terjadi sebelum `closeModal()`

**Solution:**
- Lihat error log
- Verify try-catch structure

---

## Files Modified

1. ✅ `app/Livewire/Admin/Cuti/CutiApprovalIndex.php`
   - Added logging di `approve()` & `reject()`
   - Improved logging di `processApproval()`
   - Fixed search query field name

2. ✅ `resources/views/livewire/admin/cuti/cuti-approval-index.blade.php`
   - Fixed field name from `nama` to `full_name` (line 69)
   - Fixed field name at modal (line 155)

---

## Next Steps if Button Still Not Working

1. **Check Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Manual Test via Tinker:**
   ```bash
   php artisan tinker
   ```

3. **Check Network Request:**
   - F12 → Network → Click button → Look for `/livewire/update` request
   - Check Response for errors

4. **Database Integrity:**
   - Verify foreign key relationships
   - Check data exists in all required tables

---

## Success Indicators

✅ Modal dapat dibuka dengan data lengkap
✅ Button responsive saat di-click
✅ Loading state menampilkan spinner
✅ Toast message muncul (success atau error)
✅ Modal menutup setelah action selesai
✅ Status di table berubah
✅ Data terekam di `cuti_approval` dan `cuti_approval_history`
✅ Logs muncul di `storage/logs/laravel.log`
