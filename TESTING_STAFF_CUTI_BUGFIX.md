# Staff Cuti Creation Bug Fix - Verification Guide

## Summary of Fixes Applied

### 1. **Authorization Checks Fixed**
- **File:** `app/Livewire/Admin/Cuti/CutiPengajuanIndex.php`
- **Issue:** Methods were using `authorize()` helper which silently failed
- **Solution:** Replaced with explicit `auth()->user()->can()` checks with proper error messages
- **Methods Updated:**
  - `create()` - Line 258
  - `edit()` - Line 288
  - `save()` - Line 314
  - `submit()` - Line 365
  - `cancel()` - Line 386
  - `delete()` - Line 413

### 2. **Form Data Loading Fixed**
- **File:** `app/Livewire/Admin/Cuti/CutiPengajuanIndex.php`
- **Method:** `create()` and `edit()`
- **Fix:** Added `loadCutiInfo()` call to ensure form is populated with:
  - `cuti_sisa` - Remaining leave balance
  - `cuti_maksimal` - Maximum allowed leave
  - `cuti_terpakai` - Used leave
  - `h_min_cuti` - Minimum days notice

### 3. **Calculation Flow Working**
- **File:** `app/Livewire/Admin/Cuti/CutiPengajuanIndex.php`
- **Method:** `updated()` (Line 253)
- **Logic:**
  - When `jenis_cuti` changes → calls `loadCutiInfo()`
  - When `tanggal_mulai` or `tanggal_selesai` change → calls `calculateJumlahHari()`
  - `calculateJumlahHari()` calculates working days and updates `cuti_sisa_estimasi`

### 4. **Button Visibility Fixed**
- **File:** `resources/views/livewire/admin/cuti/cuti-pengajuan-index.blade.php`
- **Change:** Added `@can('cuti.create')` check around "+ Buat Pengajuan" button
- **Result:** Button only shows to authorized users

### 5. **Routes Properly Configured**
- **File:** `routes/web.php`
- **Setup:**
  - `/cuti` - Staff route with `permission:cuti.view` middleware
  - `/admin/cuti` - Admin route with `permission:cuti.view + permission:dashboard_admin.view`
  - Same component handles both, authorization checks inside methods

### 6. **Test Data Ready**
- **Users:**
  - Betha Feriani (Staff) - Can create cuti
  - Murni Piramadani (Staff) - Can create cuti
  - Dewinta Untari (HR Manager) - Can approve cuti
- **Roles Assigned:** All users have proper roles via UserSeeder and RoleSeeder

## Testing Steps

### Step 1: Login as Staff User
```
Email: betha@example.com
Password: password123
```

### Step 2: Navigate to Cuti Page
- URL: `http://localhost/cuti` (NOT `/admin/cuti`)
- Expected: Page loads showing "Pengajuan Cuti" with "+ Buat Pengajuan" button

### Step 3: Create New Cuti Request
1. Click "+ Buat Pengajuan" button
2. Modal opens with:
   - Informasi Cuti section showing:
     - Sisa Cuti: (should show a number, e.g., 12)
     - Dipakai: (should show used days)
     - Maksimal: (should show max days)
     - Est. Sisa: (should update after date selection)
   - Jenis Cuti dropdown (Tahunan/Melahirkan)
   - Tanggal Mulai and Tanggal Selesai date inputs
   - Jumlah Hari & Estimasi section (initially showing "-")

### Step 4: Select Dates and Verify Calculations
1. Select Jenis Cuti: "Tahunan"
2. Select Tanggal Mulai: (pick a date, e.g., 2025-01-20)
3. Select Tanggal Selesai: (pick a later date, e.g., 2025-01-22)
4. Expected Results:
   - "Jumlah Hari & Estimasi" section updates showing:
     - Yang Diajukan: (calculated working days, e.g., 3 hari)
     - Est. Sisa Cuti: (sisa - jumlah_hari, e.g., 9 hari)

### Step 5: Submit Form
1. Fill in optional fields (Alasan, Contact Address, Phone) if needed
2. Click "Simpan" button (or "Buat Pengajuan" if in modal footer)
3. Expected: Success message and form closes

### Step 6: Verify Cuti Request Created
1. Should see new entry in table showing:
   - Nomor Cuti
   - Jenis Cuti: Tahunan
   - Tanggal: (date range)
   - Hari: (number of days)
   - Status: Draft
2. Row should have Edit, Submit, Batalkan buttons available

### Step 7: Submit Request for Approval
1. Click "Submit" button on draft cuti
2. Status should change to "Pending"
3. Button should disappear (no longer draft)

## Expected Behavior

| Action | Expected Outcome | Status |
|--------|------------------|--------|
| Staff logs in | Can access `/cuti` page | ✅ Should Work |
| Click "+ Buat Pengajuan" | Modal opens with form | ✅ Should Work |
| Modal opens | Cuti info displays (sisa, dipakai, maksimal) | ✅ Should Work |
| Select dates | Jumlah Hari calculates | ✅ Should Work |
| Select dates | Est. Sisa updates | ✅ Should Work |
| Submit form | Cuti request saved as Draft | ✅ Should Work |
| Click Submit button | Status changes to Pending | ✅ Should Work |

## If Issues Persist

### Issue: Button doesn't show
- Check: User has `cuti.view` permission ✓ (Staff role has this)
- Check: Route is `/cuti` not `/admin/cuti` ✓
- Check: Browser cache - clear and reload

### Issue: Form doesn't open
- Check: Browser console for errors
- Check: Livewire component loads (look for network requests)
- Verify: User has `cuti.create` permission

### Issue: Jumlah Hari doesn't calculate
- Check: Both date fields filled with valid dates
- Check: Dates are after current date (or per h_min_cuti)
- Check: No validation errors showing

### Issue: Cuti Info empty (sisa/dipakai/maksimal blank)
- Check: TahunAjaran exists and is active
- Check: CutiSaldo exists for user
- Check: CutiSetup exists in database

## Database Checks

Run these queries to verify data:

```sql
-- Check active tahun ajaran
SELECT * FROM tahun_ajarans WHERE is_active = 1;

-- Check user permissions
SELECT u.name, p.name 
FROM users u
JOIN model_has_roles mhr ON u.id = mhr.model_id
JOIN roles r ON mhr.role_id = r.id
JOIN role_has_permissions rhp ON r.id = rhp.role_id
JOIN permissions p ON rhp.permission_id = p.id
WHERE u.name = 'Betha Feriani' AND p.name LIKE 'cuti%';

-- Check cuti saldo
SELECT * FROM cuti_saldos WHERE user_id = (SELECT id FROM users WHERE name = 'Betha Feriani');

-- Check approval settings
SELECT * FROM unit_approval_settings WHERE is_active = 1;
```

## Code Flow Diagram

```
1. User clicks "+ Buat Pengajuan"
   ↓
2. Livewire calls create() method
   ↓
3. Authorization check: user->can('cuti.create')?
   ├─ NO → Dispatch error toast, return
   └─ YES → Continue
   ↓
4. Reset form, set defaults
   ↓
5. Call loadCutiInfo()
   ├─ Fetch active TahunAjaran
   ├─ Get/create CutiSaldo
   ├─ Set cuti_sisa, cuti_maksimal, etc.
   └─ Set minimum date allowed
   ↓
6. Show modal (form rendered with pre-filled data)
   ↓
7. User selects dates
   ↓
8. Livewire detects change in tanggal_mulai/tanggal_selesai
   ↓
9. Call updated() → calculateJumlahHari()
   ├─ Calculate working days (exclude weekends, holidays)
   ├─ Set jumlah_hari
   ├─ Calculate cuti_sisa_estimasi
   └─ Form re-renders with new values
   ↓
10. User clicks "Simpan"
    ↓
11. Livewire validates form (including jumlah_hari)
    ↓
12. Call save() method
    ├─ Authorization check
    ├─ Get/create CutiSaldo
    ├─ Create/update CutiPengajuan as "draft"
    └─ Show success message
    ↓
13. Modal closes, table refreshes
    ↓
14. User can now see new draft cuti in table
    ↓
15. User clicks "Submit" button
    ↓
16. Call submit() method
    ├─ Authorization check
    ├─ Update status to "pending"
    └─ Show success message
    ↓
17. Cuti now awaits approval
```

## Notes

- All calculations use working days (exclude weekends and holidays)
- Minimum date notice (h_min_cuti) is enforced in form
- Draft cuti can be edited or deleted
- Pending/Approved cuti cannot be edited
- Approvers will see cuti in `/cuti-approval` dashboard
- System supports multi-level approval (currently 2 levels)

