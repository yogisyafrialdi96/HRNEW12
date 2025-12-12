# Dokumentasi: Dua Status Field di Tabel Cuti

## Ringkasan
Ada dua field status yang berbeda di sistem cuti, masing-masing menyimpan informasi berbeda:

## 1. `cuti_pengajuan.status` (Status Pengajuan)
**Lokasi**: Tabel `cuti_pengajuan`  
**Tujuan**: Menyimpan status keseluruhan pengajuan cuti dari user

**Nilai yang valid**:
- `draft` - Pengajuan baru, belum dikirim untuk approval
- `pending` - Pengajuan sedang menunggu approval
- `approved` - Pengajuan telah disetujui oleh semua level
- `rejected` - Pengajuan ditolak oleh salah satu level
- `cancelled` - Pengajuan dibatalkan

**Ditampilkan di Modal**:
- Bagian: **Informasi Pengajuan**
- Label: **Status Pengajuan**
- Field source: `$detailModel->status`

---

## 2. `cuti_approval.status` (Status Approval Per Level)
**Lokasi**: Tabel `cuti_approval`  
**Tujuan**: Menyimpan status approval pada setiap level/atasan

**Nilai yang valid**:
- `pending` - Menunggu approval dari atasan
- `approved` - Telah disetujui oleh atasan ini
- `rejected` - Ditolak oleh atasan ini

**Ditampilkan di Modal**:
- Bagian: **Komentar & Status Approval**
- Label: **Status per level approval (dari tabel cuti_approval)**
- Field source: `$approval->status` (dari relasi `approval`)

---

## Perbedaan Kunci

| Aspek | `cuti_pengajuan.status` | `cuti_approval.status` |
|-------|--------------------------|------------------------|
| **Tabel** | `cuti_pengajuan` | `cuti_approval` |
| **Scope** | Keseluruhan pengajuan | Per level approval |
| **Kemungkinan nilai** | 5 nilai | 3 nilai |
| **Diupdate oleh** | Sistem (ketika semua level approved/rejected) | Setiap atasan saat approve/reject |
| **Hubungan** | Satu per user request | Banyak (satu per level) |
| **Di Modal** | Informasi Pengajuan | Komentar & Status Approval |

---

## Alur Data

```
User submit pengajuan cuti
    ↓
cuti_pengajuan.status = "pending"
    ↓
Level 1 Approval
    ├─ cuti_approval[level1].status = "approved"
    ├─ Approver bisa tambah komentar
    └─ Jika rejected → cuti_pengajuan.status = "rejected"
    ↓
Level 2 Approval (jika ada)
    ├─ cuti_approval[level2].status = "approved"
    └─ Jika semua approved → cuti_pengajuan.status = "approved"
```

---

## Contoh Data di Database

### `cuti_pengajuan` (overall)
```sql
id: 10
user_id: 3
status: "approved"  ← Status keseluruhan pengajuan
jenis_cuti: "tahunan"
tanggal_mulai: 2025-12-15
tanggal_selesai: 2025-12-17
created_at: 2025-12-11
```

### `cuti_approval` (per level)
```sql
-- Level 1: Atasan Langsung
id: 45
cuti_pengajuan_id: 10
atasan_user_id: 5
level: 1
status: "approved"  ← Status untuk level ini (dari atasan user_id=5)
komentar: "Sudah disetujui"
approved_by: 5
approved_at: 2025-12-11 10:30

-- Level 2: Manager
id: 46
cuti_pengajuan_id: 10
atasan_user_id: 2
level: 2
status: "approved"  ← Status untuk level ini (dari manager user_id=2)
komentar: "OK"
approved_by: 2
approved_at: 2025-12-11 11:00
```

---

## Tampilan Modal Detail

### Bagian 1: Informasi Pengajuan
```
Jenis Cuti: Tahunan
Status Pengajuan: [Approved] ← dari cuti_pengajuan.status
Tanggal Mulai: 15/12/2025
Tanggal Selesai: 17/12/2025
Jumlah Hari: 3 hari
Tanggal Pengajuan: 11/12/2025 09:00
```

### Bagian 2: Riwayat Approval
```
Menampilkan log historis dari setiap action yang dilakukan
(dari tabel cuti_approval_history)
```

### Bagian 3: Komentar & Status Approval
```
Status per level approval (dari tabel cuti_approval)

Nama Atasan: John Doe
Status: [Approved] ← dari cuti_approval.status (BUKAN cuti_pengajuan.status)
Komentar: Sudah disetujui

Nama Atasan: Jane Smith
Status: [Approved]
Komentar: OK
```

---

## Update Modal (v2)

Perubahan yang dilakukan untuk clarifikasi:

### Info Pengajuan Section
- **Sebelum**: "Status" (tidak jelas source-nya)
- **Sesudah**: "Status Pengajuan" + keterangan "(dari tabel cuti_pengajuan)"

### Komentar Section
- **Sebelum**: "Komentar Approval"
- **Sesudah**: "Komentar & Status Approval" + keterangan "(dari tabel cuti_approval)"

Ini membantu user memahami bahwa ada dua status field yang berbeda:
1. Status keseluruhan aplikasi (cuti_pengajuan)
2. Status per level approval (cuti_approval)

---

## Implementasi di Code

### Staff View: `cuti-pengajuan-index.blade.php`
- Lines 163-188: Info Pengajuan (cuti_pengajuan.status)
- Lines 248-252: Komentar & Status Approval (cuti_approval.status)

### Admin View: `admin/cuti/cuti-pengajuan-index.blade.php`
- Lines 154-179: Info Pengajuan (cuti_pengajuan.status)
- Lines 233-237: Komentar & Status Approval (cuti_approval.status)

---

## Catatan Developer

Ketika membangun query untuk modal, pastikan load relasi dengan benar:

```php
// Di component
$cuti = CutiPengajuan::with([
    'approval.approvedBy',           // approval records dengan user info
    'approvalHistories.user',        // history records
    'user'                           // employee info
])->find($id);
```

Ini memastikan:
- `$cuti->status` = cuti_pengajuan.status
- `$cuti->approval` = collection dari cuti_approval records
- `$cuti->approval->status` = cuti_approval.status (per item)
- `$cuti->approvalHistories` = log dari approval actions
