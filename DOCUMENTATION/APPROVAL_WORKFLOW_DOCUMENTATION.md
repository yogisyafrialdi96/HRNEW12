# Approval Workflow Bertingkat - Documentation

## Overview
Sistem approval workflow bertingkat untuk pengajuan cuti dengan support multiple approval levels (Level 1, 2, 3) berdasarkan hierarchy atasan karyawan.

---

## Components & Services

### 1. **ApprovalService** (`app/Services/ApprovalService.php`)
Service untuk mengelola seluruh approval workflow logic.

#### Key Methods:

```php
// Get approval hierarchy untuk user
ApprovalService::getApprovalHierarchy($userId);

// Get next approver
ApprovalService::getNextApprover($cutiPengajuan);

// Get current approval level (0-3)
ApprovalService::getCurrentApprovalLevel($cutiPengajuan);

// Get total approval levels required
ApprovalService::getTotalApprovalLevels($userId);

// Approve cuti
ApprovalService::approveCuti($cutiPengajuan, $approver, $notes);

// Reject cuti
ApprovalService::rejectCuti($cutiPengajuan, $approver, $reason);

// Get pending approvals untuk approver
ApprovalService::getPendingApprovalsForUser($userId, $level);

// Check apakah user bisa approve
ApprovalService::canApprove($cutiPengajuan, $approver);

// Get status badge config
ApprovalService::getStatusBadgeConfig($status);
```

---

### 2. **CutiApprovalDashboard** (`app/Livewire/Admin/Cuti/CutiApprovalDashboard.php`)
Livewire component untuk dashboard approval.

#### Features:
- ✅ List pending approvals untuk current user sebagai approver
- ✅ Search & filter functionality
- ✅ Modal untuk approval decision
- ✅ Approve dengan optional notes
- ✅ Reject dengan required reason
- ✅ Visual approval level progress indicator

#### Livewire Methods:
```php
openApprovalModal($cutiId)        // Buka approval modal
closeApprovalModal()               // Tutup modal
approve()                          // Approve cuti
reject()                           // Reject cuti
clearFilters()                     // Clear search & filters
```

---

### 3. **View** (`resources/views/livewire/admin/cuti/cuti-approval-dashboard.blade.php`)
Tampilan approval dashboard dengan:
- Table pending approvals
- Search & filter
- Modal dengan approval chain visualization
- Detail pengajuan cuti
- Action buttons (Approve/Reject)

---

## Database Schema

### atasan_user Table
```
- id: primary key
- user_id: karyawan yang memiliki atasan
- atasan_id: user id dari atasan
- level: level approval (1, 2, 3)
- is_active: status aktif
- effective_from: tanggal mulai berlaku
- effective_until: tanggal akhir berlaku
- notes: catatan
- created_by, updated_by: audit
```

### cuti_pengajuan Table
```
- nomor_cuti: auto-generated number
- approval_status: enum
  - pending_approval (initial)
  - approved_level_1 (level 1 approved)
  - approved_level_2 (level 2 approved)
  - approved_level_3 (final approval)
  - rejected_level_1 (rejected by level 1)
  - rejected_level_2 (rejected by level 2)
  - rejected_level_3 (rejected by level 3)
- status: draft, pending, approved, rejected, cancelled
```

---

## Approval Flow

```
┌─────────────────────────────────────────────────────────┐
│  Karyawan membuat Pengajuan Cuti                         │
│  Status: draft → pending                                 │
│  Approval Status: pending_approval                       │
└────────────────┬────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────┐
│  Level 1 Atasan Review                                   │
│  (User dengan level=1 di atasan_user)                    │
├─────────────────────────────────────────────────────────┤
│  ✓ Approve → approval_status = approved_level_1         │
│  ✗ Reject  → approval_status = rejected_level_1         │
│             → status = rejected                          │
└────────────────┬────────────────────────────────────────┘
                 │
        ┌────────┴────────┐
        │                 │
    ✓ Approve         ✗ Reject
        │                 │
        ▼                 ▼
┌───────────────┐   [REJECTED]
│  Level 2      │
│  Atasan       │
├───────────────┤
│ ✓ Approve →   │
│ approved_     │
│ level_2       │
│               │
│ ✗ Reject →    │
│ rejected_     │
│ level_2       │
└───────┬───────┘
        │
    ┌───┴───┐
    │       │
 ✓ App  ✗ Reject
    │       │
    ▼       ▼
┌────────┐[REJECTED]
│Level 3 │
│Atasan  │
├────────┤
│✓ App → │
│approv_ │
│level_3 │
│Status= │
│approve │
│        │
│✗ Rej→  │
│rejected │
│_level_3│
│Status= │
│reject  │
└────────┘
   │
   ▼
[APPROVED]
```

---

## Usage Examples

### Get Pending Approvals
```php
// Di Controller atau Component
$approvals = ApprovalService::getPendingApprovalsForUser(auth()->id());

// Contoh output:
// - Pending cuti dari level 1, 2, 3 yang belum di-approve user ini
```

### Approve Cuti
```php
$cuti = CutiPengajuan::find($cutiId);

ApprovalService::approveCuti(
    $cuti,
    auth()->user(),
    'Catatan approval'
);

// Otomatis update:
// - approval_status: approved_level_1 (jika level 1)
// - updated_by: auth()->id()
// - status: approved (jika final approval)
```

### Reject Cuti
```php
ApprovalService::rejectCuti(
    $cuti,
    auth()->user(),
    'Alasan penolakan yang detail'
);

// Otomatis update:
// - approval_status: rejected_level_1
// - status: rejected
// - catatan_reject: alasan
```

### Check Permission
```php
if (ApprovalService::canApprove($cuti, auth()->user())) {
    // User bisa approve cuti ini
}
```

---

## Route & URL

```php
// Approval Dashboard (HR Manager / Approver)
GET /admin/cuti-approval
// Menampilkan semua pending cuti yang perlu di-approve

// Pengajuan Cuti (Karyawan)
GET /admin/cuti
// Menampilkan pengajuan cuti user

// Cuti Setup (Admin)
GET /admin/settings/cuti
// Konfigurasi global cuti
```

---

## Permissions Required

```php
// Karyawan: create & view pengajuan sendiri
'cuti.create'
'cuti.view'

// HR Manager / Approver: approve pengajuan
'cuti.approve'
```

---

## Atasan/Approver Hierarchy Setup

### Create Atasan User (Approval Chain)
```php
use App\Models\Atasan\AtasanUser;

// Level 1: Manager langsung
AtasanUser::create([
    'user_id' => 2,           // Karyawan
    'atasan_id' => 3,         // Manager langsung
    'level' => 1,
    'is_active' => true,
]);

// Level 2: Kepala departemen
AtasanUser::create([
    'user_id' => 2,
    'atasan_id' => 4,         // Kepala departemen
    'level' => 2,
    'is_active' => true,
]);

// Level 3: Direktur (optional)
AtasanUser::create([
    'user_id' => 2,
    'atasan_id' => 5,         // Direktur
    'level' => 3,
    'is_active' => true,
]);
```

---

## Status Flow Diagram

```
Approval Status Enum Values:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

pending_approval
    ↓ (Level 1 Approves)
approved_level_1
    ↓ (Level 2 Approves)
approved_level_2
    ↓ (Level 3 Approves - Final)
approved_level_3 ✓ [FINAL STATE]

OR

pending_approval
    ↓ (Level 1 Rejects)
rejected_level_1 ✗ [FINAL STATE]

approved_level_1
    ↓ (Level 2 Rejects)
rejected_level_2 ✗ [FINAL STATE]

approved_level_2
    ↓ (Level 3 Rejects)
rejected_level_3 ✗ [FINAL STATE]
```

---

## Key Features

✅ **Multi-Level Approval**: Support untuk 1-3 levels approval  
✅ **Flexible Configuration**: Per-user approval hierarchy via atasan_user  
✅ **Auto Number Generation**: nomor_cuti auto-generate dengan format unik  
✅ **Status Tracking**: approval_status enum untuk tracking progress  
✅ **Permission Control**: Role-based access via Spatie Permission  
✅ **Audit Trail**: created_by, updated_by, timestamps  
✅ **Rejection Handling**: Track rejection reason & alasan  
✅ **Soft Delete Support**: CutiPengajuan support soft delete  

---

## Notes

- Approval Level harus berurutan (1 → 2 → 3)
- User hanya bisa approve di level-nya sesuai atasan_user record
- Total level requirement ditentukan dari MAX(level) di atasan_user
- Rejection berhenti approval chain immediately
- Catatan approval disimpan di updated_at & updated_by
- Status "approved" hanya set setelah final approval

---

**Created:** 2025-12-10  
**Version:** 1.0  
**Status:** Production Ready
