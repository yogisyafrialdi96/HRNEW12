# Filter Kontrak - Visual Guide

## 🎨 UI Layout Overview

```
┌─────────────────────────────────────────────────────────────────┐
│ Kontrak Karyawan                                    [+ Create]   │
│ This Page Show List of Employee Contracts                       │
├─────────────────────────────────────────────────────────────────┤
│ ┌───────────────────────────────────────────────────────────┐   │
│ │                 FILTERS & ACTIONS ROW                      │   │
│ ├─────────────────────────────────────────────────────────┤   │
│ │ [Semua Jenis Kontrak ▼] [Semua Status ▼] [Semua Sisa ▼]  │   │
│ │                                    [Show Deleted] [+Create] │   │
│ └───────────────────────────────────────────────────────────┘   │
│ ┌─────────────────────────────────────────────────────────┐     │
│ │ [10/page ▼]              🔍 Search Kontrak...          │     │
│ └─────────────────────────────────────────────────────────┘     │
├─────────────────────────────────────────────────────────────────┤
│ TABLE HEADERS:                                                   │
│ No. | No.Kontrak | Karyawan | Jenis | Jabatan | Periode | ... │
├─────────────────────────────────────────────────────────────────┤
│ Row 1: [Details] [Edit] [Delete]                               │
│ Row 2: [Details] [Edit] [Delete]                               │
│ Row 3: [Details] [Edit] [Delete]                               │
├─────────────────────────────────────────────────────────────────┤
│ « 1 2 3 » | Showing 1-10 of 45 records                         │
└─────────────────────────────────────────────────────────────────┘
```

## 🎯 Filter Dropdown Options

### Filter 1: Jenis Kontrak
```
┌─────────────────────────┐
│ ▼ Semua Jenis Kontrak   │
├─────────────────────────┤
│ Semua Jenis Kontrak     │ ← Default (shows all)
│ TETAP                   │ ← Permanent contracts
│ PKWT                    │ ← Fixed-term contracts
│ [Other types...]        │ ← Any other master kontrak
└─────────────────────────┘
```

### Filter 2: Status Kontrak
```
┌──────────────────────┐
│ ▼ Semua Status       │
├──────────────────────┤
│ Semua Status         │ ← Default (shows all)
│ Aktif                │ ← Active (green badge)
│ Selesai              │ ← Expired (gray badge)
│ Perpanjangan         │ ← Renewing (blue badge)
│ Dibatalkan           │ ← Cancelled (red badge)
└──────────────────────┘
```

### Filter 3: Sisa Kontrak
```
┌─────────────────────────────────┐
│ ▼ Semua Sisa Kontrak            │
├─────────────────────────────────┤
│ Semua Sisa Kontrak              │ ← Default (shows all)
│ Sudah Berakhir                  │ ← Past date (red)
│ Akan Berakhir (≤30 hari)        │ ← Soon (orange)
│ Masih Berlaku (>30 hari)        │ ← Valid (green)
│ Tidak Terbatas (TETAP)          │ ← No end date (gray)
└─────────────────────────────────┘
```

## 📱 Show Deleted Toggle

### Normal View State
```
┌──────────────────────────┐
│ 🗑️  Show Deleted Button   │  ← Click to see deleted
└──────────────────────────┘

Table shows: ACTIVE RECORDS
Action buttons in each row:
  👁️  (View) | ✏️  (Edit) | 🗑️  (Delete/Soft)
```

### Deleted View State
```
┌──────────────────────────┐
│ 🔄  Show Exist Button    │  ← Click to go back
└──────────────────────────┘

Table shows: SOFT-DELETED RECORDS
Action buttons in each row:
  ↩️  (Restore) | 🗑️  (Force Delete/Hard)
```

## 🔄 Action Buttons State Diagram

```
                    NORMAL STATE
                   (showDeleted=false)
                          │
                          ▼
    ┌─────────────────────────────────────┐
    │  Table Actions (per row):           │
    │  👁️ Detail | ✏️ Edit | 🗑️ Soft Delete │
    │                                      │
    │  Clicking 🗑️ triggers:              │
    │  - Confirm modal                    │
    │  - Soft delete (deleted_at set)    │
    │  - Record hidden from normal view  │
    │  - Can still restore later         │
    └─────────────────────────────────────┘
                          │
              User clicks "Show Deleted"
                          │
                          ▼
                   DELETED STATE
                  (showDeleted=true)
                          │
                          ▼
    ┌─────────────────────────────────────┐
    │  Deleted Records Actions (per row):  │
    │  ↩️  Restore | 🗑️ Force Delete       │
    │                                      │
    │  Clicking ↩️ triggers:               │
    │  - Confirm modal                    │
    │  - deleted_at cleared              │
    │  - Record returns to normal        │
    │                                      │
    │  Clicking 🗑️ triggers:              │
    │  - Confirm modal (with warning!)    │
    │  - Hard delete from database       │
    │  - ⚠️ PERMANENT - NO RECOVERY      │
    └─────────────────────────────────────┘
```

## 🎛️ Filter Combination Examples

### Scenario 1: Find Urgent Renewals
```
Filter 1: Jenis Kontrak = PKWT
Filter 2: Status = Aktif
Filter 3: Sisa Kontrak = Akan Berakhir (≤30 hari)
                        ▼
        Shows PKWT contracts expiring soon
        (Manager's "to-do" list for renewal)
```

### Scenario 2: Monitor Permanent Staff
```
Filter 1: Jenis Kontrak = TETAP
Filter 2: Status = Aktif
Filter 3: Sisa Kontrak = Tidak Terbatas (TETAP)
                        ▼
        Shows all active permanent employees
```

### Scenario 3: Audit Expired Contracts
```
Filter 1: Jenis Kontrak = (Any)
Filter 2: Status = Selesai
Filter 3: Sisa Kontrak = Sudah Berakhir
                        ▼
        Shows all expired/completed contracts
        (Compliance/record-keeping view)
```

## 📊 Data Flow Diagram

```
┌──────────────────────────────────────┐
│        USER INTERACTION               │
│  (Selects filter or clicks button)    │
└────────────┬─────────────────────────┘
             │
             ▼
┌──────────────────────────────────────┐
│   LIVEWIRE COMPONENT (Index.php)      │
│  - Receives filter value              │
│  - Updates public property            │
│  - Triggers render()                  │
└────────────┬─────────────────────────┘
             │
             ▼
┌──────────────────────────────────────┐
│    QUERY BUILDER (render method)      │
│  - Adds WHERE clauses                 │
│  - Applies soft delete scope          │
│  - Sorts & Paginates                  │
└────────────┬─────────────────────────┘
             │
             ▼
┌──────────────────────────────────────┐
│      DATABASE QUERY                   │
│  SELECT * FROM karyawan_kontrak       │
│  WHERE status = ?                     │
│  AND kontrak_id = ?                   │
│  AND tglselesai_kontrak > ?           │
│  AND deleted_at IS NULL               │
│  ORDER BY ... LIMIT ...               │
└────────────┬─────────────────────────┘
             │
             ▼
┌──────────────────────────────────────┐
│      BLADE TEMPLATE (Render)          │
│  - Loop through results               │
│  - Display table rows                 │
│  - Conditional action buttons         │
└────────────┬─────────────────────────┘
             │
             ▼
┌──────────────────────────────────────┐
│      HTML TABLE IN BROWSER            │
│  (Real-time update, no page reload)   │
└──────────────────────────────────────┘
```

## 📐 Responsive Breakpoints

```
DESKTOP (≥1024px)
┌─────────────────────────────────────────────┐
│ [Filter 1]  [Filter 2]  [Filter 3]  [Button] │
└─────────────────────────────────────────────┘
  Grid: 3 cols + 1 col action button


TABLET (768px - 1023px)
┌─────────────────────────────────┐
│ [Filter 1]  [Filter 2]  [Filter 3]│
│           [Button]              │
└─────────────────────────────────┘
  Stack filters, button below


MOBILE (<768px)
┌──────────────────┐
│ [Filter 1]       │
│ [Filter 2]       │
│ [Filter 3]       │
│ [Button]         │
└──────────────────┘
  Full width single column
```

## 🏷️ Badge Colors in Table

```
Status Column Badges:
┌─────────────┬──────────────────────┐
│ Status      │ Badge Display        │
├─────────────┼──────────────────────┤
│ Aktif       │ 🟢 Green (Active)    │
│ Selesai     │ ⚪ Gray (Completed)  │
│ Perpanjangan│ 🔵 Blue (Renewing)   │
│ Dibatalkan  │ 🔴 Red (Cancelled)   │
└─────────────┴──────────────────────┘

Remaining Days Badges:
┌──────────────────┬──────────────────┐
│ Duration         │ Badge Display    │
├──────────────────┼──────────────────┤
│ Sudah Berakhir   │ 🔴 Red          │
│ ≤30 hari tersisa │ 🟠 Orange       │
│ >30 hari tersisa │ 🟢 Green        │
│ Tidak terbatas   │ ⚪ Gray (TETAP)  │
└──────────────────┴──────────────────┘
```

## 🔐 Permission Matrix

```
┌─────────────────┬───────┬────────┬──────────┐
│ Action          │ Admin │Manager │ Employee │
├─────────────────┼───────┼────────┼──────────┤
│ View all        │  ✅   │   ✅   │    ❌    │
│ Apply filters   │  ✅   │   ✅   │    ❌    │
│ Edit contract   │  ✅   │   ✅   │    ❌    │
│ Soft delete     │  ✅   │   ✅   │    ❌    │
│ Show deleted    │  ✅   │   ✅   │    ❌    │
│ Restore         │  ✅   │   ✅   │    ❌    │
│ Force delete    │  ✅   │   ❓   │    ❌    │
└─────────────────┴───────┴────────┴──────────┘
(✅ = Full access, ❓ = Check policy, ❌ = No access)
```

## 📝 Filter State Examples

### Example 1: Default State
```
jenis_kontrak_filter = ""
status_kontrak_filter = ""
sisa_kontrak_filter = ""
showDeleted = false

Display: ALL ACTIVE CONTRACTS (no filter applied)
Count: Depends on total active contracts
```

### Example 2: Single Filter Active
```
jenis_kontrak_filter = "2"        (PKWT ID = 2)
status_kontrak_filter = ""
sisa_kontrak_filter = ""
showDeleted = false

Display: ONLY PKWT CONTRACTS, ACTIVE
Count: Reduced (only PKWT subset)
```

### Example 3: Multiple Filters Active
```
jenis_kontrak_filter = "2"        (PKWT)
status_kontrak_filter = "aktif"
sisa_kontrak_filter = "expiring_soon"
showDeleted = false

Display: PKWT + AKTIF + EXPIRING SOON
Count: Smallest subset (intersection of all)
SQL: WHERE kontrak_id=2 AND status='aktif' 
     AND tglselesai BETWEEN today AND today+30
```

### Example 4: Deleted View
```
jenis_kontrak_filter = ""
status_kontrak_filter = ""
sisa_kontrak_filter = ""
showDeleted = true

Display: ONLY SOFT-DELETED RECORDS
Action buttons change to: ↩️ Restore | 🗑️ Force Delete
```

## 🎓 Understanding Filter Logic

```
When user applies filters:

START with all contracts
  ↓
Filter 1: IF jenis_kontrak_filter selected
  → Keep only contracts of that type
  ↓
Filter 2: IF status_kontrak_filter selected
  → Keep only contracts with that status
  ↓
Filter 3: IF sisa_kontrak_filter selected
  → Keep only contracts in that duration category
  ↓
showDeleted check:
  → IF false: hide deleted (default)
  → IF true: show ONLY deleted
  ↓
RESULT: Smallest intersection matching ALL filters
```

**IMPORTANT:** All filters use AND logic (intersection)
- Selecting PKWT AND Aktif = only PKWT contracts that are Aktif
- Not OR logic (which would show PKWT OR Aktif)

---

This visual guide provides quick reference for understanding the filter interface and data flow.
