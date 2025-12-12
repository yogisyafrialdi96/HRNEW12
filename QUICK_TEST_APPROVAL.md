# 🎯 QUICK START - Test Approval Cuti Button

## Langkah Testing Cepat (5 Menit)

### 1️⃣ Buka Terminal PowerShell

```powershell
cd c:\laragon\www\HRNEW12
tail -f storage/logs/laravel.log
```

**Pesan:** Terminal akan menampilkan log file secara real-time

### 2️⃣ Buka Browser

- Buka aplikasi di browser (http://localhost atau sesuai URL Anda)
- Login sebagai user yang memiliki permission `cuti.approve`
- Navigate ke menu **Approval Cuti**

### 3️⃣ Klik Button Review

- Lihat tabel pengajuan cuti pending
- Klik button **"Review"** pada salah satu pengajuan
- Modal seharusnya muncul dengan detail pengajuan

### 4️⃣ Klik Button Setujui atau Tolak

- **PENTING:** Lihat terminal tetap aktif
- Klik button **"✓ Setujui"** atau **"✗ Tolak"**
- **Lihat terminal** - seharusnya ada log messages

### 5️⃣ Check Output di Terminal

#### ✅ Jika Berhasil, Log akan menunjukkan:

```
========== APPROVE BUTTON CLICKED ==========
User ID: 2
Pengajuan ID: 5
Approval Comment: 
==========================================
🟦 [STEP 1] Starting processApproval
   - Pengajuan ID: 5
   - Action: approved
   - User ID: 2
🟦 [STEP 2] Transaction started
🟦 [STEP 3] Pengajuan loaded
   - Pengajuan Status: pending
   - Total approvals: 2
🟦 [STEP 4] Filtered pending approvals
   - Found: 1
🟦 [STEP 5] Action validated: approved
🟦 [STEP 6] Processing approval ID: 3
   - Approval updated successfully
   - History created with ID: 5
🟦 [STEP 10] Transaction committed successfully
🟦 [STEP 11] Modal closed
✅ SUCCESS: Pengajuan cuti disetujui
```

**Harapan:** 
- Modal akan close
- Toast message muncul (hijau = success)
- Status di table berubah

#### ❌ Jika Ada Error, Log akan menunjukkan:

```
❌ ==========================================
❌ ERROR in processApproval
❌ Message: [deskripsi error]
❌ Code: [kode error]
❌ Trace: [detail error]
❌ ==========================================
```

---

## 📝 Cara Melaporkan Bug (Jika Ada)

**Salin dan bagikan informasi berikut:**

### A. Log Output
```
Salin seluruh output dari terminal saat click button
```

### B. Browser Console Error (jika ada)
```
Buka F12 → Console tab → salin error message
```

### C. Database Status
```sql
-- Run query ini di MySQL/Database client:

-- Check approval status
SELECT id, status, approved_by, approved_at 
FROM cuti_approval 
WHERE cuti_pengajuan_id = [PENGAJUAN_ID];

-- Check approval history
SELECT id, action, user_id, keterangan 
FROM cuti_approval_history 
WHERE cuti_pengajuan_id = [PENGAJUAN_ID];

-- Check user's atasan
SELECT id, user_id, level, is_active 
FROM atasan_user 
WHERE user_id = [USER_ID];
```

---

## 🔧 Pre-Check Sebelum Testing

Pastikan hal ini sudah OK:

- [ ] User sudah **LOGIN**
- [ ] User memiliki permission **`cuti.approve`** (cek di role management)
- [ ] User ada di tabel **`atasan_user`** dengan **`is_active = 1`**
- [ ] Ada minimal 1 **`cuti_pengajuan`** dengan status **`pending`**
- [ ] Ada **`cuti_approval`** untuk pengajuan tersebut dengan status **`pending`**

**Untuk check:**
```sql
-- Check user permission
SELECT r.name, GROUP_CONCAT(p.name) as permissions
FROM users u
JOIN model_has_roles mr ON u.id = mr.model_id
JOIN roles r ON mr.role_id = r.id
LEFT JOIN role_has_permissions rp ON r.id = rp.role_id
LEFT JOIN permissions p ON rp.permission_id = p.id
WHERE u.id = [USER_ID]
GROUP BY r.id;

-- Check user atasan
SELECT * FROM atasan_user 
WHERE user_id = [USER_ID] AND is_active = 1;

-- Check pending approval
SELECT * FROM cuti_approval 
WHERE status = 'pending' LIMIT 5;
```

---

## 📞 Apa yang Dilakukan

**Component sudah diupdate dengan:**

1. ✅ **Logging di button click** - Anda bisa lihat di log file kapan button di-click
2. ✅ **Step-by-step logging** - Track setiap tahap proses approval
3. ✅ **Detailed error logging** - Jika ada error, akan di-log dengan jelas
4. ✅ **Field name fix** - `nama` → `full_name` (sesuai database)
5. ✅ **Button trigger fix** - `wire:click` → `@click.prevent="$wire."`

---

## 🎯 Expected Behavior

### Jika Berhasil:
1. Button di-click → Loading spinner muncul
2. 1-2 detik → Modal close
3. Toast message muncul (hijau)
4. Table status berubah
5. Log file menunjukkan success messages

### Jika Error:
1. Button di-click → Loading spinner muncul
2. Toast message muncul (merah) dengan error message
3. Modal tetap visible
4. Log file menunjukkan error detail

---

## 📄 Dokumentasi Tambahan

File yang sudah dibuat untuk reference:
- `TESTING_APPROVAL_CUTI.md` - Detail testing guide
- `DEBUG_APPROVAL_CUTI_BUTTON.md` - Troubleshooting guide  
- `SUMMARY_APPROVAL_FIX.md` - Summary of all changes

---

**Siap testing! Bilang aja error apa yang keluar di log** 🚀
