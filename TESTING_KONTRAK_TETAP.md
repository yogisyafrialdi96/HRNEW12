# 🧪 TESTING GUIDE: Kontrak TETAP Features

## Pre-Testing Setup

1. Navigate to: `/admin/kontrak`
2. Ensure you have "TETAP" option in master data Jenis Kontrak
3. Clear browser cache (optional): Ctrl+Shift+Delete

---

## Test Cases

### TEST 1: Create Kontrak Jenis TETAP ⭐ CRITICAL

**Objective:** Verify tanggal selesai disabled for TETAP contracts

**Steps:**
1. Click **Create** button
2. Fill mandatory fields:
   - Karyawan: Select any active employee
   - Nomor Kontrak: Auto-generate or manually set
   - Jenis Kontrak: Select **"TETAP"**
   - Golongan, Department, Unit, Jabatan: Fill accordingly
   - Tanggal Mulai: Set any date (e.g., 2025-01-01)
3. **Observe Tanggal Selesai field:**
   - [ ] Field is **DISABLED** (grayed out, not clickable)
   - [ ] Label shows: **"Tidak terbatas - Kontrak Tetap"**
   - [ ] Blue info text visible: "Kontrak tetap tidak memiliki tanggal selesai"
   - [ ] Cannot click or type in the field

**Expected Result:**
```
✅ Tanggal Selesai: DISABLED
✅ Field visually grayed out
✅ Cannot interact with field
✅ Info message explains why
```

**If Failed:**
- [ ] Field is enabled → BUG (should be disabled)
- [ ] No info message → UX issue
- [ ] Label not showing → Template issue

---

### TEST 2: Change Jenis Kontrak from PKWT to TETAP ⭐ CRITICAL

**Objective:** Verify field auto-disables and clears when user changes jenis

**Steps:**
1. Click **Create** button
2. Select Jenis Kontrak: **"PKWT"** (not TETAP)
3. **Verify Tanggal Selesai is ENABLED:**
   - [ ] Field is white (enabled)
   - [ ] Can click on it
   - [ ] Can input a date
4. Input date: **2025-12-31**
5. Now **Change Jenis Kontrak to "TETAP"**
6. **Immediately Observe Tanggal Selesai field:**
   - [ ] Field becomes **DISABLED** (gray)
   - [ ] Date value is **CLEARED** (now empty)
   - [ ] Label updated to show "Tidak terbatas"
   - [ ] Info message appears

**Expected Result:**
```
✅ Field disabled immediately after change
✅ Date value auto-cleared
✅ Label/info text updated
✅ Smooth transition (no page refresh needed)
```

**If Failed:**
- [ ] Field still enabled → BUG
- [ ] Date not cleared → BUG
- [ ] Changes don't appear immediately → Livewire issue
- [ ] Need page refresh to see changes → BUG

---

### TEST 3: Save Kontrak TETAP Successfully

**Objective:** Verify contract saves correctly with null end date

**Steps:**
1. Create contract (as per TEST 1)
2. Fill all required fields
3. Leave Tanggal Selesai **empty/null**
4. Click **"Simpan Kontrak"** button
5. Wait for response

**Expected Result:**
```
✅ Toast message: "Data kontrak berhasil disimpan"
✅ Modal closes
✅ List view refreshes
✅ New contract appears in table
```

**Verify in Table:**
- [ ] Sisa Kontrak column shows: **"Tidak terbatas"** (gray badge)
- [ ] Tanggal column shows: **"-"** (no date)
- [ ] Status: **"Aktif"** (green)

**Database Verification (optional):**
```sql
SELECT nomor_kontrak, tglselesai_kontrak, status 
FROM karyawan_kontrak 
WHERE nomor_kontrak = 'YOUR_NEW_CONTRACT_NUMBER';

Expected: tglselesai_kontrak = NULL
```

**If Failed:**
- [ ] Error toast appears → Validation issue
- [ ] Table shows date instead of "Tidak terbatas" → Display issue
- [ ] Status not "Aktif" → Status logic issue

---

### TEST 4: Edit Existing PKWT Contract to TETAP

**Objective:** Verify converting PKWT → TETAP works correctly

**Steps:**
1. Find a **PKWT contract** in the list (jenis kontrak = "PKWT")
2. Note current: Tanggal Selesai, Sisa Kontrak
3. Click **Edit** button
4. **Observe current state:**
   - [ ] Tanggal Selesai shows a date (e.g., 2025-12-31)
   - [ ] Field is enabled (white)
   - [ ] Jenis Kontrak shows: "PKWT"
5. **Change Jenis Kontrak to "TETAP"**
6. **Verify immediate changes:**
   - [ ] Tanggal Selesai field becomes disabled/gray
   - [ ] Date value cleared (empty)
   - [ ] Label/info updated
7. Click **"Update Kontrak"** button

**Expected Result:**
```
✅ Toast: "Data kontrak berhasil diedit"
✅ Modal closes
✅ List updates
```

**Verify in Table:**
- [ ] Sisa Kontrak: Changed from "X hari tersisa" to **"Tidak terbatas"**
- [ ] Status: Still **"Aktif"**
- [ ] Jenis Kontrak: Now **"TETAP"**

**If Failed:**
- [ ] Sisa Kontrak still shows days → Display not updated
- [ ] Old date still appears → Data not saved
- [ ] Status changed unexpectedly → Logic issue

---

### TEST 5: Edit TETAP Contract Back to PKWT

**Objective:** Verify reverse conversion works (TETAP → PKWT)

**Steps:**
1. Find a **TETAP contract** (no end date)
2. Click **Edit**
3. **Current state:**
   - [ ] Tanggal Selesai: DISABLED, empty
   - [ ] Sisa: "Tidak terbatas"
   - [ ] Label: "Tidak terbatas - Kontrak Tetap"
4. **Change Jenis Kontrak from "TETAP" to "PKWT"**
5. **Verify immediate changes:**
   - [ ] Tanggal Selesai field **ENABLED** (turns white)
   - [ ] Info message disappears
   - [ ] Can now click and input date
6. **Input date:** 2025-12-31
7. Click **"Update Kontrak"**

**Expected Result:**
```
✅ Toast: "Data kontrak berhasil diedit"
✅ Modal closes
✅ List updates
```

**Verify in Table:**
- [ ] Sisa: Changed to **"19 hari tersisa"** (or based on date)
- [ ] Tanggal: Shows **"01 Des 2025 s/d"**
- [ ] Badge color: Green/Blue (not gray)

**If Failed:**
- [ ] Field stays disabled → BUG
- [ ] Date not saved → Save logic issue
- [ ] Sisa still shows "Tidak terbatas" → Cache issue

---

### TEST 6: Status Auto-Change When Date Expires

**Objective:** Verify status auto-changes to "selesai" when end date passes

**Steps:**
1. Create a **new PKWT contract**
2. Set Jenis Kontrak: **"PKWT"**
3. Set Tanggal Selesai: **Yesterday's date** (e.g., 2025-11-11)
4. Fill other fields
5. Click **"Simpan Kontrak"**

**Expected Result:**
```
✅ Contract saved successfully
✅ Status automatically: "selesai" (NOT "aktif")
```

**Verify in Table:**
- [ ] Sisa Kontrak: **"Sudah berakhir"** (red badge)
- [ ] Duration detail: **"X hari yang lalu"** (past tense)
- [ ] Status: **"Selesai"** (gray badge)

**Test Edit Expired Contract:**
1. Click Edit on expired contract
2. Verify Status radio shows: **"Selesai"** selected
3. Try to change to "Aktif"
4. Note what happens

**If Failed:**
- [ ] Status shows "Aktif" for past date → Auto-sync not working
- [ ] Table shows "tersisa" instead of "yang lalu" → Display logic error
- [ ] Sisa shows positive days → Duration calculation wrong

---

### TEST 7: Sisa Kontrak Display Variations

**Objective:** Verify sisa kontrak displays correctly for different scenarios

**Verify these combinations:**

| Scenario | Expected Display |
|----------|------------------|
| TETAP (no date) | "Tidak terbatas" (gray) |
| PKWT, 100+ days left | "2 bulan 10 hari tersisa" (green) |
| PKWT, 30 days left | "1 bulan tersisa" (blue) |
| PKWT, 15 days left | "15 hari tersisa" (blue) |
| PKWT, 5 days left | "5 hari tersisa" (yellow) |
| PKWT, expired 10 days ago | "Sudah berakhir (10 hari yang lalu)" (red) |

**Steps:**
1. Create test contracts with each scenario
2. Verify display in table matches expected
3. Click detail view to confirm
4. Reload page - should persist

**If Failed:**
- [ ] Wrong calculation → formatDuration() issue
- [ ] Colors wrong → CSS/condition issue
- [ ] Changes on reload → State not persisted

---

### TEST 8: Duplicate Active Contract Rule (with TETAP)

**Objective:** Verify one-active-per-employee works with TETAP

**Steps:**
1. Create **Contract A**: TETAP, Employee X, Status "Aktif"
2. Create **Contract B**: PKWT, Employee X, Status "Aktif"
3. **Expected outcome:**
   - Contract A: Still "Aktif"
   - Contract B: Auto-changed to "Selesai"
   - ✅ Only 1 active per employee

**Verify:**
- [ ] Contract B status changed
- [ ] Log shows auto-close message
- [ ] No error messages

**If Failed:**
- [ ] Both contracts "Aktif" → Duplicate rule not enforced
- [ ] Wrong contract closed → Logic reversed
- [ ] Error in logs → System issue

---

### TEST 9: Form Validation with TETAP

**Objective:** Verify form validation doesn't block TETAP (with null date)

**Steps:**
1. Create contract
2. Select TETAP
3. **Leave required fields empty:**
   - Employee: Empty ❌
   - Nomor Kontrak: Empty ❌
   - Jenis Kontrak: TETAP ✅
   - Golongan: Empty ❌
4. Click Save

**Expected Result:**
```
✅ Error toast shows validation errors
✅ FOR: Employee, Nomor, Golongan
❌ NO error for Tanggal Selesai (it's null, which is valid)
```

**Now complete those fields:**
1. Fill all required fields except Tanggal Selesai
2. Click Save

**Expected Result:**
```
✅ Contract saves successfully
✅ No error for null Tanggal Selesai
✅ Contract appears in table
```

**If Failed:**
- [ ] Error for empty Tanggal Selesai → Validation rules wrong
- [ ] Still requires date input → Not nullable

---

### TEST 10: PDF Export with TETAP Contract

**Objective:** Verify PDF export handles null dates correctly

**Steps:**
1. Create or find a TETAP contract
2. Click **Export PDF** button (if available)
3. Verify PDF downloads
4. Open PDF and check:
   - [ ] Sisa Kontrak: Shows "Tidak terbatas" or blank
   - [ ] No error about missing date
   - [ ] Layout still looks good with empty date

**If Failed:**
- [ ] PDF export fails → Error handling needed
- [ ] Shows "NULL" in PDF → Display not formatted
- [ ] Layout broken → Template issue

---

## Summary Checklist

Mark each test as ✅ PASS or ❌ FAIL

- [ ] TEST 1: Create TETAP - Field disabled
- [ ] TEST 2: Change PKWT→TETAP - Auto-clear
- [ ] TEST 3: Save TETAP - Success
- [ ] TEST 4: Edit PKWT→TETAP - Conversion
- [ ] TEST 5: Edit TETAP→PKWT - Reverse
- [ ] TEST 6: Expired date → Auto selesai
- [ ] TEST 7: Sisa display variations
- [ ] TEST 8: Duplicate active rule
- [ ] TEST 9: Validation with null date
- [ ] TEST 10: PDF export

**Overall Result:** 
- All Pass ✅ = **READY FOR PRODUCTION**
- Any Fail ❌ = Report in issue

---

## Reporting Issues

If any test fails, provide:
1. **Test number and name**
2. **Exact steps taken**
3. **Expected vs Actual result**
4. **Browser console errors** (F12)
5. **Server logs** (if applicable)
6. **Screenshot** (if helpful)

---

**Last Updated:** Nov 12, 2025
**Status:** Ready for Testing
