# Panduan Testing & Debugging - Approval Cuti Button

## 📝 Perubahan Terbaru

### 1. Enhanced Logging di Method `approve()` dan `reject()`
File: `app/Livewire/Admin/Cuti/CutiApprovalIndex.php`

```php
public function approve($pengajuanId) {
    try {
        \Log::warning('========== APPROVE BUTTON CLICKED ==========');
        \Log::warning('User ID: ' . auth()->id());
        \Log::warning('Pengajuan ID: ' . $pengajuanId);
        \Log::warning('Approval Comment: ' . $this->approvalComment);
        \Log::warning('==========================================');
        
        $this->processApproval($pengajuanId, 'approved');
    } catch (\Exception $e) {
        \Log::error('❌ Approve error: ' . $e->getMessage());
        \Log::error('Exception trace: ' . $e->getTraceAsString());
        $this->dispatch('toast', message: 'Error: ' . $e->getMessage(), type: 'error');
    }
}
```

**Output di log file:**
```
========== APPROVE BUTTON CLICKED ==========
User ID: 2
Pengajuan ID: 5
Approval Comment: Sudah saya review
==========================================
```

### 2. Step-by-Step Logging di `processApproval()`

File: `app/Livewire/Admin/Cuti/CutiApprovalIndex.php`

Setiap step di-log dengan detail lengkap:

```
🟦 [STEP 1] Starting processApproval
   - Pengajuan ID: 5
   - Action: approved
   - User ID: 2

🟦 [STEP 2] Transaction started

🟦 [STEP 3] Pengajuan loaded
   - Pengajuan Status: pending
   - Total approvals: 2

[Detailed approval filtering logs]

🟦 [STEP 4] Filtered pending approvals
   - Found: 1

🟦 [STEP 5] Action validated: approved

🟦 [STEP 6] Processing approval ID: 3
   - Update data: {...}
   - Approval updated successfully
   - Creating history with data: {...}
   - History created with ID: 5

🟦 [STEP 7] All approvals processed. History count: 1

🟦 [STEP 8] Pengajuan refreshed
   - Total approvals: 2
   - Statuses: ["approved","pending"]

🟦 [STEP 9] Status summary:
   - Approved: 1
   - Rejected: 0
   - Pending: 1
   - Pengajuan status remains: PENDING (waiting for other approvals)

🟦 [STEP 10] Transaction committed successfully

🟦 [STEP 11] Modal closed

✅ SUCCESS: Approval Anda disimpan. Menunggu approval dari level lain
```

### 3. Blade View Button Update

File: `resources/views/livewire/admin/cuti/cuti-approval-index.blade.php`

```blade
<!-- SEBELUM -->
<button wire:click="approve({{ $selectedApproval->id }})">

<!-- SEKARANG -->
<button @click.prevent="$wire.approve({{ $selectedApproval->id }})">
```

Alasan: Menggunakan `@click.prevent` memastikan tidak ada default behavior sebelum method Livewire dipanggil.

---

## 🔍 Cara Melakukan Testing

### Step 1: Check Log File

**Terminal:**
```bash
cd c:\laragon\www\HRNEW12
tail -f storage/logs/laravel.log
```

**Output yang diharapkan saat button di-click:**
```
[2025-12-11 14:25:30] local.WARNING: ========== APPROVE BUTTON CLICKED ==========
[2025-12-11 14:25:30] local.WARNING: User ID: 2
[2025-12-11 14:25:30] local.WARNING: Pengajuan ID: 5
[2025-12-11 14:25:30] local.WARNING: Approval Comment: 
[2025-12-11 14:25:30] local.WARNING: ==========================================
[2025-12-11 14:25:30] local.WARNING: 🟦 [STEP 1] Starting processApproval
[2025-12-11 14:25:30] local.WARNING:    - Pengajuan ID: 5
```

### Step 2: Test Prosedur

1. **Login** sebagai user dengan permission `cuti.approve`
2. **Pastikan user ada di tabel `atasan_user`** dengan `is_active = 1`
3. **Buka aplikasi** di browser
4. **Navigate ke Approval Cuti** page
5. **Buka terminal** dan jalankan `tail -f storage/logs/laravel.log`
6. **Klik button "Review"** pada salah satu pengajuan pending
7. **Lihat di terminal** - seharusnya muncul log dari `openApprovalModal()`:
   ```
   [STEP 3] Pengajuan loaded
   ```

8. **Isi komentar** (opsional) di textarea
9. **KLIK BUTTON "✓ Setujui"**
10. **Lihat di terminal** - seharusnya muncul log step 1-11

### Step 3: Verify Database Changes

Setelah click button, check database:

```sql
-- Check apakah cuti_approval status berubah
SELECT id, status, approved_by, approved_at FROM cuti_approval 
WHERE cuti_pengajuan_id = 5;

-- Check apakah history terekam
SELECT id, action, user_id, keterangan FROM cuti_approval_history 
WHERE cuti_pengajuan_id = 5;

-- Check apakah pengajuan status berubah
SELECT id, status FROM cuti_pengajuan WHERE id = 5;
```

---

## ❌ Troubleshooting

### Scenario 1: Log tidak ada di file

**Problem:** Tidak ada log muncul di `storage/logs/laravel.log`

**Kemungkinan Penyebab:**
- Button tidak di-trigger
- Livewire component tidak properly loaded
- JavaScript error di browser

**Solution:**
1. Check browser console (F12 → Console)
2. Lihat ada error JavaScript
3. Check Livewire component sudah ter-load (buka Network tab, filter `livewire`)

### Scenario 2: Error "Anda tidak memiliki approval yang pending"

**Log yang muncul:**
```
🟦 [STEP 4] Filtered pending approvals
   - Found: 0

❌ [STEP 5] No pending approval found
❌ Error: Anda tidak memiliki approval yang pending untuk pengajuan ini
```

**Kemungkinan Penyebab:**
- `cuti_approval` status sudah bukan 'pending' (sudah di-approve sebelumnya)
- User bukan atasan dari pengajuan tersebut
- `atasan_user` untuk user ini tidak ada atau `is_active = false`

**Solution:**
```sql
-- Check approval status
SELECT id, status, atasan_user_id FROM cuti_approval 
WHERE cuti_pengajuan_id = ?;

-- Check atasan_user
SELECT id, user_id, level, is_active FROM atasan_user 
WHERE user_id = ? AND is_active = 1;

-- Check apakah user_id match di atasan_user
SELECT au.user_id, au.level, au.is_active 
FROM cuti_approval ca
JOIN atasan_user au ON ca.atasan_user_id = au.id
WHERE ca.cuti_pengajuan_id = ?;
```

### Scenario 3: Error di Step 6 "Creating history"

**Log yang muncul:**
```
❌ Error creating history: SQLSTATE[23000]: Integrity constraint violation
```

**Kemungkinan Penyebab:**
- Field yang di-insert tidak sesuai dengan schema migration
- Foreign key constraint error
- Required field tidak ada value

**Solution:**
1. Check migration: `database/migrations/2025_12_09_000001_create_cuti_izin_tables.php`
2. Verify tabel `cuti_approval_history` structure:
   ```sql
   DESC cuti_approval_history;
   ```
3. Lihat field apa yang required (nullable vs not null)

### Scenario 4: Modal tidak close setelah click

**Problem:** Button di-click, tapi modal tetap visible

**Kemungkinan Penyebab:**
- Exception terjadi sebelum `closeModal()`
- JavaScript error

**Solution:**
1. Lihat log untuk error message
2. Check browser console (F12)
3. Verify `closeModal()` method sudah correct

---

## 📋 Database Schema Check

Jalankan query ini untuk verify struktur yang benar:

```sql
-- Cek structure cuti_approval
DESC cuti_approval;
-- Harus ada: id, cuti_pengajuan_id, atasan_user_id, level, status, komentar, approved_by, approved_at

-- Cek structure cuti_approval_history
DESC cuti_approval_history;
-- Harus ada: id, cuti_pengajuan_id, action, user_id, keterangan (dan field opsional lain)

-- Cek structure atasan_user
DESC atasan_user;
-- Harus ada: id, user_id, level, is_active
```

---

## 🎯 Checklist Testing Lengkap

### Pre-requisite:
- [ ] PHP >= 8.1
- [ ] Laravel 11
- [ ] Livewire 3
- [ ] Database connection OK
- [ ] User sudah login
- [ ] User memiliki permission `cuti.approve`
- [ ] User ada di tabel `atasan_user` dengan `is_active = 1`
- [ ] Ada minimal 1 `cuti_pengajuan` dengan status `pending`
- [ ] `cuti_approval` untuk pengajuan tersebut ada dengan status `pending`

### Testing Steps:

1. **Test Button Trigger:**
   - [ ] Buka terminal dengan `tail -f storage/logs/laravel.log`
   - [ ] Klik button "✓ Setujui"
   - [ ] Check log muncul "APPROVE BUTTON CLICKED"
   - [ ] Jika tidak ada, check browser console untuk error

2. **Test Modal Close:**
   - [ ] After click, modal seharusnya close dalam 1-2 detik
   - [ ] Jika tidak close, check log untuk error message

3. **Test Data Update:**
   - [ ] After click, status di table seharusnya berubah
   - [ ] Check database: `SELECT status FROM cuti_approval WHERE id = ?`
   - [ ] Seharusnya berubah dari 'pending' ke 'approved'

4. **Test History Recording:**
   - [ ] Check `cuti_approval_history` table
   - [ ] Seharusnya ada row baru untuk pengajuan yang di-approve
   - [ ] Verify `user_id` dan `keterangan` sudah correct

5. **Test Toast Message:**
   - [ ] After action, toast message seharusnya muncul
   - [ ] Success message: "Pengajuan cuti disetujui" atau "Approval Anda disimpan..."
   - [ ] Error message: deskripsi error yang jelas

---

## 📞 Info untuk Pelaporan Bug

Jika masih ada masalah, sertakan informasi berikut:

1. **Screenshot dari:**
   - Browser console (F12 → Console)
   - Browser Network tab saat click button
   - Terminal output dari `tail -f storage/logs/laravel.log`

2. **Query results dari:**
   ```sql
   SELECT * FROM cuti_pengajuan WHERE id = ?;
   SELECT * FROM cuti_approval WHERE cuti_pengajuan_id = ?;
   SELECT * FROM atasan_user WHERE user_id = ?;
   ```

3. **Application info:**
   - PHP version: `php -v`
   - Database: `mysql --version`
   - Timestamp saat error terjadi

---

## 🔗 Log File Location

```
c:\laragon\www\HRNEW12\storage\logs\laravel.log
```

Buka dengan text editor atau gunakan command:
```bash
cat storage/logs/laravel.log | tail -n 100
```
