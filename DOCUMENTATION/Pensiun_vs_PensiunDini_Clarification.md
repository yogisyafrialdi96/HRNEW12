# Status Pensiun vs Pensiun Dini - Clarification

## Overview
Sistem HR membedakan antara dua jenis pensiun: "Pensiun" (otomatis di usia 56) dan "Pensiun Dini" (pengajuan khusus).

## Status Codes

| ID | Status | Type | Trigger | Auto-Update | Description |
|----|--------|------|---------|------------|-------------|
| **3** | **Pensiun** | Normal | Age 56 | ✅ YES | Standard retirement at age 56 |
| **4** | **Pensiun Dini** | Early | Manual | ❌ NO | Early/voluntary retirement before 56 |

## Pensiun (ID: 3) - Normal Retirement

### Criteria
- Age reaches 56 years old
- Automatically triggered on retirement date
- Based on birth date calculation

### When Applied
✅ Automatically by system when:
- Employee age = Birth date + 56 years
- Today's date ≥ Retirement date
- Employee status was not already Pensiun

### Example
```
Birth: 1968-05-15
Age 56 Date: 2024-05-15
Today: 2025-11-13

Result: Automatically set to "Pensiun" (Status ID: 3)
```

### Who Can Update
- System (automatically via command/schedule)
- Admin (manually via UI if needed)

## Pensiun Dini (ID: 4) - Early Retirement

### Criteria
- Employee chooses to retire before age 56
- Or special circumstances (health, restructuring, etc.)
- Manually requested and approved

### When Applied
❌ Never automatically triggered
✅ Only via manual admin action:
- Employee request
- Management approval
- Admin updates status in system

### Example
```
Birth: 1970-10-20
Age at request: 52 years old
Reason: Health/family/preference

Result: Manually set to "Pensiun Dini" (Status ID: 4)
```

### Who Can Update
- Admin only (manual decision)
- HR Manager approval required

## System Behavior

### Automatic Updates (ID: 3 Only)
```bash
# This command ONLY updates to ID: 3 (Pensiun)
php artisan employees:update-retired-status

# Updated: Employees reaching age 56
# Not updated: Any with status 4 (Pensiun Dini)
```

### Manual Updates (ID: 3 or 4)
```php
// Admin can manually set either
$employee->update(['statuskaryawan_id' => 3]);  // Pensiun
$employee->update(['statuskaryawan_id' => 4]);  // Pensiun Dini
```

## Database Separation

### Query for Normal Retirement (Auto-updated)
```sql
-- Employees who automatically retired at age 56
SELECT id, nip, full_name, tanggal_lahir, tgl_berhenti, statuskaryawan_id
FROM karyawan 
WHERE statuskaryawan_id = 3  -- Pensiun
ORDER BY tgl_berhenti DESC;
```

### Query for Early Retirement (Manual)
```sql
-- Employees who chose early retirement
SELECT id, nip, full_name, tanggal_lahir, tgl_berhenti, statuskaryawan_id
FROM karyawan 
WHERE statuskaryawan_id = 4  -- Pensiun Dini
ORDER BY tgl_berhenti DESC;
```

### Query Both
```sql
-- All retired employees
SELECT id, nip, full_name, tanggal_lahir, tgl_berhenti, statuskaryawan_id,
       CASE 
           WHEN statuskaryawan_id = 3 THEN 'Pensiun (Usia 56)'
           WHEN statuskaryawan_id = 4 THEN 'Pensiun Dini (Awal)'
       END as pension_type
FROM karyawan 
WHERE statuskaryawan_id IN (3, 4)
ORDER BY tgl_berhenti DESC;
```

## Business Logic Flow

### Employee Reaches Age 56
```
1. System runs daily: php artisan employees:update-retired-status
2. Check: Is age 56? YES
3. Check: Is status already 3? NO
4. Action: Update status to 3 (Pensiun)
5. Result: Employee now shows "Pensiun" in system
```

### Employee Requests Early Retirement
```
1. Employee submits request
2. HR Manager reviews
3. If approved, Admin manually changes status to 4 (Pensiun Dini)
4. System records tgl_berhenti
5. Employee now shows "Pensiun Dini" in system
```

## UI Display

### Status Badge Colors
- **Pensiun (ID 3):** Gray badge `bg-gray-100 text-gray-800`
- **Pensiun Dini (ID 4):** Slate badge `bg-slate-100 text-slate-800`

### Table Display
```
Nama        | NIP       | Status           | Pension Date
John Doe    | EMP001    | Pensiun          | 15 May 2024  (Normal retirement, age 56)
Jane Smith  | EMP002    | Pensiun Dini     | 20 Jan 2023  (Early retirement, age 51)
```

## Important Notes

### Status ID 3 (Pensiun)
✅ Auto-updated by system  
✅ Based on age calculation (56 years)  
✅ Cannot be manually prevented (automatic at age 56)  
✅ Used for standard/normal retirement  

### Status ID 4 (Pensiun Dini)
❌ Never auto-updated  
✅ Manual admin action only  
✅ Can be applied at any age < 56  
✅ Used for early/special circumstances  

## Common Questions

**Q: Can system change ID 4 to ID 3?**
A: No, system only updates to ID 3 (Pensiun). Employees manually set to ID 4 (Pensiun Dini) stay at ID 4.

**Q: What if employee with ID 4 reaches age 56?**
A: No change. They keep ID 4 (Pensiun Dini) since already marked as retired.

**Q: Can admin change ID 3 to ID 4?**
A: Yes, manually. But not recommended once auto-updated.

**Q: Which status is used in reports?**
A: Usually both are considered "retired" but separated for analysis.

**Q: Is tgl_berhenti the same for both?**
A: Yes, both have tgl_berhenti field set to when they stopped working.

## Audit Trail

### Pensiun (ID 3) - Auto-Updated
```
Event: Daily automatic update
Updated by: System (artisan command)
Timestamp: automated
Notes: Based on age 56 calculation
```

### Pensiun Dini (ID 4) - Manual Update
```
Event: Manual status change by admin
Updated by: Admin name
Timestamp: When admin changed it
Notes: Optional admin notes
```

## Summary Table

| Aspect | Pensiun (3) | Pensiun Dini (4) |
|--------|-------------|-----------------|
| **Trigger Type** | Automatic | Manual |
| **Age Requirement** | Exactly 56+ | Any age |
| **Updated By** | System | Admin |
| **Frequency** | Once at age 56 | As needed |
| **Badge Color** | Gray | Slate |
| **Business Type** | Normal retirement | Early/special |
| **Example** | "John retired at 56" | "Jane chose early at 52" |

---

## Implementation in Phase 5I

Phase 5I specifically implements **ONLY** the automatic update to **Status ID 3 (Pensiun)** for employees reaching age 56.

Status ID 4 (Pensiun Dini) remains for manual administrative use and is not affected by Phase 5I automation.

**Command:** `php artisan employees:update-retired-status`  
**Target Status:** ID 3 (Pensiun)  
**Trigger:** Age reaches 56 years  
**Update Frequency:** Manual or scheduled daily  
