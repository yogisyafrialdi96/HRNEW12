# Approval System - Architecture & Implementation

## Overview

Sistem approval menggunakan relasi database yang terstruktur antara tabel `cuti_approval` dan `cuti_approval_history` untuk merekam setiap tahap persetujuan.

**Prinsip Utama:**
- Status approval **TIDAK** disimpan sebagai single field di tabel `cuti_pengajuan`
- Status approval diambil dari relasi `cuti_approval` dan `cuti_approval_history`
- Setiap approval action dicatat di `cuti_approval_history` untuk audit trail yang lengkap
- `cuti_approval` menyimpan status real-time approval per level

---

## Database Structure

### 1. Tabel: cuti_pengajuan
```sql
Table: cuti_pengajuan
Columns:
  - id (PK)
  - nomor_cuti (string, unique) -- Auto-generated number
  - user_id (FK) -- Employee who requested
  - unit_approval_setting_id (FK) -- Approval template
  - cuti_saldo_id (FK)
  - tahun_ajaran_id (FK)
  - jenis_cuti (enum: tahunan, melahirkan)
  - status (enum: draft, pending, approved, rejected, cancelled) -- Main status
  - tanggal_mulai, tanggal_selesai (dates)
  - jumlah_hari (int)
  - alasan (text)
  - catatan_reject (text) -- Only if rejected
  - created_by, updated_by (FK to users)
  - created_at, updated_at
```

**Key Point:** Kolom `status` di sini adalah **final status** (draft/pending/approved/rejected), bukan tahap approval.

### 2. Tabel: cuti_approval (Real-time approval tracking)
```sql
Table: cuti_approval
Purpose: Mencatat approval status per level secara real-time
Columns:
  - id (PK)
  - cuti_pengajuan_id (FK) -- Reference to cuti
  - atasan_user_id (FK) -- Reference to atasan_user (approver)
  - level (int) -- Approval level (1, 2, 3, etc)
  - status (enum: pending, approved, rejected) -- Current approval status
  - komentar (text) -- Approver notes
  - approved_by (FK to users) -- Who approved/rejected
  - approved_at (timestamp)
  - urutan_approval (int) -- Sequence order
  - created_at, updated_at

Contoh:
  Cuti ID 1 memiliki 2 level approval:
  - Row 1: level=1, status=approved, approver=Admin
  - Row 2: level=2, status=pending, approver=Dewinta
  
  Jika Admin approve, Row 1 status jadi "approved"
  Jika Dewinta approve, Row 2 status jadi "approved" dan cuti_pengajuan.status jadi "approved"
```

### 3. Tabel: cuti_approval_history (Audit trail)
```sql
Table: cuti_approval_history
Purpose: Merekam setiap action pada approval untuk audit trail
Columns:
  - id (PK)
  - cuti_pengajuan_id (FK)
  - level (int) -- Which level approved/rejected
  - status (enum: pending, approved, rejected) -- What happened
  - approved_by (FK to users) -- Who took the action
  - approval_comment (text) -- Comments if any
  - action (enum: created, submitted, approved, rejected, cancelled)
  - user_id (FK) -- Who initiated the action
  - old_data (json) -- Previous values
  - new_data (json) -- New values
  - keterangan (text) -- Description
  - created_at, updated_at

Contoh Timeline untuk 1 cuti:
  - Action 1: created, user_id=Betha (employee), action='created'
  - Action 2: submitted, user_id=Betha, action='submitted'
  - Action 3: approved, approved_by=Admin, level=1, action='approved'
  - Action 4: approved, approved_by=Dewinta, level=2, action='approved'
```

---

## Model Relationships

### CutiPengajuan Model
```php
// One-to-Many: Satu cuti memiliki banyak approval records
public function approval() {
    return $this->hasMany(CutiApproval::class, 'cuti_pengajuan_id');
}

// One-to-Many: Satu cuti memiliki history records
public function history() {
    return $this->hasMany(CutiApprovalHistory::class, 'cuti_pengajuan_id');
}

// Method untuk mendapatkan approval status
public function getCurrentApprovalStatus() {
    // Return status berdasarkan approval records
    // Contoh: 'pending_level_1', 'approved_level_1_pending_level_2', 'approved', 'rejected'
}

public function getCurrentApprover() {
    // Return user yang harus approve saat ini
}

public function getLastApprovalHistory() {
    // Return record history terakhir
}
```

### CutiApproval Model
```php
public function cutiPengajuan() {
    return $this->belongsTo(CutiPengajuan::class);
}

public function atasanUser() {
    return $this->belongsTo(AtasanUser::class);
}

public function approvedBy() {
    return $this->belongsTo(User::class, 'approved_by');
}
```

### CutiApprovalHistory Model
```php
public function cutiPengajuan() {
    return $this->belongsTo(CutiPengajuan::class);
}

public function user() {
    return $this->belongsTo(User::class, 'user_id');
}

public function approver() {
    return $this->belongsTo(User::class, 'approved_by');
}
```

---

## Approval Service (ApprovalService)

### Key Methods

```php
// Get current approval level
CurrentApprovalLevel($cutiPengajuan): int
  - Mengambil last non-pending approval untuk mendapat level terakhir
  - Return 0 jika belum ada approval sama sekali

// Check total levels needed
getTotalApprovalLevels($userId): int
  - Query atasan_user table untuk max level
  - Return berapa level yang diperlukan

// Approve cuti
approveCuti($cutiPengajuan, $approver, $notes): bool
  - Find pending approval dengan status='pending'
  - Update CutiApproval record: status='approved', approved_by=$approver->id
  - Create CutiApprovalHistory record
  - Jika semua level approved, update cuti_pengajuan.status='approved'

// Reject cuti
rejectCuti($cutiPengajuan, $approver, $reason): bool
  - Similar to approve tapi set status='rejected'
  - Update cuti_pengajuan.status='rejected'

// Get pending approvals untuk approver
getPendingApprovalsForUser($userId, $level): Collection
  - Query CutiApproval table where atasan_user_id=$userId and status='pending'
  - Return cutiPengajuan objects

// Check can approve
canApprove($cutiPengajuan, $approver): bool
  - Verify ada pending approval dengan approver ini
  - Prevent wrong person dari approve

// Get approval history
getApprovalHistory($cutiPengajuan): Collection
  - Return CutiApprovalHistory records ordered by created_at
```

---

## Flow: Approval Process

### 1. Employee Create Cuti
```
Betha creates cuti request
  ↓
cuti_pengajuan table:
  - nomor_cuti: CUTI/2024/0001 (auto-generated)
  - user_id: Betha
  - status: draft
  
cuti_approval table: (created by system when status becomes 'pending')
  - Belum ada record yet
  
cuti_approval_history table:
  - Action: created, user_id=Betha
```

### 2. Employee Submit Cuti
```
Betha clicks "Submit" button
  ↓
cuti_pengajuan.status becomes 'pending'
  ↓
System creates approval records in cuti_approval:
  - For each level in user's approval hierarchy
  - Level 1: atasan_user_id=Admin, status=pending
  - Level 2: atasan_user_id=Dewinta, status=pending

cuti_approval_history table:
  - Action: submitted, user_id=Betha
```

### 3. Level 1 Approver (Admin) Reviews
```
Admin sees pending approvals in dashboard
  ↓
Admin clicks "Approve" button
  ↓
ApprovalService::approveCuti() called
  ↓
cuti_approval table (Level 1):
  - status: approved
  - approved_by: Admin->id
  - approved_at: now()
  
cuti_approval_history table:
  - Action: approved, level=1, approved_by=Admin
  
cuti_pengajuan table:
  - status: still 'pending' (waiting for level 2)
```

### 4. Level 2 Approver (Dewinta) Reviews
```
Dewinta sees pending approvals in dashboard
  ↓
Dewinta clicks "Approve" button
  ↓
ApprovalService::approveCuti() called
  ↓
cuti_approval table (Level 2):
  - status: approved
  - approved_by: Dewinta->id
  - approved_at: now()
  
ApprovalService checks: approved_count == total_levels?
  - YES! All approved
  ↓
cuti_pengajuan table:
  - status: approved ← FINAL STATUS
  
cuti_approval_history table:
  - Action: approved, level=2, approved_by=Dewinta
```

### 5. Final State
```
cuti_pengajuan:
  - id: 1
  - nomor_cuti: CUTI/2024/0001
  - user_id: Betha
  - status: approved
  - created_by: Betha
  
cuti_approval:
  - Row 1: level=1, status=approved, approver=Admin, approved_at=2024-12-10
  - Row 2: level=2, status=approved, approver=Dewinta, approved_at=2024-12-10 (later)

cuti_approval_history:
  - Row 1: action=created, user_id=Betha
  - Row 2: action=submitted, user_id=Betha
  - Row 3: action=approved, level=1, approved_by=Admin
  - Row 4: action=approved, level=2, approved_by=Dewinta
```

---

## Getting Approval Status (Examples)

### Method 1: Using getCurrentApprovalStatus()
```php
$cuti = CutiPengajuan::find(1);
$status = $cuti->getCurrentApprovalStatus();
// Returns: 'pending_level_1', 'approved_level_1_pending_level_2', 'approved', 'rejected_level_2', etc
```

### Method 2: Direct Query
```php
$cuti = CutiPengajuan::find(1);

// Get all approvals
$approvals = $cuti->approval()->orderBy('urutan_approval')->get();
// [
//   { level: 1, status: 'approved', approver: Admin },
//   { level: 2, status: 'pending', approver: Dewinta }
// ]

// Get who needs to approve next
$nextApprover = $cuti->getCurrentApprover();
// Returns Dewinta's atasan_user record

// Get approval history
$history = $cuti->history()->orderBy('created_at')->get();
// Detailed timeline of all actions
```

### Method 3: For Dashboard Display
```php
// Show pending approvals for user (as approver)
$pendingApprovals = ApprovalService::getPendingApprovalsForUser($dewinta->id);
// Returns array of cuti pengajuan objects waiting for Dewinta

// Check if specific user can approve
$canApprove = ApprovalService::canApprove($cuti, $admin);
// Returns true/false
```

---

## Migration History

### Migration 1: Create Tables (2025_12_09_000001)
- Creates: cuti_approval, cuti_approval_history
- Basic structure

### Migration 2: Update History Table (2025_12_09_000002)
- Adds: level, status, approved_by, approval_comment to history table
- For detailed audit trail

### Migration 3: Add nomor_cuti (2025_12_10_000003)
- Adds: nomor_cuti column with unique constraint
- Auto-generated by CutiNumberGenerator

---

## Implementation Checklist

- [x] Database tables created (cuti_approval, cuti_approval_history)
- [x] Models relationships configured
- [x] ApprovalService updated to use relations
- [x] Methods to get approval status from relations
- [x] Approval process works with multi-level support
- [x] Audit trail recorded in history table
- [x] nomor_cuti auto-generated
- [ ] Component dashboard updated to use new methods
- [ ] Blade templates updated to display approval status correctly
- [ ] Tests created for approval workflow

---

## Key Points to Remember

1. **NO approval_status field in cuti_pengajuan table**
   - Use relations instead
   - More flexible and audit-friendly

2. **cuti_approval records are created when status becomes 'pending'**
   - Not when creating the cuti

3. **cuti_approval_history is immutable audit log**
   - Never update, only create new records

4. **Use ApprovalService methods for all approval operations**
   - Ensures consistency
   - Handles multi-level properly

5. **getCurrentApprovalStatus() calculates status on the fly**
   - No need to store
   - Always accurate

