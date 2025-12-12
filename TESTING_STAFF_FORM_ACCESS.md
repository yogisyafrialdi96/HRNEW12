# Testing Guide - Staff Cuti & Izin Form Fix

**Date**: 2025-12-10  
**Testing User**: Any user with "Staff" role  
**Affected Features**: Cuti & Izin request form submission

## Quick Test Steps

### 1. Login as Staff User

Gunakan login credentials untuk karyawan dengan role "Staff":

```
Username: [staff_user_email]
Password: [password]
```

**Expected**: Successfully logged in, redirected to dashboard

---

### 2. Navigate to Cuti Form

**Path**: Click menu → Cuti → Pengajuan Cuti (or visit `/admin/cuti`)

**Expected Results**:
- ✅ Page loads without error
- ✅ Title: "Pengajuan Cuti"
- ✅ Button "+ Buat Pengajuan" is **VISIBLE**
- ✅ Table shows pengajuan history (if exists)
- ✅ No console errors (F12 → Console tab)

**If Failed**:
- Button not visible → Check Staff role has `cuti.create` permission
- Page error → Check `storage/logs/laravel.log`

---

### 3. Open Cuti Form Modal

**Action**: Click "+ Buat Pengajuan" button

**Expected Results**:
- ✅ Modal opens with title "Buat Pengajuan Cuti"
- ✅ Form shows all fields:
  - Jenis Cuti (dropdown)
  - Tanggal Mulai & Selesai (date pickers)
  - **"Jumlah Hari & Estimasi" section visible** ⭐
  - Alasan (textarea)
  - Kontak Info (optional)
- ✅ "Informasi Cuti" box at top shows:
  - Sisa Cuti: 12 (or your saldo)
  - Dipakai: 0
  - Maksimal: 12
  - Est. Sisa: -

**If "Jumlah Hari & Estimasi" NOT showing**:
- Check if form fields rendered
- Check browser console for JS errors
- Check `storage/logs/laravel.log` for "Error loading cuti info"

---

### 4. Select Dates & View Calculated Days

**Actions**:
1. Jenis Cuti: Select "Tahunan" (should be default)
2. Tanggal Mulai: Select date (e.g., 15 Dec 2025)
3. Tanggal Selesai: Select date (e.g., 19 Dec 2025)
4. Wait 1-2 seconds for calculation

**Expected Results**:
- ✅ "Jumlah Hari & Estimasi" section updates:
  - "Yang Diajukan": Shows "5 hari" (calculated)
  - "Est. Sisa Cuti": Shows "7 hari" (12 - 5)
- ✅ Form automatically calculates when dates change
- ✅ Numbers update in real-time

**If NOT showing calculated days**:
- Check `storage/logs/laravel.log` for "Error calculating jumlah hari"
- Should see fallback value (calendar-based count)
- If still nothing, check CutiSaldo exists for user/year

---

### 5. Fill Complete Form & Submit

**Actions**:
1. Fill "Alasan": "Test cuti form"
2. Click "Simpan"

**Expected Results**:
- ✅ Modal closes
- ✅ Toast success message: "Pengajuan cuti berhasil disimpan"
- ✅ New row appears in table with:
  - Status: "Draft"
  - Tanggal: Your selected dates
  - Jenis: "Tahunan"
  - Hari: "5 hari"

**If Submit Failed**:
- Check validation errors in form
- Check `storage/logs/laravel.log` for errors
- Check Console tab (F12) for JS errors

---

### 6. Test Izin Form (Similar Process)

**Path**: Click menu → Izin → Pengajuan Izin (or visit `/admin/izin`)

**Steps Same As Cuti**:
1. ✅ Page loads, button visible
2. ✅ Modal opens
3. ✅ Fill form (Jenis Izin, Tanggal, Alasan)
4. ✅ Click Simpan
5. ✅ See new row in table

---

## Validation Points

### Permission Check
```php
// Should PASS for Staff users
User->can('cuti.create')  // TRUE
User->can('izin.create')  // TRUE
User->can('cuti.submit')  // TRUE
User->can('izin.submit')  // TRUE
```

### Database Check
```bash
# Verify Staff role has permissions
php artisan tinker
>>> $role = Role::findByName('staff');
>>> $role->permissions->pluck('name');
// Should include: cuti.create, cuti.edit, cuti.submit, cuti.cancel
//               izin.create, izin.edit, izin.submit, izin.cancel
```

### CutiSaldo Auto-Creation
```bash
# After opening cuti form, should auto-create:
php artisan tinker
>>> CutiSaldo::where('user_id', <user_id>)->first();
// Should show a record with:
//   cuti_tahunan_awal: 12
//   cuti_tahunan_sisa: 12
//   cuti_tahunan_terpakai: 0
```

---

## Error Diagnosis

### Issue: Button Not Visible

**Possible Causes**:
1. User doesn't have Staff role
2. Staff role missing `cuti.create` permission
3. Blade view has @can guard (should be removed now)

**Check**:
```bash
php artisan tinker
>>> User::find(<id>)->roles;
>>> User::find(<id>)->can('cuti.create');
```

### Issue: "Jumlah Hari & Estimasi" Blank

**Possible Causes**:
1. CutiCalculationService failing (but has fallback)
2. User->karyawan relationship broken
3. null values in CutiSaldo

**Check Logs**:
```bash
tail -50 storage/logs/laravel.log | grep "Error"
```

**Expected Fallback**:
- Even if service fails, should show calendar-day count
- Example: 15-19 Dec = 5 days

### Issue: Form Won't Save

**Possible Causes**:
1. Validation error (dates, alasan, etc.)
2. Missing UnitApprovalSetting
3. TahunAjaran not active

**Check**:
```bash
php artisan tinker
>>> UnitApprovalSetting::where('is_active', true)->first();
>>> TahunAjaran::where('is_active', true)->first();
```

---

## Browser Console Checks

**Open DevTools**: Press F12 → Console tab

**Look for**:
- ❌ Red error messages (bad!)
- ✅ Blue info messages (good)
- No "undefined" variables

**Clear console** and repeat test to see fresh errors

---

## Log File Analysis

**Location**: `storage/logs/laravel.log`

**Commands**:
```bash
# See last 50 lines
tail -50 storage/logs/laravel.log

# See errors only
grep "Error loading cuti info\|Error calculating jumlah hari" storage/logs/laravel.log

# Watch in real-time
tail -f storage/logs/laravel.log
```

**Expected Lines**:
- ✅ Only general Laravel logs
- ❌ NO "Error loading cuti info" messages
- ❌ NO "Error calculating jumlah hari" messages

If you see errors, that means fallback logic is triggered (not ideal but acceptable).

---

## Success Criteria

✅ **Test PASSED** if all items work:

1. [✓] Staff user can open cuti/izin page
2. [✓] "+ Buat Pengajuan" button visible
3. [✓] Modal opens on button click
4. [✓] "Jumlah Hari & Estimasi" section displays values
5. [✓] Calculated days update when dates change
6. [✓] Form saves without error
7. [✓] New request appears in table
8. [✓] No errors in browser console
9. [✓] No errors in `storage/logs/laravel.log`

---

## Notes

- This fix applies to both Cuti and Izin forms
- All authenticated staff users should have access
- If specific user lacks access, check role/permission assignment
- Fallback calculation ensures form always shows a value
- Check logs if calculation looks wrong (may be using fallback)
