# 🎯 MULTI-LEVEL APPROVAL SYSTEM - COMPLETE IMPLEMENTATION GUIDE

## 📋 Overview

Sistem approval bertingkat telah berhasil diimplementasikan dengan dukungan penuh untuk:
- ✅ Multi-level approval hierarchy (3 levels)
- ✅ Role-based approval access (tidak hanya admin)
- ✅ Granular Spatie Permissions
- ✅ Real-time approval dashboard
- ✅ Flexible approval workflow

---

## 🔐 Permission & Role System

### **New Granular Permissions**

```php
// Granular cuti approval permissions
'cuti.approve' => 'Approve leave request (any level)',
'cuti.approve_level_1' => 'Approve leave request (Level 1)',
'cuti.approve_level_2' => 'Approve leave request (Level 2)',
'cuti.approve_level_3' => 'Approve leave request (Level 3)',
```

**Location:** `database/seeders/PermissionSeeder.php` (lines 196-203)

### **Updated Roles**

#### 1. **super_admin & admin** (unchanged)
- All permissions including full approval access

#### 2. **staff** (UPDATED)
```php
'staff' => [
    // Core
    'dashboard.view',
    'karyawan.view',
    'karyawan.edit_own_profile',
    
    // Cuti (dapat submit sendiri)
    'cuti.view',
    'cuti.create',
    'cuti.edit',
    'cuti.submit',
    'cuti.cancel',
    
    // Izin
    'izin.view',
    'izin.create',
    'izin.edit',
    'izin.submit',
    'izin.cancel',
    
    // Attendance
    'attendance.view',
    'attendance.create',
    'attendance.edit',
]
```
**Result:** Staff dapat membuat dan submit pengajuan cuti mereka sendiri

#### 3. **hr_manager** (ENHANCED)
```php
'hr_manager' => [
    // All staff permissions +
    'dashboard_admin.view',
    'users.view',
    'users.create',
    'users.edit',
    'karyawan.create',
    'karyawan.import',
    'pengurus.create',
    'pengurus.edit',
    'pengurus.export',
    'pengurus.import',
    'kontrak_kerja.view',
    'kontrak_kerja.create',
    'kontrak_kerja.edit',
    'kontrak_kerja.print',
    'kontrak_kerja.approve',
    
    // Cuti approval - ALL LEVELS
    'cuti.approve',
    'cuti.approve_level_1',
    'cuti.approve_level_2',
    'cuti.approve_level_3',
    'cuti.export',
    
    // Izin approval
    'izin.approve',
    
    // Hierarchy management
    'atasan.view',
    'atasan.edit',
    
    // Reports
    'reports.view',
    'reports.export',
]
```
**Result:** HR Manager dapat approve cuti di semua level, manage approval hierarchy

#### 4. **approval_manager** (NEW - untuk managers/supervisors)
```php
'approval_manager' => [
    // Dashboard only
    'dashboard.view',
    
    // View employees
    'karyawan.view',
    'karyawan.view_list',
    
    // Cuti approval - ALL LEVELS
    'cuti.view',
    'cuti.approve',
    'cuti.approve_level_1',
    'cuti.approve_level_2',
    'cuti.approve_level_3',
    
    // Izin approval
    'izin.view',
    'izin.approve',
    
    // View hierarchy
    'atasan.view',
    
    // Reports
    'reports.view',
]
```
**Result:** Middle managers (Kepala Departemen, Manager, Supervisor) dapat approve subordinate cuti tanpa akses admin penuh

#### 5. **finance_manager** (unchanged)
- Financial operations only, NO approval rights

**Location:** `database/seeders/RoleSeeder.php` (lines 15-169)

---

## 🏗️ Architecture & Components

### **1. ApprovalService** (Business Logic)

**Location:** `app/Services/ApprovalService.php` (240+ lines)

**Key Methods:**

```php
// Get approval chain untuk user
getApprovalHierarchy($userId): Collection

// Get next approver dalam chain
getNextApprover(CutiPengajuan): ?User

// Get current level (0-3)
getCurrentApprovalLevel(CutiPengajuan): int

// Total approval levels required
getTotalApprovalLevels($userId): int

// Approve cuti, update ke level berikutnya
approveCuti(CutiPengajuan, User, $notes = null): bool

// Reject cuti (stop workflow)
rejectCuti(CutiPengajuan, User, $reason): bool

// Get pending approvals untuk user
getPendingApprovalsForUser($userId, $level = null): Collection

// Check permission untuk approve
canApprove(CutiPengajuan, User): bool

// Get approval history
getApprovalHistory(CutiPengajuan): Collection

// Badge styling config
getStatusBadgeConfig($status): array
```

### **2. CutiApprovalDashboard** (UI Component)

**Location:** `app/Livewire/Admin/Cuti/CutiApprovalDashboard.php`

**Features:**
- List pending cuti awaiting current user's approval
- Search by employee name or cuti number
- Filter by approval level
- Modal untuk review dan approve/reject
- Real-time updates via Livewire
- Validation untuk notes dan rejection reasons

**Blade View:** `resources/views/livewire/admin/cuti/cuti-approval-dashboard.blade.php` (400+ lines)

### **3. Updated Employee View**

**Location:** `resources/views/livewire/admin/cuti/cuti-pengajuan-index.blade.php`

**New Columns:**
- **No. Cuti**: Nomor unik auto-generated (001/YKPI-CUTI/XII/2025)
- **Approval**: Status approval dengan color-coded badges
  - Kuning: Menunggu Persetujuan (pending_approval)
  - Hijau: Approved Level 1
  - Emerald: Approved Level 2
  - Lime: Approved Level 3
  - Merah: Rejected dengan level indicator

---

## 📊 Approval Workflow

### **Standard 3-Level Approval Flow**

```
Staff/Karyawan
     ↓
[Level 1 Approval] - Direct Manager/Supervisor
     ↓ (if approved)
[Level 2 Approval] - Manager/Head of Department
     ↓ (if approved)
[Level 3 Approval] - Director/Head of Division
     ↓ (if approved)
[APPROVED] → Staff dapat lihat cuti approved
```

### **Status Enum Values**

```php
// Pending
'pending_approval'

// Approved path
'approved_level_1'
'approved_level_2'
'approved_level_3'

// Rejected path (stops workflow)
'rejected_level_1'
'rejected_level_2'
'rejected_level_3'
```

### **Database Tables**

1. **atasan_user** - Approval hierarchy
   - user_id: Employee (subordinate)
   - atasan_id: Approver (superior)
   - level: 1-3
   - is_active: Status

2. **cuti_pengajuan** - Leave requests
   - approval_status: Current workflow state
   - nomor_cuti: Auto-generated number

3. **Spatie Permission Tables**
   - roles
   - permissions
   - role_has_permissions
   - model_has_permissions

---

## 🔗 Routes

### **Employee Routes** (permission: cuti.view)
```php
GET /admin/cuti → CutiPengajuanIndex
```

### **Approval Routes** (permission: cuti.approve)
```php
GET /admin/cuti-approval → CutiApprovalDashboard
```

**Access Control:**
- Super Admin: ✅
- Admin: ✅
- HR Manager: ✅
- Approval Manager: ✅
- Manager (with approval_manager role): ✅
- Supervisor (with approval_manager role): ✅
- Staff: ❌ (but can view own cuti)

---

## 🧪 Testing Guide

### **Test Users Created**

```
1. Dewinta Untari
   - Email: dewinta@example.com
   - Role: HR Manager
   - Permission: All approval levels
   - Can: View/approve all cuti

2. Betha Feriani
   - Email: betha@example.com
   - Role: Staff
   - Permission: Create/submit own cuti
   - Can: Request leave

3. Murni Piramadani
   - Email: murni@example.com
   - Role: Staff
   - Permission: Create/submit own cuti
   - Can: Request leave
```

### **Approval Hierarchy Created**

```
Murni (Staff)
  ├─ Level 1 Atasan: Betha (Staff)
  └─ Level 2 Atasan: Dewinta (HR Manager)

Betha (Staff)
  └─ Level 1 Atasan: Dewinta (HR Manager)
```

### **Step-by-Step Test**

**1. Create Leave Request (as Betha)**
```
LOGIN: betha@example.com
MENU: Dashboard → Cuti → Pengajuan Cuti Baru
FILL: Tanggal, Jenis Cuti, Alasan
SUBMIT: Pengajuan Cuti
RESULT: Nomor cuti generated, status = pending_approval
```

**2. Approve Leave (as Dewinta)**
```
LOGIN: dewinta@example.com
MENU: Dashboard → Persetujuan Cuti
VIEW: Pending approvals dari Betha
CLICK: Modal detail
REVIEW: Tanggal, hari, jenis, alasan
ACTION: Approve with optional notes
RESULT: Status = approved_level_1 → approved_level_2 → approved_level_3
```

**3. Verify Status (as Betha)**
```
LOGIN: betha@example.com
MENU: Dashboard → Cuti → Pengajuan Cuti
VIEW: Nomor cuti & status approval (Lime badge = fully approved)
```

---

## 🛠️ Configuration & Setup

### **Already Completed**

✅ PermissionSeeder - Granular permissions defined  
✅ RoleSeeder - All roles created with permissions synced  
✅ UserSeeder - Test users created with roles  
✅ AtasanUserSeeder - Approval hierarchy created  
✅ CutiCalculationService - Smart day calculation  
✅ ApprovalService - Workflow logic  
✅ CutiApprovalDashboard - Livewire component  
✅ Routes configured  
✅ Views created/updated  

### **Database Seeders Run**
```
✅ php artisan db:seed --class=PermissionSeeder
✅ php artisan db:seed --class=RoleSeeder
✅ php artisan db:seed --class=UserSeeder
✅ php artisan db:seed --class=AtasanUserSeeder
```

### **Verify Installation**

```bash
# Check permissions
php artisan permission:show

# Check roles
php artisan tinker
>>> Role::with('permissions')->get();

# Check users with roles
>>> User::with('roles')->get();

# Check approval hierarchy
>>> AtasanUser::with(['user', 'atasan'])->get();
```

---

## 🔄 Approval Workflow Detailed

### **What Happens on Submit**

1. ✅ Cuti pengajuan created dengan approval_status = 'pending_approval'
2. ✅ Nomor cuti auto-generated (001/YKPI-CUTI/XII/2025)
3. ✅ Status tracked dalam approval_status column
4. ✅ Employee dapat view di dashboard

### **What Happens on Approve**

1. ✅ ApprovalService::approveCuti() called
2. ✅ Check current level dari approval_status
3. ✅ Get next approver dari atasan_user table
4. ✅ Update approval_status ke approved_level_X
5. ✅ Increment level untuk next approver
6. ✅ Jika all levels approved → status = approved_level_3
7. ✅ Employee dapat lihat progression di badges

### **What Happens on Reject**

1. ✅ ApprovalService::rejectCuti() called
2. ✅ Set approval_status = rejected_level_X
3. ✅ Store rejection reason
4. ✅ Workflow stops (tidak bisa approved lebih lanjut)
5. ✅ Employee dapat lihat red badge dengan rejection notice

---

## 📱 User Journeys

### **Journey 1: Staff Creating Leave Request**

```
LOGIN (Staff)
  ↓
DASHBOARD → CUTI MENU
  ↓
PENGAJUAN CUTI BARU
  ↓
FILL FORM
  ├─ Tanggal Mulai
  ├─ Tanggal Selesai
  ├─ Jenis Cuti (Cuti Tahunan, Cuti Sakit, etc)
  ├─ Alasan/Keterangan
  └─ SUBMIT
  ↓
NOMOR CUTI AUTO-GENERATED → 001/YKPI-CUTI/XII/2025
  ↓
STATUS = PENDING APPROVAL (Yellow badge)
  ↓
WAITING FOR LEVEL 1 APPROVER
```

### **Journey 2: Manager Approving Leave**

```
LOGIN (Manager with approval_manager role)
  ↓
DASHBOARD → PERSETUJUAN CUTI
  ↓
VIEW PENDING APPROVALS
  ├─ List dari subordinate (Betha, Murni, etc)
  ├─ Search by name atau nomor cuti
  └─ Filter by level
  ↓
CLICK DETAIL / APPROVE BUTTON
  ↓
MODAL OPENS
  ├─ Karyawan Info
  ├─ Cuti Details (tanggal, hari, jenis)
  ├─ Alasan/Keterangan
  ├─ Approval Chain Visual (level indicators)
  ├─ Optional Notes (untuk approval)
  └─ APPROVE / REJECT BUTTONS
  ↓
IF APPROVE:
  ├─ Status update → approved_level_1
  ├─ Pass ke next approver
  ├─ Employee notified (future feature)
  └─ Modal closes
  ↓
IF REJECT:
  ├─ Require rejection reason
  ├─ Status update → rejected_level_1
  ├─ Workflow stops
  ├─ Employee notified of rejection (future feature)
  └─ Modal closes
```

### **Journey 3: HR Manager Overseeing All Approvals**

```
LOGIN (HR Manager)
  ↓
DASHBOARD → PERSETUJUAN CUTI
  ↓
SEE ALL PENDING
  ├─ Level 1 pending (from supervisors)
  ├─ Level 2 pending (from managers)
  ├─ Level 3 pending (from directors)
  └─ Can approve at any level
  ↓
MANAGE APPROVAL HIERARCHY
  ├─ View atasan_user relationships
  ├─ Edit approval chain
  └─ Override approvals if needed
```

---

## 🎨 Badge & Status Display

### **Approval Status Color Coding**

| Status | Badge Color | Label |
|--------|------------|-------|
| pending_approval | Yellow | Menunggu Persetujuan |
| approved_level_1 | Green | Disetujui Level 1 |
| approved_level_2 | Emerald | Disetujui Level 2 |
| approved_level_3 | Lime | Disetujui Level 3 (FINAL) |
| rejected_level_1 | Red | Ditolak Level 1 |
| rejected_level_2 | Rose | Ditolak Level 2 |
| rejected_level_3 | Pink | Ditolak Level 3 |

---

## 📚 Related Documentation

- `APPROVAL_WORKFLOW_DOCUMENTATION.md` - Original approval workflow docs
- `DATABASE_SCHEMA_REFERENCE.md` - Complete database schema
- `cuti-approval-dashboard.blade.php` - Approval dashboard template
- `ApprovalService.php` - Approval business logic
- `CutiApprovalDashboard.php` - Livewire component

---

## ✅ Implementation Checklist

- [x] Granular permissions created
- [x] Roles updated with proper permissions
- [x] New approval_manager role created
- [x] ApprovalService implemented
- [x] CutiApprovalDashboard component created
- [x] Blade views updated
- [x] Routes configured
- [x] Test users seeded
- [x] Approval hierarchy created
- [x] Database seeders run successfully
- [x] Permission/role system synced

---

## 🚀 Next Steps (Optional Enhancements)

1. **Notifications**
   - Email notification when cuti pending approval
   - Email notification when cuti approved/rejected
   - Dashboard notification count

2. **Audit Trail**
   - Log semua approval decisions
   - Track who approved/rejected dan kapan
   - Store approval notes in separate table

3. **Advanced Features**
   - Conditional approval rules (based on cuti type, duration, etc)
   - Approval deadline/escalation
   - Bulk approval actions
   - Approval templates with custom rules

4. **Integration**
   - Calendar integration to block approved dates
   - Integration dengan payroll system
   - API untuk mobile app

---

## 📞 Support & Troubleshooting

**Q: Approver tidak melihat pending cuti?**  
A: Check atasan_user table ada relationship antara user_id dan atasan_id. Run `AtasanUserSeeder` jika data kosong.

**Q: Tidak bisa access approval dashboard?**  
A: Verify user role memiliki permission 'cuti.approve'. Check di roles table.

**Q: Status tidak update setelah approve?**  
A: Check ApprovalService::approveCuti() called dengan correct parameters. Verify atasan hierarchy complete.

---

**Last Updated:** 2025-12-10  
**System Status:** ✅ PRODUCTION READY

