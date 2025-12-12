# 🚀 QUICK START GUIDE - APPROVAL SYSTEM

## ⚡ 30-Second Overview

You now have a **complete multi-level approval system** where:
- ✅ Staff can submit leave requests  
- ✅ Managers can approve subordinate leave (with approval_manager role)  
- ✅ HR Managers have full oversight  
- ✅ Approval status tracked with auto-generated numbers  
- ✅ Not just admin, but specific roles can approve  

---

## 🎯 For Testing

### Test Users Ready
```
1. Dewinta Untari (HR Manager)
   Email: dewinta@example.com
   Password: password
   Can: Approve any cuti, manage approvals

2. Betha Feriani (Staff)
   Email: betha@example.com
   Password: password
   Can: Create/submit own cuti

3. Murni Piramadani (Staff)
   Email: murni@example.com
   Password: password
   Can: Create/submit own cuti
```

### Test Approval Flow
```
STEP 1: Login as Betha → Create cuti → Submit
STEP 2: Login as Dewinta → Cuti Approval Dashboard → Approve
STEP 3: Login as Betha → See status updated
```

---

## 📍 Routes

```
/admin/cuti                    → Staff view their leave requests
/admin/cuti-approval           → Approvers dashboard (for managers)
```

---

## 🔐 New Role: approval_manager

**Who:** Managers, Supervisors, Team Leads  
**What they can do:**
- View employees under them
- Approve their subordinates' leave requests
- Cannot access full admin panel
- Cannot manage other users

**How to assign:**
```php
$user->assignRole('approval_manager');
```

---

## 📊 What Was Built

| Component | File | Purpose |
|-----------|------|---------|
| Service | `app/Services/ApprovalService.php` | Approval logic |
| Component | `app/Livewire/Admin/Cuti/CutiApprovalDashboard.php` | Approver dashboard |
| View | `cuti-approval-dashboard.blade.php` | UI for approvals |
| Permissions | `PermissionSeeder.php` | 4 new cuti permissions |
| Roles | `RoleSeeder.php` | Updated roles + new approval_manager |

---

## ✨ New Features

### **1. Leave Numbers**
Automatically generated: `001/YKPI-CUTI/XII/2025`

### **2. Approval Status Tracking**
- Yellow badge: Menunggu Persetujuan
- Green/Emerald/Lime badges: Approved Level 1/2/3
- Red/Rose/Pink badges: Rejected Level 1/2/3

### **3. Approver Dashboard**
- See all pending approvals from subordinates
- Search by name or leave number
- Modal with full details + approval chain visualization
- Approve with optional notes OR reject with required reason

### **4. Role-Based Approval**
Not just admin! These roles can approve:
- ✅ super_admin
- ✅ admin
- ✅ hr_manager
- ✅ **approval_manager (NEW)** - for managers/supervisors

---

## 🔄 Approval Levels Explained

```
Level 1: Direct Manager/Supervisor
  ↓
Level 2: Department Manager/Head
  ↓
Level 3: Director/Division Head
  ↓
APPROVED ✅
```

Each level is optional - can set fewer levels per employee.

---

## 🎨 Approval Status Flow

```
SUBMITTED
  ↓
pending_approval (Yellow - Waiting Level 1)
  ↓
[Level 1 Approves]
  ↓
approved_level_1 (Green - Passed Level 1)
  ↓
[Level 2 Approves]
  ↓
approved_level_2 (Emerald - Passed Level 2)
  ↓
[Level 3 Approves]
  ↓
approved_level_3 (Lime - FULLY APPROVED) ✅
```

**OR if rejected at any level:**
```
rejected_level_1 (Red) - STOP ❌
rejected_level_2 (Rose) - STOP ❌
rejected_level_3 (Pink) - STOP ❌
```

---

## 🛠️ Configuration

### Already Done ✅
- Permissions created
- Roles updated
- Users seeded
- Approval hierarchy created
- Routes configured
- Database migrated

### What You Need to Do
1. Test the system with test users
2. Create your own approval hierarchy (atasan_user relationships)
3. Optional: Assign approval_manager role to your managers

---

## 📝 Managing Approval Hierarchy

The approval chain is managed via `atasan_user` table:

```
Karyawan A
├─ Level 1 Atasan: Manager B
├─ Level 2 Atasan: Director C
└─ Level 3 Atasan: VP D
```

### Manual Setup (via Tinker)
```php
php artisan tinker

use App\Models\Atasan\AtasanUser;

AtasanUser::create([
    'user_id' => 2,              // Betha
    'atasan_id' => 1,            // Dewinta (her manager)
    'level' => 1,                // This is level 1 approval
    'is_active' => true,
]);
```

---

## 🔍 Verify Installation

```bash
# Check permissions exist
php artisan permission:show | grep cuti.approve

# Check roles
php artisan tinker
>>> Role::with('permissions')->get()

# Check test data
>>> User::count()  # Should show 3+ users
>>> AtasanUser::count()  # Should show 6+
```

---

## 🐛 Troubleshooting

**Q: Approver can't see pending cuti?**
- ✅ Check user has `cuti.approve` permission
- ✅ Check atasan_user table has relationship
- ✅ Check subordinate's cuti status = pending_approval

**Q: Can't access /admin/cuti-approval?**
- ✅ Check middleware: permission:cuti.approve
- ✅ Check user role has this permission
- ✅ Check user is logged in

**Q: Nomor cuti not generated?**
- ✅ Check CutiNumberGenerator service
- ✅ Check cuti_pengajuan.nomor_cuti column exists
- ✅ Check database migration ran

---

## 📞 File Locations

```
Core System Files:
├─ app/Services/ApprovalService.php
├─ app/Livewire/Admin/Cuti/CutiApprovalDashboard.php
├─ resources/views/livewire/admin/cuti/cuti-approval-dashboard.blade.php
├─ database/seeders/PermissionSeeder.php
├─ database/seeders/RoleSeeder.php
└─ routes/web.php

Documentation:
├─ DOCUMENTATION/SYSTEM_APPROVAL_IMPLEMENTATION.md
├─ DOCUMENTATION/IMPLEMENTATION_COMPLETION_REPORT.md
├─ DOCUMENTATION/APPROVAL_WORKFLOW_DOCUMENTATION.md
└─ DOCUMENTATION/DATABASE_SCHEMA_REFERENCE.md
```

---

## 🎓 Learning Path

1. **First:** Understand the roles in RoleSeeder.php
2. **Second:** Check AtasanUser model & approval hierarchy
3. **Third:** Review ApprovalService methods
4. **Fourth:** Test with provided test users
5. **Fifth:** Create your own approval hierarchy

---

## ✅ System Status

```
✅ Code Implementation: COMPLETE
✅ Database Setup: COMPLETE
✅ Test Data: COMPLETE
✅ Routes: COMPLETE
✅ Permissions: COMPLETE
✅ Documentation: COMPLETE

🟢 Status: PRODUCTION READY
```

---

## 🚀 Next Actions

1. **Test the system**
   - Login as test users
   - Create & approve leave requests
   - Verify status updates

2. **Customize for your organization**
   - Create approval hierarchies for real employees
   - Assign approval_manager role to your managers
   - Test real workflows

3. **Enhance (optional)**
   - Add email notifications
   - Add approval deadline/escalation
   - Add custom approval rules

---

**Last Updated:** 2025-12-10  
**Ready Since:** ✅ All systems go!

